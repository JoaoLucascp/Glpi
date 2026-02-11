<?php

/**
* ---------------------------------------------------------------------
* Dashboard Principal - Plugin Newbase
* ---------------------------------------------------------------------
*
* Este arquivo exibe a página inicial do plugin com:
* - Estatísticas gerais (empresas, sistemas, tarefas)
* - Tarefas recentes
* - Links rápidos para principais funcionalidades
* @package   Plugin - Newbase
* @author    João Lucas
* @license   GPLv2+
*/

// 1 SEGURANÇA: Carregar o núcleo do GLPI
include('../../../inc/includes.php');

// 2 SEGURANÇA: Verificar se usuário está logado
Session::checkLoginUser();

// 3 IMPORTAR CLASSES DO PLUGIN
use GlpiPlugin\Newbase\CompanyData;
use GlpiPlugin\Newbase\System;
use GlpiPlugin\Newbase\Task;

// 4 RENDERIZAR CABEÇALHO DO GLPI
Html::header(
    __('Newbase Dashboard', 'newbase'),
    $_SERVER['PHP_SELF'],
    'management',
    'GlpiPlugin\Newbase\Menu',
    'dashboard'
);

// COLETAR ESTATÍSTICAS

// 5 CONTAR EMPRESAS ATIVAS
$company = new CompanyData();
$total_companies = countElementsInTable(
    'glpi_plugin_newbase_company_extras',
    ['is_deleted' => 0]
);

// 6 CONTAR SISTEMAS ATIVOS
$system = new System();
$total_systems = countElementsInTable(
    'glpi_plugin_newbase_systems',
    ['is_deleted' => 0]
);

// 7 CONTAR TAREFAS POR STATUS
$total_tasks = countElementsInTable(
    'glpi_plugin_newbase_tasks',
    ['is_deleted' => 0]
);

$pending_tasks = countElementsInTable(
    'glpi_plugin_newbase_tasks',
    [
        'is_deleted'   => 0,
        'is_completed' => 0
    ]
);

$completed_tasks = countElementsInTable(
    'glpi_plugin_newbase_tasks',
    [
        'is_deleted'   => 0,
        'is_completed' => 1
    ]
);

// BUSCAR TAREFAS RECENTES

global $DB;

// 8 QUERY: Últimas 10 tarefas criadas
$recent_tasks_query = "
    SELECT
        t.id,
        t.description AS title,
        t.is_completed,
        t.date_creation,
        e.name AS company_name
    FROM glpi_plugin_newbase_tasks AS t
    LEFT JOIN glpi_plugin_newbase_company_extras AS c
        ON t.entities_id = c.entities_id
    LEFT JOIN glpi_entities AS e
        ON c.entities_id = e.id
    WHERE t.is_deleted = 0
    ORDER BY t.date_creation DESC
    LIMIT 10
";

$recent_tasks_result = $DB->query($recent_tasks_query);
$recent_tasks = [];

if ($recent_tasks_result) {
    while ($row = $DB->fetchAssoc($recent_tasks_result)) {
        $recent_tasks[] = $row;
    }
}

// RENDERIZAR DASHBOARD
?>

<!-- 9 GRID DE ESTATÍSTICAS -->
<div class="dashboard-grid">

    <!-- Card 1: Empresas -->
    <div class="dashboard-card">
        <div class="card-icon">
            <i class="ti ti-building"></i>
        </div>
        <div class="card-content">
            <h3><?php echo $total_companies; ?></h3>
            <p><?php echo __('Total Companies', 'newbase'); ?></p>
        </div>
        <div class="card-action">
            <a href="<?php echo $CFG_GLPI['root_doc']; ?>/plugins/newbase/front/companydata.php">
                <?php echo __('View all', 'newbase'); ?> →
            </a>
        </div>
    </div>

    <!-- Card 2: Sistemas -->
    <div class="dashboard-card">
        <div class="card-icon">
            <i class="ti ti-phone"></i>
        </div>
        <div class="card-content">
            <h3><?php echo $total_systems; ?></h3>
            <p><?php echo __('Phone Systems', 'newbase'); ?></p>
        </div>
        <div class="card-action">
            <a href="<?php echo $CFG_GLPI['root_doc']; ?>/plugins/newbase/front/system.php">
                <?php echo __('View all', 'newbase'); ?> →
            </a>
        </div>
    </div>

    <!-- Card 3: Tarefas Pendentes -->
    <div class="dashboard-card">
        <div class="card-icon status-pending">
            <i class="ti ti-clock"></i>
        </div>
        <div class="card-content">
            <h3><?php echo $pending_tasks; ?></h3>
            <p><?php echo __('Pending Tasks', 'newbase'); ?></p>
        </div>
        <div class="card-action">
            <a href="<?php echo $CFG_GLPI['root_doc']; ?>/plugins/newbase/front/task.php">
                <?php echo __('View all', 'newbase'); ?> →
            </a>
        </div>
    </div>

    <!-- Card 4: Tarefas Concluídas -->
    <div class="dashboard-card">
        <div class="card-icon status-completed">
            <i class="ti ti-check"></i>
        </div>
        <div class="card-content">
            <h3><?php echo $completed_tasks; ?></h3>
            <p><?php echo __('Completed Tasks', 'newbase'); ?></p>
        </div>
        <div class="card-action">
            <span class="progress-bar">
                <?php
                $completion_rate = $total_tasks > 0
                    ? round(($completed_tasks / $total_tasks) * 100)
                    : 0;
                echo $completion_rate . '%';
                ?>
            </span>
        </div>
    </div>

