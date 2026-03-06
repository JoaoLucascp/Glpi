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

/* @newbase/companydata/sections/ipbx_pabx.html.twig */
class __TwigTemplate_593702b4e220cc36a88c66ecb5ffe40a extends Template
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

\t<form id=\"nb-form-ipbx\" class=\"nb-section-form\" method=\"post\" novalidate data-section-key=\"ipbx\" data-save-label=\"Salvar IPBX / PABX\">

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
        // line 34
        yield "\t\t<div class=\"nb-card mb-4\">
\t\t\t<div class=\"nb-card-header\">
\t\t\t\t<i class=\"ti ti-server me-2 text-primary\"></i>
\t\t\t\t<strong>Informações do Servidor</strong>
\t\t\t</div>
\t\t\t<div class=\"nb-card-body\">
\t\t\t\t<div class=\"row g-3\">

\t\t\t\t\t<div class=\"col-12 col-md-6\">
\t\t\t\t\t\t<label class=\"form-label\">Modelo</label>
\t\t\t\t\t\t<input type=\"text\" name=\"modelo\" value=\"";
        // line 44
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "modelo", [], "any", false, false, false, 44));
        yield "\" class=\"form-control\" placeholder=\"Ex: NewCloud, Issabel, FreePBX\">
\t\t\t\t\t</div>

\t\t\t\t\t<div class=\"col-12 col-md-6\">
\t\t\t\t\t\t<label class=\"form-label\">Versão</label>
\t\t\t\t\t\t<input type=\"text\" name=\"versao\" value=\"";
        // line 49
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "versao", [], "any", false, false, false, 49));
        yield "\" class=\"form-control\" placeholder=\"Ex: 3.19\">
\t\t\t\t\t</div>

\t\t\t\t\t<div class=\"col-12 col-md-6\">
\t\t\t\t\t\t<label class=\"form-label\">IP Interno</label>
\t\t\t\t\t\t<input type=\"text\" name=\"ip_interno\" value=\"";
        // line 54
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "ip_interno", [], "any", false, false, false, 54));
        yield "\" class=\"form-control\" placeholder=\"Ex: 192.168.0.10\">
\t\t\t\t\t</div>

\t\t\t\t\t<div class=\"col-12 col-md-6\">
\t\t\t\t\t\t<label class=\"form-label\">IP Externo</label>
\t\t\t\t\t\t<input type=\"text\" name=\"ip_externo\" value=\"";
        // line 59
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "ip_externo", [], "any", false, false, false, 59));
        yield "\" class=\"form-control\" placeholder=\"Ex: 200.x.x.x\">
\t\t\t\t\t</div>

\t\t\t\t\t<div class=\"col-12 col-md-3\">
\t\t\t\t\t\t<label class=\"form-label\">Porta Web</label>
\t\t\t\t\t\t<input type=\"text\" name=\"porta_web\" value=\"";
        // line 64
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "porta_web", [], "any", false, false, false, 64));
        yield "\" class=\"form-control\" placeholder=\"Ex: 2080\">
\t\t\t\t\t</div>

\t\t\t\t\t<div class=\"col-12 col-md-3\">
\t\t\t\t\t\t<label class=\"form-label\">
\t\t\t\t\t\t\tSenha Web
\t\t\t\t\t\t\t<span class=\"badge bg-warning-lt text-warning ms-1 fw-normal fs-6\">visível</span>
\t\t\t\t\t\t</label>
\t\t\t\t\t\t<input type=\"text\" name=\"senha_web\" value=\"";
        // line 72
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "senha_web", [], "any", false, false, false, 72));
        yield "\" class=\"form-control nb-pwd\" placeholder=\"Senha de acesso web\" autocomplete=\"off\">
\t\t\t\t\t</div>

\t\t\t\t\t<div class=\"col-12 col-md-3\">
\t\t\t\t\t\t<label class=\"form-label\">Porta SSH</label>
\t\t\t\t\t\t<input type=\"text\" name=\"porta_ssh\" value=\"";
        // line 77
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "porta_ssh", [], "any", false, false, false, 77));
        yield "\" class=\"form-control\" placeholder=\"Ex: 22\">
