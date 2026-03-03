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

/* @newbase/companydata/sections/dispositivos.html.twig */
class __TwigTemplate_40c0ca447fa07e26106fe907dbff611c extends Template
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

    <form id=\"nb-form-dispositivos\" class=\"nb-section-form\" method=\"post\" novalidate
          data-section-key=\"dispositivos\"
          data-save-label=\"Salvar Dispositivos\">

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
                <i class=\"ti ti-devices me-2 text-purple\"></i>
                <strong>Dispositivos de Rede</strong>
                <button type=\"button\"
                        class=\"btn btn-sm btn-outline-purple ms-auto nb-btn-add-row\"
                        data-target-body=\"nb-dispositivos-body\"
                        data-template=\"nb-tpl-dispositivo\">
                    <i class=\"ti ti-plus me-1\"></i> Adicionar Dispositivo
                </button>
            </div>
            <div class=\"nb-card-body p-0\">
                <div class=\"table-responsive\">
                    <table class=\"table table-sm nb-table align-middle mb-0\">
                        <thead class=\"table-light\">
                            <tr>
                                <th style=\"min-width:160px\">Tipo</th>
                                <th style=\"min-width:160px\">IP</th>
                                <th style=\"min-width:160px\">
                                    Senha
                                    <span class=\"badge bg-warning-lt text-warning ms-1 fw-normal\">visível</span>
                                </th>
                                <th>Observações</th>
                                <th class=\"nb-col-action\"></th>
                            </tr>
                        </thead>
                        <tbody id=\"nb-dispositivos-body\">
                            ";
        // line 56
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
            // line 57
            yield "                            <tr class=\"nb-dyn-row\">
                                <td>
                                    <input type=\"text\"
                                           class=\"form-control form-control-sm\"
                                           name=\"rows[";
            // line 61
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 61), "html", null, true);
            yield "][tipo_dispositivo]\"
                                           value=\"";
            // line 62
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["row"], "tipo_dispositivo", [], "any", true, true, false, 62)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["row"], "tipo_dispositivo", [], "any", false, false, false, 62), "")) : ("")));
            yield "\"
                                           placeholder=\"Ex: Switch, Roteador, AP\">
                                </td>
                                <td>
                                    <input type=\"text\"
                                           class=\"form-control form-control-sm\"
                                           name=\"rows[";
            // line 68
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 68), "html", null, true);
            yield "][ip_dispositivo]\"
                                           value=\"";
            // line 69
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["row"], "ip_dispositivo", [], "any", true, true, false, 69)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["row"], "ip_dispositivo", [], "any", false, false, false, 69), "")) : ("")));
            yield "\"
                                           placeholder=\"Ex: 192.168.0.1\">
                                </td>
                                <td>
                                    <input type=\"text\"
                                           class=\"form-control form-control-sm nb-pwd\"
                                           name=\"rows[";
            // line 75
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 75), "html", null, true);
            yield "][senha_dispositivo]\"
                                           value=\"";
            // line 76
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["row"], "senha_dispositivo", [], "any", true, true, false, 76)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["row"], "senha_dispositivo", [], "any", false, false, false, 76), "")) : ("")));
            yield "\"
                                           placeholder=\"Senha de acesso\"
                                           autocomplete=\"off\">
                                </td>
                                <td>
                                    <input type=\"text\"
                                           class=\"form-control form-control-sm\"
                                           name=\"rows[";
            // line 83
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 83), "html", null, true);
            yield "][observacoes]\"
                                           value=\"";
            // line 84
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["row"], "observacoes", [], "any", true, true, false, 84)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["row"], "observacoes", [], "any", false, false, false, 84), "")) : ("")));
            yield "\"
                                           placeholder=\"Observações\">
                                </td>
                                <td>
                                    <button type=\"button\"
                                            class=\"btn btn-sm btn-ghost-danger nb-btn-remove-row\"
                                            title=\"Remover dispositivo\">
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
            // line 96
            yield "                            <tr id=\"nb-dispositivos-empty\" class=\"nb-empty-row\">
                                <td colspan=\"5\" class=\"text-center text-muted py-3\">
                                    <i class=\"ti ti-inbox me-1\"></i> Nenhum dispositivo cadastrado. Clique em \"+ Adicionar Dispositivo\".
                                </td>
                            </tr>
                            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['row'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 102
        yield "                        </tbody>
                    </table>
                </div>
            </div>";
        // line 106
        yield "        </div>";
        // line 107
        yield "
        ";
        // line 111
        yield "        <div class=\"d-flex justify-content-end mt-2 pb-3\">
            <button type=\"submit\" class=\"btn btn-primary nb-btn-save\">
                <i class=\"ti ti-device-floppy me-2\"></i>
                Salvar Dispositivos
            </button>
        </div>

    </form>

    ";
        // line 123
        yield "    <template id=\"nb-tpl-dispositivo\">
        <tr class=\"nb-dyn-row\">
            <td>
                <input type=\"text\"
                       class=\"form-control form-control-sm\"
                       name=\"rows[__IDX__][tipo_dispositivo]\"
                       placeholder=\"Ex: Switch, Roteador, AP\">
            </td>
            <td>
                <input type=\"text\"
                       class=\"form-control form-control-sm\"
                       name=\"rows[__IDX__][ip_dispositivo]\"
                       placeholder=\"Ex: 192.168.0.1\">
            </td>
            <td>
                <input type=\"text\"
                       class=\"form-control form-control-sm nb-pwd\"
                       name=\"rows[__IDX__][senha_dispositivo]\"
                       placeholder=\"Senha de acesso\"
                       autocomplete=\"off\">
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
                        title=\"Remover dispositivo\">
                    <i class=\"ti ti-trash\"></i>
                </button>
            </td>
        </tr>
    </template>

</div>";
        // line 161
        yield "
";
        // line 162
        yield from         $this->loadTemplate("@newbase/companydata/sections/_shared_js.html.twig", "@newbase/companydata/sections/dispositivos.html.twig", 162)->unwrap()->yield($context);
        return; yield '';
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "@newbase/companydata/sections/dispositivos.html.twig";
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
        return array (  256 => 162,  253 => 161,  214 => 123,  203 => 111,  200 => 107,  198 => 106,  193 => 102,  182 => 96,  157 => 84,  153 => 83,  143 => 76,  139 => 75,  130 => 69,  126 => 68,  117 => 62,  113 => 61,  107 => 57,  89 => 56,  60 => 29,  55 => 24,  51 => 23,  47 => 22,  38 => 15,);
    }

    public function getSourceContext()
    {
        return new Source("", "@newbase/companydata/sections/dispositivos.html.twig", "D:\\laragon\\www\\glpi\\plugins\\newbase\\templates\\companydata\\sections\\dispositivos.html.twig");
    }
}
