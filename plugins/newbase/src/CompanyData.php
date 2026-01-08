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

public function getSearchOptions()
{
    $tab = [];

    $tab['common'] = __('Características');

    $tab[1] = [
        'id'            => 1,
        'table'         => static::getTable(),
        'field'         => 'name',
        'name'          => __('Nome'),
        'datatype'      => 'itemlink',
        'massiveaction' => false,
        'forcegroupby'  => true,
        'autocomplete'  => true,
    ];

    $tab[2] = [
        'id'            => 2,
        'table'         => static::getTable(),
        'field'         => 'id',
        'name'          => __('ID'),
        'massiveaction' => false,
        'datatype'      => 'number',
        'forcegroupby'  => true,
    ];

    $tab[3] = [
        'id'            => 3,
        'table'         => static::getTable(),
        'field'         => 'cnpj',
        'name'          => __('CNPJ'),
        'datatype'      => 'string',
        'massiveaction' => false,
        'forcegroupby'  => true,
    ];

    $tab[4] = [
        'id'            => 4,
        'table'         => static::getTable(),
        'field'         => 'corporate_name',
        'name'          => __('Razão Social'),
        'datatype'      => 'string',
        'massiveaction' => false,
        'forcegroupby'  => true,
    ];

    $tab[5] = [
        'id'            => 5,
        'table'         => static::getTable(),
        'field'         => 'fantasy_name',
        'name'          => __('Nome Fantasia'),
        'datatype'      => 'string',
        'massiveaction' => false,
        'forcegroupby'  => true,
    ];

    $tab[6] = [
        'id'            => 6,
        'table'         => static::getTable(),
        'field'         => 'branch',
        'name'          => __('Filial'),
        'datatype'      => 'string',
        'massiveaction' => false,
        'forcegroupby'  => true,
    ];

    $tab[7] = [
        'id'            => 7,
        'table'         => static::getTable(),
        'field'         => 'federal_registration',
        'name'          => __('Inscrição Federal'),
        'datatype'      => 'string',
        'massiveaction' => false,
        'forcegroupby'  => true,
    ];

    $tab[8] = [
        'id'            => 8,
        'table'         => static::getTable(),
        'field'         => 'state_registration',
        'name'          => __('Inscrição Estadual'),
        'datatype'      => 'string',
        'massiveaction' => false,
        'forcegroupby'  => true,
    ];

    $tab[9] = [
        'id'            => 9,
        'table'         => static::getTable(),
        'field'         => 'city_registration',
        'name'          => __('Inscrição Municipal'),
        'datatype'      => 'string',
        'massiveaction' => false,
        'forcegroupby'  => true,
    ];

    $tab[10] = [
        'id'            => 10,
        'table'         => static::getTable(),
        'field'         => 'contract_status',
        'name'          => __('Status do Contrato'),
        'datatype'      => 'string',
        'massiveaction' => false,
        'forcegroupby'  => true,
    ];

    $tab[11] = [
        'id'            => 11,
        'table'         => static::getTable(),
        'field'         => 'date_creation',
        'name'          => __('Data de Criação'),
        'datatype'      => 'datetime',
        'massiveaction' => false,
        'forcegroupby'  => true,
    ];

    $tab[12] = [
        'id'            => 12,
        'table'         => static::getTable(),
        'field'         => 'date_mod',
        'name'          => __('Data de Modificação'),
        'datatype'      => 'datetime',
        'massiveaction' => false,
        'forcegroupby'  => true,
    ];

    $tab[80] = [
        'id'            => 80,
        'table'         => 'glpi_entities',
        'field'         => 'completename',
        'name'          => __('Entity'),
        'datatype'      => 'dropdown',
        'massiveaction' => false,
        'forcegroupby'  => true,
    ];

    $tab[86] = [
        'id'            => 86,
        'table'         => static::getTable(),
        'field'         => 'is_recursive',
        'name'          => __('Child entities'),
        'datatype'      => 'bool',
        'massiveaction' => false,
        'forcegroupby'  => true,
    ];

    return $tab;
}

public function rawSearchOptions()
{
    return $this->getSearchOptions();
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