\t\t\t\t\t</div>

\t\t\t\t\t<div class=\"col-12 col-md-3\">
\t\t\t\t\t\t<label class=\"form-label\">
\t\t\t\t\t\t\tSenha SSH
\t\t\t\t\t\t\t<span class=\"badge bg-warning-lt text-warning ms-1 fw-normal fs-6\">visível</span>
\t\t\t\t\t\t</label>
\t\t\t\t\t\t<input type=\"text\" name=\"senha_ssh\" value=\"";
        // line 85
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "senha_ssh", [], "any", false, false, false, 85));
        yield "\" class=\"form-control nb-pwd\" placeholder=\"Senha SSH\" autocomplete=\"off\">
\t\t\t\t\t</div>

\t\t\t\t\t<div class=\"col-12\">
\t\t\t\t\t\t<label class=\"form-label\">Observações</label>
\t\t\t\t\t\t<textarea name=\"observacoes\" class=\"form-control\" rows=\"3\" placeholder=\"Observações adicionais sobre o servidor...\">";
        // line 90
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "observacoes", [], "any", false, false, false, 90));
        yield "</textarea>
\t\t\t\t\t</div>

\t\t\t\t</div>
\t\t\t\t";
        // line 95
        yield "\t\t\t</div>
\t\t\t";
        // line 97
        yield "\t\t</div>
\t\t";
        // line 99
        yield "
\t\t";
        // line 101
        yield "\t\t<div class=\"nb-card mb-4\">
\t\t\t<div class=\"nb-card-header\">
\t\t\t\t<i class=\"ti ti-device-mobile me-2 text-blue\"></i>
\t\t\t\t<strong>Ramais</strong>
\t\t\t\t<button type=\"button\" class=\"btn btn-sm btn-outline-primary ms-auto nb-btn-add-row\" data-target-body=\"nb-ramais-body\" data-template=\"nb-tpl-ramal\">
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
\t\t\t\t\t\t<tbody id=\"nb-ramais-body\">
\t\t\t\t\t\t\t";
        // line 126
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
            // line 127
            yield "\t\t\t\t\t\t\t\t<tr class=\"nb-dyn-row\">
\t\t\t\t\t\t\t\t\t<td><input type=\"text\" class=\"form-control form-control-sm\" name=\"ramais[";
            // line 128
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 128), "html", null, true);
            yield "][ramal]\" value=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "ramal", [], "any", true, true, false, 128)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "ramal", [], "any", false, false, false, 128), "")) : ("")));
            yield "\" placeholder=\"2001\"></td>
\t\t\t\t\t\t\t\t\t<td><input type=\"text\" class=\"form-control form-control-sm nb-pwd\" name=\"ramais[";
            // line 129
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 129), "html", null, true);
            yield "][senha]\" value=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "senha", [], "any", true, true, false, 129)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "senha", [], "any", false, false, false, 129), "")) : ("")));
            yield "\" placeholder=\"senha\" autocomplete=\"off\"></td>
\t\t\t\t\t\t\t\t\t<td><input type=\"text\" class=\"form-control form-control-sm\" name=\"ramais[";
            // line 130
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 130), "html", null, true);
            yield "][ip]\" value=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "ip", [], "any", true, true, false, 130)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "ip", [], "any", false, false, false, 130), "")) : ("")));
            yield "\" placeholder=\"192.168.x.x\"></td>
\t\t\t\t\t\t\t\t\t<td><input type=\"text\" class=\"form-control form-control-sm\" name=\"ramais[";
            // line 131
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 131), "html", null, true);
            yield "][nome]\" value=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "nome", [], "any", true, true, false, 131)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "nome", [], "any", false, false, false, 131), "")) : ("")));
            yield "\" placeholder=\"Ex: João\"></td>
