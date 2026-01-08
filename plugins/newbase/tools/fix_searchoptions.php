<?php
/**
 * Script de Correção Automática - Plugin Newbase
 * 
 * Este script corrige automaticamente os erros de SearchOptions
 * Executar via linha de comando ou browser
 * 
 * Uso: php fix_searchoptions.php
 */

// Configurações
define('GLPI_ROOT', 'D:/laragon/www/glpi');
define('PLUGIN_PATH', GLPI_ROOT . '/plugins/newbase');

// Cores para output no terminal
class Colors {
    public static $RED = "\033[31m";
    public static $GREEN = "\033[32m";
    public static $YELLOW = "\033[33m";
    public static $BLUE = "\033[34m";
    public static $RESET = "\033[0m";
}

// Função para output colorido
function output($message, $color = null, $newline = true) {
    if (PHP_SAPI === 'cli') {
        echo ($color ?? Colors::$RESET) . $message . Colors::$RESET;
        if ($newline) echo "\n";
    } else {
        $style = '';
        switch ($color) {
            case Colors::$RED: $style = 'color: red;'; break;
            case Colors::$GREEN: $style = 'color: green;'; break;
            case Colors::$YELLOW: $style = 'color: orange;'; break;
            case Colors::$BLUE: $style = 'color: blue;'; break;
        }
        echo "<p style='$style'>$message</p>";
    }
}

output("╔═══════════════════════════════════════════════════════╗", Colors::$BLUE);
output("║   CORREÇÃO AUTOMÁTICA - PLUGIN NEWBASE               ║", Colors::$BLUE);
output("║   SearchOptions & Database Structure                 ║", Colors::$BLUE);
output("╚═══════════════════════════════════════════════════════╝", Colors::$BLUE);
output("");

// Passo 1: Verificar estrutura de diretórios
output("[ 1/6 ] Verificando estrutura de diretórios...", Colors::$YELLOW);

$requiredDirs = [
    GLPI_ROOT,
    PLUGIN_PATH,
    PLUGIN_PATH . '/src',
];

$allDirsExist = true;
foreach ($requiredDirs as $dir) {
    if (!is_dir($dir)) {
        output("  ✗ Diretório não encontrado: $dir", Colors::$RED);
        $allDirsExist = false;
    } else {
        output("  ✓ $dir", Colors::$GREEN);
    }
}

if (!$allDirsExist) {
    output("\n❌ Estrutura de diretórios incompleta. Verifique a instalação.", Colors::$RED);
    exit(1);
}

// Passo 2: Criar backup do arquivo CompanyData.php
output("\n[ 2/6 ] Criando backup do CompanyData.php...", Colors::$YELLOW);

$companyDataFile = PLUGIN_PATH . '/src/CompanyData.php';
$backupFile = PLUGIN_PATH . '/src/CompanyData.php.backup_' . date('YmdHis');

if (file_exists($companyDataFile)) {
    if (copy($companyDataFile, $backupFile)) {
        output("  ✓ Backup criado: $backupFile", Colors::$GREEN);
    } else {
        output("  ✗ Erro ao criar backup", Colors::$RED);
        exit(1);
    }
} else {
    output("  ⚠ Arquivo CompanyData.php não encontrado", Colors::$YELLOW);
}

// Passo 3: Gerar conteúdo corrigido
output("\n[ 3/6 ] Gerando código corrigido...", Colors::$YELLOW);

$correctedContent = <<<'PHP'
<?php

/**
 * -------------------------------------------------------------------------
 * Newbase plugin for GLPI
 * -------------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of Newbase.
 *
 * Newbase is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * Newbase is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */

namespace GlpiPlugin\Newbase;

use CommonDBTM;
use Session;
use Html;

if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access directly to this file");
}

class CompanyData extends CommonDBTM
{
    public static $rightname = 'plugin_newbase_companydata';
    
    public static function getTypeName($nb = 0)
    {
        return __('Company Data', 'newbase');
    }

