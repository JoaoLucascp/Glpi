<?php
/**
* Legacy Class Aliases - Compatibility layer for GLPI's automatic class discovery
*
* This file provides aliases for all Newbase classes to the namespace structure expected by GLPI
* DbUtils::getItemTypeForTable() method which looks for classes without the Src\ namespace
* @package   PluginNewbase
* @author    João Lucas
* @copyright 2026 João Lucas
* @license   GPLv2+
* @version   2.0.0
*/

declare(strict_types=1);

namespace GlpiPlugin\Newbase;

// Import all source classes
use GlpiPlugin\Newbase\Src\CompanyData as SourceCompanyData;
use GlpiPlugin\Newbase\Src\Address as SourceAddress;
use GlpiPlugin\Newbase\Src\System as SourceSystem;
use GlpiPlugin\Newbase\Src\Task as SourceTask;
use GlpiPlugin\Newbase\Src\TaskSignature as SourceTaskSignature;
use GlpiPlugin\Newbase\Src\Config as SourceConfig;

// Create aliases for GLPI's automatic discovery (without the Src\ namespace)

if (!class_exists('GlpiPlugin\Newbase\CompanyData')) {
    class CompanyData extends SourceCompanyData
    {
    }
}

if (!class_exists('GlpiPlugin\Newbase\Address')) {
    class Address extends SourceAddress
    {
    }
}

if (!class_exists('GlpiPlugin\Newbase\System')) {
    class System extends SourceSystem
    {
    }
}

if (!class_exists('GlpiPlugin\Newbase\Task')) {
    class Task extends SourceTask
    {
    }
}

if (!class_exists('GlpiPlugin\Newbase\TaskSignature')) {
    class TaskSignature extends SourceTaskSignature
    {
    }
}

if (!class_exists('GlpiPlugin\Newbase\Config')) {
    class Config extends SourceConfig
    {
    }
}

