<?php

/**
* ---------------------------------------------------------------------
* Página de Listagem de Sistemas - Plugin Newbase
* ---------------------------------------------------------------------
*
* Este arquivo exibe a lista de sistemas telefônicos cadastrados:
* - Asterisk (IPBX local)
* - CloudPBX (IPBX em nuvem)
* - Chatbot (Sistema omnichannel)
* - VoIP (Linha fixa)
*
* Oferece busca, filtros, paginação e ações em massa.
* @package   Plugin - Newbase
* @author    João Lucas
* @license   GPLv2+
*/

// 1 SEGURANÇA: Carregar o núcleo do GLPI
include('../../../inc/includes.php');

// 2 SEGURANÇA: Verificar se usuário está logado
Session::checkLoginUser();

// 3 IMPORTAR A CLASSE DO PLUGIN
use GlpiPlugin\Newbase\System;

// 4 RENDERIZAR CABEÇALHO DO GLPI
Html::header(
    System::getTypeName(Session::getPluralNumber()),  // Título no plural
    $_SERVER['PHP_SELF'],                             // URL atual
    'management',                                     // Categoria do menu
    System::class,                                    // Classe do item
    'system'                                          // Identificador único
);

// 5 VERIFICAR DIREITOS DE ACESSO
// Se o usuário não tem permissão para ver sistemas, bloqueia acesso
if (!System::canView()) {
    Html::displayRightError();
}

// 6 EXIBIR O MECANISMO DE BUSCA DO GLPI
// Esta linha cria automaticamente toda a interface de busca!
Search::show('GlpiPlugin\Newbase\System');

// 7 RENDERIZAR RODAPÉ DO GLPI
Html::footer();