\t\t\t\t\t\t\t\t\t<td><input type=\"text\" class=\"form-control form-control-sm\" name=\"ramais[";
            // line 132
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 132), "html", null, true);
            yield "][localidade]\" value=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "localidade", [], "any", true, true, false, 132)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "localidade", [], "any", false, false, false, 132), "")) : ("")));
            yield "\" placeholder=\"Financeiro\"></td>
\t\t\t\t\t\t\t\t\t<td>
\t\t\t\t\t\t\t\t\t\t<select class=\"form-select form-select-sm\" name=\"ramais[";
            // line 134
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 134), "html", null, true);
            yield "][gravacao]\">
\t\t\t\t\t\t\t\t\t\t\t<option value=\"nao\" ";
            // line 135
            yield (((((CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "gravacao", [], "any", true, true, false, 135)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "gravacao", [], "any", false, false, false, 135), "nao")) : ("nao")) == "nao")) ? ("selected") : (""));
            yield ">Não</option>
\t\t\t\t\t\t\t\t\t\t\t<option value=\"sim\" ";
            // line 136
            yield (((((CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "gravacao", [], "any", true, true, false, 136)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "gravacao", [], "any", false, false, false, 136), "")) : ("")) == "sim")) ? ("selected") : (""));
            yield ">Sim</option>
\t\t\t\t\t\t\t\t\t\t</select>
\t\t\t\t\t\t\t\t\t</td>
\t\t\t\t\t\t\t\t\t<td><input type=\"text\" class=\"form-control form-control-sm\" name=\"ramais[";
            // line 139
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 139), "html", null, true);
            yield "][obs]\" value=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "obs", [], "any", true, true, false, 139)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "obs", [], "any", false, false, false, 139), "")) : ("")));
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
            // line 147
            yield "\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<tr id=\"nb-ramais-empty\" class=\"nb-empty-row\">
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
        // line 155
        yield "\t\t\t\t\t\t</tbody>
\t\t\t\t\t</table>
\t\t\t\t</div>
\t\t\t</div>
\t\t\t";
        // line 160
        yield "\t\t</div>
\t\t";
        // line 162
        yield "
\t\t";
        // line 164
        yield "\t\t<div class=\"d-flex justify-content-end mt-2 pb-3\">
\t\t\t<button type=\"submit\" class=\"btn btn-primary nb-btn-save\">
\t\t\t\t<i class=\"ti ti-device-floppy me-2\"></i>
\t\t\t\tSalvar IPBX / PABX
\t\t\t</button>
\t\t</div>

\t</form>

\t";
        // line 174
        yield "
\t";
        // line 176
        yield "\t<template id=\"nb-tpl-ramal\">
\t\t<tr class=\"nb-dyn-row\">
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
        // line 200
        yield "
";
        // line 201
        yield from         $this->loadTemplate("@newbase/companydata/sections/_shared_js.html.twig", "@newbase/companydata/sections/ipbx_pabx.html.twig", 201)->unwrap()->yield($context);
        return; yield '';
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "@newbase/companydata/sections/ipbx_pabx.html.twig";
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
        return array (  339 => 201,  336 => 200,  311 => 176,  308 => 174,  297 => 164,  294 => 162,  291 => 160,  285 => 155,  273 => 147,  250 => 139,  244 => 136,  240 => 135,  236 => 134,  229 => 132,  223 => 131,  217 => 130,  211 => 129,  205 => 128,  202 => 127,  184 => 126,  157 => 101,  154 => 99,  151 => 97,  148 => 95,  141 => 90,  133 => 85,  122 => 77,  114 => 72,  103 => 64,  95 => 59,  87 => 54,  79 => 49,  71 => 44,  59 => 34,  54 => 29,  49 => 27,  45 => 26,  38 => 21,);
    }

    public function getSourceContext()
    {
        return new Source("", "@newbase/companydata/sections/ipbx_pabx.html.twig", "D:\\laragon\\www\\glpi\\plugins\\newbase\\templates\\companydata\\sections\\ipbx_pabx.html.twig");
    }
}
