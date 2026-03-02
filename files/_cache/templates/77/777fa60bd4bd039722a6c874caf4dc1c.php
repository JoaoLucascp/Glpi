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

/* @newbase/companydata/sections/ipbx_cloud.html.twig */
class __TwigTemplate_317595606915448095556f594c63ad9e extends Template
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
        <input type=\"hidden\" name=\"section_key\"         value=\"ipbx_cloud\">
        <input type=\"hidden\" name=\"_glpi_csrf_token\"    value=\"";
        // line 18
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["csrf_token"] ?? null), "html", null, true);
        yield "\">

        ";
        // line 21
        yield "        <p class=\"nb-section-label\">
            <i class=\"ti ti-server me-1\"></i> Informações do Servidor Cloud
        </p>
        <div class=\"row g-3\">

            <div class=\"col-12 col-lg-6\">
                <label class=\"form-label\">Modelo</label>
                <input type=\"text\"
                       name=\"systems_config[ipbx_cloud][model]\"
                       value=\"";
        // line 30
        (((CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "model", [], "any", true, true, false, 30) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "model", [], "any", false, false, false, 30)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "model", [], "any", false, false, false, 30), "html", null, true)) : (yield ""));
        yield "\"
                       class=\"form-control nb-fc\"
                       placeholder=\"Ex: VoIP.ms, 3CX Cloud, Twilio\">
            </div>

            <div class=\"col-12 col-lg-6\">
                <label class=\"form-label\">Versão</label>
                <input type=\"text\"
                       name=\"systems_config[ipbx_cloud][version]\"
                       value=\"";
        // line 39
        (((CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "version", [], "any", true, true, false, 39) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "version", [], "any", false, false, false, 39)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "version", [], "any", false, false, false, 39), "html", null, true)) : (yield ""));
        yield "\"
                       class=\"form-control nb-fc\"
                       placeholder=\"Ex: 19.0\">
            </div>

            <div class=\"col-12 col-lg-6\">
                <label class=\"form-label\">IP Interno</label>
                <input type=\"text\"
                       name=\"systems_config[ipbx_cloud][internal_ip]\"
                       value=\"";
        // line 48
        (((CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "internal_ip", [], "any", true, true, false, 48) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "internal_ip", [], "any", false, false, false, 48)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "internal_ip", [], "any", false, false, false, 48), "html", null, true)) : (yield ""));
        yield "\"
                       class=\"form-control nb-fc\"
                       placeholder=\"Ex: 10.0.0.1\">
            </div>

            <div class=\"col-12 col-lg-6\">
                <label class=\"form-label\">Host / IP Externo</label>
                <input type=\"text\"
                       name=\"systems_config[ipbx_cloud][external_ip]\"
                       value=\"";
        // line 57
        (((CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "external_ip", [], "any", true, true, false, 57) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "external_ip", [], "any", false, false, false, 57)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "external_ip", [], "any", false, false, false, 57), "html", null, true)) : (yield ""));
        yield "\"
                       class=\"form-control nb-fc\"
                       placeholder=\"Ex: pbx.empresa.com ou 200.x.x.x\">
            </div>

            <div class=\"col-12 col-lg-6\">
                <label class=\"form-label\">Porta Acesso Web</label>
                <input type=\"text\"
                       name=\"systems_config[ipbx_cloud][web_port]\"
                       value=\"";
        // line 66
        (((CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "web_port", [], "any", true, true, false, 66) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "web_port", [], "any", false, false, false, 66)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "web_port", [], "any", false, false, false, 66), "html", null, true)) : (yield ""));
        yield "\"
                       class=\"form-control nb-fc\"
                       placeholder=\"Ex: 5001\">
            </div>

            <div class=\"col-12 col-lg-6\">
                <label class=\"form-label nb-pwd-label\">
                    Senha Acesso Web
                    <span class=\"badge bg-warning-lt text-warning ms-1 fw-normal\">visível</span>
                </label>
                <input type=\"text\"
                       name=\"systems_config[ipbx_cloud][web_password]\"
                       value=\"";
        // line 78
        (((CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "web_password", [], "any", true, true, false, 78) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "web_password", [], "any", false, false, false, 78)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "web_password", [], "any", false, false, false, 78), "html", null, true)) : (yield ""));
        yield "\"
                       class=\"form-control nb-fc nb-pwd\"
                       placeholder=\"Senha web\"
                       autocomplete=\"off\">
            </div>

            <div class=\"col-12 col-lg-6\">
                <label class=\"form-label\">Porta SSH</label>
                <input type=\"text\"
                       name=\"systems_config[ipbx_cloud][ssh_port]\"
                       value=\"";
        // line 88
        (((CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "ssh_port", [], "any", true, true, false, 88) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "ssh_port", [], "any", false, false, false, 88)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "ssh_port", [], "any", false, false, false, 88), "html", null, true)) : (yield ""));
        yield "\"
                       class=\"form-control nb-fc\"
                       placeholder=\"Ex: 22\">
            </div>

            <div class=\"col-12 col-lg-6\">
                <label class=\"form-label nb-pwd-label\">
                    Senha SSH
                    <span class=\"badge bg-warning-lt text-warning ms-1 fw-normal\">visível</span>
                </label>
                <input type=\"text\"
                       name=\"systems_config[ipbx_cloud][ssh_password]\"
                       value=\"";
        // line 100
        (((CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "ssh_password", [], "any", true, true, false, 100) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "ssh_password", [], "any", false, false, false, 100)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "ssh_password", [], "any", false, false, false, 100), "html", null, true)) : (yield ""));
        yield "\"
                       class=\"form-control nb-fc nb-pwd\"
                       placeholder=\"Senha SSH\"
                       autocomplete=\"off\">
            </div>

            <div class=\"col-12\">
                <label class=\"form-label\">Observações</label>
                <textarea name=\"systems_config[ipbx_cloud][observations]\"
                          class=\"form-control nb-fc\"
                          rows=\"3\"
                          placeholder=\"Observações do servidor em nuvem...\">";
        // line 111
        (((CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "observations", [], "any", true, true, false, 111) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "observations", [], "any", false, false, false, 111)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "observations", [], "any", false, false, false, 111), "html", null, true)) : (yield ""));
        yield "</textarea>
            </div>

        </div>";
        // line 115
        yield "
        ";
        // line 117
        yield "        <div class=\"text-center mt-4 pb-2\">
            <button type=\"submit\" class=\"btn btn-primary nb-save-btn\" id=\"nb-systems-save-btn\">
                <i class=\"ti ti-device-floppy me-2\"></i>
                Salvar IPBX Cloud
            </button>
        </div>

    </form>
</div>";
        // line 126
        yield "
";
        // line 127
        yield from         $this->loadTemplate("@newbase/companydata/sections/_systems_js.html.twig", "@newbase/companydata/sections/ipbx_cloud.html.twig", 127)->unwrap()->yield($context);
        return; yield '';
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "@newbase/companydata/sections/ipbx_cloud.html.twig";
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
        return array (  191 => 127,  188 => 126,  178 => 117,  175 => 115,  169 => 111,  155 => 100,  140 => 88,  127 => 78,  112 => 66,  100 => 57,  88 => 48,  76 => 39,  64 => 30,  53 => 21,  48 => 18,  43 => 16,  38 => 13,);
    }

    public function getSourceContext()
    {
        return new Source("", "@newbase/companydata/sections/ipbx_cloud.html.twig", "D:\\laragon\\www\\glpi\\plugins\\newbase\\templates\\companydata\\sections\\ipbx_cloud.html.twig");
    }
}
