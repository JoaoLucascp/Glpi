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
        // line 21
        yield "
<div class=\"newbase-section-wrapper\">

\t<form id=\"nb-form-ipbx-cloud\" class=\"nb-section-form\" method=\"post\" novalidate data-section-key=\"ipbx_cloud\" data-save-label=\"Salvar IPBX Cloud\">

\t\t<input type=\"hidden\" name=\"id\" value=\"";
        // line 26
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["item_id"] ?? null), "html", null, true);
        yield "\">
\t\t<input type=\"hidden\" name=\"section_key\" value=\"";
        // line 27
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["section_key"] ?? null), "html", null, true);
        yield "\">
\t\t<input
\t\ttype=\"hidden\" name=\"_glpi_csrf_token\" value=\"";
        // line 29
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["csrf_token"] ?? null), "html", null, true);
        yield "\">

\t\t";
        // line 32
        yield "\t\t<div class=\"nb-card mb-4\">
\t\t\t<div class=\"nb-card-header\">
\t\t\t\t<i class=\"ti ti-cloud me-2 text-cyan\"></i>
\t\t\t\t<strong>Informações do Servidor Cloud</strong>
\t\t\t</div>
\t\t\t<div class=\"nb-card-body\">
\t\t\t\t<div class=\"row g-3\">

\t\t\t\t\t<div class=\"col-12 col-md-6\">
\t\t\t\t\t\t<label class=\"form-label\">Modelo</label>
\t\t\t\t\t\t<input type=\"text\" name=\"modelo\" value=\"";
        // line 42
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "modelo", [], "any", false, false, false, 42));
        yield "\" class=\"form-control\" placeholder=\"Ex: 3CX Cloud, VoIP.ms, Twilio\">
\t\t\t\t\t</div>

\t\t\t\t\t<div class=\"col-12 col-md-6\">
\t\t\t\t\t\t<label class=\"form-label\">Versão</label>
\t\t\t\t\t\t<input type=\"text\" name=\"versao\" value=\"";
        // line 47
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "versao", [], "any", false, false, false, 47));
        yield "\" class=\"form-control\" placeholder=\"Ex: 19.0\">
\t\t\t\t\t</div>

\t\t\t\t\t<div class=\"col-12 col-md-6\">
\t\t\t\t\t\t<label class=\"form-label\">IP Interno</label>
\t\t\t\t\t\t<input type=\"text\" name=\"ip_interno\" value=\"";
        // line 52
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "ip_interno", [], "any", false, false, false, 52));
        yield "\" class=\"form-control\" placeholder=\"Ex: 10.0.0.1\">
\t\t\t\t\t</div>

\t\t\t\t\t<div class=\"col-12 col-md-6\">
\t\t\t\t\t\t<label class=\"form-label\">Host / IP Externo</label>
\t\t\t\t\t\t<input type=\"text\" name=\"ip_externo\" value=\"";
        // line 57
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "ip_externo", [], "any", false, false, false, 57));
        yield "\" class=\"form-control\" placeholder=\"Ex: pbx.empresa.com ou 200.x.x.x\">
\t\t\t\t\t</div>

\t\t\t\t\t<div class=\"col-12 col-md-3\">
\t\t\t\t\t\t<label class=\"form-label\">Porta Web</label>
\t\t\t\t\t\t<input type=\"text\" name=\"porta_web\" value=\"";
        // line 62
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "porta_web", [], "any", false, false, false, 62));
        yield "\" class=\"form-control\" placeholder=\"Ex: 5001\">
\t\t\t\t\t</div>

\t\t\t\t\t<div class=\"col-12 col-md-3\">
\t\t\t\t\t\t<label class=\"form-label\">
\t\t\t\t\t\t\tSenha Web
\t\t\t\t\t\t\t<span class=\"badge bg-warning-lt text-warning ms-1 fw-normal fs-6\">visível</span>
\t\t\t\t\t\t</label>
\t\t\t\t\t\t<input type=\"text\" name=\"senha_web\" value=\"";
        // line 70
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "senha_web", [], "any", false, false, false, 70));
        yield "\" class=\"form-control nb-pwd\" placeholder=\"Senha de acesso web\" autocomplete=\"off\">