    public function getSearchOptionsNew()
    {
        $tab = [];

        // Common
        $tab[] = [
            'id'                 => 'common',
            'name'               => __('Characteristics')
        ];

        // ID: 1 - Nome
        $tab[] = [
            'id'                 => '1',
            'table'              => $this->getTable(),
            'field'              => 'name',
            'name'               => __('Name'),
            'datatype'           => 'itemlink',
            'massiveaction'      => false,
            'forcegroupby'       => true,
            'autocomplete'       => true,
        ];

        // ID: 2 - ID
        $tab[] = [
            'id'                 => '2',
            'table'              => $this->getTable(),
            'field'              => 'id',
            'name'               => __('ID'),
            'massiveaction'      => false,
            'datatype'           => 'number',
            'forcegroupby'       => true
        ];

        // ID: 3 - CNPJ
        $tab[] = [
            'id'                 => '3',
            'table'              => $this->getTable(),
            'field'              => 'cnpj',
            'name'               => __('CNPJ', 'newbase'),
            'datatype'           => 'string',
            'massiveaction'      => false,
            'forcegroupby'       => true
        ];

        // ID: 4 - Razão Social
        $tab[] = [
            'id'                 => '4',
            'table'              => $this->getTable(),
            'field'              => 'corporate_name',
            'name'               => __('Razão Social', 'newbase'),
            'datatype'           => 'string',
            'massiveaction'      => false,
            'forcegroupby'       => true
        ];

        // ID: 5 - Nome Fantasia
        $tab[] = [
            'id'                 => '5',
            'table'              => $this->getTable(),
            'field'              => 'fantasy_name',
            'name'               => __('Nome Fantasia', 'newbase'),
            'datatype'           => 'string',
            'massiveaction'      => false,
            'forcegroupby'       => true
        ];

        // ID: 6 - Filial
        $tab[] = [
            'id'                 => '6',
            'table'              => $this->getTable(),
            'field'              => 'branch',
            'name'               => __('Filial', 'newbase'),
            'datatype'           => 'string',
            'massiveaction'      => false,
            'forcegroupby'       => true
        ];

        // ID: 7 - Inscrição Federal
        $tab[] = [
            'id'                 => '7',
            'table'              => $this->getTable(),
            'field'              => 'federal_registration',
            'name'               => __('Inscrição Federal', 'newbase'),
            'datatype'           => 'string',
            'massiveaction'      => false,
            'forcegroupby'       => true
        ];

        // ID: 8 - Inscrição Estadual
        $tab[] = [
            'id'                 => '8',
            'table'              => $this->getTable(),
            'field'              => 'state_registration',
            'name'               => __('Inscrição Estadual', 'newbase'),
            'datatype'           => 'string',
            'massiveaction'      => false,
            'forcegroupby'       => true
        ];

        // ID: 9 - Inscrição Municipal
        $tab[] = [
            'id'                 => '9',
            'table'              => $this->getTable(),
            'field'              => 'city_registration',
            'name'               => __('Inscrição Municipal', 'newbase'),
            'datatype'           => 'string',
            'massiveaction'      => false,
            'forcegroupby'       => true
        ];

        // ID: 10 - Status do Contrato
        $tab[] = [
            'id'                 => '10',
            'table'              => $this->getTable(),
            'field'              => 'contract_status',
            'name'               => __('Status do Contrato', 'newbase'),
            'datatype'           => 'string',
            'massiveaction'      => false,
            'forcegroupby'       => true
        ];

        // ID: 11 - Data de Criação
        $tab[] = [
            'id'                 => '11',
            'table'              => $this->getTable(),
            'field'              => 'date_creation',
            'name'               => __('Data de criação', 'newbase'),
            'datatype'           => 'datetime',
            'massiveaction'      => false,
            'forcegroupby'       => true
        ];

        // ID: 12 - Data de Modificação
        $tab[] = [
            'id'                 => '12',
            'table'              => $this->getTable(),
            'field'              => 'date_mod',
            'name'               => __('Data de modificação', 'newbase'),
            'datatype'           => 'datetime',
            'massiveaction'      => false,
            'forcegroupby'       => true
        ];

        // ID: 80 - Entidade
        $tab[] = [
            'id'                 => '80',
            'table'              => 'glpi_entities',
            'field'              => 'completename',
            'name'               => __('Entity'),
            'datatype'           => 'dropdown',
            'massiveaction'      => false,
            'forcegroupby'       => true
        ];

        // ID: 86 - Recursivo
        $tab[] = [
            'id'                 => '86',
            'table'              => $this->getTable(),
            'field'              => 'is_recursive',
            'name'               => __('Child entities'),
            'datatype'           => 'bool',
            'massiveaction'      => false,
            'forcegroupby'       => true
        ];

        return $tab;
    }

    public function rawSearchOptions()
    {
        return $this->getSearchOptionsNew();
    }

