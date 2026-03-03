<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\CoreExtension;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* @newbase/companydata/sections/_shared_js.html.twig */
class __TwigTemplate_b61b531c1fd5a35e53478d03cc9ca115 extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        yield "<script>
(function (\$) {
    'use strict';

    // ═══════════════════════════════════════════════════════════════════════
    // 1. INJETAR CSS DE SUPORTE (executado uma única vez no DOM)
    // ═══════════════════════════════════════════════════════════════════════
    if (!document.getElementById('nb-shared-style')) {
        var style       = document.createElement('style');
        style.id        = 'nb-shared-style';
        style.textContent = [

            /* ── Cards ── */
            '.newbase-section-wrapper{padding:8px 4px}',
            '.nb-card{border:1px solid var(--tblr-border-color,#e6e7e9);border-radius:6px;background:#fff;margin-bottom:0}',
            '.nb-card-header{display:flex;align-items:center;padding:10px 14px;',
                'border-bottom:1px solid var(--tblr-border-color,#e6e7e9);',
                'background:var(--tblr-bg-surface-secondary,#f8f9fa);',
                'border-radius:6px 6px 0 0;font-size:.875rem;gap:4px}',
            '.nb-card-body{padding:14px}',

            /* ── Tabelas ── */
            '.nb-table thead th{font-size:.75rem;text-transform:uppercase;',
                'letter-spacing:.04em;white-space:nowrap;',
                'background:var(--tblr-bg-surface-secondary,#f8f9fa)}',
            '.nb-table td{vertical-align:middle;padding:4px 6px}',
            '.nb-col-action{width:38px;text-align:center}',

            /* ── Linha vazia (placeholder) ── */
            '.nb-empty-row td{font-size:.82rem}',

            /* ── Senha visível ── */
            '.nb-pwd{font-family:monospace;letter-spacing:.05em}',

            /* ── Spinner do botão salvar ── */
            '@keyframes nb-spin-anim{to{transform:rotate(360deg)}}',
            '.nb-spin{animation:nb-spin-anim .7s linear infinite;',
                'display:inline-block;line-height:1}'

        ].join('');
        document.head.appendChild(style);
    }

    // ═══════════════════════════════════════════════════════════════════════
    // 2. HELPERS
    // ═══════════════════════════════════════════════════════════════════════

    /**
     * Renumera os índices [N] nos atributos name= de todas as <tr> de um tbody.
     *
     * Padrão esperado:  prefixo[N][campo]
     * Após renumerar:   prefixo[i][campo]   (i = posição real da <tr>)
     *
     * Funciona para qualquer prefixo:
     *   ramais, operadoras, rows, comunicacao_massa, restricoes, usuarios…
     *
     * @param {string} tbodyId  ID do <tbody>
     */
    function renumerarLinhas(tbodyId) {
        \$('#' + tbodyId + ' tr.nb-dyn-row').each(function (i) {
            \$(this).find('[name]').each(function () {
                var name = \$(this).attr('name');
                if (!name) { return; }
                // Substitui APENAS o primeiro segmento numérico: prefixo[N][campo]
                \$(this).attr('name', name.replace(/^([^\\[]+)\\[(\\d+)\\]/, '\$1[' + i + ']'));
            });
        });
    }

    /**
     * Conta as linhas dinâmicas reais (exclui a linha de placeholder).
     *
     * @param  {string} tbodyId
     * @returns {number}
     */
    function contarLinhas(tbodyId) {
        return \$('#' + tbodyId + ' tr.nb-dyn-row').length;
    }

    /**
     * Clona o conteúdo de um <template>, substitui __IDX__ pelo índice
     * informado e retorna o elemento <tr> resultante.
     *
     * @param  {string} templateId  ID do elemento <template>
     * @param  {number} idx         Índice a substituir em __IDX__
     * @returns {jQuery|null}
     */
    function clonarTemplate(templateId, idx) {
        var tpl = document.getElementById(templateId);
        if (!tpl) {
            console.warn('[Newbase] Template não encontrado: #' + templateId);
            return null;
        }

        // Clonar o conteúdo do <template>
        var clone = document.importNode(tpl.content, true);
        var \$tr   = \$(clone).find('tr').first();

        if (!\$tr.length) {
            // Fallback: talvez o <tr> seja o próprio fragmento
            \$tr = \$(clone);
        }

        // Substituir __IDX__ em todos os atributos name=
        \$tr.find('[name]').each(function () {
            var name = \$(this).attr('name');
            if (name) {
                \$(this).attr('name', name.replace(/__IDX__/g, String(idx)));
            }
        });

        return \$tr;
    }

    // ═══════════════════════════════════════════════════════════════════════
    // 3. ADICIONAR LINHA
    //    Delegação no document para funcionar em abas carregadas via AJAX
    // ═══════════════════════════════════════════════════════════════════════
    \$(document).on('click', '.nb-btn-add-row', function () {
        var targetBody  = \$(this).data('target-body');
        var templateId  = \$(this).data('template');

        if (!targetBody || !templateId) {
            console.warn('[Newbase] .nb-btn-add-row sem data-target-body ou data-template.');
            return;
        }

        var idx  = contarLinhas(targetBody);   // próximo índice
        var \$tr  = clonarTemplate(templateId, idx);

        if (!\$tr) { return; }

        var \$tbody = \$('#' + targetBody);

        // Remover linha de placeholder (empty-row) se presente
        \$tbody.find('tr.nb-empty-row').remove();

        \$tbody.append(\$tr);

        // Foca o primeiro input da nova linha
        \$tbody.find('tr.nb-dyn-row:last input:first, tr.nb-dyn-row:last select:first')
              .first()
              .trigger('focus');
    });

    // ═══════════════════════════════════════════════════════════════════════
    // 4. REMOVER LINHA
    // ═══════════════════════════════════════════════════════════════════════
    \$(document).on('click', '.nb-btn-remove-row', function () {
        var \$tr    = \$(this).closest('tr');
        var \$tbody = \$tr.closest('tbody');
        var tbodyId = \$tbody.attr('id') || '';

        \$tr.remove();

        // Renumerar os índices que restaram
        if (tbodyId) {
            renumerarLinhas(tbodyId);
        }

        // Se ficou vazio, reexibir placeholder
        if (\$tbody.find('tr.nb-dyn-row').length === 0) {
            // Tenta achar a linha de placeholder original pelo ID convencional
            // Ex: nb-ramais-body → nb-ramais-empty
            var emptyId = tbodyId.replace(/-body\$/, '-empty');
            if (\$('#' + emptyId).length === 0) {
                // Criar placeholder genérico
                var cols = \$tbody.closest('table').find('thead th').length || 4;
                \$tbody.append(
                    '<tr class=\"nb-empty-row\">'
                    + '<td colspan=\"' + cols + '\" class=\"text-center text-muted py-3\">'
                    + '<i class=\"ti ti-inbox me-1\"></i> Nenhum registro. Clique em \"+ Adicionar\".'
                    + '</td></tr>'
                );
            } else {
                // Reexibir o placeholder original que estava no HTML inicial
                \$('#' + emptyId).removeClass('d-none').show();
            }
        }
    });

    // ═══════════════════════════════════════════════════════════════════════
    // 5. SUBMIT AJAX — qualquer .nb-section-form
    // ═══════════════════════════════════════════════════════════════════════
    \$(document).off('submit.nbSection').on('submit.nbSection', '.nb-section-form', function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();

        var \$form        = \$(this);
        var \$btn         = \$form.find('.nb-btn-save');
        var originalHtml = \$btn.html();
        var saveLabel    = \$form.data('save-label') || 'Salvar';

        // Desabilitar e mostrar spinner
        \$btn.prop('disabled', true)
            .html('<span class=\"nb-spin me-2\">⟳</span> Salvando...');

        // URL do endpoint — compatível com qualquer subpasta de instalação do GLPI
        var rootDoc = (typeof CFG_GLPI !== 'undefined' && CFG_GLPI.root_doc)
                      ? CFG_GLPI.root_doc
                      : '';
        var ajaxUrl = rootDoc + '/plugins/newbase/ajax/systemsConfig.php';

        \$.ajax({
            type    : 'POST',
            url     : ajaxUrl,
            data    : \$form.serialize(),
            dataType: 'json',
            timeout : 20000,

            success: function (res) {
                \$btn.prop('disabled', false)
                    .html('<i class=\"ti ti-device-floppy me-2\"></i>' + saveLabel);

                if (res && res.success) {
                    _nbNotify(res.message || 'Salvo com sucesso!', 'success');
                } else {
                    _nbNotify(res.message || 'Erro ao salvar. Verifique os logs.', 'error');
                }
            },

            error: function (xhr) {
                \$btn.prop('disabled', false)
                    .html('<i class=\"ti ti-device-floppy me-2\"></i>' + saveLabel);

                var msg;
                switch (xhr.status) {
                    case 0:   msg = 'Sem resposta do servidor. Verifique sua conexão.'; break;
                    case 403: msg = 'Sessão expirada ou token inválido. Recarregue a página (F5).'; break;
                    case 404: msg = 'Endpoint não encontrado. Contate o administrador.'; break;
                    case 500: msg = 'Erro interno no servidor. Verifique os logs do GLPI.'; break;
                    default:  msg = 'Erro HTTP ' + xhr.status + '. Tente novamente.';
                }
                _nbNotify(msg, 'error');
            }
        });
    });

    // ═══════════════════════════════════════════════════════════════════════
    // 6. NOTIFICAÇÃO
    //    Hierarquia: SweetAlert2 → toast nativo do GLPI → alert()
    // ═══════════════════════════════════════════════════════════════════════

    /**
     * Exibe uma notificação de sucesso ou erro.
     *
     * @param {string} msg   Mensagem a exibir
     * @param {string} type  'success' | 'error'
     */
    function _nbNotify(msg, type) {
        // SweetAlert2 (disponível no GLPI via Tabler)
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon              : type,
                text              : msg,
                toast             : (type === 'success'),
                position          : (type === 'success') ? 'top-end' : 'center',
                timer             : (type === 'success') ? 2800 : 0,
                timerProgressBar  : (type === 'success'),
                showConfirmButton : (type !== 'success'),
                confirmButtonColor: '#206bc4',
                confirmButtonText : 'OK'
            });
            return;
        }

        // Toast nativo do GLPI (fallback)
        if (type === 'success' && typeof glpi_toast_info === 'function') {
            glpi_toast_info(msg);
            return;
        }
        if (type === 'error' && typeof glpi_toast_error === 'function') {
            glpi_toast_error(msg);
            return;
        }

        // Fallback final
        alert(msg);
    }

})(jQuery);
</script>
";
        return; yield '';
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "@newbase/companydata/sections/_shared_js.html.twig";
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo()
    {
        return array ();
    }

    public function getSourceContext()
    {
        return new Source("", "@newbase/companydata/sections/_shared_js.html.twig", "D:\\laragon\\www\\glpi\\plugins\\newbase\\templates\\companydata\\sections\\_shared_js.html.twig");
    }
}
