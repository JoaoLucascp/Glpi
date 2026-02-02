<?php

/**
* Common Class - Base class for all Newbase entities
* @package   Plugin - Newbase
* @author    João Lucas
* @copyright 2026 João Lucas
* @license   GPLv2+
* @version   2.0.0
*/

namespace GlpiPlugin\Newbase;

use Session;
use Toolbox;
use Html;
use CommonGLPI;
use CommonDBTM;
use Plugin;

/**
* Common - Base class with shared functionality
*
* Métodos herdados de CommonDBTM:
* @method int getID() Obter ID do item
* @method bool canView() Verificar se usuário pode visualizar
* @method bool canCreate() Verificar se usuário pode criar
* @method bool canUpdate() Verificar se usuário pode atualizar
* @method bool canDelete() Verificar se usuário pode deletar
* @method bool canPurge() Verificar se usuário pode purgar
* @method bool canEdit(int $ID) Verificar se usuário pode editar item
* @method bool check(int $ID, int $right) Verificar permissão
* @method bool getEmpty() Limpar campos do item
* @method array getFields() Obter campos do item
* @method bool post_addItem() Executar após adicionar item
* @method bool post_updateItem() Executar após atualizar item
* @method bool post_deleteItem() Executar após deletar item
*/
abstract class Common extends CommonDBTM
{
/**
* Rights management
* @var string
*/
    public static $rightname = 'plugin_newbase';

/**
* Enable history tracking
* @var bool
*/
    public $dohistory = true;

/**
* Get type name
* @param int $nb Number of items
* @return string Type name
*/
    public static function getTypeName($nb = 0): string
    {
        return static::class;
    }

/**
* Get table name
* @param string|null $classname Class name
* @return string Table name
*/
    public static function getTable($classname = null): string
    {
        $classname ??= static::class;
        $class = explode('\\', $classname);
        $class = end($class);
        return 'glpi_plugin_newbase_' . strtolower($class);
    }

/**
* Get type (class name without namespace)
* @return string Type name
*/
    public static function getType(): string
    {
        $class = static::class;
        $parts = explode('\\', $class);
        return end($parts);
    }

/**
* Get search URL
* @param bool $full Full path
* @return string Search URL
*/
    public static function getSearchURL($full = true): string
    {
        $dir = Plugin::getWebDir('newbase', $full);
        $item = strtolower(static::getType());
        return "$dir/front/$item.php";
    }

/**
* Get form URL
* @param bool $full Full path
* @return string Form URL
*/
    public static function getFormURL($full = true): string
    {
        $dir = Plugin::getWebDir('newbase', $full);
        $item = strtolower(static::getType());
        return "$dir/front/$item.form.php";
    }

/**
* Get form URL with ID
* @param int $id Item ID
* @param bool $full Full path
* @return string Form URL with ID
*/
    public static function getFormURLWithID($id = 0, $full = true): string
    {
        $link = static::getFormURL($full);
        $link .= (strpos($link, '?') !== false ? '&' : '?') . 'id=' . $id;
        return $link;
    }

/**
* Display form header
* @param array $options Form options
* @return void
*/
    public function showFormHeader($options = []): void
    {
        $id = $this->fields['id'] ?? 0;
        $target = $this->getFormURL();

        echo "<form name='form' method='post' action='$target'>";

        if ($id > 0) {
            echo "<input type='hidden' name='id' value='$id'>";
        }

        echo "<div class='spaced'>";
        echo "<table class='tab_cadre_fixe'>";
    }

/**
* Display form buttons
* @param array $options Form options
* @return void
*/
    public function showFormButtons($options = []): void
    {
        $id = $this->fields['id'] ?? 0;
        $canedit = $this->canEdit($id);

        echo "</table>";
        echo "</div>";

        if ($canedit) {
            echo "<div class='center'>";

            if ($id > 0) {
                echo "<input type='submit' name='update' value='" . __('Save') . "' class='btn btn-primary'>";

                if ($this->canPurge()) {
                    echo "&nbsp;&nbsp;";
                    echo "<input type='submit' name='purge' value='" . __('Delete permanently') . "'
                        class='btn btn-danger'
                        onclick='return confirm(\"" . __('Confirm the final deletion?') . "\");'>";
                }
            } else {
                echo "<input type='submit' name='add' value='" . __('Add') . "' class='btn btn-primary'>";
            }

            echo "</div>";
        }

        Html::closeForm();
    }

/**
* Display item
* @param array $options Display options
* @return bool Success
*/
    public function display($options = []): bool
    {
        return $this->showForm($options['id'] ?? 0, $options);
    }

/**
* Show form (must be implemented by child classes)
* @param int   $ID      Item ID
* @param array $options Form options
* @return bool Success
*/
    public function showForm($ID, array $options = []): bool
    {
        return false;
    }

/**
* Redirect to list
* @return void
*/
    public function redirectToList(): void
    {
        Html::redirect($this->getSearchURL());
    }

/**
* Validate CNPJ format and checksum
* @param string|null $cnpj CNPJ without formatting (14 digits)
* @return bool True if valid, false otherwise
*/
    public static function validateCNPJ(?string $cnpj): bool
    {
        if ($cnpj === null || $cnpj === '') {
            return false;
        }

        // Remove non-numeric characters
        $cnpj = preg_replace('/[^0-9]/', '', $cnpj);

        // Check if it has exactly 14 digits
        if (strlen($cnpj) !== 14) {
            return false;
        }

        // Check if all digits are the same (invalid CNPJ)
        if (preg_match('/^(\d)\1{13}$/', $cnpj)) {
            return false;
        }

        // Calculate first check digit
        $firstDigit = self::calculateCNPJCheckDigit(substr($cnpj, 0, 12), 5);
        if ((int) $cnpj[12] !== $firstDigit) {
            return false;
        }

        // Calculate second check digit
        $secondDigit = self::calculateCNPJCheckDigit(substr($cnpj, 0, 13), 6);
        if ((int) $cnpj[13] !== $secondDigit) {
            return false;
        }

        return true;
    }

/**
* Calculate CNPJ check digit
* @param string $base Base string (12 or 13 digits)
* @param int $multiplierStart Starting multiplier (5 or 6)
* @return int Check digit
*/
    private static function calculateCNPJCheckDigit(string $base, int $multiplierStart): int
    {
        $sum = 0;
        $multiplier = $multiplierStart;

        for ($i = 0; $i < strlen($base); $i++) {
            $sum += (int) $base[$i]* $multiplier;
            $multiplier--;
            if ($multiplier < 2) {
                $multiplier = 9;
            }
        }

        $remainder = $sum % 11;
        return $remainder < 2 ? 0 : 11 - $remainder;
    }

/**
* Search company data by CNPJ using external API
* @param string|null $cnpj CNPJ without formatting
* @return array|false Company data or false if not found
*/
    public static function searchCompanyByCNPJ(?string $cnpj): array|false
    {
        if ($cnpj === null || $cnpj === '') {
            return false;
        }

        // Remove non-numeric characters
        $cnpj = preg_replace('/[^0-9]/', '', $cnpj);

        // Validate CNPJ first
        if (!self::validateCNPJ($cnpj)) {
            return false;
        }

        try {
            // Use Brasil API (free, no authentication required)
            $url = "https://brasilapi.com.br/api/cnpj/v1/{$cnpj}";

            $options = [
                'http' => [
                    'method' => 'GET',
                    'header' => 'User-Agent: GLPI-Newbase/2.0.0',
                    'timeout' => 10,
                ],
            ];

            $context = stream_context_create($options);
            $response = @file_get_contents($url, false, $context);

            if ($response === false) {
                return false;
            }

            $data = json_decode($response, true);

            if (isset($data['status']) && $data['status'] === 400) {
                return false; // CNPJ not found
            }

            // Return standardized data
            return [
                'legal_name' => $data['nome'] ?? $data['legal_name'] ?? '',
                'fantasy_name' => $data['fantasia'] ?? $data['fantasy_name'] ?? '',
                'cnpj' => $data['cnpj'] ?? $cnpj,
                'status' => $data['situacao'] ?? '',
                'opening_date' => $data['data_abertura'] ?? '',
            ];
        } catch (\Exception $e) {
            Toolbox::logInFile(
                'newbase_plugin',
                "Error searching CNPJ {$cnpj}: " . $e->getMessage()
            );
            return false;
        }
    }

/**
* Search for additional company data (email and phone) via multiple APIs
* @param string|null $cnpj CNPJ number
* @param string $companyName Company name for alternative search
* @return array Company data with email and phone
*/
    public static function searchCompanyAdditionalData(?string $cnpj, string $companyName = ''): array
    {
        $result = [
            'email' => '',
            'phone' => '',
            'website' => '',
        ];

        if ($cnpj === null || $cnpj === '') {
            return $result;
        }

        // Remove formatting
        $cnpj = preg_replace('/[^0-9]/', '', $cnpj);

        // Try ReceitaWS API (alternative to Brasil API with more data)
        $result = array_merge($result, self::searchReceitaWSAPI($cnpj));

        // If we have email/phone, return early
        if (!empty($result['email']) || !empty($result['phone'])) {
            return $result;
        }

        // Try alternative data sources
        $result = array_merge($result, self::searchPublicDataSources($cnpj, $companyName));

        return $result;
    }

/**
* Search for company data via ReceitaWS API
* @param string $cnpj CNPJ number
* @return array Company data or empty array
*/
    private static function searchReceitaWSAPI(string $cnpj): array
    {
        $result = [
            'email' => '',
            'phone' => '',
            'website' => '',
        ];

        try {
            // ReceitaWS API - Brazilian public data
            $url = "https://www.receitaws.com.br/v1/cnpj/{$cnpj}";

            $options = [
                'http' => [
                    'method' => 'GET',
                    'header' => 'User-Agent: GLPI-Newbase/2.0.0',
                    'timeout' => 8,
                ],
            ];

            $context = stream_context_create($options);
            $response = @file_get_contents($url, false, $context);

            if ($response === false) {
                return $result;
            }

            $data = json_decode($response, true);

            // Check if request was successful
            if (isset($data['status']) && $data['status'] === 'OK') {
                $result['email'] = $data['email'] ?? '';
                $result['phone'] = $data['telefone'] ?? '';
                $result['website'] = $data['website'] ?? '';

                Toolbox::logInFile(
                    'newbase_plugin',
                    "ReceitaWS API found data for CNPJ {$cnpj}"
                );
            }
        } catch (\Exception $e) {
            Toolbox::logInFile(
                'newbase_plugin',
                "ReceitaWS API Error for CNPJ {$cnpj}: " . $e->getMessage()
            );
        }

        return $result;
    }

/**
* Search for company data in public GLPI database
* @param string $cnpj CNPJ number
* @param string $companyName Company name
* @return array Company data or empty array
*/
    private static function searchPublicDataSources(string $cnpj, string $companyName = ''): array
    {
        $result = [
            'email' => '',
            'phone' => '',
            'website' => '',
        ];

        if (empty($companyName)) {
            return $result;
        }

        try {
            // Verificar se classe Company está disponível
            if (!class_exists('Company')) {
                Toolbox::logInFile(
                    'newbase_plugin',
                    "Classe Company não disponível para busca em GLPI"
                );
                return $result;
            }

            // Search in GLPI's own Company database for additional info
            $glpiCompany = new \Company();
            $found = $glpiCompany->find(['name' => $companyName]);

            if (!empty($found)) {
                foreach ($found as $company) {
                    if (!empty($company['phonenumber'])) {
                        $result['phone'] = $company['phonenumber'];
                    }
                    if (!empty($company['email'])) {
                        $result['email'] = $company['email'];
                    }
                    if (!empty($company['website'])) {
                        $result['website'] = $company['website'];
                    }
                    break;
                }
            }
        } catch (\Exception $e) {
            Toolbox::logInFile(
                'newbase_plugin',
                "Error searching GLPI database for company data: " . $e->getMessage()
            );
        }

        return $result;
    }

/**
* Format phone number for Brazilian format
* @param string|null $phone Phone number
* @return string Formatted phone number
*/
    public static function formatPhone(?string $phone = ''): string
    {
        if ($phone === null || $phone === '') {
            return '';
        }

        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Format as (XX) XXXXX-XXXX or (XX) XXXX-XXXX
        if (strlen($phone) === 11) {
            // Cell phone: (XX) XXXXX-XXXX
            return sprintf('(%s) %s-%s', substr($phone, 0, 2), substr($phone, 2, 5), substr($phone, 7));
        } elseif (strlen($phone) === 10) {
            // Landline: (XX) XXXX-XXXX
            return sprintf('(%s) %s-%s', substr($phone, 0, 2), substr($phone, 2, 4), substr($phone, 6));
        }
        return $phone;
    }

/**
* Format CEP (Brazilian ZIP code)
* @param string|null $cep CEP to format
* @return string Formatted CEP
*/
    public static function formatCEP(?string $cep): string
    {
        if ($cep === null || $cep === '') {
            return '';
        }

        $cep = preg_replace('/[^0-9]/', '', $cep);

        if (strlen($cep) === 8) {
            return substr($cep, 0, 5) . '-' . substr($cep, 5);
        }

        return $cep;
    }

/**
* Format CNPJ to Brazilian format
* @param string|null $cnpj CNPJ to format
* @return string Formatted CNPJ (XX.XXX.XXX/XXXX-XX)
*/
    public static function formatCNPJ(?string $cnpj): string
    {
        if ($cnpj === null || $cnpj === '') {
            return '';
        }

        // Remove all non-numeric characters
        $cnpj = preg_replace('/[^0-9]/', '', $cnpj);

        // Format as XX.XXX.XXX/XXXX-XX
        if (strlen($cnpj) === 14) {
            return sprintf(
                '%s.%s.%s/%s-%s',
                substr($cnpj, 0, 2),
                substr($cnpj, 2, 3),
                substr($cnpj, 5, 3),
                substr($cnpj, 8, 4),
                substr($cnpj, 12, 2)
            );
        }

        return $cnpj;
    }

/**
* Calculate distance between two coordinates using Haversine formula
* @param float $lat1 Latitude of first point
* @param float $lng1 Longitude of first point
* @param float $lat2 Latitude of second point
* @param float $lng2 Longitude of second point
* @return float Distance in kilometers
*/
    public static function calculateDistance(
        float $lat1,
        float $lng1,
        float $lat2,
        float $lng2
    ): float {
        // Earth radius in kilometers
        $earthRadius = 6371.0;

        // Convert degrees to radians
        $lat1Rad = deg2rad($lat1);
        $lng1Rad = deg2rad($lng1);
        $lat2Rad = deg2rad($lat2);
        $lng2Rad = deg2rad($lng2);

        // Haversine formula
        $deltaLat = $lat2Rad - $lat1Rad;
        $deltaLng = $lng2Rad - $lng1Rad;

        $a = sin($deltaLat / 2)** 2
            + cos($lat1Rad)* cos($lat2Rad)* sin($deltaLng / 2)** 2;

        $c = 2* atan2(sqrt($a), sqrt(1 - $a));

        $distance = $earthRadius* $c;

        // Round to 2 decimal places
        return round($distance, 2);
    }
}
