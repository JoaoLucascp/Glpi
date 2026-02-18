<?php

include ('../../../inc/includes.php');

// Importação da classe com Namespace (Padrão GLPI 10)
use GlpiPlugin\Newbase\CompanyData;

// 1. Verificação de Sessão
\Session::checkLoginUser();

// 2. Verificação de Permissão de Leitura (READ)
// Se o usuário não puder ver, o GLPI já exibe o erro e para o script aqui.
if (!CompanyData::canView()) {
    \Html::displayRightError();
}

// 3. Cabeçalho
// Parâmetros: Título, URL atual, Menu Pai (plugins), Nome do Plugin (newbase), Slug do Menu (companydata)
\Html::header(
    CompanyData::getTypeName(\Session::getPluralNumber()),
    $_SERVER['PHP_SELF'],
    "plugins",
    "newbase",
    "companydata"
);

// 4. Mecanismo de Busca (Search Engine)
// Usar ::class garante que o namespace esteja sempre correto
\Search::show(CompanyData::class);

// 5. Rodapé
\Html::footer();
