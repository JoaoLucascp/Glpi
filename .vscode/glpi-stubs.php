<?php
/**
 * GLPI Constants Stubs + PHP Extensions
 *
 * Este arquivo existe APENAS para o Intelephense reconhecer constantes e funções.
 * NÃO será incluído em produção.
 */

// ========================================
// CONSTANTES DO GLPI
// ========================================

if (!defined('GLPI_ROOT')) {
    define('GLPI_ROOT', '/var/www/html/glpi');
}

if (!defined('GLPI_CONFIG_DIR')) {
    define('GLPI_CONFIG_DIR', GLPI_ROOT . '/config');
}

if (!defined('GLPI_VAR_DIR')) {
    define('GLPI_VAR_DIR', GLPI_ROOT . '/files');
}

if (!defined('GLPI_LOG_DIR')) {
    define('GLPI_LOG_DIR', GLPI_ROOT . '/files/_log');
}

if (!defined('GLPI_DOC_DIR')) {
    define('GLPI_DOC_DIR', GLPI_ROOT . '/files');
}

if (!defined('GLPI_CACHE_DIR')) {
    define('GLPI_CACHE_DIR', GLPI_ROOT . '/files/_cache');
}

if (!defined('GLPI_CRON_DIR')) {
    define('GLPI_CRON_DIR', GLPI_ROOT . '/files/_cron');
}

if (!defined('GLPI_DUMP_DIR')) {
    define('GLPI_DUMP_DIR', GLPI_ROOT . '/files/_dumps');
}

if (!defined('GLPI_GRAPH_DIR')) {
    define('GLPI_GRAPH_DIR', GLPI_ROOT . '/files/_graphs');
}

if (!defined('GLPI_LOCK_DIR')) {
    define('GLPI_LOCK_DIR', GLPI_ROOT . '/files/_lock');
}

if (!defined('GLPI_PICTURE_DIR')) {
    define('GLPI_PICTURE_DIR', GLPI_ROOT . '/files/_pictures');
}

if (!defined('GLPI_PLUGIN_DOC_DIR')) {
    define('GLPI_PLUGIN_DOC_DIR', GLPI_ROOT . '/files/_plugins');
}

if (!defined('GLPI_RSS_DIR')) {
    define('GLPI_RSS_DIR', GLPI_ROOT . '/files/_rss');
}

if (!defined('GLPI_TMP_DIR')) {
    define('GLPI_TMP_DIR', GLPI_ROOT . '/files/_tmp');
}

if (!defined('GLPI_UPLOAD_DIR')) {
    define('GLPI_UPLOAD_DIR', GLPI_ROOT . '/files/_uploads');
}

if (!defined('GLPI_LOG_LVL')) {
    define('GLPI_LOG_LVL', 4);
}

// ========================================
// FUNÇÕES DO ZEND OPCACHE
// ========================================

if (!function_exists('opcache_reset')) {
    /**
     * Resets the contents of the opcode cache
     * @return bool Returns TRUE if the opcode cache was reset, or FALSE if the opcode cache is disabled.
     * @link https://www.php.net/manual/en/function.opcache-reset.php
     */
    function opcache_reset(): bool {
        return true;
    }
}

if (!function_exists('opcache_invalidate')) {
    /**
     * Invalidates a cached script
     * @param string $filename The path to the script being invalidated.
     * @param bool $force If TRUE, the script will be invalidated regardless of whether invalidation is necessary.
     * @return bool Returns TRUE if the opcode cache for filename was invalidated or if there was nothing to invalidate, or FALSE if the opcode cache is disabled.
     * @link https://www.php.net/manual/en/function.opcache-invalidate.php
     */
    function opcache_invalidate(string $filename, bool $force = false): bool {
        return true;
    }
}

if (!function_exists('opcache_get_status')) {
    /**
     * Get status information about the cache
     * @param bool $include_scripts Include script specific state information
     * @return array|false Returns an array of information, optionally containing script specific state information, or FALSE on failure.
     * @link https://www.php.net/manual/en/function.opcache-get-status.php
     */
    function opcache_get_status(bool $include_scripts = true): array|false {
        return [];
    }
}
