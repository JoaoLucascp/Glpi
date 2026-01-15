<?php
declare(strict_types=1);

namespace GlpiPlugin\Newbase\Src;

use CommonDBTM;
use Session;
use Toolbox;
use Exception;

/*******************************************************************************
* Funções utilitárias comuns para o plugin Newbase
* Fornece funções utilitárias compartilhadas para o plugin
*
* @package   PluginNewbase
* @author    João Lucas
* @copyright Copyright © 2026 João Lucas
* @license   GPLv2+
* @since     2.0.0
*
* Plugin Newbase para GLPI.
*******************************************************************************/
class Common extends CommonDBTM
{
    /**
    * Gestão de direitos
    * @var string
    */
    public static $rightname = 'plugin_newbase';

    /**
    * Ativar histórico
    * @var bool
    */
    public $dohistory = true;

    /**
    * Obter nome de exibição
    * @param bool $plural Return plural form
    * @return string
    */
    public static function getDisplayName($plural = false)
    {
        return $plural ? __('Empresas', 'newbase') : __('Empresa', 'newbase');
    }

    /**
    * Verificar se o usuário pode visualizar o item
    * @return bool
    */
    public function canViewItem()
    {
        return Session::haveRight(static::$rightname, READ);
    }

    /**
    * Verificar se o usuário pode criar um item.
    * @return bool
    */
    public function canCreateItem()
    {
        return Session::haveRight(static::$rightname, CREATE);
    }

    /**
    * Verificar se o usuário pode atualizar o item
    * @return bool
    */
    public function canUpdateItem()
    {
        return Session::haveRight(static::$rightname, UPDATE);
    }

    /**
    * Verificar se o usuário pode deletar o item
    * @return bool
    */
    public function canDeleteItem()
    {
        return Session::haveRight(static::$rightname, DELETE);
    }

    /**
    * Calcular distância entre duas coordenadas usando a fórmula de Haversine
    * @param float $lat1 Latitude of point 1
    * @param float $lng1 Longitude of point 1
    * @param float $lat2 Latitude of point 2
    * @param float $lng2 Longitude of point 2
    * @return float Distance in kilometers
    */
    public static function calculateDistance(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371.0; // Raio da Terra em quilômetros

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
                cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
                sin($dLng / 2) * sin($dLng / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadius * $c, 2);
    }

    /**
    * Procurar empresa por CNPJ em APIs brasileiras
    * Tenta 3 APIs na ordem: BrasilAPI → ReceitaWS → MinhaReceita
    * Retorna null se todas falharem
    * @param string $cnpj CNPJ com ou sem formatação
    * @return array|null Dados da empresa(legal_name, fantasy_name, email, phone)
    */
    public static function searchCompanyByCNPJ($cnpj): ?array
    {
        // Remove caracteres especiais
        $cnpj = preg_replace('/[^0-9]/', '', $cnpj);

        if (strlen($cnpj) !== 14) {
            return null;
        }

        $companyData = [
            'legal_name'    => '',
            'fantasy_name'  => '',
            'email'         => '',
            'phone'         => ''
        ];

        // 1. Tentar BrasilAPI (Primeira fonte)
        $brasilApiUrl = "https://brasilapi.com.br/api/cnpj/v1/" . $cnpj;
        $data = self::fetchJson($brasilApiUrl);

        if ($data) {
            $companyData['legal_name'] = $data['razao_social'] ?? $data['nome'] ?? '';
            $companyData['fantasy_name'] = $data['nome_fantasia'] ?? $data['fantasia'] ?? '';
            $companyData['email'] = $data['email'] ?? '';
            $companyData['phone'] = $data['telefone'] ?? $data['ddd_telefone_1'] ?? '';
        }

        // 2. Tentar ReceitaWS se algum campo importante estiver faltando (especialmente email)
        if (empty($companyData['email']) || empty($companyData['legal_name'])) {
            $receitaWsUrl = "https://receitaws.com.br/v1/cnpj/" . $cnpj;
            $data = self::fetchJson($receitaWsUrl);

            if ($data && (!isset($data['status']) || ($data['status'] ?? '') !== 'ERROR')) {
                if (empty($companyData['legal_name'])) {
                    $companyData['legal_name'] = $data['nome'] ?? '';
                }
                if (empty($companyData['fantasy_name'])) {
                    $companyData['fantasy_name'] = $data['fantasia'] ?? '';
                }
                if (empty($companyData['email'])) {
                    $companyData['email'] = $data['email'] ?? '';
                }
                if (empty($companyData['phone'])) {
                    $companyData['phone'] = $data['telefone'] ?? '';
                }
            }
        }

        // 3. Tentar Minha Receita como terceira fallback se email ainda estiver vazio
        if (empty($companyData['email'])) {
            $minhaReceitaUrl = "https://minhareceita.org/" . $cnpj;
            $data = self::fetchJson($minhaReceitaUrl);

            if ($data && !isset($data['error'])) {
                if (empty($companyData['email'])) {
                    $companyData['email'] = $data['email'] ?? '';
                }
                if (empty($companyData['phone']) && !empty($data['telefone'])) {
                    $companyData['phone'] = $data['telefone'];
                }
            }
        }

        // Retornar null se não conseguirmos obter pelo menos o nome legal
        if (empty($companyData['legal_name'])) {
            return null;
        }

        return $companyData;
    }

