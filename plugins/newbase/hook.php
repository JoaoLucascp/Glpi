<?php
/**
 * Hooks file for Newbase Plugin
 *
 * @package   PluginNewbase
 */

declare(strict_types=1);

use GlpiPlugin\Newbase\CompanyData;
use GlpiPlugin\Newbase\Task;
use GlpiPlugin\Newbase\System;
use GlpiPlugin\Newbase\Address;
use GlpiPlugin\Newbase\Config;
use CommonDBTM;
use Entity;
use MassiveAction;
use Toolbox;
use Html;

// Composer autoloader
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
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
                    'FROM'  => $companydata->getTable(),
                    'WHERE' => ['entities_id' => $item->getID()],
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
    } catch (Throwable $e) {
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
    } catch (Throwable $e) {
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
        if (in_array($item::class, [CompanyData::class, Task::class], true)) {
            Toolbox::logInFile('newbase_plugin', $item->getType() . " updated: ID " . $item->getID() . "\n");
        }
    } catch (Throwable $e) {
        Toolbox::logInFile('newbase_plugin', "ERROR in newbase_item_update(): " . $e->getMessage() . "\n");
    }
}

/**
 * Hook called to add actions to massive actions
 *
 * @param string $itemtype The itemtype
 * @return array
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
 * @return array
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
                'jointype' => 'child',
            ],
        ];

        $sopt[9001] = [
            'table'         => 'glpi_plugin_newbase_companydata',
            'field'         => 'cnpj',
            'name'          => __('CNPJ', 'newbase'),
            'datatype'      => 'string',
            'massiveaction' => false,
            'joinparams'    => [
                'jointype' => 'child',
            ],
        ];
    }

    return $sopt;
}

/**
 * Hook called to define rights for profiles
 *
 * @return array
 */
function newbase_getRights(): array
{
    return [
        'plugin_newbase_companydata' => [
            'name'   => __('Company Data', 'newbase'),
            'rights' => [
                READ   => __('Read'),
                CREATE => __('Create'),
                UPDATE => __('Update'),
                DELETE => __('Delete'),
                PURGE  => __('Purge'),
            ],
        ],
        'plugin_newbase_task' => [
            'name'   => __('Tasks', 'newbase'),
            'rights' => [
                READ   => __('Read'),
                CREATE => __('Create'),
                UPDATE => __('Update'),
                DELETE => __('Delete'),
                PURGE  => __('Purge'),
            ],
        ],
        'plugin_newbase_system' => [
            'name'   => __('Systems', 'newbase'),
            'rights' => [
                READ   => __('Read'),
                CREATE => __('Create'),
                UPDATE => __('Update'),
                DELETE => __('Delete'),
                PURGE  => __('Purge'),
            ],
        ],
        'plugin_newbase_config' => [
            'name'   => __('Configuration', 'newbase'),
            'rights' => [
                READ   => __('Read'),
                UPDATE => __('Update'),
            ],
        ],
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
    $item  = $params['item'];
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
                    'active'    => __('Active', 'newbase'),
                    'inactive'  => __('Inactive', 'newbase'),
                    'cancelled' => __('Cancelled', 'newbase'),
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
        $iterator    = $DB->request([
            'FROM'  => $companydata->getTable(),
            'WHERE' => ['entities_id' => $entity->getID()],
        ]);

        foreach ($iterator as $data) {
            $companydata->delete(['id' => $data['id']], true);
        }

        Toolbox::logInFile('newbase_plugin', "Cleaned all data for entity " . $entity->getID() . "\n");
    } catch (Throwable $e) {
        Toolbox::logInFile('newbase_plugin', "ERROR in newbase_cleanForEntity(): " . $e->getMessage() . "\n");
    }
}
