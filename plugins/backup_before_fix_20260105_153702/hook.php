<?php
/**
 * Hooks file for Newbase Plugin
 *
 * This file handles all GLPI hooks for the Newbase plugin
 * Compatible with GLPI 10.0.20
 *
 * @package   PluginNewbase
 * @author    JoÃ£o Lucas
 * @copyright Copyright (c) 2025 JoÃ£o Lucas
 * @license   GPLv2+
 * @since     2.0.0
 */

declare(strict_types=1);

use GlpiPlugin\Newbase\CompanyData;
use GlpiPlugin\Newbase\Task;
use GlpiPlugin\Newbase\System;
use GlpiPlugin\Newbase\Address;
use GlpiPlugin\Newbase\Config;

// Composer autoloader
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once(__DIR__ . '/vendor/autoload.php');
}

/**
 * Hook called when an item is purged
 *
 * @param CommonDBTM $item The item being purged
 * @return void
 */
function newbase_item_purge(CommonDBTM $item): void
{
    global $DB;

    try {
        switch ($item->getType()) {
            case 'Entity':
                // Clean CompanyData when entity is purged
                $companydata = new CompanyData();
                $iterator = $DB->request([
                    'FROM' => $companydata->getTable(),
                    'WHERE' => ['entities_id' => $item->getID()]
                ]);

                foreach ($iterator as $data) {
                    $companydata->delete(['id' => $data['id']], true);
                }

                Toolbox::logInFile('newbase_plugin', "Cleaned CompanyData for entity " . $item->getID() . "\n");
                break;

            case 'User':
                // Update tasks when user is purged
                $DB->update(
                    'glpi_plugin_newbase_task',
                    ['assigned_to' => null],
                    ['assigned_to' => $item->getID()]
                );

                Toolbox::logInFile('newbase_plugin', "Updated tasks for deleted user " . $item->getID() . "\n");
                break;
        }
    } catch (Exception $e) {
        Toolbox::logInFile('newbase_plugin', "ERROR in newbase_item_purge(): " . $e->getMessage() . "\n");
    }
}

/**
 * Hook called when an item is added
 *
 * @param CommonDBTM $item The item being added
 * @return void
 */
function newbase_item_add(CommonDBTM $item): void
{
    try {
        if ($item->getType() === 'Entity') {
            Toolbox::logInFile('newbase_plugin', "New entity created: " . $item->getID() . "\n");
        }
    } catch (Exception $e) {
        Toolbox::logInFile('newbase_plugin', "ERROR in newbase_item_add(): " . $e->getMessage() . "\n");
    }
}

/**
 * Hook called when an item is updated
 *
 * @param CommonDBTM $item The item being updated
 * @return void
 */
function newbase_item_update(CommonDBTM $item): void
{
    try {
        // Log specific updates if needed
        if (in_array($item::class, [CompanyData::class, Task::class])) {
            Toolbox::logInFile('newbase_plugin', $item->getType() . " updated: ID " . $item->getID() . "\n");
        }
    } catch (Exception $e) {
        Toolbox::logInFile('newbase_plugin', "ERROR in newbase_item_update(): " . $e->getMessage() . "\n");
    }
}

/**
 * Hook called to add actions to massive actions
 *
 * @param string $itemtype The itemtype
 * @return array Array of massive actions
 */
function newbase_MassiveActions(string $itemtype): array
{
    $actions = [];

    switch ($itemtype) {
        case CompanyData::class:
            $actions[CompanyData::class . MassiveAction::CLASS_ACTION_SEPARATOR . 'activate']
                = __('Activate contract', 'newbase');
            $actions[CompanyData::class . MassiveAction::CLASS_ACTION_SEPARATOR . 'deactivate']
                = __('Deactivate contract', 'newbase');
            $actions[CompanyData::class . MassiveAction::CLASS_ACTION_SEPARATOR . 'cancel']
                = __('Cancel contract', 'newbase');
            break;

        case Task::class:
            $actions[Task::class . MassiveAction::CLASS_ACTION_SEPARATOR . 'assign']
                = __('Assign tasks', 'newbase');
            $actions[Task::class . MassiveAction::CLASS_ACTION_SEPARATOR . 'complete']
                = __('Complete tasks', 'newbase');
            break;

        case System::class:
            $actions[System::class . MassiveAction::CLASS_ACTION_SEPARATOR . 'activate']
                = __('Activate systems', 'newbase');
            $actions[System::class . MassiveAction::CLASS_ACTION_SEPARATOR . 'deactivate']
                = __('Deactivate systems', 'newbase');
            break;
    }

    return $actions;
}

/**
 * Hook called to add search options
 *
 * @param string $itemtype The itemtype
 * @return array Array of search options
 */
function newbase_getAddSearchOptions(string $itemtype): array
{
    $sopt = [];

    if ($itemtype === 'Entity') {
        $sopt[9000] = [
            'table'         => 'glpi_plugin_newbase_companydata',
            'field'         => 'name',
            'name'          => __('Company Data', 'newbase'),
            'datatype'      => 'dropdown',
            'forcegroupby'  => true,
            'massiveaction' => false,
            'joinparams'    => [
                'jointype'   => 'child'
            ]
        ];

        $sopt[9001] = [
            'table'         => 'glpi_plugin_newbase_companydata',
            'field'         => 'cnpj',
            'name'          => __('CNPJ', 'newbase'),
            'datatype'      => 'string',
            'massiveaction' => false,
            'joinparams'    => [
                'jointype'   => 'child'
            ]
        ];
    }

    return $sopt;
}

