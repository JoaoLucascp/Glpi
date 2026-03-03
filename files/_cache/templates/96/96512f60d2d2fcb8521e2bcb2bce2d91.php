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

/* @newbase/companydata/sections/rede.html.twig */
class __TwigTemplate_198812d37a2817605f8ad288677e56fb extends Template
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
        // line 15
        yield "
<div class=\"newbase-section-wrapper\">

    <form id=\"nb-form-rede\" class=\"nb-section-form\" method=\"post\" novalidate
          data-section-key=\"rede\"
          data-save-label=\"Salvar Rede\">

        <input type=\"hidden\" name=\"id\"               value=\"";
        // line 22
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["item_id"] ?? null), "html", null, true);
        yield "\">
        <input type=\"hidden\" name=\"section_key\"      value=\"";
        // line 23
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["section_key"] ?? null), "html", null, true);
        yield "\">
        <input type=\"hidden\" name=\"_glpi_csrf_token\" value=\"";
        // line 24
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["csrf_token"] ?? null), "html", null, true);
        yield "\">

        ";
        // line 29
        yield "        <div class=\"nb-card mb-4\">
            <div class=\"nb-card-header\">
                <i class=\"ti ti-network me-2 text-teal\"></i>
                <strong>Configurações de Rede</strong>
                <button type=\"button\"
                        class=\"btn btn-sm btn-outline-teal ms-auto nb-btn-add-row\"
                        data-target-body=\"nb-rede-body\"
                        data-template=\"nb-tpl-rede\">
                    <i class=\"ti ti-plus me-1\"></i> Adicionar Rede
                </button>
            </div>
            <div class=\"nb-card-body p-0\">
                <div class=\"table-responsive\">
                    <table class=\"table table-sm nb-table align-middle mb-0\">
                        <thead class=\"table-light\">
                            <tr>
                                <th style=\"min-width:140px\">IP</th>
                                <th style=\"min-width:140px\">Máscara</th>
                                <th style=\"min-width:140px\">Gateway</th>
                                <th style=\"min-width:140px\">DNS Primário</th>
                                <th style=\"min-width:140px\">DNS Secundário</th>
                                <th>Observações</th>
                                <th class=\"nb-col-action\"></th>
                            </tr>
                        </thead>
                        <tbody id=\"nb-rede-body\">
                            ";
        // line 55
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(($context["rows"] ?? null));
        $context['_iterated'] = false;
        $context['loop'] = [
          'parent' => $context['_parent'],
          'index0' => 0,
          'index'  => 1,
          'first'  => true,
        ];
        if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof \Countable)) {
            $length = count($context['_seq']);
            $context['loop']['revindex0'] = $length - 1;
            $context['loop']['revindex'] = $length;
            $context['loop']['length'] = $length;
            $context['loop']['last'] = 1 === $length;
        }
        foreach ($context['_seq'] as $context["_key"] => $context["row"]) {
            // line 56
            yield "                            <tr class=\"nb-dyn-row\">
                                <td>
                                    <input type=\"text\"
                                           class=\"form-control form-control-sm\"
                                           name=\"rows[";
            // line 60
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 60), "html", null, true);
            yield "][ip]\"
                                           value=\"";
            // line 61
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["row"], "ip", [], "any", true, true, false, 61)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["row"], "ip", [], "any", false, false, false, 61), "")) : ("")));
            yield "\"
                                           placeholder=\"Ex: 192.168.0.0\">
                                </td>
                                <td>
                                    <input type=\"text\"
                                           class=\"form-control form-control-sm\"
                                           name=\"rows[";
            // line 67
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 67), "html", null, true);
            yield "][mascara]\"
                                           value=\"";
            // line 68
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["row"], "mascara", [], "any", true, true, false, 68)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["row"], "mascara", [], "any", false, false, false, 68), "")) : ("")));
            yield "\"
                                           placeholder=\"Ex: 255.255.255.0\">
                                </td>
                                <td>
                                    <input type=\"text\"
                                           class=\"form-control form-control-sm\"
                                           name=\"rows[";
            // line 74
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 74), "html", null, true);
            yield "][gateway]\"
                                           value=\"";
            // line 75
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["row"], "gateway", [], "any", true, true, false, 75)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["row"], "gateway", [], "any", false, false, false, 75), "")) : ("")));
            yield "\"
                                           placeholder=\"Ex: 192.168.0.1\">
                                </td>
                                <td>
                                    <input type=\"text\"
                                           class=\"form-control form-control-sm\"
                                           name=\"rows[";
            // line 81
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 81), "html", null, true);
            yield "][dns_primario]\"
                                           value=\"";
            // line 82
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["row"], "dns_primario", [], "any", true, true, false, 82)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["row"], "dns_primario", [], "any", false, false, false, 82), "")) : ("")));
            yield "\"
                                           placeholder=\"Ex: 8.8.8.8\">
                                </td>
                                <td>
                                    <input type=\"text\"
                                           class=\"form-control form-control-sm\"
                                           name=\"rows[";
            // line 88
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 88), "html", null, true);
            yield "][dns_secundario]\"
                                           value=\"";
            // line 89
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["row"], "dns_secundario", [], "any", true, true, false, 89)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["row"], "dns_secundario", [], "any", false, false, false, 89), "")) : ("")));
            yield "\"
                                           placeholder=\"Ex: 8.8.4.4\">
                                </td>
                                <td>
                                    <input type=\"text\"
                                           class=\"form-control form-control-sm\"
                                           name=\"rows[";
            // line 95
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 95), "html", null, true);
            yield "][observacoes]\"
                                           value=\"";
            // line 96
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["row"], "observacoes", [], "any", true, true, false, 96)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["row"], "observacoes", [], "any", false, false, false, 96), "")) : ("")));
            yield "\"
                                           placeholder=\"Observações\">
                                </td>
                                <td>
                                    <button type=\"button\"
                                            class=\"btn btn-sm btn-ghost-danger nb-btn-remove-row\"
                                            title=\"Remover rede\">
                                        <i class=\"ti ti-trash\"></i>
                                    </button>
                                </td>
                            </tr>
                            ";
            $context['_iterated'] = true;
            ++$context['loop']['index0'];
            ++$context['loop']['index'];
            $context['loop']['first'] = false;
            if (isset($context['loop']['length'])) {
                --$context['loop']['revindex0'];
                --$context['loop']['revindex'];
                $context['loop']['last'] = 0 === $context['loop']['revindex0'];
            }
        }
        if (!$context['_iterated']) {
            // line 108
            yield "                            <tr id=\"nb-rede-empty\" class=\"nb-empty-row\">
                                <td colspan=\"7\" class=\"text-center text-muted py-3\">
                                    <i class=\"ti ti-inbox me-1\"></i> Nenhuma configuração de rede cadastrada. Clique em \"+ Adicionar Rede\".
                                </td>
                            </tr>
                            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['row'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 114
        yield "                        </tbody>
                    </table>
                </div>
            </div>";
        // line 118
        yield "        </div>";
        // line 119
        yield "
        ";
        // line 123
        yield "        <div class=\"d-flex justify-content-end mt-2 pb-3\">
            <button type=\"submit\" class=\"btn btn-primary nb-btn-save\">
                <i class=\"ti ti-device-floppy me-2\"></i>
                Salvar Rede
            </button>
        </div>

    </form>

    ";
        // line 135
        yield "    <template id=\"nb-tpl-rede\">
        <tr class=\"nb-dyn-row\">
            <td>
                <input type=\"text\"
                       class=\"form-control form-control-sm\"
                       name=\"rows[__IDX__][ip]\"
                       placeholder=\"Ex: 192.168.0.0\">
            </td>
            <td>
                <input type=\"text\"
                       class=\"form-control form-control-sm\"
                       name=\"rows[__IDX__][mascara]\"
                       placeholder=\"Ex: 255.255.255.0\">
            </td>
            <td>
                <input type=\"text\"
                       class=\"form-control form-control-sm\"
                       name=\"rows[__IDX__][gateway]\"
                       placeholder=\"Ex: 192.168.0.1\">
            </td>
            <td>
                <input type=\"text\"
                       class=\"form-control form-control-sm\"
                       name=\"rows[__IDX__][dns_primario]\"
                       placeholder=\"Ex: 8.8.8.8\">
            </td>
            <td>
                <input type=\"text\"
                       class=\"form-control form-control-sm\"
                       name=\"rows[__IDX__][dns_secundario]\"
                       placeholder=\"Ex: 8.8.4.4\">
            </td>
            <td>
                <input type=\"text\"
                       class=\"form-control form-control-sm\"
                       name=\"rows[__IDX__][observacoes]\"
                       placeholder=\"Observações\">
            </td>
            <td>
                <button type=\"button\"
                        class=\"btn btn-sm btn-ghost-danger nb-btn-remove-row\"
                        title=\"Remover rede\">
                    <i class=\"ti ti-trash\"></i>
                </button>
            </td>
        </tr>
    </template>

</div>";
        // line 184
        yield "
";
        // line 185
        yield from         $this->loadTemplate("@newbase/companydata/sections/_shared_js.html.twig", "@newbase/companydata/sections/rede.html.twig", 185)->unwrap()->yield($context);
        return; yield '';
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "@newbase/companydata/sections/rede.html.twig";
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
        return array (  291 => 185,  288 => 184,  238 => 135,  227 => 123,  224 => 119,  222 => 118,  217 => 114,  206 => 108,  181 => 96,  177 => 95,  168 => 89,  164 => 88,  155 => 82,  151 => 81,  142 => 75,  138 => 74,  129 => 68,  125 => 67,  116 => 61,  112 => 60,  106 => 56,  88 => 55,  60 => 29,  55 => 24,  51 => 23,  47 => 22,  38 => 15,);
    }

    public function getSourceContext()
    {
        return new Source("", "@newbase/companydata/sections/rede.html.twig", "D:\\laragon\\www\\glpi\\plugins\\newbase\\templates\\companydata\\sections\\rede.html.twig");
    }
}
