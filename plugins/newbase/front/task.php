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

// 1. Verificação de Sessão
Session::checkLoginUser();

// 2. Verificação de Permissões (ANTES do cabeçalho para economizar processamento)
if (!Task::canView()) {
    Html::displayRightError();
}

// 3. Cabeçalho
Html::header(
    Task::getTypeName(Session::getPluralNumber()),
    $_SERVER['PHP_SELF'],
    "plugins",        // Menu pai (geralmente "plugins" para plugins de terceiros)
    "newbase",        // Nome do plugin
    "task"            // Slug do submenu
);

// 4. Motor de Busca
// Usar ::class em vez de string garante compatibilidade com namespaces
Search::show(Task::class);

// 5. Rodapé
Html::footer();
