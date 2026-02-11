<?php

/**
* ---------------------------------------------------------------------
* Relatórios e Estatísticas - Plugin Newbase
* ---------------------------------------------------------------------
*
* Este arquivo exibe relatórios analíticos sobre:
* - Status de tarefas
* - Quilometragem (total, média)
* - Tarefas por empresa
* - Tarefas por usuário
*
* @package   GlpiPlugin\Newbase
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

// 4 VERIFICAR PERMISSÕES DE VISUALIZAÇÃO
if (!Task::canView()) {
    Html::displayRightError();
}

// 5 PROCESSAR FILTROS DE PERÍODO (opcional)
$period = $_GET['period'] ?? 'all';
$date_filter = '';

switch ($period) {
    case 'today':
        $date_filter = " AND DATE(t.date_creation) = CURDATE()";
        break;
    case 'week':
        $date_filter = " AND t.date_creation >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
        break;
    case 'month':
        $date_filter = " AND MONTH(t.date_creation) = MONTH(NOW())
                        AND YEAR(t.date_creation) = YEAR(NOW())";
        break;
    case 'year':
        $date_filter = " AND YEAR(t.date_creation) = YEAR(NOW())";
        break;
    default:
        $date_filter = ''; // all
}

// COLETAR DADOS DOS RELATÓRIOS

global $DB;

// 6 RELATÓRIO 1: Tarefas por Status
$status_query = "
    SELECT
        is_completed,
        COUNT(*) AS count
    FROM glpi_plugin_newbase_tasks AS t
    WHERE is_deleted = 0
    $date_filter
    GROUP BY is_completed
";

$status_result = $DB->query($status_query);
$status_data = [
    'pending' => 0,
    'completed' => 0
];

if ($status_result) {
    while ($row = $DB->fetchAssoc($status_result)) {
        if ($row['is_completed'] == 1) {
            $status_data['completed'] = (int) $row['count'];
        } else {
            $status_data['pending'] = (int) $row['count'];
        }
    }
}

$status_data['total'] = $status_data['pending'] + $status_data['completed'];

// 7 RELATÓRIO 2: Quilometragem
$mileage_query = "
    SELECT
        SUM(mileage) AS total_mileage,
        AVG(mileage) AS avg_mileage,
        COUNT(CASE WHEN mileage > 0 THEN 1 END) AS task_count
    FROM glpi_plugin_newbase_tasks AS t
    WHERE is_deleted = 0
    $date_filter
";

$mileage_result = $DB->query($mileage_query);
$mileage_data = [
    'total_mileage' => 0,
    'avg_mileage' => 0,
    'task_count' => 0
];

if ($mileage_result && $row = $DB->fetchAssoc($mileage_result)) {
    $mileage_data = [
        'total_mileage' => (float) ($row['total_mileage'] ?? 0),
        'avg_mileage' => (float) ($row['avg_mileage'] ?? 0),
        'task_count' => (int) ($row['task_count'] ?? 0)
    ];
}

// 8 RELATÓRIO 3: Tarefas por Empresa
$company_query = "
    SELECT
        e.name AS company_name,
        COUNT(t.id) AS task_count
    FROM glpi_plugin_newbase_tasks AS t
    LEFT JOIN glpi_entities AS e ON t.entities_id = e.id
    WHERE t.is_deleted = 0
    $date_filter
    GROUP BY t.entities_id, e.name
    ORDER BY task_count DESC
    LIMIT 10
";

$company_result = $DB->query($company_query);
$company_data = [];

if ($company_result) {
    while ($row = $DB->fetchAssoc($company_result)) {
        $company_data[] = $row;
    }
}

// 9 RELATÓRIO 4: Tarefas por Usuário
$user_query = "
    SELECT
        u.name AS user_name,
        COUNT(t.id) AS task_count
    FROM glpi_plugin_newbase_tasks AS t
    LEFT JOIN glpi_users AS u ON t.users_id = u.id
    WHERE t.is_deleted = 0
    AND t.users_id > 0
    $date_filter
    GROUP BY t.users_id, u.name
    ORDER BY task_count DESC
    LIMIT 10
";

$user_result = $DB->query($user_query);
$user_data = [];

if ($user_result) {
    while ($row = $DB->fetchAssoc($user_result)) {
        $user_data[] = $row;
    }
}

// RENDERIZAR PÁGINA

// 10 CABEÇALHO
Html::header(
    __('Reports', 'newbase'),
    $_SERVER['PHP_SELF'],
    'management',
    'GlpiPlugin\Newbase\Menu',
    'report'
);

?>

<!-- 11 FILTROS DE PERÍODO -->
<div class="report-filters">
    <h2><?php echo __('Period Filter', 'newbase'); ?></h2>
    <form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <select name="period" onchange="this.form.submit()">
            <option value="all" <?php echo $period === 'all' ? 'selected' : ''; ?>>
                <?php echo __('All Time', 'newbase'); ?>
            </option>
            <option value="today" <?php echo $period === 'today' ? 'selected' : ''; ?>>
                <?php echo __('Today', 'newbase'); ?>
            </option>
            <option value="week" <?php echo $period === 'week' ? 'selected' : ''; ?>>
                <?php echo __('Last 7 Days', 'newbase'); ?>
            </option>
            <option value="month" <?php echo $period === 'month' ? 'selected' : ''; ?>>
                <?php echo __('Current Month', 'newbase'); ?>
            </option>
            <option value="year" <?php echo $period === 'year' ? 'selected' : ''; ?>>
                <?php echo __('Current Year', 'newbase'); ?>
            </option>
        </select>
    </form>
</div>

<!-- 12 CARDS DE RESUMO -->
<div class="report-summary">

    <div class="summary-card">
        <div class="card-icon pending">
            <i class="ti ti-clock"></i>
        </div>
        <div class="card-value"><?php echo $status_data['pending']; ?></div>
        <div class="card-label"><?php echo __('Pending Tasks', 'newbase'); ?></div>
    </div>

    <div class="summary-card">
        <div class="card-icon completed">
            <i class="ti ti-check"></i>
        </div>
        <div class="card-value"><?php echo $status_data['completed']; ?></div>
        <div class="card-label"><?php echo __('Completed Tasks', 'newbase'); ?></div>
    </div>

    <div class="summary-card">
        <div class="card-icon total">
            <i class="ti ti-sum"></i>
        </div>
        <div class="card-value"><?php echo $status_data['total']; ?></div>
        <div class="card-label"><?php echo __('Total Tasks', 'newbase'); ?></div>
    </div>

    <div class="summary-card">
        <div class="card-icon mileage">
            <i class="ti ti-route"></i>
        </div>
        <div class="card-value">
            <?php echo number_format($mileage_data['total_mileage'], 2, ',', '.'); ?> km
        </div>
        <div class="card-label"><?php echo __('Total Mileage', 'newbase'); ?></div>
    </div>

</div>

<!-- 13 RELATÓRIO: Quilometragem Detalhada -->
<div class="report-section">
    <h2>
        <i class="ti ti-car"></i>
        <?php echo __('Mileage Statistics', 'newbase'); ?>
    </h2>

    <table class="table table-striped">
        <thead>
            <tr>
                <th><?php echo __('Metric', 'newbase'); ?></th>
                <th><?php echo __('Value', 'newbase'); ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?php echo __('Total Mileage', 'newbase'); ?></td>
                <td><?php echo number_format($mileage_data['total_mileage'], 2, ',', '.'); ?> km</td>
            </tr>
            <tr>
                <td><?php echo __('Average Mileage per Task', 'newbase'); ?></td>
                <td><?php echo number_format($mileage_data['avg_mileage'], 2, ',', '.'); ?> km</td>
            </tr>
            <tr>
                <td><?php echo __('Tasks with Mileage', 'newbase'); ?></td>
                <td><?php echo $mileage_data['task_count']; ?></td>
            </tr>
        </tbody>
    </table>
</div>

<!-- 14 RELATÓRIO: Tarefas por Empresa -->
<div class="report-section">
    <h2>
        <i class="ti ti-building"></i>
        <?php echo __('Tasks by Company', 'newbase'); ?>
    </h2>

    <?php if (count($company_data) > 0) : ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th><?php echo __('Company', 'newbase'); ?></th>
                    <th><?php echo __('Tasks', 'newbase'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($company_data as $row) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['company_name']); ?></td>
                        <td><?php echo $row['task_count']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p class="alert alert-info"><?php echo __('No data available', 'newbase'); ?></p>
    <?php endif; ?>
</div>

<!-- 15 RELATÓRIO: Tarefas por Usuário -->
<div class="report-section">
    <h2>
        <i class="ti ti-user"></i>
        <?php echo __('Tasks by User', 'newbase'); ?>
    </h2>

    <?php if (count($user_data) > 0) : ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th><?php echo __('User', 'newbase'); ?></th>
                    <th><?php echo __('Tasks', 'newbase'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($user_data as $row) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['user_name']); ?></td>
                        <td><?php echo $row['task_count']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p class="alert alert-info"><?php echo __('No data available', 'newbase'); ?></p>
    <?php endif; ?>
</div>

<!-- 16 CSS CUSTOMIZADO -->
<style>
.report-filters {
    background: #fff;
    padding: 20px;
    margin: 20px 0;
    border: 1px solid #ddd;
    border-radius: 8px;
}

.report-filters select {
    padding: 8px 12px;
    font-size: 14px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

.report-summary {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin: 20px 0;
}

.summary-card {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 20px;
    text-align: center;
}

.summary-card .card-icon {
    font-size: 40px;
    margin-bottom: 10px;
}

.summary-card .card-icon.pending { color: #FF9800; }
.summary-card .card-icon.completed { color: #4CAF50; }
.summary-card .card-icon.total { color: #2196F3; }
.summary-card .card-icon.mileage { color: #9C27B0; }

.summary-card .card-value {
    font-size: 32px;
    font-weight: bold;
    color: #333;
    margin: 10px 0;
}

.summary-card .card-label {
    font-size: 14px;
    color: #666;
}

.report-section {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 20px;
    margin: 20px 0;
}

.report-section h2 {
    margin-top: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}
</style>

<?php
// 17 RODAPÉ
Html::footer();
?>
