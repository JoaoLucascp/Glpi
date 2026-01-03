<?php
/**
 * GLPI CSRF Token Bug Fix - PASSIVE SYNC VERSION
 * 
 * This file fixes GLPI's CSRF bug by passively synchronizing tokens
 * WITHOUT interfering with token creation
 * 
 * @package   PluginNewbase
 * @author    João Lucas
 * @license   GPLv2+
 */

if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access this file directly");
}

/**
 * Passive CSRF token synchronization
 * 
 * GLPI Bug: Saves to 'glpicsrftokens', validates from 'glpi_csrf_tokens'
 * 
 * This passive sync:
 * 1. Waits for GLPI to create tokens naturally
 * 2. Copies from wrong key to correct key ONLY if needed
 * 3. Never deletes or interferes with token creation
 */
function plugin_newbase_passive_csrf_sync() {
    if (!isset($_SESSION)) {
        return false;
    }
    
    $synced = false;
    
    // PASSIVE MODE: Only sync if wrong key has tokens and correct key doesn't
    
    // Check if wrong key has tokens
    $has_wrong_tokens = isset($_SESSION['glpicsrftokens']) && 
                       is_array($_SESSION['glpicsrftokens']) && 
                       !empty($_SESSION['glpicsrftokens']);
    
    // Check if correct key is empty or missing
    $needs_correct_tokens = !isset($_SESSION['glpi_csrf_tokens']) || 
                           !is_array($_SESSION['glpi_csrf_tokens']) || 
                           empty($_SESSION['glpi_csrf_tokens']);
    
    // ONLY sync if wrong key has tokens AND correct key needs them
    if ($has_wrong_tokens && $needs_correct_tokens) {
        // Copy all tokens from wrong key to correct key
        $_SESSION['glpi_csrf_tokens'] = $_SESSION['glpicsrftokens'];
        $synced = true;
        
        if (function_exists('Toolbox::logInFile')) {
            Toolbox::logInFile('newbase_csrf_fix', sprintf(
                "[%s] Passive CSRF Sync: Copied %d tokens from 'glpicsrftokens' to 'glpi_csrf_tokens'\n",
                date('Y-m-d H:i:s'),
                count($_SESSION['glpicsrftokens'])
            ));
        }
    }
    
    // ALSO: Keep them in sync if both exist
    if ($has_wrong_tokens && isset($_SESSION['glpi_csrf_tokens']) && is_array($_SESSION['glpi_csrf_tokens'])) {
        // Merge both arrays to ensure all tokens are in both places
        $merged = array_merge($_SESSION['glpi_csrf_tokens'], $_SESSION['glpicsrftokens']);
        $_SESSION['glpi_csrf_tokens'] = $merged;
        $_SESSION['glpicsrftokens'] = $merged;
    }
    
    return $synced;
}

// Auto-sync on include (passive mode)
plugin_newbase_passive_csrf_sync();