    /**
    * Buscar dados JSON da URL usando cURL
    * @param string $url URL para buscar
    * @return array|null Decodificado JSON ou null em caso de erro
    */
    private static function fetchJson(string $url): ?array
    {
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_USERAGENT, 'GLPI Newbase Plugin/2.0.0');
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

            $response = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($http_code === 200 && $response) {
                return json_decode($response, true);
            }
        } catch (Exception $e) {
            Toolbox::logInFile('newbase_plugin', "Fetch error for $url: " . $e->getMessage() . "\n");
        }

        return null;
    }

    /**
    * Procurar endereço por CEP via API ViaCEP
    * @param string $cep CEP sem formatação
    * @return array|null Dados do endereço ou null em caso de erro
    */
    public static function searchAddressByCEP(string $cep): ?array
    {
        $cep = preg_replace('/[^0-9]/', '', $cep);

        if (strlen($cep) !== 8) {
            return null;
        }

        try {
            $api_url = "https://viacep.com.br/ws/" . $cep . '/json/';

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $api_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_USERAGENT, 'GLPI Newbase Plugin/2.0.0');
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_MAXREDIRS, 3);

            $response = curl_exec($ch);
            $curl_errno = curl_errno($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($curl_errno !== 0) {
                Toolbox::logInFile('newbase_plugin', "CEP API curl error ($curl_errno)\n");
                return null;
            }

            if ($http_code !== 200 || !$response) {
                Toolbox::logInFile('newbase_plugin', "CEP API error: HTTP $http_code\n");
                return null;
            }

            $data = json_decode($response, true);

            if (!$data || isset($data['erro'])) {
                return null;
            }

            // Obter coordenadas do CEP (aproximadas)
            $coordinates = self::getCoordinatesFromAddress(
                $data['logradouro'] ?? '',
                $data['bairro'] ?? '',
                $data['localidade'] ?? '',
                $data['uf'] ?? ''
            );

            return [
                'street'       => $data['logradouro'] ?? '',
                'neighborhood' => $data['bairro'] ?? '',
                'city'         => $data['localidade'] ?? '',
                'state'        => $data['uf'] ?? '',
                'latitude'     => $coordinates['lat'] ?? null,
                'longitude'    => $coordinates['lng'] ?? null
            ];
        } catch (Exception $e) {
            Toolbox::logInFile('newbase_plugin', "ERROR in searchAddressByCEP(): " . $e->getMessage() . "\n");
            return null;
        }
    }

    /**
     * Obter coordenadas a partir do endereço (geocoding)
     * Usando Nominatim (OpenStreetMap) - gratuito e sem necessidade de chave API
     * @param string $street Nome da rua
     * @param string $neighborhood Bairro
     * @param string $city Cidade
     * @param string $state Estado
     * @return array|null Coordenadas ou null
     */
    public static function getCoordinatesFromAddress(string $street, string $neighborhood, string $city, string $state): ?array
    {
        try {
            $address = implode(', ', array_filter([$street, $neighborhood, $city, $state, 'Brasil']));
            $address = urlencode($address);
            $api_url = "https://nominatim.openstreetmap.org/search?q={$address}&format=json&limit=1";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $api_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_USERAGENT, 'GLPI Newbase Plugin/2.0.0');
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_MAXREDIRS, 3);

            $response = curl_exec($ch);
            $curl_errno = curl_errno($ch);
            curl_close($ch);

            if ($curl_errno !== 0) {
                Toolbox::logInFile('newbase_plugin', "Nominatim API curl error ($curl_errno)\n");
                return null;
            }

            if (!$response) {
                return null;
            }

            $data = json_decode($response, true);

            if (!$data || count($data) === 0) {
                return null;
            }

            return [
                'lat' => floatval($data[0]['lat']),
                'lng' => floatval($data[0]['lon'])
            ];
        } catch (Exception $e) {
            Toolbox::logInFile('newbase_plugin', "ERROR in getCoordinatesFromAddress(): " . $e->getMessage() . "\n");
            return null;
        }
    }

    /**
    * Formate o CNPJ para exibição
    * @param string $cnpj CNPJ sem formatação
    * @return string CNPJ formatado
    */
    public static function formatCNPJ(string $cnpj): string
    {
        $cnpj = preg_replace('/[^0-9]/', '', $cnpj);

        if (strlen($cnpj) !== 14) {
            return $cnpj;
        }

        return preg_replace(
            '/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/',
            '$1.$2.$3/$4-$5',
            $cnpj
        );
    }

    /**
    * Formate o CEP para exibição
    * @param string $cep CEP sem formatação
    * @return string CEP formatado
    */
    public static function formatCEP(string $cep): string
    {
        $cep = preg_replace('/[^0-9]/', '', $cep);

        if (strlen($cep) !== 8) {
            return $cep;
        }

        return preg_replace('/(\d{5})(\d{3})/', '$1-$2', $cep);
    }

    /**
    * Formate o telefone para exibição
    * @param string $phone Telefone sem formatação
    * @return string Telefone formatado
    */
    public static function formatPhone(string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (strlen($phone) === 11) {
            // Celular: (XX) XXXXX-XXXX
            return preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $phone);
        } elseif (strlen($phone) === 10) {
            // Telefone fixo: (XX) XXXX-XXXX
            return preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1) $2-$3', $phone);
        }

        return $phone;
    }

    /**
    * Valide o CNPJ
    * @param string $cnpj CNPJ sem formatação
    * @return bool
    */
    public static function validateCNPJ(string $cnpj): bool
    {
        $cnpj = preg_replace('/[^0-9]/', '', $cnpj);

        if (strlen($cnpj) != 14) {
            return false;
        }

        if (preg_match('/^(\d)\1+$/', $cnpj)) {
            return false;
        }

        // Valide o primeiro dígito verificador
        $sum = 0;
        $weights = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        for ($i = 0; $i < 12; $i++) {
            $sum += intval($cnpj[$i]) * $weights[$i];
        }

        $remainder = $sum % 11;
        $digit1 = $remainder < 2 ? 0 : 11 - $remainder;

        if (intval($cnpj[12]) !== $digit1) {
            return false;
        }

        // Valide o segundo dígito verificador
        $sum = 0;
        $weights = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        for ($i = 0; $i < 13; $i++) {
            $sum += intval($cnpj[$i]) * $weights[$i];
        }

        $remainder = $sum % 11;
        $digit2 = $remainder < 2 ? 0 : 11 - $remainder;

        return (intval($cnpj[13]) === $digit2);
    }
}
