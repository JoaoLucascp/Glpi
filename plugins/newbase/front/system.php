<?php

include ('../../../inc/includes.php');

use GlpiPlugin\Newbase\System;

// 1. Verificação de Sessão
\Session::checkLoginUser();

// 2. Verificação de Permissões (ANTES do cabeçalho para economizar processamento)
if (!System::canView()) {
    \Html::displayRightError();
}

// 3. Cabeçalho
\Html::header(
    System::getTypeName(\Session::getPluralNumber()),
    $_SERVER['PHP_SELF'],
    "plugins",        // Menu pai (geralmente "plugins" para plugins de terceiros)
    "newbase",        // Nome do plugin
    "system"          // Slug do submenu
);

// 4. Motor de Busca
// Usar ::class em vez de string garante compatibilidade com namespaces
\Search::show(System::class);

// 5. Rodapé
\Html::footer();
