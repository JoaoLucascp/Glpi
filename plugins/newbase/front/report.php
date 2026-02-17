<?php

include ('../../../inc/includes.php');

use GlpiPlugin\Newbase\Task;

// 1. Verificação de Sessão
Session::checkLoginUser();

// 2. Verificação de Permissões
if (!Task::canView()) {
    Html::displayRightError();
}

// 3. Cabeçalho
Html::header(
    __('Reports', 'newbase'),
    $_SERVER['PHP_SELF'],
    'management',
    'GlpiPlugin\Newbase\CompanyData', // Ajuste conforme seu menu pai real
    'report'
);

// --- PROCESSAMENTO DE FILTROS ---

$period = $_GET['period'] ?? 'all';
$where_date = [];

// Usando lógica de array para WHERE (Padrão GLPI)
switch ($period) {
    case 'today':
        $where_date = ['DATE(t.date_creation)' => date('Y-m-d')];
        break;
    case 'week':
        $where_date = ['t.date_creation' => ['>=', new \QueryExpression('DATE_SUB(NOW(), INTERVAL 7 DAY)')]];
        break;
    case 'month':
        $where_date = [
            'MONTH(t.date_creation)' => date('m'),
            'YEAR(t.date_creation)'  => date('Y')
        ];
        break;
    case 'year':
        $where_date = ['YEAR(t.date_creation)' => date('Y')];
        break;
    default:
        $where_date = []; // All time
}

// Base criteria para todas as buscas
$base_criteria = [
    'FROM'  => 'glpi_plugin_newbase_tasks AS t',
    'WHERE' => array_merge(['t.is_deleted' => 0], $where_date)
];

// --- COLETA DE DADOS (DBUtils Iterator) ---

// 1. Status Counter
$iterator = $DB->request([
    'SELECT' => ['t.is_completed', 'COUNT(*) AS count'],
    'GROUP'  => ['t.is_completed']
] + $base_criteria);

$status_data = ['pending' => 0, 'completed' => 0, 'total' => 0];

foreach ($iterator as $row) {
    if ($row['is_completed']) {
        $status_data['completed'] = $row['count'];
    } else {
        $status_data['pending'] = $row['count'];
    }
}
$status_data['total'] = $status_data['pending'] + $status_data['completed'];

// 2. Mileage Stats
$iterator = $DB->request([
    'SELECT' => [
        'SUM(t.mileage) AS total_mileage',
        'AVG(t.mileage) AS avg_mileage',
        'COUNT(t.id) AS task_count'
    ],
    'WHERE'  => array_merge($base_criteria['WHERE'], ['t.mileage' => ['>', 0]])
] + $base_criteria);

$mileage_data = ['total' => 0, 'avg' => 0, 'count' => 0];
foreach ($iterator as $row) {
    $mileage_data = [
        'total' => (float)($row['total_mileage'] ?? 0),
        'avg'   => (float)($row['avg_mileage'] ?? 0),
        'count' => (int)($row['task_count'] ?? 0)
    ];
}

// 3. Top Empresas
$iterator_companies = $DB->request([
    'SELECT'    => ['e.name AS company_name', 'COUNT(t.id) AS task_count'],
    'LEFT JOIN' => [
        'glpi_entities AS e' => ['ON' => ['t.entities_id' => 'e.id']]
    ],
    'GROUP'     => ['t.entities_id', 'e.name'],
    'ORDER'     => ['task_count DESC'],
    'LIMIT'     => 10
] + $base_criteria);

// 4. Top Usuários
$iterator_users = $DB->request([
    'SELECT'    => ['u.name AS user_name', 'COUNT(t.id) AS task_count'],
    'LEFT JOIN' => [
        'glpi_users AS u' => ['ON' => ['t.users_id' => 'u.id']]
    ],
    'WHERE'     => array_merge($base_criteria['WHERE'], ['t.users_id' => ['>', 0]]),
    'GROUP'     => ['t.users_id', 'u.name'],
    'ORDER'     => ['task_count DESC'],
    'LIMIT'     => 10
] + $base_criteria);

// --- EXIBIÇÃO (Bootstrap 5 Nativo) ---
?>

