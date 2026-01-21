<?php
/**
* Global Namespace Aliases - Compatibility for GLPI's MassiveAction
*
* This file provides aliases in the global namespace for classes that GLPI's MassiveAction
* and other core systems try to access without full namespace qualification.
*
* @package   PluginNewbase
* @author    João Lucas
* @copyright 2026 João Lucas
* @license   GPLv2+
* @version   2.0.0
*/

declare(strict_types=1);

// Use namespace global (implícito em PHP ao não declarar namespace)

use GlpiPlugin\Newbase\Src\CompanyData as SourceCompanyData;
use GlpiPlugin\Newbase\Src\Address as SourceAddress;
use GlpiPlugin\Newbase\Src\System as SourceSystem;
use GlpiPlugin\Newbase\Src\Task as SourceTask;
use GlpiPlugin\Newbase\Src\TaskSignature as SourceTaskSignature;
use GlpiPlugin\Newbase\Src\Config as SourceConfig;

// Create global namespace aliases for GLPI's MassiveAction and other core systems

if (!class_exists('CompanyData')) {
    class CompanyData extends SourceCompanyData
    {
    }
}

if (!class_exists('Address')) {
    class Address extends SourceAddress
    {
    }
}

if (!class_exists('System')) {
    class System extends SourceSystem
    {
    }
}

if (!class_exists('Task')) {
    class Task extends SourceTask
    {
    }
}

if (!class_exists('TaskSignature')) {
    class TaskSignature extends SourceTaskSignature
    {
    }
}

if (!class_exists('Config')) {
    class Config extends SourceConfig
    {
    }
}