/**
 * Hook called to define rights for profiles
 *
 * @return array Rights definition
 */
function newbase_getRights(): array
{
    return [
        'plugin_newbase_companydata' => [
            'name'   => __('Company Data', 'newbase'),
            'rights' => [
                READ    => __('Read'),
                CREATE  => __('Create'),
                UPDATE  => __('Update'),
                DELETE  => __('Delete'),
                PURGE   => __('Purge')
            ]
        ],
        'plugin_newbase_task' => [
            'name'   => __('Tasks', 'newbase'),
            'rights' => [
                READ    => __('Read'),
                CREATE  => __('Create'),
                UPDATE  => __('Update'),
                DELETE  => __('Delete'),
                PURGE   => __('Purge')
            ]
        ],
        'plugin_newbase_system' => [
            'name'   => __('Systems', 'newbase'),
            'rights' => [
                READ    => __('Read'),
                CREATE  => __('Create'),
                UPDATE  => __('Update'),
                DELETE  => __('Delete'),
                PURGE   => __('Purge')
            ]
        ],
        'plugin_newbase_config' => [
            'name'   => __('Configuration', 'newbase'),
            'rights' => [
                READ    => __('Read'),
                UPDATE  => __('Update')
            ]
        ]
    ];
}

/**
 * Hook called to display specific fields
 *
 * @param array $params Parameters
 * @return void
 */
function newbase_displaySpecificFields(array $params): void
{
    $item = $params['item'];
    $field = $params['field'];

    if ($item instanceof CompanyData) {
        switch ($field) {
            case 'cnpj':
                echo Html::formatNumber($item->fields['cnpj'], false, 0);
                break;
            case 'phone':
                echo $item->fields['phone'];
                break;
            case 'contract_status':
                $status_labels = [
                    'active' => __('Active', 'newbase'),
                    'inactive' => __('Inactive', 'newbase'),
                    'cancelled' => __('Cancelled', 'newbase')
                ];
                echo $status_labels[$item->fields['contract_status']] ?? $item->fields['contract_status'];
                break;
        }
    }
}

/**
 * Clean data for entity
 *
 * @param Entity $entity The entity
 * @return void
 */
function newbase_cleanForEntity(Entity $entity): void
{
    global $DB;

    try {
        $companydata = new CompanyData();
        $iterator = $DB->request([
            'FROM' => $companydata->getTable(),
            'WHERE' => ['entities_id' => $entity->getID()]
        ]);

        foreach ($iterator as $data) {
            $companydata->delete(['id' => $data['id']], true);
        }

        Toolbox::logInFile('newbase_plugin', "Cleaned all data for entity " . $entity->getID() . "\n");
    } catch (Exception $e) {
        Toolbox::logInFile('newbase_plugin', "ERROR in newbase_cleanForEntity(): " . $e->getMessage() . "\n");
    }
}

/**
 * Funcao de instalacao do plugin
 *
 * @return boolean
 */
function plugin_newbase_install()
{
    global $DB;
    
    $migration = new Migration(PLUGIN_NEWBASE_VERSION);
    
    // Carregar SQL de instalacao
    $sqlFile = PLUGIN_NEWBASE_DIR . '/install/mysql/2.0.0.sql';
    
    if (!file_exists($sqlFile)) {
        echo "Arquivo SQL nao encontrado: $sqlFile\n";
        return false;
    }
    
    $sql = file_get_contents($sqlFile);
    
    // Dividir em comandos individuais
    $commands = array_filter(
        array_map('trim', explode(';', $sql)),
        function ($cmd) {
            return !empty($cmd);
        }
    );
    
    // Executar cada comando
    foreach ($commands as $command) {
        if (!empty($command)) {
            try {
                $DB->query($command) or die("Erro ao executar: $command\n" . $DB->error());
            } catch (Exception $e) {
                echo "Erro na instalacao: " . $e->getMessage() . "\n";
                return false;
            }
        }
    }
    
    $migration->executeMigration();
    
    return true;
}

/**
 * Funcao de desinstalacao do plugin
 *
 * @return boolean
 */
function plugin_newbase_uninstall()
{
    global $DB;
    
    $tables = [
        'glpi_plugin_newbase_companydatas',
        'glpi_plugin_newbase_addresses',
        'glpi_plugin_newbase_systems',
        'glpi_plugin_newbase_tasks',
        'glpi_plugin_newbase_tasksignatures',
        'glpi_plugin_newbase_configs'
    ];
    
    foreach ($tables as $table) {
        $DB->query("DROP TABLE IF EXISTS `$table`") or die("Erro ao remover tabela $table\n");
    }
    
    // Remover direitos
    $query = "DELETE FROM `glpi_profilerights` 
              WHERE `name` LIKE 'plugin_newbase_%'";
    $DB->query($query);
    
    // Remover displays
    $query = "DELETE FROM `glpi_displaypreferences` 
              WHERE `itemtype` LIKE 'PluginNewbase%'";
    $DB->query($query);
    
    return true;
}
