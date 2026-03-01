<?php

include('../../../inc/includes.php');

// Importação da classe com Namespace (Padrão GLPI 10)
use GlpiPlugin\Newbase\CompanyData;

// 1. Verificação de Sessão
\Session::checkLoginUser();

// 2. Verificação de Permissão de Leitura (READ)
// Se o usuário não puder ver, o GLPI já exibe o erro e para o script aqui.
if (!CompanyData::canView()) {
    \Html::displayRightError();
}

if (!isset($_GET['itemtype'])) {
    $_GET['itemtype'] = 'GlpiPlugin\\Newbase\\CompanyData';
}
if (!isset($_GET['sort']))  { $_GET['sort']  = 1; }
if (!isset($_GET['order'])) { $_GET['order'] = 'ASC'; }
if (!isset($_GET['start'])) { $_GET['start'] = 0; }


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
\Search::show($_GET['itemtype']);

// O GLPI gera o onclick com o nome do form usando backslashes do namespace PHP:
// document.forms['searchformglpiplugin\newbase\companydata']
// Em JS, \n vira newline e \c fica corrompido, então o form nunca é encontrado.
// Solução: sobrescrever o onclick diretamente com o nome correto que o GLPI renderiza no DOM
// (tudo minúsculo, sem separadores): 'searchformglpipluginnewbasecompanydata'
echo '<script>
document.addEventListener("DOMContentLoaded", function() {
    var trashToggle = document.querySelector(".search-controls input[name=\'is_deleted\']")
        || document.querySelector("input[name=\'is_deleted\'][type=\'checkbox\']");
    if (!trashToggle) return;

    trashToggle.onclick = function() {
        toogle(\'is_deleted\', \'\', \'\', \'\');
        var form = document.forms[\'searchformglpipluginnewbasecompanydata\'];
        if (form) form.submit();
    };
});
</script>';


// 5. Rodapé
\Html::footer();