<div class="container-fluid mt-4">

    <!-- Filtro -->
    <div class="card mb-4">
        <div class="card-body p-3 d-flex align-items-center justify-content-between">
            <h3 class="m-0"><i class="ti ti-filter"></i> <?php echo __('Period Filter', 'newbase'); ?></h3>
            <form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="d-inline-block">
                <select name="period" class="form-select" onchange="this.form.submit()">
                    <option value="all" <?php echo $period === 'all' ? 'selected' : ''; ?>><?php echo __('All Time', 'newbase'); ?></option>
                    <option value="today" <?php echo $period === 'today' ? 'selected' : ''; ?>><?php echo __('Today', 'newbase'); ?></option>
                    <option value="week" <?php echo $period === 'week' ? 'selected' : ''; ?>><?php echo __('Last 7 Days', 'newbase'); ?></option>
                    <option value="month" <?php echo $period === 'month' ? 'selected' : ''; ?>><?php echo __('Current Month', 'newbase'); ?></option>
                    <option value="year" <?php echo $period === 'year' ? 'selected' : ''; ?>><?php echo __('Current Year', 'newbase'); ?></option>
                </select>
            </form>
        </div>
    </div>

    <!-- Cards Resumo -->
    <div class="row mb-4">
        <?php
        $cards = [
            ['icon' => 'ti-clock', 'color' => 'warning', 'value' => $status_data['pending'], 'label' => __('Pending Tasks', 'newbase')],
            ['icon' => 'ti-check', 'color' => 'success', 'value' => $status_data['completed'], 'label' => __('Completed Tasks', 'newbase')],
            ['icon' => 'ti-sum', 'color' => 'primary', 'value' => $status_data['total'], 'label' => __('Total Tasks', 'newbase')],
            ['icon' => 'ti-route', 'color' => 'info', 'value' => number_format($mileage_data['total'], 2, ',', '.') . ' km', 'label' => __('Total Mileage', 'newbase')],
        ];

        foreach($cards as $c) {
            echo "
            <div class='col-md-3'>
                <div class='card text-center mb-3'>
                    <div class='card-body'>
                        <i class='ti {$c['icon']} text-{$c['color']} fs-1 mb-2'></i>
                        <h2 class='fw-bold mb-0'>{$c['value']}</h2>
                        <span class='text-muted'>{$c['label']}</span>
                    </div>
                </div>
            </div>";
        }
        ?>
    </div>

    <div class="row">
        <!-- Tabela: Empresas -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h3 class="card-title"><i class="ti ti-building"></i> <?php echo __('Tasks by Company', 'newbase'); ?></h3>
                </div>
                <div class="card-body p-0 table-responsive">
                    <table class="table table-striped table-hover m-0">
                        <thead>
                            <tr>
                                <th><?php echo __('Company', 'newbase'); ?></th>
                                <th class="text-end"><?php echo __('Tasks', 'newbase'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (count($iterator_companies)) {
                                foreach ($iterator_companies as $row) {
                                    echo "<tr>
                                        <td>" . htmlspecialchars($row['company_name'] ?? '-') . "</td>
                                        <td class='text-end'><span class='badge bg-secondary rounded-pill'>{$row['task_count']}</span></td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='2' class='text-center'>" . __('No data available', 'newbase') . "</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tabela: Usuários -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h3 class="card-title"><i class="ti ti-user"></i> <?php echo __('Tasks by User', 'newbase'); ?></h3>
                </div>
                <div class="card-body p-0 table-responsive">
                    <table class="table table-striped table-hover m-0">
                        <thead>
                            <tr>
                                <th><?php echo __('User', 'newbase'); ?></th>
                                <th class="text-end"><?php echo __('Tasks', 'newbase'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (count($iterator_users)) {
                                foreach ($iterator_users as $row) {
                                    echo "<tr>
                                        <td>" . htmlspecialchars($row['user_name'] ?? '-') . "</td>
                                        <td class='text-end'><span class='badge bg-secondary rounded-pill'>{$row['task_count']}</span></td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='2' class='text-center'>" . __('No data available', 'newbase') . "</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

<?php
Html::footer();