\t\t\t\t\t</div>

\t\t\t\t\t<div class=\"col-12 col-md-3\">
\t\t\t\t\t\t<label class=\"form-label\">Porta SSH</label>
\t\t\t\t\t\t<input type=\"text\" name=\"porta_ssh\" value=\"";
        // line 75
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "porta_ssh", [], "any", false, false, false, 75));
        yield "\" class=\"form-control\" placeholder=\"Ex: 22\">
\t\t\t\t\t</div>

\t\t\t\t\t<div class=\"col-12 col-md-3\">
\t\t\t\t\t\t<label class=\"form-label\">
\t\t\t\t\t\t\tSenha SSH
\t\t\t\t\t\t\t<span class=\"badge bg-warning-lt text-warning ms-1 fw-normal fs-6\">visível</span>
\t\t\t\t\t\t</label>
\t\t\t\t\t\t<input type=\"text\" name=\"senha_ssh\" value=\"";
        // line 83
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "senha_ssh", [], "any", false, false, false, 83));
        yield "\" class=\"form-control nb-pwd\" placeholder=\"Senha SSH\" autocomplete=\"off\">
\t\t\t\t\t</div>

\t\t\t\t\t<div class=\"col-12\">
\t\t\t\t\t\t<label class=\"form-label\">Observações</label>
\t\t\t\t\t\t<textarea name=\"observacoes\" class=\"form-control\" rows=\"3\" placeholder=\"Observações adicionais sobre o servidor em nuvem...\">";
        // line 88
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "observacoes", [], "any", false, false, false, 88));
        yield "</textarea>
\t\t\t\t\t</div>

\t\t\t\t</div>
\t\t\t\t";
        // line 93
        yield "\t\t\t</div>
\t\t\t";
        // line 95
        yield "\t\t</div>
\t\t";
        // line 97
        yield "
\t\t";
        // line 99
        yield "\t\t<div class=\"nb-card mb-4\">
\t\t\t<div class=\"nb-card-header\">
\t\t\t\t<i class=\"ti ti-device-mobile me-2 text-blue\"></i>
\t\t\t\t<strong>Ramais</strong>
\t\t\t\t<button type=\"button\" class=\"btn btn-sm btn-outline-primary ms-auto nb-btn-add-row\" data-target-body=\"nb-cloud-ramais-body\" data-template=\"nb-tpl-cloud-ramal\">
\t\t\t\t\t<i class=\"ti ti-plus me-1\"></i>
\t\t\t\t\tAdicionar Ramal
\t\t\t\t</button>
\t\t\t</div>
\t\t\t<div class=\"nb-card-body p-0\">
\t\t\t\t<div class=\"table-responsive\">
\t\t\t\t\t<table class=\"table table-sm nb-table align-middle mb-0\">
\t\t\t\t\t\t<thead class=\"table-light\">
\t\t\t\t\t\t\t<tr>
\t\t\t\t\t\t\t\t<th>Ramal</th>
\t\t\t\t\t\t\t\t<th>Senha</th>
\t\t\t\t\t\t\t\t<th>IP Aparelho</th>
\t\t\t\t\t\t\t\t<th>Nome</th>
\t\t\t\t\t\t\t\t<th>Localidade</th>
\t\t\t\t\t\t\t\t<th>Gravação</th>
\t\t\t\t\t\t\t\t<th>Observações</th>
\t\t\t\t\t\t\t\t<th class=\"nb-col-action\"></th>
\t\t\t\t\t\t\t</tr>
\t\t\t\t\t\t</thead>
\t\t\t\t\t\t<tbody id=\"nb-cloud-ramais-body\">
\t\t\t\t\t\t\t";
        // line 124
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(($context["ramais"] ?? null));
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
        foreach ($context['_seq'] as $context["_key"] => $context["ramal"]) {
            // line 125
            yield "\t\t\t\t\t\t\t\t<tr class=\"nb-dyn-row\">
\t\t\t\t\t\t\t\t\t<td><input type=\"text\" class=\"form-control form-control-sm\" name=\"ramais[";
            // line 126
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 126), "html", null, true);
            yield "][ramal]\" value=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "ramal", [], "any", true, true, false, 126)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "ramal", [], "any", false, false, false, 126), "")) : ("")));
            yield "\" placeholder=\"2001\"></td>
