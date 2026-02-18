<?php

/**
 * ---------------------------------------------------------------------
 * Página de Listagem de Tarefas - Plugin Newbase
 * ---------------------------------------------------------------------
 *
 * Este arquivo exibe a lista de tarefas cadastradas com:
 * - Geolocalização (início e fim)
 * - Quilometragem calculada
 * - Assinatura digital
 * - Status (pendente/concluída)
 * - Vinculação com empresas
 *
 * Oferece busca, filtros, paginação e ações em massa.
 * @package   GlpiPlugin\Newbase
 * @author    João Lucas
 * @license   GPLv2+
 */

include ('../../../inc/includes.php');

use GlpiPlugin\Newbase\Task;
use Glpi\Application\Request\CookieableRequest;

// 1. Verificação de Sessão
\Session::checkLoginUser();

// 2. Verificação de Permissões (ANTES do cabeçalho para economizar processamento)
if (!Task::canView()) {
    \Html::displayRightError();
}

// 3. Cabeçalho
\Html::header(
    Task::getTypeName(\Session::getPluralNumber()),
    $_SERVER['PHP_SELF'],
    "plugins",        // Menu pai (geralmente "plugins" para plugins de terceiros)
    "newbase",        // Nome do plugin
    "task"            // Slug do submenu
);

// 4. Motor de Busca
// Usar ::class em vez de string garante compatibilidade com namespaces
\Search::show(Task::class);

// 5. Rodapé
\Html::footer();

// Opção 1: Usar classe Input do GLPI
$entity_id = CookieableRequest::getInstance()
    ->getInteger('entities_id') ?? \Session::getActiveEntity() ?? 0;

// Opção 2: Usar filter_input (PHP padrão, mais portável)
$entity_id = filter_input(INPUT_GET, 'entities_id', FILTER_VALIDATE_INT)
    ?? \Session::getActiveEntity()
    ?? 0;

// Opção 3: Usar isset + filter_var (mais explícito)
if (isset($_GET['entities_id']) && is_numeric($_GET['entities_id'])) {
    $entity_id = (int) $_GET['entities_id'];
} else {
    $entity_id = \Session::getActiveEntity() ?? 0;
}