    public function defineTabs($options = [])
    {
        $ong = [];
        $this->addDefaultFormTab($ong);
        $this->addStandardTab('Log', $ong, $options);
        return $ong;
    }

    public function showForm($ID, array $options = [])
    {
        global $CFG_GLPI;

        $this->initForm($ID, $options);
        $this->showFormHeader($options);

        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Name') . "</td>";
        echo "<td>";
        echo Html::input('name', ['value' => $this->fields['name'], 'size' => 40]);
        echo "</td>";
        echo "<td>" . __('CNPJ', 'newbase') . "</td>";
        echo "<td>";
        echo Html::input('cnpj', ['value' => $this->fields['cnpj'], 'size' => 20]);
        echo "</td>";
        echo "</tr>";

        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Razão Social', 'newbase') . "</td>";
        echo "<td>";
        echo Html::input('corporate_name', ['value' => $this->fields['corporate_name'], 'size' => 40]);
        echo "</td>";
        echo "<td>" . __('Nome Fantasia', 'newbase') . "</td>";
        echo "<td>";
        echo Html::input('fantasy_name', ['value' => $this->fields['fantasy_name'], 'size' => 40]);
        echo "</td>";
        echo "</tr>";

        $this->showFormButtons($options);

        return true;
    }

    public function prepareInputForAdd($input)
    {
        if (!isset($input['date_creation'])) {
            $input['date_creation'] = $_SESSION['glpi_currenttime'];
        }
        return $input;
    }

    public function prepareInputForUpdate($input)
    {
        $input['date_mod'] = $_SESSION['glpi_currenttime'];
        return $input;
    }
}
PHP;

output("  ✓ Código corrigido gerado", Colors::$GREEN);

// Passo 4: Salvar arquivo corrigido
output("\n[ 4/6 ] Salvando arquivo corrigido...", Colors::$YELLOW);

if (file_put_contents($companyDataFile, $correctedContent)) {
    output("  ✓ Arquivo salvo com sucesso", Colors::$GREEN);
} else {
    output("  ✗ Erro ao salvar arquivo", Colors::$RED);
    exit(1);
}

// Passo 5: Verificar banco de dados
output("\n[ 5/6 ] Verificando banco de dados...", Colors::$YELLOW);

if (!file_exists(GLPI_ROOT . '/config/config_db.php')) {
    output("  ⚠ Arquivo de configuração do banco não encontrado", Colors::$YELLOW);
    output("  ℹ Execute o script SQL manualmente", Colors::$BLUE);
} else {
    output("  ✓ Configuração do banco encontrada", Colors::$GREEN);
    output("  ℹ Execute este SQL no MySQL:", Colors::$BLUE);
    output("");
    output("DESCRIBE glpi_plugin_newbase_companydata;", Colors::$YELLOW);
    output("");
}

// Passo 6: Limpar cache
output("\n[ 6/6 ] Limpando cache do GLPI...", Colors::$YELLOW);

$cacheDirs = [
    GLPI_ROOT . '/files/_cache',
    GLPI_ROOT . '/files/_sessions',
];

foreach ($cacheDirs as $cacheDir) {
    if (is_dir($cacheDir)) {
        $files = glob($cacheDir . '/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                @unlink($file);
            }
        }
        output("  ✓ Cache limpo: $cacheDir", Colors::$GREEN);
    }
}

// Resultado final
output("");
output("╔═══════════════════════════════════════════════════════╗", Colors::$GREEN);
output("║   ✓ CORREÇÃO CONCLUÍDA COM SUCESSO!                  ║", Colors::$GREEN);
output("╚═══════════════════════════════════════════════════════╝", Colors::$GREEN);
output("");
output("Próximos passos:", Colors::$BLUE);
output("1. Reinicie o Apache/Laragon", Colors::$YELLOW);
output("2. No GLPI, vá em Configurar > Plugins", Colors::$YELLOW);
output("3. Desinstale e reinstale o plugin Newbase", Colors::$YELLOW);
output("4. Teste a pesquisa no menu de Company Data", Colors::$YELLOW);
output("");
output("Arquivo de backup salvo em:", Colors::$BLUE);
output($backupFile, Colors::$GREEN);
output("");
output("Se houver problemas, restaure o backup:", Colors::$BLUE);
output("cp $backupFile $companyDataFile", Colors::$YELLOW);
output("");

?>
