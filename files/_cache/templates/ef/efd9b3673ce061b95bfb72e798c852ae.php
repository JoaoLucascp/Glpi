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

/* @newbase/companydata/sections/linha_telefonica.html.twig */
class __TwigTemplate_5d00f938508941ff4a00e6ac1a2e3341 extends Template
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
        // line 19
        yield "
<div class=\"newbase-section-wrapper\">

    <form id=\"nb-form-linha\" class=\"nb-section-form\" method=\"post\" novalidate
          data-section-key=\"linha_telefonica\"
          data-save-label=\"Salvar Linha Telefônica\">

        <input type=\"hidden\" name=\"id\"               value=\"";
        // line 26
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["item_id"] ?? null), "html", null, true);
        yield "\">
        <input type=\"hidden\" name=\"section_key\"      value=\"";
        // line 27
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["section_key"] ?? null), "html", null, true);
        yield "\">
        <input type=\"hidden\" name=\"_glpi_csrf_token\" value=\"";
        // line 28
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["csrf_token"] ?? null), "html", null, true);
        yield "\">

        ";
        // line 33
        yield "        <div class=\"nb-card mb-4\">
            <div class=\"nb-card-header\">
                <i class=\"ti ti-phone me-2 text-orange\"></i>
                <strong>Dados da Linha</strong>
            </div>
            <div class=\"nb-card-body\">
                <div class=\"row g-3\">

                    <div class=\"col-12 col-md-4\">
                        <label class=\"form-label\">Número Piloto</label>
                        <input type=\"text\"
                               name=\"numero_piloto\"
                               value=\"";
        // line 45
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "numero_piloto", [], "any", false, false, false, 45));
        yield "\"
                               class=\"form-control\"
                               placeholder=\"Ex: (27) 3333-0000\">
                    </div>

                    <div class=\"col-12 col-md-4\">
                        <label class=\"form-label\">Tipo de Linha</label>
                        <input type=\"text\"
                               name=\"tipo_linha\"
                               value=\"";
        // line 54
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "tipo_linha", [], "any", false, false, false, 54));
        yield "\"
                               class=\"form-control\"
                               placeholder=\"Ex: SIP, Analógica, Digital E1\">
                    </div>

                    <div class=\"col-12 col-md-4\">
                        <label class=\"form-label\">Operadora</label>
                        <input type=\"text\"
                               name=\"operadora\"
                               value=\"";
        // line 63
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "operadora", [], "any", false, false, false, 63));
        yield "\"
                               class=\"form-control\"
                               placeholder=\"Ex: Claro, Vivo, TIM, Oi\">
                    </div>

                    <div class=\"col-6 col-md-3\">
                        <label class=\"form-label\">Qtd. Canais</label>
                        <input type=\"number\"
                               name=\"qtd_canais\"
                               value=\"";
        // line 72
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "qtd_canais", [], "any", true, true, false, 72)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "qtd_canais", [], "any", false, false, false, 72), 0)) : (0)), "html", null, true);
        yield "\"
                               class=\"form-control\"
                               min=\"0\"
                               placeholder=\"0\">
                    </div>

                    <div class=\"col-6 col-md-3\">
                        <label class=\"form-label\">Qtd. DDR</label>
                        <input type=\"number\"
                               name=\"qtd_ddr\"
                               value=\"";
        // line 82
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "qtd_ddr", [], "any", true, true, false, 82)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "qtd_ddr", [], "any", false, false, false, 82), 0)) : (0)), "html", null, true);
        yield "\"
                               class=\"form-control\"
                               min=\"0\"
                               placeholder=\"0\">
                    </div>

                    <div class=\"col-12 col-md-3\">
                        <label class=\"form-label\">Status da Linha</label>
                        <select name=\"status_linha\" class=\"form-select\">
                            ";
        // line 91
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(($context["status_options"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["opt"]) {
            // line 92
            yield "                            <option value=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($context["opt"], "html", null, true);
            yield "\"
                                    ";
            // line 93
            yield (((((CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "status_linha", [], "any", true, true, false, 93)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "status_linha", [], "any", false, false, false, 93), "ativo")) : ("ativo")) == $context["opt"])) ? ("selected") : (""));
            yield ">
                                ";
            // line 94
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::capitalize($this->env->getCharset(), $context["opt"]), "html", null, true);
            yield "
                            </option>
                            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['opt'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 97
        yield "                        </select>
                    </div>

                    <div class=\"col-12 col-md-3\">
                        <label class=\"form-label\">Data de Ativação</label>
                        <input type=\"date\"
                               name=\"data_ativacao\"
                               value=\"";
        // line 104
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "data_ativacao", [], "any", false, false, false, 104));
        yield "\"
                               class=\"form-control\">
                    </div>

                    <div class=\"col-12 col-md-3\">
                        <label class=\"form-label\">Data de Vencimento</label>
                        <input type=\"date\"
                               name=\"data_vencimento\"
                               value=\"";
        // line 112
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "data_vencimento", [], "any", false, false, false, 112));
        yield "\"
                               class=\"form-control\">
                    </div>

                </div>";
        // line 117
        yield "            </div>";
        // line 118
        yield "        </div>";
        // line 119
        yield "
        ";
        // line 123
        yield "        <div class=\"nb-card mb-4\">
            <div class=\"nb-card-header\">
                <i class=\"ti ti-arrows-exchange me-2 text-blue\"></i>
                <strong>Portabilidade</strong>
            </div>
            <div class=\"nb-card-body\">
                <div class=\"row g-3\">

                    <div class=\"col-12 col-md-3 d-flex align-items-center gap-2 pt-md-4\">
                        <label class=\"form-check form-switch mb-0\">
                            <input type=\"hidden\"   name=\"portabilidade\" value=\"0\">
                            <input type=\"checkbox\" name=\"portabilidade\" value=\"1\"
                                   class=\"form-check-input\"
                                   id=\"nb-portabilidade-check\"
                                   ";
        // line 137
        yield ((CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "portabilidade", [], "any", false, false, false, 137)) ? ("checked") : (""));
        yield ">
                            <span class=\"form-check-label\">Portabilidade</span>
                        </label>
                    </div>

                    <div class=\"col-12 col-md-3\" id=\"nb-portabilidade-fields\"
                         style=\"";
        // line 143
        yield ((CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "portabilidade", [], "any", false, false, false, 143)) ? ("") : ("display:none"));
        yield "\">
                        <label class=\"form-label\">Data de Portabilidade</label>
                        <input type=\"date\"
                               name=\"data_portabilidade\"
                               value=\"";
        // line 147
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "data_portabilidade", [], "any", false, false, false, 147));
        yield "\"
                               class=\"form-control\">
                    </div>

                    <div class=\"col-12 col-md-6\" id=\"nb-portabilidade-operadora\"
                         style=\"";
        // line 152
        yield ((CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "portabilidade", [], "any", false, false, false, 152)) ? ("") : ("display:none"));
        yield "\">
                        <label class=\"form-label\">Operadora Anterior</label>
                        <input type=\"text\"
                               name=\"operadora_anterior\"
                               value=\"";
        // line 156
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "operadora_anterior", [], "any", false, false, false, 156));
        yield "\"
                               class=\"form-control\"
                               placeholder=\"Ex: Oi, TIM, Claro\">
                    </div>

                </div>";
        // line 162
        yield "            </div>";
        // line 163
        yield "        </div>";
        // line 164
        yield "
        ";
        // line 168
        yield "        <div class=\"nb-card mb-4\">
            <div class=\"nb-card-header\">
                <i class=\"ti ti-server-2 me-2 text-teal\"></i>
                <strong>Dados Técnicos</strong>
            </div>
            <div class=\"nb-card-body\">
                <div class=\"row g-3\">

                    <div class=\"col-12 col-md-4\">
                        <label class=\"form-label\">IP Proxy</label>
                        <input type=\"text\"
                               name=\"ip_proxy\"
                               value=\"";
        // line 180
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "ip_proxy", [], "any", false, false, false, 180));
        yield "\"
                               class=\"form-control\"
                               placeholder=\"Ex: 192.168.0.10\">
                    </div>

                    <div class=\"col-12 col-md-4\">
                        <label class=\"form-label\">Porta Proxy</label>
                        <input type=\"text\"
                               name=\"porta_proxy\"
                               value=\"";
        // line 189
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "porta_proxy", [], "any", false, false, false, 189));
        yield "\"
                               class=\"form-control\"
                               placeholder=\"Ex: 5060\">
                    </div>

                    <div class=\"col-12 col-md-4\">
                        <label class=\"form-label\">IP Áudio</label>
                        <input type=\"text\"
                               name=\"ip_audio\"
                               value=\"";
        // line 198
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "ip_audio", [], "any", false, false, false, 198));
        yield "\"
                               class=\"form-control\"
                               placeholder=\"Ex: 192.168.0.20\">
                    </div>

                </div>";
        // line 204
        yield "            </div>";
        // line 205
        yield "        </div>";
        // line 206
        yield "
        ";
        // line 210
        yield "        <div class=\"nb-card mb-4\">
            <div class=\"nb-card-header\">
                <i class=\"ti ti-notes me-2 text-muted\"></i>
                <strong>Observações</strong>
            </div>
            <div class=\"nb-card-body\">
                <textarea name=\"observacoes\"
                          class=\"form-control\"
                          rows=\"4\"
                          placeholder=\"Observações gerais sobre a linha telefônica...\">";
        // line 219
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "observacoes", [], "any", false, false, false, 219));
        yield "</textarea>
            </div>
        </div>

        ";
        // line 226
        yield "        <div class=\"d-flex justify-content-end mt-2 pb-3\">
            <button type=\"submit\" class=\"btn btn-primary nb-btn-save\">
                <i class=\"ti ti-device-floppy me-2\"></i>
                Salvar Linha Telefônica
            </button>
        </div>

    </form>

