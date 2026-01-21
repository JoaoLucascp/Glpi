// Certifique-se de que CompanyData é incluído apenas UMA VEZ
// Remova linhas duplicadas como:
// - require_once PLUGIN_NEWBASE_DIR . '/src/CompanyData.php';
// - include()/include_once() repetidas

// Use apenas:
require_once PLUGIN_NEWBASE_DIR . '/vendor/autoload.php';

// O Composer autoloader cuida da inclusão automática
// Não inclua classes manualmente se estiver usando PSR-4