</div>

<!-- 10 TABELA DE TAREFAS RECENTES -->
<div class="dashboard-section">
    <h2>
        <i class="ti ti-list"></i>
        <?php echo __('Recent Tasks', 'newbase'); ?>
    </h2>

    <?php if (count($recent_tasks) > 0) : ?>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th><?php echo __('Title', 'newbase'); ?></th>
                    <th><?php echo __('Company', 'newbase'); ?></th>
                    <th><?php echo __('Status', 'newbase'); ?></th>
                    <th><?php echo __('Created', 'newbase'); ?></th>
                    <th><?php echo __('Actions', 'newbase'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recent_tasks as $task) : ?>
                    <tr>
                        <td>
                            <a href="<?php echo $CFG_GLPI['root_doc']; ?>/plugins/newbase/front/task.form.php?id=<?php echo $task['id']; ?>">
                                <?php echo htmlspecialchars($task['title']); ?>
                            </a>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($task['company_name'] ?? '-'); ?>
                        </td>
                        <td>
                            <?php if ($task['is_completed']) : ?>
                                <span class="badge badge-success">
                                    <i class="ti ti-check"></i>
                                    <?php echo __('Completed', 'newbase'); ?>
                                </span>
                            <?php else : ?>
                                <span class="badge badge-warning">
                                    <i class="ti ti-clock"></i>
                                    <?php echo __('Pending', 'newbase'); ?>
                                </span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php echo Html::convDateTime($task['date_creation']); ?>
                        </td>
                        <td>
                            <a href="<?php echo $CFG_GLPI['root_doc']; ?>/plugins/newbase/front/task.form.php?id=<?php echo $task['id']; ?>" 
                                class="btn btn-sm btn-primary">
                                <?php echo __('View', 'newbase'); ?>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <?php else : ?>
        <div class="alert alert-info">
            <i class="ti ti-info-circle"></i>
            <?php echo __('No recent tasks', 'newbase'); ?>
        </div>

    <?php endif; ?>

</div>

<!-- 11 LINKS RÁPIDOS -->
<div class="dashboard-section">
    <h2>
        <i class="ti ti-rocket"></i>
        <?php echo __('Quick Actions', 'newbase'); ?>
    </h2>

    <div class="quick-actions">

        <a href="<?php echo $CFG_GLPI['root_doc']; ?>/plugins/newbase/front/companydata.form.php"
            class="quick-action-btn">
            <i class="ti ti-building-plus"></i>
            <?php echo __('New Company', 'newbase'); ?>
        </a>

        <a href="<?php echo $CFG_GLPI['root_doc']; ?>/plugins/newbase/front/system.form.php"
            class="quick-action-btn">
            <i class="ti ti-phone-plus"></i>
            <?php echo __('New System', 'newbase'); ?>
        </a>

        <a href="<?php echo $CFG_GLPI['root_doc']; ?>/plugins/newbase/front/task.form.php"
            class="quick-action-btn">
            <i class="ti ti-checkbox-plus"></i>
            <?php echo __('New Task', 'newbase'); ?>
        </a>

        <a href="<?php echo $CFG_GLPI['root_doc']; ?>/plugins/newbase/front/report.php"
            class="quick-action-btn">
            <i class="ti ti-file-report"></i>
            <?php echo __('Reports', 'newbase'); ?>
        </a>

    </div>

</div>

<!-- 12 ADICIONAR CSS CUSTOMIZADO -->
<style>
.dashboard-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin: 20px 0;
}

.dashboard-card {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 20px;
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.dashboard-card .card-icon {
    font-size: 32px;
    color: #2196F3;
}

.dashboard-card .card-icon.status-pending {
    color: #FF9800;
}

.dashboard-card .card-icon.status-completed {
    color: #4CAF50;
}

.dashboard-card h3 {
    font-size: 36px;
    font-weight: bold;
    margin: 0;
    color: #333;
}

.dashboard-card p {
    color: #666;
    margin: 0;
}

.dashboard-card .card-action a {
    color: #2196F3;
    text-decoration: none;
    font-weight: 500;
}

.dashboard-section {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 20px;
    margin: 20px 0;
}

.dashboard-section h2 {
    margin-top: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.quick-actions {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
}

.quick-action-btn {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 15px;
    background: #2196F3;
    color: white;
    text-decoration: none;
    border-radius: 6px;
    font-weight: 500;
    transition: background 0.3s;
}

.quick-action-btn:hover {
    background: #1976D2;
}

.badge {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 500;
}

.badge-success {
    background: #4CAF50;
    color: white;
}

.badge-warning {
    background: #FF9800;
    color: white;
}
</style>

<?php
// 13 RENDERIZAR RODAPÉ DO GLPI
Html::footer();
?>
