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
 * @package   GlpiPlugin - Newbase
 * @author    João Lucas
 * @license   GPLv2+
 */

// 1 SEGURANÇA: Carregar o núcleo do GLPI
include('../../../inc/includes.php');

// 2 SEGURANÇA: Verificar se usuário está logado
Session::checkLoginUser();

// 3 IMPORTAR A CLASSE DO PLUGIN
use GlpiPlugin\Newbase\Task;

// 4 RENDERIZAR CABEÇALHO DO GLPI
Html::header(
    Task::getTypeName(Session::getPluralNumber()),  // Título no plural ("Tarefas")
    $_SERVER['PHP_SELF'],                           // URL atual
    'management',                                   // Categoria do menu
    Task::class,                                    // Classe do item
    'task'                                          // Identificador único
);

// 5 VERIFICAR DIREITOS DE ACESSO
// Se o usuário não tem permissão para ver tarefas, bloqueia acesso
if (!Task::canView()) {
    Html::displayRightError();
}

// 6 EXIBIR O MECANISMO DE BUSCA DO GLPI
// Esta linha cria automaticamente toda a interface de busca!
Search::show('GlpiPlugin\Newbase\Task');

// 7 RENDERIZAR RODAPÉ DO GLPI
Html::footer();