</div>";
        // line 236
        yield "
";
        // line 241
        yield "<script>
(function () {
    'use strict';

    var chk    = document.getElementById('nb-portabilidade-check');
    var fields = document.getElementById('nb-portabilidade-fields');
    var opField= document.getElementById('nb-portabilidade-operadora');

    if (!chk) return;

    function togglePortabilidade() {
        var show = chk.checked;
        fields.style.display  = show ? '' : 'none';
        opField.style.display = show ? '' : 'none';
    }

    chk.addEventListener('change', togglePortabilidade);
}());
</script>

";
        // line 261
        yield from         $this->loadTemplate("@newbase/companydata/sections/_shared_js.html.twig", "@newbase/companydata/sections/linha_telefonica.html.twig", 261)->unwrap()->yield($context);
        return; yield '';
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "@newbase/companydata/sections/linha_telefonica.html.twig";
    }

    /**
     * @codeCoverageIgnore
     */
    public function isTraitable()
    {
        return false;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo()
    {
        return array (  360 => 261,  338 => 241,  335 => 236,  324 => 226,  317 => 219,  306 => 210,  303 => 206,  301 => 205,  299 => 204,  291 => 198,  279 => 189,  267 => 180,  253 => 168,  250 => 164,  248 => 163,  246 => 162,  238 => 156,  231 => 152,  223 => 147,  216 => 143,  207 => 137,  191 => 123,  188 => 119,  186 => 118,  184 => 117,  177 => 112,  166 => 104,  157 => 97,  148 => 94,  144 => 93,  139 => 92,  135 => 91,  123 => 82,  110 => 72,  98 => 63,  86 => 54,  74 => 45,  60 => 33,  55 => 28,  51 => 27,  47 => 26,  38 => 19,);
    }

    public function getSourceContext()
    {
        return new Source("", "@newbase/companydata/sections/linha_telefonica.html.twig", "D:\\laragon\\www\\glpi\\plugins\\newbase\\templates\\companydata\\sections\\linha_telefonica.html.twig");
    }
}
