<?php

/**
 * Newbase Plugin - Configuration Page
 */

include('../../../inc/includes.php');

use GlpiPlugin\Newbase\Config as NewbaseConfig;
use Config as CoreConfig;

\Session::checkLoginUser();

if (!\Session::haveRight('config', UPDATE)) {
    \Html::displayRightError();
}

// ── Processar POST ────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_config'])) {

    // NOTA: O GLPI 10.0.x já valida o token CSRF automaticamente em includes.php
    // para TODAS as requisições POST (incluindo plugins). NÃO chamar checkCSRF()
    // aqui pois o token já foi consumido da sessão, causando falha na segunda validação.

    CoreConfig::setConfigurationValues('plugin:newbase', [
        'enable_signature'   => isset($_POST['enable_signature'])   ? 1 : 0,
        'require_signature'  => isset($_POST['require_signature'])  ? 1 : 0,
        'enable_gps'         => isset($_POST['enable_gps'])         ? 1 : 0,
        'calculate_mileage'  => isset($_POST['calculate_mileage'])  ? 1 : 0,
        'default_zoom'       => max(1, min(20, (int)($_POST['default_zoom'] ?? 10))),
        'enable_cnpj_search' => isset($_POST['enable_cnpj_search']) ? 1 : 0,
        'enable_cep_search'  => isset($_POST['enable_cep_search'])  ? 1 : 0,
    ]);

    \Session::addMessageAfterRedirect('Configurações salvas com sucesso!', false, INFO);

    // PRG: redirect para GET para evitar reenvio do formulário
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// ── Cabeçalho ─────────────────────────────────────────────────────────────
\Html::header(
    'Newbase – Configurações',
    $_SERVER['PHP_SELF'],
    'config',
    'plugin:newbase'
);

// ── Carregar configuração ─────────────────────────────────────────────────
$config  = array_merge(NewbaseConfig::getDefaultConfig(), NewbaseConfig::getConfig());
$canedit = (bool)\Session::haveRight('config', UPDATE);
$csrf    = \Session::getNewCSRFToken();

?>
<style>
.nb-config-table { width: 100%; border-collapse: collapse; }
.nb-config-table td, .nb-config-table th { padding: 10px 12px; vertical-align: middle; }
.nb-config-table .nb-label { font-weight: 600; white-space: nowrap; }
.nb-config-table .nb-hint  { font-size: 0.82rem; color: #6c757d; white-space: nowrap; }
.nb-config-table tr + tr   { border-top: 1px solid #dee2e6; }
.nb-config-table td:last-child { text-align: right; white-space: nowrap; }
.nb-section-title { display: flex; align-items: center; gap: 8px; font-size: 1rem; font-weight: 600; margin: 0; }
</style>

<div class="container-fluid mt-3">

    <div style="display:flex;align-items:center;gap:10px;margin-bottom:1.5rem;">
        <i class="ti ti-settings" style="font-size:1.6rem;color:#6c757d;"></i>
        <h2 style="margin:0;">Newbase – Configurações do Plugin</h2>
    </div>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
        <input type="hidden" name="_glpi_csrf_token" value="<?php echo htmlspecialchars($csrf); ?>">

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;max-width:1100px;">

            <!-- ── Funcionalidades de Tarefas ─────────────────────────── -->
            <div class="card">
                <div class="card-header">
                    <p class="nb-section-title">
                        <i class="ti ti-clipboard-list"></i>
                        Funcionalidades de Tarefas
                    </p>
                </div>
                <div class="card-body" style="padding:0;">
                    <table class="nb-config-table">
                        <tr>
                            <td>
                                <div class="nb-label">Habilitar captura de assinatura</div>
                            </td>
                            <td>
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" role="switch"
                                            name="enable_signature" value="1"
                                            <?php echo $config['enable_signature'] ? 'checked' : ''; ?>
                                            <?php echo $canedit ? '' : 'disabled'; ?>>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="nb-label">Exigir assinatura para fechar tarefa</div>
                            </td>
                            <td>
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" role="switch"
                                            name="require_signature" value="1"
                                            <?php echo $config['require_signature'] ? 'checked' : ''; ?>
                                            <?php echo $canedit ? '' : 'disabled'; ?>>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="nb-label">Habilitar rastreamento GPS</div>
                            </td>
                            <td>
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" role="switch"
                                            name="enable_gps" value="1"
                                            <?php echo $config['enable_gps'] ? 'checked' : ''; ?>
                                            <?php echo $canedit ? '' : 'disabled'; ?>>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="nb-label">Calcular quilometragem automaticamente</div>
                            </td>
                            <td>
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" role="switch"
                                            name="calculate_mileage" value="1"
                                            <?php echo $config['calculate_mileage'] ? 'checked' : ''; ?>
                                            <?php echo $canedit ? '' : 'disabled'; ?>>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="nb-label">Nível de zoom padrão do mapa</div>
                                <div class="nb-hint">Entre 1 (mundo) e 20 (rua)</div>
                            </td>
                            <td>
                                <input type="number" class="form-control form-control-sm"
                                        style="width:80px;text-align:center;"
                                        name="default_zoom" min="1" max="20"
                                        value="<?php echo (int)$config['default_zoom']; ?>"
                                        <?php echo $canedit ? '' : 'disabled'; ?>>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- ── Integrações de API ──────────────────────────────────── -->
            <div class="card">
                <div class="card-header">
                    <p class="nb-section-title">
                        <i class="ti ti-api"></i>
                        Integrações de API
                    </p>
                </div>
                <div class="card-body" style="padding:0;">
                    <table class="nb-config-table">
                        <tr>
                            <td>
                                <div class="nb-label">Preenchimento automático de CNPJ</div>
                                <div class="nb-hint">Usa BrasilAPI e ReceitaWS</div>
                            </td>
                            <td>
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" role="switch"
                                            name="enable_cnpj_search" value="1"
                                            <?php echo $config['enable_cnpj_search'] ? 'checked' : ''; ?>
                                            <?php echo $canedit ? '' : 'disabled'; ?>>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="nb-label">Preenchimento automático de CEP</div>
                                <div class="nb-hint">Usa ViaCEP API</div>
                            </td>
                            <td>
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" role="switch"
                                            name="enable_cep_search" value="1"
                                            <?php echo $config['enable_cep_search'] ? 'checked' : ''; ?>
                                            <?php echo $canedit ? '' : 'disabled'; ?>>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

        </div><!-- /grid -->

        <?php if ($canedit): ?>
        <div style="margin-top:1.5rem;display:flex;justify-content:flex-end;gap:0.5rem;max-width:1100px;">
            <a href="<?php echo \Plugin::getWebDir('newbase'); ?>/front/index.php"
                class="btn btn-secondary">
                <i class="ti ti-arrow-left me-1"></i>Voltar
            </a>
            <button type="submit" name="update_config" class="btn btn-primary">
                <i class="ti ti-device-floppy me-1"></i>Salvar
            </button>
        </div>
        <?php endif; ?>

    </form>
</div>

<?php \Html::footer(); ?>
