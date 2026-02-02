<?php

/**
* ---------------------------------------------------------------------
* Página de Listagem de Empresas - Plugin Newbase
* ---------------------------------------------------------------------
*
* Este arquivo exibe a lista de empresas cadastradas no plugin,
* com sistema de busca, filtros, paginação e ações em massa.
* @package   Plugin - Newbase
* @author    João Lucas
* @license   GPLv2+
*/

// 1 SEGURANÇA: Carregar o núcleo do GLPI
include('../../../inc/includes.php');

// 2 SEGURANÇA: Verificar se usuário está logado
Session::checkLoginUser();

// 3 IMPORTAR A CLASSE DO PLUGIN
use GlpiPlugin\Newbase\CompanyData;

// 4 RENDERIZAR CABEÇALHO DO GLPI
Html::header(
    CompanyData::getTypeName(Session::getPluralNumber()),  // Título da página (plural)
    $_SERVER['PHP_SELF'],                                   // URL atual
    'management',                                           // Categoria do menu
    CompanyData::class,                                     // Classe do item
    'companydata'                                           // Identificador único
);

// 5 VERIFICAR DIREITOS DE ACESSO
// Se o usuário não tem permissão para ver empresas, bloqueia acesso
if (!CompanyData::canView()) {
    Html::displayRightError();
}

// 6 EXIBIR O MECANISMO DE BUSCA DO GLPI
// Esta é a MAGIA do GLPI: uma linha só cria toda a interface de busca!
Search::show('GlpiPlugin\Newbase\CompanyData');

// 7 RENDERIZAR RODAPÉ DO GLPI
Html::footer();
