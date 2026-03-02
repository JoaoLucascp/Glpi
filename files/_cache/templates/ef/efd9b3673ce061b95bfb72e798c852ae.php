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
        // line 13
        yield "
<div class=\"newbase-systems-wrapper\">
    <form id=\"nb-systems-form\" method=\"post\" novalidate>
        <input type=\"hidden\" name=\"id\"                  value=\"";
        // line 16
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["item_id"] ?? null), "html", null, true);
        yield "\">
        <input type=\"hidden\" name=\"section_key\"         value=\"linha\">
        <input type=\"hidden\" name=\"_glpi_csrf_token\"    value=\"";
        // line 18
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["csrf_token"] ?? null), "html", null, true);
        yield "\">

        ";
        // line 21
        yield "        <p class=\"nb-section-label\">
            <i class=\"ti ti-phone me-1\"></i> Dados da Linha Telefônica
        </p>
        <div class=\"row g-3\">

            <div class=\"col-12 col-lg-6\">
                <label class=\"form-label\">Operadora</label>
                <input type=\"text\"
                       name=\"systems_config[linha][operadora]\"
                       value=\"";
        // line 30
        (((CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "operadora", [], "any", true, true, false, 30) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "operadora", [], "any", false, false, false, 30)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "operadora", [], "any", false, false, false, 30), "html", null, true)) : (yield ""));
        yield "\"
                       class=\"form-control nb-fc\"
                       placeholder=\"Ex: Claro, Vivo, TIM, Oi\">
            </div>

            <div class=\"col-12 col-lg-6\">
                <label class=\"form-label\">Número do Contrato</label>
                <input type=\"text\"
                       name=\"systems_config[linha][contrato]\"
                       value=\"";
        // line 39
        (((CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", true, true, false, 39) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", false, false, false, 39)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "contrato", [], "any", false, false, false, 39), "html", null, true)) : (yield ""));
        yield "\"
                       class=\"form-control nb-fc\"
                       placeholder=\"Número ou código do contrato\">
            </div>

            <div class=\"col-12\">
                <label class=\"form-label\">Notas</label>
                <textarea name=\"systems_config[linha][notas]\"
                          class=\"form-control nb-fc\"
                          rows=\"5\"
                          placeholder=\"Informações adicionais sobre a linha telefônica...\">";
        // line 49
        (((CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "notas", [], "any", true, true, false, 49) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "notas", [], "any", false, false, false, 49)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "notas", [], "any", false, false, false, 49), "html", null, true)) : (yield ""));
        yield "</textarea>
            </div>

        </div>";
        // line 53
        yield "
        ";
        // line 55
        yield "        <div class=\"text-center mt-4 pb-2\">
            <button type=\"submit\" class=\"btn btn-primary nb-save-btn\" id=\"nb-systems-save-btn\">
                <i class=\"ti ti-device-floppy me-2\"></i>
                Salvar Linha Telefônica
            </button>
        </div>

    </form>
</div>";
        // line 64
        yield "
";
        // line 65
        yield from         $this->loadTemplate("@newbase/companydata/sections/_systems_js.html.twig", "@newbase/companydata/sections/linha_telefonica.html.twig", 65)->unwrap()->yield($context);
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
        return array (  111 => 65,  108 => 64,  98 => 55,  95 => 53,  89 => 49,  76 => 39,  64 => 30,  53 => 21,  48 => 18,  43 => 16,  38 => 13,);
    }

    public function getSourceContext()
    {
        return new Source("", "@newbase/companydata/sections/linha_telefonica.html.twig", "D:\\laragon\\www\\glpi\\plugins\\newbase\\templates\\companydata\\sections\\linha_telefonica.html.twig");
    }
}