\t\t\t\t\t\t\t\t\t<td><input type=\"text\" class=\"form-control form-control-sm nb-pwd\" name=\"ramais[";
            // line 127
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 127), "html", null, true);
            yield "][senha]\" value=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "senha", [], "any", true, true, false, 127)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "senha", [], "any", false, false, false, 127), "")) : ("")));
            yield "\" placeholder=\"senha\" autocomplete=\"off\"></td>
\t\t\t\t\t\t\t\t\t<td><input type=\"text\" class=\"form-control form-control-sm\" name=\"ramais[";
            // line 128
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 128), "html", null, true);
            yield "][ip]\" value=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "ip", [], "any", true, true, false, 128)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "ip", [], "any", false, false, false, 128), "")) : ("")));
            yield "\" placeholder=\"192.168.x.x\"></td>
\t\t\t\t\t\t\t\t\t<td><input type=\"text\" class=\"form-control form-control-sm\" name=\"ramais[";
            // line 129
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 129), "html", null, true);
            yield "][nome]\" value=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "nome", [], "any", true, true, false, 129)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "nome", [], "any", false, false, false, 129), "")) : ("")));
            yield "\" placeholder=\"Ex: João\"></td>
\t\t\t\t\t\t\t\t\t<td><input type=\"text\" class=\"form-control form-control-sm\" name=\"ramais[";
            // line 130
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 130), "html", null, true);
            yield "][localidade]\" value=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "localidade", [], "any", true, true, false, 130)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "localidade", [], "any", false, false, false, 130), "")) : ("")));
            yield "\" placeholder=\"Financeiro\"></td>
\t\t\t\t\t\t\t\t\t<td>
\t\t\t\t\t\t\t\t\t\t<select class=\"form-select form-select-sm\" name=\"ramais[";
            // line 132
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 132), "html", null, true);
            yield "][gravacao]\">
\t\t\t\t\t\t\t\t\t\t\t<option value=\"nao\" ";
            // line 133
            yield (((((CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "gravacao", [], "any", true, true, false, 133)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "gravacao", [], "any", false, false, false, 133), "nao")) : ("nao")) == "nao")) ? ("selected") : (""));
            yield ">Não</option>
\t\t\t\t\t\t\t\t\t\t\t<option value=\"sim\" ";
            // line 134
            yield (((((CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "gravacao", [], "any", true, true, false, 134)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "gravacao", [], "any", false, false, false, 134), "")) : ("")) == "sim")) ? ("selected") : (""));
            yield ">Sim</option>
\t\t\t\t\t\t\t\t\t\t</select>
\t\t\t\t\t\t\t\t\t</td>
\t\t\t\t\t\t\t\t\t<td><input type=\"text\" class=\"form-control form-control-sm\" name=\"ramais[";
            // line 137
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 137), "html", null, true);
            yield "][obs]\" value=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "obs", [], "any", true, true, false, 137)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "obs", [], "any", false, false, false, 137), "")) : ("")));
            yield "\" placeholder=\"...\"></td>
\t\t\t\t\t\t\t\t\t<td>
\t\t\t\t\t\t\t\t\t\t<button type=\"button\" class=\"btn btn-sm btn-ghost-danger nb-btn-remove-row\" title=\"Remover\">
\t\t\t\t\t\t\t\t\t\t\t<i class=\"ti ti-trash\"></i>
\t\t\t\t\t\t\t\t\t\t</button>
\t\t\t\t\t\t\t\t\t</td>
\t\t\t\t\t\t\t\t</tr>
\t\t\t\t\t\t\t";
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
            // line 145
            yield "\t\t\t\t\t\t\t\t<tr id=\"nb-cloud-ramais-empty\" class=\"nb-empty-row\">
