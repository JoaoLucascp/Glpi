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

/* @newbase/companydata/sections/chatbot.html.twig */
class __TwigTemplate_ef1f422e4d71a0ad1457b4e0a74f2d61 extends Template
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
        <input type=\"hidden\" name=\"section_key\"         value=\"chatbot\">
        <input type=\"hidden\" name=\"_glpi_csrf_token\"    value=\"";
        // line 18
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["csrf_token"] ?? null), "html", null, true);
        yield "\">

        ";
        // line 21
        yield "        <p class=\"nb-section-label\">
            <i class=\"ti ti-robot me-1\"></i> Configurações do Chatbot
        </p>
        <div class=\"row g-3\">

            <div class=\"col-12 col-lg-6\">
                <label class=\"form-label\">Plataforma</label>
                <input type=\"text\"
                       name=\"systems_config[chatbot][platform]\"
                       value=\"";
        // line 30
        (((CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "platform", [], "any", true, true, false, 30) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "platform", [], "any", false, false, false, 30)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "platform", [], "any", false, false, false, 30), "html", null, true)) : (yield ""));
        yield "\"
                       class=\"form-control nb-fc\"
                       placeholder=\"Ex: Chatwoot, Zenvia, Take Blip, ChatGPT\">
            </div>

            <div class=\"col-12 col-lg-6\">
                <label class=\"form-label nb-pwd-label\">
                    API Key
                    <span class=\"badge bg-warning-lt text-warning ms-1 fw-normal\">visível</span>
                </label>
                <input type=\"text\"
                       name=\"systems_config[chatbot][api_key]\"
                       value=\"";
        // line 42
        (((CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "api_key", [], "any", true, true, false, 42) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "api_key", [], "any", false, false, false, 42)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "api_key", [], "any", false, false, false, 42), "html", null, true)) : (yield ""));
        yield "\"
                       class=\"form-control nb-fc nb-pwd\"
                       placeholder=\"Chave de API da plataforma\"
                       autocomplete=\"off\">
            </div>

            <div class=\"col-12\">
                <label class=\"form-label\">Configuração / Notas</label>
                <textarea name=\"systems_config[chatbot][config]\"
                          class=\"form-control nb-fc\"
                          rows=\"6\"
                          placeholder=\"Webhooks, endpoints, configurações adicionais...\">";
        // line 53
        (((CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "config", [], "any", true, true, false, 53) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "config", [], "any", false, false, false, 53)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "config", [], "any", false, false, false, 53), "html", null, true)) : (yield ""));
        yield "</textarea>
            </div>

        </div>";
        // line 57
        yield "
        ";
        // line 59
        yield "        <div class=\"text-center mt-4 pb-2\">
            <button type=\"submit\" class=\"btn btn-primary nb-save-btn\" id=\"nb-systems-save-btn\">
                <i class=\"ti ti-device-floppy me-2\"></i>
                Salvar Chatbot
            </button>
        </div>

    </form>
</div>";
        // line 68
        yield "
";
        // line 69
        yield from         $this->loadTemplate("@newbase/companydata/sections/_systems_js.html.twig", "@newbase/companydata/sections/chatbot.html.twig", 69)->unwrap()->yield($context);
        return; yield '';
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "@newbase/companydata/sections/chatbot.html.twig";
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
        return array (  115 => 69,  112 => 68,  102 => 59,  99 => 57,  93 => 53,  79 => 42,  64 => 30,  53 => 21,  48 => 18,  43 => 16,  38 => 13,);
    }

    public function getSourceContext()
    {
        return new Source("", "@newbase/companydata/sections/chatbot.html.twig", "D:\\laragon\\www\\glpi\\plugins\\newbase\\templates\\companydata\\sections\\chatbot.html.twig");
    }
}
