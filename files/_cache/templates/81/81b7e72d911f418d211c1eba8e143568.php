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
        // line 13
        yield "
<div class=\"newbase-systems-wrapper\">
    <form id=\"nb-systems-form\" method=\"post\" novalidate>
        <input type=\"hidden\" name=\"id\"                  value=\"";
        // line 16
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["item_id"] ?? null), "html", null, true);
        yield "\">
        <input type=\"hidden\" name=\"section_key\"         value=\"ipbx\">
        <input type=\"hidden\" name=\"_glpi_csrf_token\"    value=\"";
        // line 18
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["csrf_token"] ?? null), "html", null, true);
        yield "\">

        ";
        // line 21
        yield "        <p class=\"nb-section-label\">
            <i class=\"ti ti-server me-1\"></i> Informações do Servidor
        </p>
        <div class=\"row g-3 mb-4\">

            <div class=\"col-12 col-lg-6\">
                <label class=\"form-label\">Modelo</label>
                <input type=\"text\"
                       name=\"systems_config[ipbx][model]\"
                       value=\"";
        // line 30
        (((CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "model", [], "any", true, true, false, 30) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "model", [], "any", false, false, false, 30)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "model", [], "any", false, false, false, 30), "html", null, true)) : (yield ""));
        yield "\"
                       class=\"form-control nb-fc\"
                       placeholder=\"Ex: NewCloud, Issabel, FreePBX\">
            </div>

            <div class=\"col-12 col-lg-6\">
                <label class=\"form-label\">Versão do Servidor</label>
                <input type=\"text\"
                       name=\"systems_config[ipbx][version]\"
                       value=\"";
        // line 39
        (((CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "version", [], "any", true, true, false, 39) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "version", [], "any", false, false, false, 39)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "version", [], "any", false, false, false, 39), "html", null, true)) : (yield ""));
        yield "\"
                       class=\"form-control nb-fc\"
                       placeholder=\"Ex: 3.19\">
            </div>

            <div class=\"col-12 col-lg-6\">
                <label class=\"form-label\">IP Interno</label>
                <input type=\"text\"
                       name=\"systems_config[ipbx][internal_ip]\"
                       value=\"";
        // line 48
        (((CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "internal_ip", [], "any", true, true, false, 48) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "internal_ip", [], "any", false, false, false, 48)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "internal_ip", [], "any", false, false, false, 48), "html", null, true)) : (yield ""));
        yield "\"
                       class=\"form-control nb-fc\"
                       placeholder=\"Ex: 192.168.0.10\">
            </div>

            <div class=\"col-12 col-lg-6\">
                <label class=\"form-label\">IP Externo</label>
                <input type=\"text\"
                       name=\"systems_config[ipbx][external_ip]\"
                       value=\"";
        // line 57
        (((CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "external_ip", [], "any", true, true, false, 57) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "external_ip", [], "any", false, false, false, 57)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "external_ip", [], "any", false, false, false, 57), "html", null, true)) : (yield ""));
        yield "\"
                       class=\"form-control nb-fc\"
                       placeholder=\"Ex: 200.x.x.x\">
            </div>

            <div class=\"col-12 col-lg-6\">
                <label class=\"form-label\">Porta Acesso Web</label>
                <input type=\"text\"
                       name=\"systems_config[ipbx][web_port]\"
                       value=\"";
        // line 66
        (((CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "web_port", [], "any", true, true, false, 66) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "web_port", [], "any", false, false, false, 66)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "web_port", [], "any", false, false, false, 66), "html", null, true)) : (yield ""));
        yield "\"
                       class=\"form-control nb-fc\"
                       placeholder=\"Ex: 2080\">
            </div>

            <div class=\"col-12 col-lg-6\">
                <label class=\"form-label nb-pwd-label\">
                    Senha Acesso Web
                    <span class=\"badge bg-warning-lt text-warning ms-1 fw-normal\">visível</span>
                </label>
                <input type=\"text\"
                       name=\"systems_config[ipbx][web_password]\"
                       value=\"";
        // line 78
        (((CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "web_password", [], "any", true, true, false, 78) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "web_password", [], "any", false, false, false, 78)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "web_password", [], "any", false, false, false, 78), "html", null, true)) : (yield ""));
        yield "\"
                       class=\"form-control nb-fc nb-pwd\"
                       placeholder=\"Senha de acesso web\"
                       autocomplete=\"off\">
            </div>

            <div class=\"col-12 col-lg-6\">
                <label class=\"form-label\">Porta Acesso SSH</label>
                <input type=\"text\"
                       name=\"systems_config[ipbx][ssh_port]\"
                       value=\"";
        // line 88
        (((CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "ssh_port", [], "any", true, true, false, 88) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "ssh_port", [], "any", false, false, false, 88)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "ssh_port", [], "any", false, false, false, 88), "html", null, true)) : (yield ""));
        yield "\"
                       class=\"form-control nb-fc\"
                       placeholder=\"Ex: 2022\">
            </div>

            <div class=\"col-12 col-lg-6\">
                <label class=\"form-label nb-pwd-label\">
                    Senha Acesso SSH
                    <span class=\"badge bg-warning-lt text-warning ms-1 fw-normal\">visível</span>
                </label>
                <input type=\"text\"
                       name=\"systems_config[ipbx][ssh_password]\"
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
                <textarea name=\"systems_config[ipbx][observations]\"
                          class=\"form-control nb-fc\"
                          rows=\"3\"
                          placeholder=\"Observações adicionais sobre o servidor...\">";
        // line 111
        (((CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "observations", [], "any", true, true, false, 111) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "observations", [], "any", false, false, false, 111)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "observations", [], "any", false, false, false, 111), "html", null, true)) : (yield ""));
        yield "</textarea>
            </div>

        </div>";
        // line 115
        yield "
        ";
        // line 117
        yield "        <p class=\"nb-section-label\">
            <i class=\"ti ti-device-mobile me-1\"></i> Ramais
        </p>
        <div class=\"table-responsive mb-2\">
            <table class=\"table table-sm nb-table align-middle\" id=\"nb-ipbx-ramais-table\">
                <thead class=\"table-light\">
                    <tr>
                        <th>Ramal</th>
                        <th>Senha</th>
                        <th>IP Aparelho</th>
                        <th>Nome</th>
                        <th>Localidade</th>
                        <th>Gravação</th>
                        <th>Observações</th>
                        <th style=\"width:40px\"></th>
                    </tr>
                </thead>
                <tbody id=\"nb-ipbx-ramais-body\">
                    ";
        // line 135
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable((((CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "ramais", [], "any", true, true, false, 135) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "ramais", [], "any", false, false, false, 135)))) ? (CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "ramais", [], "any", false, false, false, 135)) : ([])));
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
            // line 136
            yield "                    <tr class=\"nb-ramal-row\">
                        <td>
                            <input type=\"text\"
                                   class=\"form-control nb-fc form-control-sm\"
                                   name=\"systems_config[ipbx][ramais][";
            // line 140
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 140), "html", null, true);
            yield "][ramal]\"
                                   value=\"";
            // line 141
            (((CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "ramal", [], "any", true, true, false, 141) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "ramal", [], "any", false, false, false, 141)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "ramal", [], "any", false, false, false, 141), "html", null, true)) : (yield ""));
            yield "\"
                                   placeholder=\"2001\">
                        </td>
                        <td>
                            <input type=\"text\"
                                   class=\"form-control nb-fc nb-pwd form-control-sm\"
                                   name=\"systems_config[ipbx][ramais][";
            // line 147
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 147), "html", null, true);
            yield "][senha]\"
                                   value=\"";
            // line 148
            (((CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "senha", [], "any", true, true, false, 148) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "senha", [], "any", false, false, false, 148)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "senha", [], "any", false, false, false, 148), "html", null, true)) : (yield ""));
            yield "\"
                                   placeholder=\"senha\"
                                   autocomplete=\"off\">
                        </td>
                        <td>
                            <input type=\"text\"
                                   class=\"form-control nb-fc form-control-sm\"
                                   name=\"systems_config[ipbx][ramais][";
            // line 155
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 155), "html", null, true);
            yield "][ip]\"
                                   value=\"";
            // line 156
            (((CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "ip", [], "any", true, true, false, 156) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "ip", [], "any", false, false, false, 156)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "ip", [], "any", false, false, false, 156), "html", null, true)) : (yield ""));
            yield "\"
                                   placeholder=\"192.168.x.x\">
                        </td>
                        <td>
                            <input type=\"text\"
                                   class=\"form-control nb-fc form-control-sm\"
                                   name=\"systems_config[ipbx][ramais][";
            // line 162
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 162), "html", null, true);
            yield "][nome]\"
                                   value=\"";
            // line 163
            (((CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "nome", [], "any", true, true, false, 163) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "nome", [], "any", false, false, false, 163)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "nome", [], "any", false, false, false, 163), "html", null, true)) : (yield ""));
            yield "\"
                                   placeholder=\"Ex: José\">
                        </td>
                        <td>
                            <input type=\"text\"
                                   class=\"form-control nb-fc form-control-sm\"
                                   name=\"systems_config[ipbx][ramais][";
            // line 169
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 169), "html", null, true);
            yield "][localidade]\"
                                   value=\"";
            // line 170
            (((CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "localidade", [], "any", true, true, false, 170) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "localidade", [], "any", false, false, false, 170)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "localidade", [], "any", false, false, false, 170), "html", null, true)) : (yield ""));
            yield "\"
                                   placeholder=\"Financeiro\">
                        </td>
                        <td>
                            <select class=\"form-select nb-fc form-select-sm\"
                                    name=\"systems_config[ipbx][ramais][";
            // line 175
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 175), "html", null, true);
            yield "][gravacao]\">
                                <option value=\"nao\" ";
            // line 176
            yield ((((((CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "gravacao", [], "any", true, true, false, 176) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "gravacao", [], "any", false, false, false, 176)))) ? (CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "gravacao", [], "any", false, false, false, 176)) : ("nao")) == "nao")) ? ("selected") : (""));
            yield ">Não</option>
                                <option value=\"sim\" ";
            // line 177
            yield ((((((CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "gravacao", [], "any", true, true, false, 177) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "gravacao", [], "any", false, false, false, 177)))) ? (CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "gravacao", [], "any", false, false, false, 177)) : ("")) == "sim")) ? ("selected") : (""));
            yield ">Sim</option>
                            </select>
                        </td>
                        <td>
                            <input type=\"text\"
                                   class=\"form-control nb-fc form-control-sm\"
                                   name=\"systems_config[ipbx][ramais][";
            // line 183
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 183), "html", null, true);
            yield "][obs]\"
                                   value=\"";
            // line 184
            (((CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "obs", [], "any", true, true, false, 184) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "obs", [], "any", false, false, false, 184)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "obs", [], "any", false, false, false, 184), "html", null, true)) : (yield ""));
            yield "\"
                                   placeholder=\"...\">
                        </td>
                        <td>
                            <button type=\"button\"
                                    class=\"btn btn-sm btn-danger nb-remove-row\"
                                    data-body=\"nb-ipbx-ramais-body\"
                                    title=\"Remover ramal\">
                                <i class=\"ti ti-trash\"></i>
                            </button>
                        </td>
                    </tr>
                    ";
            ++$context['loop']['index0'];
            ++$context['loop']['index'];
            $context['loop']['first'] = false;
            if (isset($context['loop']['length'])) {
                --$context['loop']['revindex0'];
                --$context['loop']['revindex'];
                $context['loop']['last'] = 0 === $context['loop']['revindex0'];
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['ramal'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 197
        yield "                </tbody>
            </table>
        </div>
        <button type=\"button\"
                class=\"btn btn-sm btn-outline-primary\"
                id=\"nb-btn-add-ramal\">
            <i class=\"ti ti-plus me-1\"></i> Adicionar Ramal
        </button>

        ";
        // line 207
        yield "        <p class=\"nb-section-label mt-4\">
            <i class=\"ti ti-building-broadcast-tower me-1\"></i> Informações de Operadora (Troncos)
        </p>
        <div class=\"table-responsive mb-2\">
            <table class=\"table table-sm nb-table align-middle\" id=\"nb-ipbx-ops-table\">
                <thead class=\"table-light\">
                    <tr>
                        <th>Nº Piloto</th>
                        <th>Tipo Tronco</th>
                        <th>Operadora</th>
                        <th>DDR</th>
                        <th>Canais</th>
                        <th>IP Proxy</th>
                        <th>Porta Proxy</th>
                        <th>IP Áudio</th>
                        <th style=\"width:40px\"></th>
                    </tr>
                </thead>
                <tbody id=\"nb-ipbx-ops-body\">
                    ";
        // line 226
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable((((CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "operadoras", [], "any", true, true, false, 226) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "operadoras", [], "any", false, false, false, 226)))) ? (CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "operadoras", [], "any", false, false, false, 226)) : ([])));
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
        foreach ($context['_seq'] as $context["_key"] => $context["op"]) {
            // line 227
            yield "                    <tr class=\"nb-op-row\">
                        <td><input type=\"text\"   class=\"form-control nb-fc form-control-sm\" name=\"systems_config[ipbx][operadoras][";
            // line 228
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 228), "html", null, true);
            yield "][numero_piloto]\" value=\"";
            (((CoreExtension::getAttribute($this->env, $this->source, $context["op"], "numero_piloto", [], "any", true, true, false, 228) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["op"], "numero_piloto", [], "any", false, false, false, 228)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["op"], "numero_piloto", [], "any", false, false, false, 228), "html", null, true)) : (yield ""));
            yield "\" placeholder=\"(27)3xxx-xxxx\"></td>
                        <td><input type=\"text\"   class=\"form-control nb-fc form-control-sm\" name=\"systems_config[ipbx][operadoras][";
            // line 229
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 229), "html", null, true);
            yield "][tipo_tronco]\"   value=\"";
            (((CoreExtension::getAttribute($this->env, $this->source, $context["op"], "tipo_tronco", [], "any", true, true, false, 229) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["op"], "tipo_tronco", [], "any", false, false, false, 229)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["op"], "tipo_tronco", [], "any", false, false, false, 229), "html", null, true)) : (yield ""));
            yield "\"   placeholder=\"SIP\"></td>
                        <td><input type=\"text\"   class=\"form-control nb-fc form-control-sm\" name=\"systems_config[ipbx][operadoras][";
            // line 230
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 230), "html", null, true);
            yield "][operadora]\"     value=\"";
            (((CoreExtension::getAttribute($this->env, $this->source, $context["op"], "operadora", [], "any", true, true, false, 230) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["op"], "operadora", [], "any", false, false, false, 230)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["op"], "operadora", [], "any", false, false, false, 230), "html", null, true)) : (yield ""));
            yield "\"     placeholder=\"Vivo\"></td>
                        <td><input type=\"text\"   class=\"form-control nb-fc form-control-sm\" name=\"systems_config[ipbx][operadoras][";
            // line 231
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 231), "html", null, true);
            yield "][ddr]\"           value=\"";
            (((CoreExtension::getAttribute($this->env, $this->source, $context["op"], "ddr", [], "any", true, true, false, 231) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["op"], "ddr", [], "any", false, false, false, 231)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["op"], "ddr", [], "any", false, false, false, 231), "html", null, true)) : (yield ""));
            yield "\"           placeholder=\"33727000-7100\"></td>
                        <td><input type=\"number\" class=\"form-control nb-fc form-control-sm\" name=\"systems_config[ipbx][operadoras][";
            // line 232
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 232), "html", null, true);
            yield "][canais]\"        value=\"";
            (((CoreExtension::getAttribute($this->env, $this->source, $context["op"], "canais", [], "any", true, true, false, 232) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["op"], "canais", [], "any", false, false, false, 232)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["op"], "canais", [], "any", false, false, false, 232), "html", null, true)) : (yield ""));
            yield "\"        placeholder=\"10\" min=\"1\"></td>
                        <td><input type=\"text\"   class=\"form-control nb-fc form-control-sm\" name=\"systems_config[ipbx][operadoras][";
            // line 233
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 233), "html", null, true);
            yield "][ip_proxy]\"      value=\"";
            (((CoreExtension::getAttribute($this->env, $this->source, $context["op"], "ip_proxy", [], "any", true, true, false, 233) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["op"], "ip_proxy", [], "any", false, false, false, 233)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["op"], "ip_proxy", [], "any", false, false, false, 233), "html", null, true)) : (yield ""));
            yield "\"     placeholder=\"192.x.x.x\"></td>
                        <td><input type=\"text\"   class=\"form-control nb-fc form-control-sm\" name=\"systems_config[ipbx][operadoras][";
            // line 234
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 234), "html", null, true);
            yield "][porta_proxy]\"   value=\"";
            (((CoreExtension::getAttribute($this->env, $this->source, $context["op"], "porta_proxy", [], "any", true, true, false, 234) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["op"], "porta_proxy", [], "any", false, false, false, 234)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["op"], "porta_proxy", [], "any", false, false, false, 234), "html", null, true)) : (yield ""));
            yield "\"  placeholder=\"5060\"></td>
                        <td><input type=\"text\"   class=\"form-control nb-fc form-control-sm\" name=\"systems_config[ipbx][operadoras][";
            // line 235
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 235), "html", null, true);
            yield "][ip_audio]\"      value=\"";
            (((CoreExtension::getAttribute($this->env, $this->source, $context["op"], "ip_audio", [], "any", true, true, false, 235) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["op"], "ip_audio", [], "any", false, false, false, 235)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["op"], "ip_audio", [], "any", false, false, false, 235), "html", null, true)) : (yield ""));
            yield "\"     placeholder=\"192.x.x.x\"></td>
                        <td>
                            <button type=\"button\"
                                    class=\"btn btn-sm btn-danger nb-remove-row\"
                                    data-body=\"nb-ipbx-ops-body\"
                                    title=\"Remover operadora\">
                                <i class=\"ti ti-trash\"></i>
                            </button>
                        </td>
                    </tr>
                    ";
            ++$context['loop']['index0'];
            ++$context['loop']['index'];
            $context['loop']['first'] = false;
            if (isset($context['loop']['length'])) {
                --$context['loop']['revindex0'];
                --$context['loop']['revindex'];
                $context['loop']['last'] = 0 === $context['loop']['revindex0'];
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['op'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 246
        yield "                </tbody>
            </table>
        </div>
        <button type=\"button\"
                class=\"btn btn-sm btn-outline-secondary\"
                id=\"nb-btn-add-operadora\">
            <i class=\"ti ti-plus me-1\"></i> Adicionar Operadora
        </button>

        ";
        // line 256
        yield "        <div class=\"text-center mt-4 pb-2\">
            <button type=\"submit\" class=\"btn btn-primary nb-save-btn\" id=\"nb-systems-save-btn\">
                <i class=\"ti ti-device-floppy me-2\"></i>
                Salvar IPBX / PABX
            </button>
        </div>

    </form>
</div>";
        // line 265
        yield "
";
        // line 266
        yield from         $this->loadTemplate("@newbase/companydata/sections/_systems_js.html.twig", "@newbase/companydata/sections/ipbx_pabx.html.twig", 266)->unwrap()->yield($context);
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
        return array (  479 => 266,  476 => 265,  466 => 256,  455 => 246,  428 => 235,  422 => 234,  416 => 233,  410 => 232,  404 => 231,  398 => 230,  392 => 229,  386 => 228,  383 => 227,  366 => 226,  345 => 207,  334 => 197,  307 => 184,  303 => 183,  294 => 177,  290 => 176,  286 => 175,  278 => 170,  274 => 169,  265 => 163,  261 => 162,  252 => 156,  248 => 155,  238 => 148,  234 => 147,  225 => 141,  221 => 140,  215 => 136,  198 => 135,  178 => 117,  175 => 115,  169 => 111,  155 => 100,  140 => 88,  127 => 78,  112 => 66,  100 => 57,  88 => 48,  76 => 39,  64 => 30,  53 => 21,  48 => 18,  43 => 16,  38 => 13,);
    }

    public function getSourceContext()
    {
        return new Source("", "@newbase/companydata/sections/ipbx_pabx.html.twig", "D:\\laragon\\www\\glpi\\plugins\\newbase\\templates\\companydata\\sections\\ipbx_pabx.html.twig");
    }
}