\t\t\t\t\t\t\t\t\t<td colspan=\"8\" class=\"text-center text-muted py-3\">
\t\t\t\t\t\t\t\t\t\t<i class=\"ti ti-inbox me-1\"></i>
\t\t\t\t\t\t\t\t\t\tNenhum ramal cadastrado. Clique em \"+ Adicionar Ramal\".
\t\t\t\t\t\t\t\t\t</td>
\t\t\t\t\t\t\t\t</tr>
\t\t\t\t\t\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['ramal'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 152
        yield "\t\t\t\t\t\t</tbody>
\t\t\t\t\t</table>
\t\t\t\t</div>
\t\t\t</div>
\t\t\t";
        // line 157
        yield "\t\t</div>
\t\t";
        // line 159
        yield "
\t\t";
        // line 161
        yield "\t\t<div class=\"d-flex justify-content-end mt-2 pb-3\">
\t\t\t<button type=\"submit\" class=\"btn btn-primary nb-btn-save\">
\t\t\t\t<i class=\"ti ti-device-floppy me-2\"></i>
\t\t\t\tSalvar IPBX Cloud
\t\t\t</button>
\t\t</div>

\t</form>

\t";
        // line 171
        yield "\t";
        // line 172
        yield "
\t";
        // line 174
        yield "\t\t<template id=\"nb-tpl-cloud-ramal\"> <tr class=\"nb-dyn-row\">
\t\t\t<td><input type=\"text\" class=\"form-control form-control-sm\" name=\"ramais[__IDX__][ramal]\" placeholder=\"2001\"></td>
\t\t\t<td><input type=\"text\" class=\"form-control form-control-sm nb-pwd\" name=\"ramais[__IDX__][senha]\" placeholder=\"senha\" autocomplete=\"off\"></td>
\t\t\t<td><input type=\"text\" class=\"form-control form-control-sm\" name=\"ramais[__IDX__][ip]\" placeholder=\"192.168.x.x\"></td>
\t\t\t<td><input type=\"text\" class=\"form-control form-control-sm\" name=\"ramais[__IDX__][nome]\" placeholder=\"Ex: João\"></td>
\t\t\t<td><input type=\"text\" class=\"form-control form-control-sm\" name=\"ramais[__IDX__][localidade]\" placeholder=\"Financeiro\"></td>
\t\t\t<td>
\t\t\t\t<select class=\"form-select form-select-sm\" name=\"ramais[__IDX__][gravacao]\">
\t\t\t\t\t<option value=\"nao\" selected>Não</option>
\t\t\t\t\t<option value=\"sim\">Sim</option>
\t\t\t\t</select>
\t\t\t</td>
\t\t\t<td><input type=\"text\" class=\"form-control form-control-sm\" name=\"ramais[__IDX__][obs]\" placeholder=\"...\"></td>
\t\t\t<td>
\t\t\t\t<button type=\"button\" class=\"btn btn-sm btn-ghost-danger nb-btn-remove-row\" title=\"Remover\">
\t\t\t\t\t<i class=\"ti ti-trash\"></i>
\t\t\t\t</button>
\t\t\t</td>
\t\t</tr>
\t</template>

</div>
";
        // line 197
        yield "
";
        // line 198
        yield from         $this->loadTemplate("@newbase/companydata/sections/_shared_js.html.twig", "@newbase/companydata/sections/ipbx_cloud.html.twig", 198)->unwrap()->yield($context);
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
        return array (  340 => 198,  337 => 197,  313 => 174,  310 => 172,  308 => 171,  297 => 161,  294 => 159,  291 => 157,  285 => 152,  273 => 145,  250 => 137,  244 => 134,  240 => 133,  236 => 132,  229 => 130,  223 => 129,  217 => 128,  211 => 127,  205 => 126,  202 => 125,  184 => 124,  157 => 99,  154 => 97,  151 => 95,  148 => 93,  141 => 88,  133 => 83,  122 => 75,  114 => 70,  103 => 62,  95 => 57,  87 => 52,  79 => 47,  71 => 42,  59 => 32,  54 => 29,  49 => 27,  45 => 26,  38 => 21,);
    }

    public function getSourceContext()
    {
        return new Source("", "@newbase/companydata/sections/ipbx_cloud.html.twig", "D:\\laragon\\www\\glpi\\plugins\\newbase\\templates\\companydata\\sections\\ipbx_cloud.html.twig");
    }
}
