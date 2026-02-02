<?php

/**
* ---------------------------------------------------------------------
* Formulário de Gerenciamento de Tarefas - Plugin Newbase
* ---------------------------------------------------------------------
*
* Este arquivo processa ações CRUD para tarefas com funcionalidades especiais:
* - Cálculo automático de quilometragem via geolocalização
* - Assinatura digital
* - Geolocalização de início e fim
* - Vinculação com empresas
* @package   GlpiPlugin - Newbase
* @author    João Lucas
* @license   GPLv2+
*/

// 1 SEGURANÇA: Carregar o núcleo do GLPI
include('../../../inc/includes.php');

// 2 SEGURANÇA: Verificar se usuário está logado
Session::checkLoginUser();

// 3 IMPORTAR CLASSES DO PLUGIN
use GlpiPlugin\Newbase\Task;
use GlpiPlugin\Newbase\CompanyData;
use GlpiPlugin\Newbase\Config;
use GlpiPlugin\Newbase\Common;

// 4 CRIAR INSTÂNCIA DO OBJETO TASK
$task = new Task();

// PROCESSAMENTO DE AÇÕES (POST)

// 5 AÇÃO: ADICIONAR NOVA TAREFA
if (isset($_POST['add'])) {

    // 🔒 CSRF: Verificar token de segurança
    Session::checkCSRF($_POST);

    // Verificar direitos de criação
    $task->check(-1, CREATE, $_POST);

    // CÁLCULO AUTOMÁTICO DE QUILOMETRAGEM

    // Verificar se cálculo automático está habilitado na config
    if (Config::getConfigValue('autocalculatemileage', 1) == 1) {

        // Verificar se todas as coordenadas foram fornecidas
        if (!empty($_POST['latitude_start']) && !empty($_POST['longitude_start'])
            && !empty($_POST['latitude_end']) && !empty($_POST['longitude_end'])) {

            // Calcular distância usando fórmula de Haversine
            $_POST['mileage'] = Common::calculateDistance(
                (float) $_POST['latitude_start'],
                (float) $_POST['longitude_start'],
                (float) $_POST['latitude_end'],
                (float) $_POST['longitude_end']
            );
        }
    }

    // Tentar adicionar tarefa
    $newID = $task->add($_POST);

    if ($newID) {
        Session::addMessageAfterRedirect(
            __('Task added successfully', 'newbase'),
            false,
            INFO
        );

        // Redirecionar de volta para a empresa (se veio de lá)
        if (isset($_POST['plugin_newbase_companydata_id']) && $_POST['plugin_newbase_companydata_id'] > 0) {
            Html::redirect($CFG_GLPI['root_doc'] . '/plugins/newbase/front/companydata.form.php?id=' . $_POST['plugin_newbase_companydata_id']);
        } else {
            // Ou redirecionar para a nova tarefa criada
            Html::redirect($CFG_GLPI['root_doc'] . '/plugins/newbase/front/task.form.php?id=' . $newID);
        }
    } else {
        Session::addMessageAfterRedirect(
            __('Error creating task', 'newbase'),
            false,
            ERROR
        );
        Html::back();
    }

// 6 AÇÃO: ATUALIZAR TAREFA EXISTENTE
} elseif (isset($_POST['update'])) {

    Session::checkCSRF($_POST);

    // Verificar direitos de atualização
    $task->check($_POST['id'], UPDATE);


    // RECALCULAR QUILOMETRAGEM SE COORDENADAS MUDARAM


    if (Config::getConfigValue('autocalculatemileage', 1) == 1) {

        if (!empty($_POST['latitude_start']) && !empty($_POST['longitude_start'])
            && !empty($_POST['latitude_end']) && !empty($_POST['longitude_end'])) {

            $_POST['mileage'] = Common::calculateDistance(
                (float) $_POST['latitude_start'],
                (float) $_POST['longitude_start'],
                (float) $_POST['latitude_end'],
                (float) $_POST['longitude_end']
            );
        }
    }

    if ($task->update($_POST)) {
        Session::addMessageAfterRedirect(
            __('Task updated successfully', 'newbase'),
            false,
            INFO
        );
        Html::back();
    } else {
        Session::addMessageAfterRedirect(
            __('Error updating task', 'newbase'),
            false,
            ERROR
        );
        Html::back();
    }

// 7 AÇÃO: DELETAR TAREFA (soft delete - vai para lixeira)
} elseif (isset($_POST['delete'])) {

    Session::checkCSRF($_POST);

    // Verificar direitos de deleção
    $task->check($_POST['id'], DELETE);

    if ($task->delete($_POST)) {
        Session::addMessageAfterRedirect(
            __('Task deleted successfully', 'newbase'),
            false,
            INFO
        );
        Html::redirect($CFG_GLPI['root_doc'] . '/plugins/newbase/front/task.php');
    } else {
        Session::addMessageAfterRedirect(
            __('Error deleting task', 'newbase'),
            false,
            ERROR
        );
        Html::back();
    }

// 8 AÇÃO: PURGAR TAREFA (hard delete - remove permanentemente)
} elseif (isset($_POST['purge'])) {

    Session::checkCSRF($_POST);

    // Verificar direitos de purga
    $task->check($_POST['id'], PURGE);

    if ($task->delete($_POST, 1)) {
        Session::addMessageAfterRedirect(
            __('Task purged successfully', 'newbase'),
            false,
            INFO
        );
        Html::redirect($CFG_GLPI['root_doc'] . '/plugins/newbase/front/task.php');
    } else {
        Session::addMessageAfterRedirect(
            __('Error purging task', 'newbase'),
            false,
            ERROR
        );
        Html::back();
    }
}

// EXIBIÇÃO DO FORMULÁRIO (GET)

// 9 VALIDAR E SANITIZAR PARÂMETROS DA URL
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($id === false || $id === null) {
    $id = 0;
}
$id = max(0, $id);

$company_id = filter_input(INPUT_GET, 'plugin_newbase_companydata_id', FILTER_VALIDATE_INT);
if ($company_id === false || $company_id === null) {
    $company_id = 0;
}
$company_id = max(0, $company_id);

// 10 RENDERIZAR CABEÇALHO DO GLPI
Html::header(
    Task::getTypeName(1),
    $_SERVER['PHP_SELF'],
    'management',
    CompanyData::class,
    'task'
);

// 11 CARREGAR DADOS DA TAREFA (se estiver editando)
if ($id > 0) {
    // Modo edição: usar método padrão do GLPI
    if (!$task->getFromDB($id)) {
        Session::addMessageAfterRedirect(
            __('Task not found', 'newbase'),
            false,
            ERROR
        );
        Html::displayErrorAndDie(__('Task not found', 'newbase'));
    }
} elseif ($company_id > 0) {
    // Modo criação: pré-preencher ID da empresa
    $task->fields['entities_id'] = $company_id;
}

// 12 EXIBIR FORMULÁRIO
$task->showForm($id, [
    'plugin_newbase_companydata_id' => $company_id
]);

// 13 RENDERIZAR RODAPÉ DO GLPI
Html::footer();
