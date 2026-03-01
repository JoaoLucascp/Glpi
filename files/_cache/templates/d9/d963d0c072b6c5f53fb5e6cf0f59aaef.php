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

/* @newbase/companydata/systems_tab.html.twig */
class __TwigTemplate_e43a61ac852ea0018c52af603d74c5fa extends Template
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
<div class=\"newbase-systems-wrapper\">

    ";
        // line 21
        yield "    <form id=\"nb-systems-form\" method=\"post\" novalidate>
        <input type=\"hidden\" name=\"id\"               value=\"";
        // line 22
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["item_id"] ?? null), "html", null, true);
        yield "\">
        <input type=\"hidden\" name=\"_glpi_csrf_token\" value=\"";
        // line 23
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["csrf_token"] ?? null), "html", null, true);
        yield "\">

        ";
        // line 28
        yield "        <div class=\"accordion accordion-flush nb-accordion\" id=\"nbSystemsAccordion\">

            ";
        // line 33
        yield "            <div class=\"accordion-item nb-accordion-item\">
                <h2 class=\"accordion-header\" id=\"hIPBX\">
                    <button class=\"accordion-button nb-accordion-btn\"
                            type=\"button\"
                            data-bs-toggle=\"collapse\"
                            data-bs-target=\"#pIPBX\"
                            aria-expanded=\"true\"
                            aria-controls=\"pIPBX\">
                        <i class=\"ti ti-phone-call text-primary me-2\"></i>
                        <strong>IPBX / PABX</strong>
                        <span class=\"badge bg-primary-lt ms-2 text-primary\">Servidor de Telefonia</span>
                    </button>
                </h2>
                <div id=\"pIPBX\" class=\"accordion-collapse collapse show\" aria-labelledby=\"hIPBX\">
                    <div class=\"accordion-body\">

                        ";
        // line 50
        yield "                        <p class=\"nb-section-label\">
                            <i class=\"ti ti-server me-1\"></i> Informações do Servidor
                        </p>
                        <div class=\"row g-3 mb-4\">

                            <div class=\"col-12 col-lg-6\">
                                <label class=\"form-label\">Modelo</label>
                                <input type=\"text\"
                                       name=\"systems_config[ipbx][model]\"
                                       value=\"";
        // line 59
        (((CoreExtension::getAttribute($this->env, $this->source, ($context["ipbx"] ?? null), "model", [], "any", true, true, false, 59) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["ipbx"] ?? null), "model", [], "any", false, false, false, 59)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["ipbx"] ?? null), "model", [], "any", false, false, false, 59), "html", null, true)) : (yield ""));
        yield "\"
                                       class=\"form-control nb-fc\"
                                       placeholder=\"Ex: NewCloud, Issabel, FreePBX\">
                            </div>

                            <div class=\"col-12 col-lg-6\">
                                <label class=\"form-label\">Versão do Servidor</label>
                                <input type=\"text\"
                                       name=\"systems_config[ipbx][version]\"
                                       value=\"";
        // line 68
        (((CoreExtension::getAttribute($this->env, $this->source, ($context["ipbx"] ?? null), "version", [], "any", true, true, false, 68) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["ipbx"] ?? null), "version", [], "any", false, false, false, 68)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["ipbx"] ?? null), "version", [], "any", false, false, false, 68), "html", null, true)) : (yield ""));
        yield "\"
                                       class=\"form-control nb-fc\"
                                       placeholder=\"Ex: 3.19\">
                            </div>

                            <div class=\"col-12 col-lg-6\">
                                <label class=\"form-label\">IP Interno</label>
                                <input type=\"text\"
                                       name=\"systems_config[ipbx][internal_ip]\"
                                       value=\"";
        // line 77
        (((CoreExtension::getAttribute($this->env, $this->source, ($context["ipbx"] ?? null), "internal_ip", [], "any", true, true, false, 77) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["ipbx"] ?? null), "internal_ip", [], "any", false, false, false, 77)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["ipbx"] ?? null), "internal_ip", [], "any", false, false, false, 77), "html", null, true)) : (yield ""));
        yield "\"
                                       class=\"form-control nb-fc\"
                                       placeholder=\"Ex: 192.168.0.10\">
                            </div>

                            <div class=\"col-12 col-lg-6\">
                                <label class=\"form-label\">IP Externo</label>
                                <input type=\"text\"
                                       name=\"systems_config[ipbx][external_ip]\"
                                       value=\"";
        // line 86
        (((CoreExtension::getAttribute($this->env, $this->source, ($context["ipbx"] ?? null), "external_ip", [], "any", true, true, false, 86) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["ipbx"] ?? null), "external_ip", [], "any", false, false, false, 86)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["ipbx"] ?? null), "external_ip", [], "any", false, false, false, 86), "html", null, true)) : (yield ""));
        yield "\"
                                       class=\"form-control nb-fc\"
                                       placeholder=\"Ex: 200.x.x.x\">
                            </div>

                            <div class=\"col-12 col-lg-6\">
                                <label class=\"form-label\">Porta Acesso Web</label>
                                <input type=\"text\"
                                       name=\"systems_config[ipbx][web_port]\"
                                       value=\"";
        // line 95
        (((CoreExtension::getAttribute($this->env, $this->source, ($context["ipbx"] ?? null), "web_port", [], "any", true, true, false, 95) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["ipbx"] ?? null), "web_port", [], "any", false, false, false, 95)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["ipbx"] ?? null), "web_port", [], "any", false, false, false, 95), "html", null, true)) : (yield ""));
        yield "\"
                                       class=\"form-control nb-fc\"
                                       placeholder=\"Ex: 2080\">
                            </div>

                            <div class=\"col-12 col-lg-6\">
                                <label class=\"form-label nb-pwd-label\">
                                    Senha Acesso Web
                                    <span class=\"badge bg-warning-lt text-warning ms-1 fw-normal\">visível</span>
                                </label>
                                ";
        // line 106
        yield "                                <input type=\"text\"
                                       name=\"systems_config[ipbx][web_password]\"
                                       value=\"";
        // line 108
        (((CoreExtension::getAttribute($this->env, $this->source, ($context["ipbx"] ?? null), "web_password", [], "any", true, true, false, 108) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["ipbx"] ?? null), "web_password", [], "any", false, false, false, 108)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["ipbx"] ?? null), "web_password", [], "any", false, false, false, 108), "html", null, true)) : (yield ""));
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
        // line 118
        (((CoreExtension::getAttribute($this->env, $this->source, ($context["ipbx"] ?? null), "ssh_port", [], "any", true, true, false, 118) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["ipbx"] ?? null), "ssh_port", [], "any", false, false, false, 118)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["ipbx"] ?? null), "ssh_port", [], "any", false, false, false, 118), "html", null, true)) : (yield ""));
        yield "\"
                                       class=\"form-control nb-fc\"
                                       placeholder=\"Ex: 2022\">
                            </div>

                            <div class=\"col-12 col-lg-6\">
                                <label class=\"form-label nb-pwd-label\">
                                    Senha Acesso SSH
                                    <span class=\"badge bg-warning-lt text-warning ms-1 fw-normal\">visível</span>
                                </label>
                                ";
        // line 129
        yield "                                <input type=\"text\"
                                       name=\"systems_config[ipbx][ssh_password]\"
                                       value=\"";
        // line 131
        (((CoreExtension::getAttribute($this->env, $this->source, ($context["ipbx"] ?? null), "ssh_password", [], "any", true, true, false, 131) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["ipbx"] ?? null), "ssh_password", [], "any", false, false, false, 131)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["ipbx"] ?? null), "ssh_password", [], "any", false, false, false, 131), "html", null, true)) : (yield ""));
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
        // line 142
        (((CoreExtension::getAttribute($this->env, $this->source, ($context["ipbx"] ?? null), "observations", [], "any", true, true, false, 142) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["ipbx"] ?? null), "observations", [], "any", false, false, false, 142)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["ipbx"] ?? null), "observations", [], "any", false, false, false, 142), "html", null, true)) : (yield ""));
        yield "</textarea>
                            </div>

                        </div>";
        // line 146
        yield "
                        ";
        // line 148
        yield "                        <p class=\"nb-section-label\">
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
        // line 166
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable((((CoreExtension::getAttribute($this->env, $this->source, ($context["ipbx"] ?? null), "ramais", [], "any", true, true, false, 166) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["ipbx"] ?? null), "ramais", [], "any", false, false, false, 166)))) ? (CoreExtension::getAttribute($this->env, $this->source, ($context["ipbx"] ?? null), "ramais", [], "any", false, false, false, 166)) : ([])));
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
            // line 167
            yield "                                    <tr class=\"nb-ramal-row\">
                                        <td>
                                            <input type=\"text\"
                                                   class=\"form-control nb-fc form-control-sm\"
                                                   name=\"systems_config[ipbx][ramais][";
            // line 171
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 171), "html", null, true);
            yield "][ramal]\"
                                                   value=\"";
            // line 172
            (((CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "ramal", [], "any", true, true, false, 172) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "ramal", [], "any", false, false, false, 172)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "ramal", [], "any", false, false, false, 172), "html", null, true)) : (yield ""));
            yield "\"
                                                   placeholder=\"2001\">
                                        </td>
                                        <td>
                                            <input type=\"text\"
                                                   class=\"form-control nb-fc nb-pwd form-control-sm\"
                                                   name=\"systems_config[ipbx][ramais][";
            // line 178
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 178), "html", null, true);
            yield "][senha]\"
                                                   value=\"";
            // line 179
            (((CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "senha", [], "any", true, true, false, 179) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "senha", [], "any", false, false, false, 179)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "senha", [], "any", false, false, false, 179), "html", null, true)) : (yield ""));
            yield "\"
                                                   placeholder=\"senha\"
                                                   autocomplete=\"off\">
                                        </td>
                                        <td>
                                            <input type=\"text\"
                                                   class=\"form-control nb-fc form-control-sm\"
                                                   name=\"systems_config[ipbx][ramais][";
            // line 186
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 186), "html", null, true);
            yield "][ip]\"
                                                   value=\"";
            // line 187
            (((CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "ip", [], "any", true, true, false, 187) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "ip", [], "any", false, false, false, 187)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "ip", [], "any", false, false, false, 187), "html", null, true)) : (yield ""));
            yield "\"
                                                   placeholder=\"192.168.x.x\">
                                        </td>
                                        <td>
                                            <input type=\"text\"
                                                   class=\"form-control nb-fc form-control-sm\"
                                                   name=\"systems_config[ipbx][ramais][";
            // line 193
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 193), "html", null, true);
            yield "][nome]\"
                                                   value=\"";
            // line 194
            (((CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "nome", [], "any", true, true, false, 194) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "nome", [], "any", false, false, false, 194)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "nome", [], "any", false, false, false, 194), "html", null, true)) : (yield ""));
            yield "\"
                                                   placeholder=\"Ex: José\">
                                        </td>
                                        <td>
                                            <input type=\"text\"
                                                   class=\"form-control nb-fc form-control-sm\"
                                                   name=\"systems_config[ipbx][ramais][";
            // line 200
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 200), "html", null, true);
            yield "][localidade]\"
                                                   value=\"";
            // line 201
            (((CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "localidade", [], "any", true, true, false, 201) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "localidade", [], "any", false, false, false, 201)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "localidade", [], "any", false, false, false, 201), "html", null, true)) : (yield ""));
            yield "\"
                                                   placeholder=\"Financeiro\">
                                        </td>
                                        <td>
                                            <select class=\"form-select nb-fc form-select-sm\"
                                                    name=\"systems_config[ipbx][ramais][";
            // line 206
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 206), "html", null, true);
            yield "][gravacao]\">
                                                <option value=\"nao\" ";
            // line 207
            yield ((((((CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "gravacao", [], "any", true, true, false, 207) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "gravacao", [], "any", false, false, false, 207)))) ? (CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "gravacao", [], "any", false, false, false, 207)) : ("nao")) == "nao")) ? ("selected") : (""));
            yield ">Não</option>
                                                <option value=\"sim\" ";
            // line 208
            yield ((((((CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "gravacao", [], "any", true, true, false, 208) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "gravacao", [], "any", false, false, false, 208)))) ? (CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "gravacao", [], "any", false, false, false, 208)) : ("")) == "sim")) ? ("selected") : (""));
            yield ">Sim</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type=\"text\"
                                                   class=\"form-control nb-fc form-control-sm\"
                                                   name=\"systems_config[ipbx][ramais][";
            // line 214
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 214), "html", null, true);
            yield "][obs]\"
                                                   value=\"";
            // line 215
            (((CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "obs", [], "any", true, true, false, 215) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "obs", [], "any", false, false, false, 215)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "obs", [], "any", false, false, false, 215), "html", null, true)) : (yield ""));
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
        // line 228
        yield "                                </tbody>
                            </table>
                        </div>
                        <button type=\"button\"
                                class=\"btn btn-sm btn-outline-primary\"
                                id=\"nb-btn-add-ramal\">
                            <i class=\"ti ti-plus me-1\"></i> Adicionar Ramal
                        </button>

                        ";
        // line 238
        yield "                        <p class=\"nb-section-label mt-4\">
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
        // line 257
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable((((CoreExtension::getAttribute($this->env, $this->source, ($context["ipbx"] ?? null), "operadoras", [], "any", true, true, false, 257) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["ipbx"] ?? null), "operadoras", [], "any", false, false, false, 257)))) ? (CoreExtension::getAttribute($this->env, $this->source, ($context["ipbx"] ?? null), "operadoras", [], "any", false, false, false, 257)) : ([])));
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
            // line 258
            yield "                                    <tr class=\"nb-op-row\">
                                        <td><input type=\"text\" class=\"form-control nb-fc form-control-sm\" name=\"systems_config[ipbx][operadoras][";
            // line 259
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 259), "html", null, true);
            yield "][numero_piloto]\" value=\"";
            (((CoreExtension::getAttribute($this->env, $this->source, $context["op"], "numero_piloto", [], "any", true, true, false, 259) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["op"], "numero_piloto", [], "any", false, false, false, 259)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["op"], "numero_piloto", [], "any", false, false, false, 259), "html", null, true)) : (yield ""));
            yield "\" placeholder=\"(27)3xxx-xxxx\"></td>
                                        <td><input type=\"text\" class=\"form-control nb-fc form-control-sm\" name=\"systems_config[ipbx][operadoras][";
            // line 260
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 260), "html", null, true);
            yield "][tipo_tronco]\"   value=\"";
            (((CoreExtension::getAttribute($this->env, $this->source, $context["op"], "tipo_tronco", [], "any", true, true, false, 260) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["op"], "tipo_tronco", [], "any", false, false, false, 260)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["op"], "tipo_tronco", [], "any", false, false, false, 260), "html", null, true)) : (yield ""));
            yield "\"   placeholder=\"SIP\"></td>
                                        <td><input type=\"text\" class=\"form-control nb-fc form-control-sm\" name=\"systems_config[ipbx][operadoras][";
            // line 261
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 261), "html", null, true);
            yield "][operadora]\"     value=\"";
            (((CoreExtension::getAttribute($this->env, $this->source, $context["op"], "operadora", [], "any", true, true, false, 261) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["op"], "operadora", [], "any", false, false, false, 261)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["op"], "operadora", [], "any", false, false, false, 261), "html", null, true)) : (yield ""));
            yield "\"     placeholder=\"Vivo\"></td>
                                        <td><input type=\"text\" class=\"form-control nb-fc form-control-sm\" name=\"systems_config[ipbx][operadoras][";
            // line 262
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 262), "html", null, true);
            yield "][ddr]\"           value=\"";
            (((CoreExtension::getAttribute($this->env, $this->source, $context["op"], "ddr", [], "any", true, true, false, 262) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["op"], "ddr", [], "any", false, false, false, 262)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["op"], "ddr", [], "any", false, false, false, 262), "html", null, true)) : (yield ""));
            yield "\"           placeholder=\"33727000-7100\"></td>
                                        <td><input type=\"number\" class=\"form-control nb-fc form-control-sm\" name=\"systems_config[ipbx][operadoras][";
            // line 263
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 263), "html", null, true);
            yield "][canais]\"      value=\"";
            (((CoreExtension::getAttribute($this->env, $this->source, $context["op"], "canais", [], "any", true, true, false, 263) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["op"], "canais", [], "any", false, false, false, 263)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["op"], "canais", [], "any", false, false, false, 263), "html", null, true)) : (yield ""));
            yield "\"        placeholder=\"10\" min=\"1\"></td>
                                        <td><input type=\"text\" class=\"form-control nb-fc form-control-sm\" name=\"systems_config[ipbx][operadoras][";
            // line 264
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 264), "html", null, true);
            yield "][ip_proxy]\"     value=\"";
            (((CoreExtension::getAttribute($this->env, $this->source, $context["op"], "ip_proxy", [], "any", true, true, false, 264) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["op"], "ip_proxy", [], "any", false, false, false, 264)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["op"], "ip_proxy", [], "any", false, false, false, 264), "html", null, true)) : (yield ""));
            yield "\"     placeholder=\"192.x.x.x\"></td>
                                        <td><input type=\"text\" class=\"form-control nb-fc form-control-sm\" name=\"systems_config[ipbx][operadoras][";
            // line 265
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 265), "html", null, true);
            yield "][porta_proxy]\"  value=\"";
            (((CoreExtension::getAttribute($this->env, $this->source, $context["op"], "porta_proxy", [], "any", true, true, false, 265) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["op"], "porta_proxy", [], "any", false, false, false, 265)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["op"], "porta_proxy", [], "any", false, false, false, 265), "html", null, true)) : (yield ""));
            yield "\"  placeholder=\"5060\"></td>
                                        <td><input type=\"text\" class=\"form-control nb-fc form-control-sm\" name=\"systems_config[ipbx][operadoras][";
            // line 266
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 266), "html", null, true);
            yield "][ip_audio]\"     value=\"";
            (((CoreExtension::getAttribute($this->env, $this->source, $context["op"], "ip_audio", [], "any", true, true, false, 266) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["op"], "ip_audio", [], "any", false, false, false, 266)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["op"], "ip_audio", [], "any", false, false, false, 266), "html", null, true)) : (yield ""));
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
        // line 277
        yield "                                </tbody>
                            </table>
                        </div>
                        <button type=\"button\"
                                class=\"btn btn-sm btn-outline-secondary\"
                                id=\"nb-btn-add-operadora\">
                            <i class=\"ti ti-plus me-1\"></i> Adicionar Operadora
                        </button>

                    </div>";
        // line 287
        yield "                </div>
            </div>";
        // line 289
        yield "
            ";
        // line 293
        yield "            <div class=\"accordion-item nb-accordion-item\">
                <h2 class=\"accordion-header\" id=\"hCloud\">
                    <button class=\"accordion-button nb-accordion-btn collapsed\"
                            type=\"button\"
                            data-bs-toggle=\"collapse\"
                            data-bs-target=\"#pCloud\"
                            aria-expanded=\"false\"
                            aria-controls=\"pCloud\">
                        <i class=\"ti ti-cloud text-cyan me-2\"></i>
                        <strong>IPBX Cloud</strong>
                        <span class=\"badge bg-cyan-lt ms-2 text-cyan\">Servidor em Nuvem</span>
                    </button>
                </h2>
                <div id=\"pCloud\" class=\"accordion-collapse collapse\" aria-labelledby=\"hCloud\">
                    <div class=\"accordion-body\">

                        <p class=\"nb-section-label\">
                            <i class=\"ti ti-server me-1\"></i> Informações do Servidor Cloud
                        </p>
                        <div class=\"row g-3\">

                            <div class=\"col-12 col-lg-6\">
                                <label class=\"form-label\">Modelo</label>
                                <input type=\"text\"
                                       name=\"systems_config[ipbx_cloud][model]\"
                                       value=\"";
        // line 318
        (((CoreExtension::getAttribute($this->env, $this->source, ($context["ipbx_cloud"] ?? null), "model", [], "any", true, true, false, 318) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["ipbx_cloud"] ?? null), "model", [], "any", false, false, false, 318)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["ipbx_cloud"] ?? null), "model", [], "any", false, false, false, 318), "html", null, true)) : (yield ""));
        yield "\"
                                       class=\"form-control nb-fc\"
                                       placeholder=\"Ex: VoIP.ms, 3CX Cloud, Twilio\">
                            </div>

                            <div class=\"col-12 col-lg-6\">
                                <label class=\"form-label\">Versão</label>
                                <input type=\"text\"
                                       name=\"systems_config[ipbx_cloud][version]\"
                                       value=\"";
        // line 327
        (((CoreExtension::getAttribute($this->env, $this->source, ($context["ipbx_cloud"] ?? null), "version", [], "any", true, true, false, 327) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["ipbx_cloud"] ?? null), "version", [], "any", false, false, false, 327)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["ipbx_cloud"] ?? null), "version", [], "any", false, false, false, 327), "html", null, true)) : (yield ""));
        yield "\"
                                       class=\"form-control nb-fc\"
                                       placeholder=\"Ex: 19.0\">
                            </div>

                            <div class=\"col-12 col-lg-6\">
                                <label class=\"form-label\">IP Interno</label>
                                <input type=\"text\"
                                       name=\"systems_config[ipbx_cloud][internal_ip]\"
                                       value=\"";
        // line 336
        (((CoreExtension::getAttribute($this->env, $this->source, ($context["ipbx_cloud"] ?? null), "internal_ip", [], "any", true, true, false, 336) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["ipbx_cloud"] ?? null), "internal_ip", [], "any", false, false, false, 336)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["ipbx_cloud"] ?? null), "internal_ip", [], "any", false, false, false, 336), "html", null, true)) : (yield ""));
        yield "\"
                                       class=\"form-control nb-fc\"
                                       placeholder=\"Ex: 10.0.0.1\">
                            </div>

                            <div class=\"col-12 col-lg-6\">
                                <label class=\"form-label\">Host / IP Externo</label>
                                <input type=\"text\"
                                       name=\"systems_config[ipbx_cloud][external_ip]\"
                                       value=\"";
        // line 345
        (((CoreExtension::getAttribute($this->env, $this->source, ($context["ipbx_cloud"] ?? null), "external_ip", [], "any", true, true, false, 345) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["ipbx_cloud"] ?? null), "external_ip", [], "any", false, false, false, 345)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["ipbx_cloud"] ?? null), "external_ip", [], "any", false, false, false, 345), "html", null, true)) : (yield ""));
        yield "\"
                                       class=\"form-control nb-fc\"
                                       placeholder=\"Ex: pbx.empresa.com ou 200.x.x.x\">
                            </div>

                            <div class=\"col-12 col-lg-6\">
                                <label class=\"form-label\">Porta Acesso Web</label>
                                <input type=\"text\"
                                       name=\"systems_config[ipbx_cloud][web_port]\"
                                       value=\"";
        // line 354
        (((CoreExtension::getAttribute($this->env, $this->source, ($context["ipbx_cloud"] ?? null), "web_port", [], "any", true, true, false, 354) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["ipbx_cloud"] ?? null), "web_port", [], "any", false, false, false, 354)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["ipbx_cloud"] ?? null), "web_port", [], "any", false, false, false, 354), "html", null, true)) : (yield ""));
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
        // line 366
        (((CoreExtension::getAttribute($this->env, $this->source, ($context["ipbx_cloud"] ?? null), "web_password", [], "any", true, true, false, 366) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["ipbx_cloud"] ?? null), "web_password", [], "any", false, false, false, 366)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["ipbx_cloud"] ?? null), "web_password", [], "any", false, false, false, 366), "html", null, true)) : (yield ""));
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
        // line 376
        (((CoreExtension::getAttribute($this->env, $this->source, ($context["ipbx_cloud"] ?? null), "ssh_port", [], "any", true, true, false, 376) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["ipbx_cloud"] ?? null), "ssh_port", [], "any", false, false, false, 376)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["ipbx_cloud"] ?? null), "ssh_port", [], "any", false, false, false, 376), "html", null, true)) : (yield ""));
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
        // line 388
        (((CoreExtension::getAttribute($this->env, $this->source, ($context["ipbx_cloud"] ?? null), "ssh_password", [], "any", true, true, false, 388) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["ipbx_cloud"] ?? null), "ssh_password", [], "any", false, false, false, 388)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["ipbx_cloud"] ?? null), "ssh_password", [], "any", false, false, false, 388), "html", null, true)) : (yield ""));
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
        // line 399
        (((CoreExtension::getAttribute($this->env, $this->source, ($context["ipbx_cloud"] ?? null), "observations", [], "any", true, true, false, 399) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["ipbx_cloud"] ?? null), "observations", [], "any", false, false, false, 399)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["ipbx_cloud"] ?? null), "observations", [], "any", false, false, false, 399), "html", null, true)) : (yield ""));
        yield "</textarea>
                            </div>

                        </div>";
        // line 403
        yield "                    </div>
                </div>
            </div>";
        // line 406
        yield "
            ";
        // line 410
        yield "            <div class=\"accordion-item nb-accordion-item\">
                <h2 class=\"accordion-header\" id=\"hChatbot\">
                    <button class=\"accordion-button nb-accordion-btn collapsed\"
                            type=\"button\"
                            data-bs-toggle=\"collapse\"
                            data-bs-target=\"#pChatbot\"
                            aria-expanded=\"false\"
                            aria-controls=\"pChatbot\">
                        <i class=\"ti ti-robot text-green me-2\"></i>
                        <strong>Chatbot</strong>
                        <span class=\"badge bg-success-lt ms-2 text-success\">Omnichannel</span>
                    </button>
                </h2>
                <div id=\"pChatbot\" class=\"accordion-collapse collapse\" aria-labelledby=\"hChatbot\">
                    <div class=\"accordion-body\">
                        <div class=\"row g-3\">

                            <div class=\"col-12 col-lg-6\">
                                <label class=\"form-label\">Plataforma</label>
                                <input type=\"text\"
                                       name=\"systems_config[chatbot][platform]\"
                                       value=\"";
        // line 431
        (((CoreExtension::getAttribute($this->env, $this->source, ($context["chatbot"] ?? null), "platform", [], "any", true, true, false, 431) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["chatbot"] ?? null), "platform", [], "any", false, false, false, 431)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["chatbot"] ?? null), "platform", [], "any", false, false, false, 431), "html", null, true)) : (yield ""));
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
        // line 443
        (((CoreExtension::getAttribute($this->env, $this->source, ($context["chatbot"] ?? null), "api_key", [], "any", true, true, false, 443) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["chatbot"] ?? null), "api_key", [], "any", false, false, false, 443)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["chatbot"] ?? null), "api_key", [], "any", false, false, false, 443), "html", null, true)) : (yield ""));
        yield "\"
                                       class=\"form-control nb-fc nb-pwd\"
                                       placeholder=\"Chave de API da plataforma\"
                                       autocomplete=\"off\">
                            </div>

                            <div class=\"col-12\">
                                <label class=\"form-label\">Configuração / Notas</label>
                                <textarea name=\"systems_config[chatbot][config]\"
                                          class=\"form-control nb-fc\"
                                          rows=\"4\"
                                          placeholder=\"Webhooks, endpoints, configurações adicionais...\">";
        // line 454
        (((CoreExtension::getAttribute($this->env, $this->source, ($context["chatbot"] ?? null), "config", [], "any", true, true, false, 454) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["chatbot"] ?? null), "config", [], "any", false, false, false, 454)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["chatbot"] ?? null), "config", [], "any", false, false, false, 454), "html", null, true)) : (yield ""));
        yield "</textarea>
                            </div>

                        </div>
                    </div>
                </div>
            </div>";
        // line 461
        yield "
            ";
        // line 465
        yield "            <div class=\"accordion-item nb-accordion-item\">
                <h2 class=\"accordion-header\" id=\"hLinha\">
                    <button class=\"accordion-button nb-accordion-btn collapsed\"
                            type=\"button\"
                            data-bs-toggle=\"collapse\"
                            data-bs-target=\"#pLinha\"
                            aria-expanded=\"false\"
                            aria-controls=\"pLinha\">
                        <i class=\"ti ti-phone text-orange me-2\"></i>
                        <strong>Linha Telefônica</strong>
                        <span class=\"badge bg-orange-lt ms-2 text-orange\">Linha Fixa</span>
                    </button>
                </h2>
                <div id=\"pLinha\" class=\"accordion-collapse collapse\" aria-labelledby=\"hLinha\">
                    <div class=\"accordion-body\">
                        <div class=\"row g-3\">

                            <div class=\"col-12 col-lg-6\">
                                <label class=\"form-label\">Operadora</label>
                                <input type=\"text\"
                                       name=\"systems_config[linha][operadora]\"
                                       value=\"";
        // line 486
        (((CoreExtension::getAttribute($this->env, $this->source, ($context["linha"] ?? null), "operadora", [], "any", true, true, false, 486) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["linha"] ?? null), "operadora", [], "any", false, false, false, 486)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["linha"] ?? null), "operadora", [], "any", false, false, false, 486), "html", null, true)) : (yield ""));
        yield "\"
                                       class=\"form-control nb-fc\"
                                       placeholder=\"Ex: Claro, Vivo, TIM, Oi\">
                            </div>

                            <div class=\"col-12 col-lg-6\">
                                <label class=\"form-label\">Número do Contrato</label>
                                <input type=\"text\"
                                       name=\"systems_config[linha][contrato]\"
                                       value=\"";
        // line 495
        (((CoreExtension::getAttribute($this->env, $this->source, ($context["linha"] ?? null), "contrato", [], "any", true, true, false, 495) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["linha"] ?? null), "contrato", [], "any", false, false, false, 495)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["linha"] ?? null), "contrato", [], "any", false, false, false, 495), "html", null, true)) : (yield ""));
        yield "\"
                                       class=\"form-control nb-fc\"
                                       placeholder=\"Número ou código do contrato\">
                            </div>

                            <div class=\"col-12\">
                                <label class=\"form-label\">Notas</label>
                                <textarea name=\"systems_config[linha][notas]\"
                                          class=\"form-control nb-fc\"
                                          rows=\"3\"
                                          placeholder=\"Informações adicionais sobre a linha telefônica...\">";
        // line 505
        (((CoreExtension::getAttribute($this->env, $this->source, ($context["linha"] ?? null), "notas", [], "any", true, true, false, 505) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["linha"] ?? null), "notas", [], "any", false, false, false, 505)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["linha"] ?? null), "notas", [], "any", false, false, false, 505), "html", null, true)) : (yield ""));
        yield "</textarea>
                            </div>

                        </div>
                    </div>
                </div>
            </div>";
        // line 512
        yield "
        </div>";
        // line 514
        yield "
        ";
        // line 518
        yield "        <div class=\"text-center mt-4 pb-2\">
            <button type=\"submit\" class=\"btn btn-primary nb-save-btn\" id=\"nb-systems-save-btn\">
                <i class=\"ti ti-device-floppy me-2\"></i>
                Salvar Configurações de Sistemas
            </button>
        </div>

    </form>
</div>";
        // line 527
        yield "
";
        // line 531
        yield "<script>
(function (\$) {
    'use strict';

    // ── Helpers ─────────────────────────────────────────────────

    /**
     * Renumera os índices dos campos de uma tbody após remover linha.
     * Substitui [N][ no final de cada name= pelo índice correto.
     */
    function renumerarLinhas(tbodyId) {
        \$('#' + tbodyId + ' tr').each(function (i) {
            \$(this).find('input, select, textarea').each(function () {
                var name = \$(this).attr('name');
                if (!name) return;
                // Ex: systems_config[ipbx][ramais][3][ramal] → [i][ramal]
                \$(this).attr('name', name.replace(/\\]\\[(\\d+)\\]\\[/, '][' + i + ']['));
            });
        });
    }

    /**
     * Gera HTML de uma linha de ramal vazia.
     */
    function novaLinhaRamal(idx) {
        var p = 'systems_config[ipbx][ramais][' + idx + ']';
        return '<tr class=\"nb-ramal-row nb-row-new\">'
            + '<td><input type=\"text\"   class=\"form-control nb-fc form-control-sm\" name=\"' + p + '[ramal]\"      placeholder=\"2001\"></td>'
            + '<td><input type=\"text\"   class=\"form-control nb-fc nb-pwd form-control-sm\" name=\"' + p + '[senha]\" placeholder=\"senha\" autocomplete=\"off\"></td>'
            + '<td><input type=\"text\"   class=\"form-control nb-fc form-control-sm\" name=\"' + p + '[ip]\"         placeholder=\"192.168.x.x\"></td>'
            + '<td><input type=\"text\"   class=\"form-control nb-fc form-control-sm\" name=\"' + p + '[nome]\"       placeholder=\"Ex: José\"></td>'
            + '<td><input type=\"text\"   class=\"form-control nb-fc form-control-sm\" name=\"' + p + '[localidade]\" placeholder=\"Financeiro\"></td>'
            + '<td><select class=\"form-select nb-fc form-select-sm\" name=\"' + p + '[gravacao]\">'
            +     '<option value=\"nao\">Não</option><option value=\"sim\">Sim</option>'
            + '</select></td>'
            + '<td><input type=\"text\"   class=\"form-control nb-fc form-control-sm\" name=\"' + p + '[obs]\"        placeholder=\"...\"></td>'
            + '<td><button type=\"button\" class=\"btn btn-sm btn-danger nb-remove-row\" data-body=\"nb-ipbx-ramais-body\" title=\"Remover\"><i class=\"ti ti-trash\"></i></button></td>'
            + '</tr>';
    }

    /**
     * Gera HTML de uma linha de operadora vazia.
     */
    function novaLinhaOperadora(idx) {
        var p = 'systems_config[ipbx][operadoras][' + idx + ']';
        return '<tr class=\"nb-op-row nb-row-new\">'
            + '<td><input type=\"text\"   class=\"form-control nb-fc form-control-sm\" name=\"' + p + '[numero_piloto]\" placeholder=\"(27)3xxx-xxxx\"></td>'
            + '<td><input type=\"text\"   class=\"form-control nb-fc form-control-sm\" name=\"' + p + '[tipo_tronco]\"   placeholder=\"SIP\"></td>'
            + '<td><input type=\"text\"   class=\"form-control nb-fc form-control-sm\" name=\"' + p + '[operadora]\"     placeholder=\"Vivo\"></td>'
            + '<td><input type=\"text\"   class=\"form-control nb-fc form-control-sm\" name=\"' + p + '[ddr]\"           placeholder=\"33727000-7100\"></td>'
            + '<td><input type=\"number\" class=\"form-control nb-fc form-control-sm\" name=\"' + p + '[canais]\"        placeholder=\"10\" min=\"1\"></td>'
            + '<td><input type=\"text\"   class=\"form-control nb-fc form-control-sm\" name=\"' + p + '[ip_proxy]\"      placeholder=\"192.x.x.x\"></td>'
            + '<td><input type=\"text\"   class=\"form-control nb-fc form-control-sm\" name=\"' + p + '[porta_proxy]\"   placeholder=\"5060\"></td>'
            + '<td><input type=\"text\"   class=\"form-control nb-fc form-control-sm\" name=\"' + p + '[ip_audio]\"      placeholder=\"192.x.x.x\"></td>'
            + '<td><button type=\"button\" class=\"btn btn-sm btn-danger nb-remove-row\" data-body=\"nb-ipbx-ops-body\" title=\"Remover\"><i class=\"ti ti-trash\"></i></button></td>'
            + '</tr>';
    }

    // ── Adicionar Ramal ──────────────────────────────────────────
    \$(document).on('click', '#nb-btn-add-ramal', function () {
        var \$body = \$('#nb-ipbx-ramais-body');
        \$body.append(novaLinhaRamal(\$body.find('tr').length));
        // Foca o primeiro campo da nova linha
        \$body.find('tr:last input:first').trigger('focus');
    });

    // ── Adicionar Operadora ──────────────────────────────────────
    \$(document).on('click', '#nb-btn-add-operadora', function () {
        var \$body = \$('#nb-ipbx-ops-body');
        \$body.append(novaLinhaOperadora(\$body.find('tr').length));
        \$body.find('tr:last input:first').trigger('focus');
    });

    // ── Remover linha (Ramal ou Operadora) ───────────────────────
    \$(document).on('click', '.nb-remove-row', function () {
        var bodyId = \$(this).data('body');
        \$(this).closest('tr').remove();
        renumerarLinhas(bodyId);
    });

    // ── Interceptar submit → AJAX com SweetAlert2 ───────────────
    \$('#nb-systems-form').on('submit', function (e) {
        e.preventDefault();

        var \$form = \$(this);
        var \$btn  = \$('#nb-systems-save-btn');

        \$btn.prop('disabled', true)
            .html('<span class=\"nb-spin d-inline-block me-2\">⟳</span> Salvando...');

        // URL do endpoint AJAX dedicado (na mesma pasta ajax/ do plugin)
        var ajaxUrl = (typeof CFG_GLPI !== 'undefined' ? CFG_GLPI.root_doc : '')
                    + '/plugins/newbase/ajax/systemsConfig.php';

        \$.ajax({
            type    : 'POST',
            url     : ajaxUrl,
            data    : \$form.serialize(),
            dataType: 'json',
            timeout : 15000,
            success : function (res) {
                \$btn.prop('disabled', false)
                    .html('<i class=\"ti ti-device-floppy me-2\"></i> Salvar Configurações de Sistemas');

                if (res && res.success) {
                    _nbNotify('Configurações salvas com sucesso!', 'success');
                } else {
                    _nbNotify(res.message || 'Erro ao salvar configurações.', 'error');
                }
            },
            error   : function (xhr) {
                \$btn.prop('disabled', false)
                    .html('<i class=\"ti ti-device-floppy me-2\"></i> Salvar Configurações de Sistemas');

                var msg = 'Erro de comunicação com o servidor.';
                if (xhr.status === 403) msg = 'Sessão expirada. Recarregue a página.';
                if (xhr.status === 400) msg = 'Dados inválidos enviados.';
                _nbNotify(msg, 'error');
            }
        });
    });

    // ── Notificação: SweetAlert2 > GLPI toast > alert ────────────
    function _nbNotify(msg, type) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon              : type,
                text              : msg,
                toast             : (type === 'success'),
                position          : (type === 'success') ? 'top-end' : 'center',
                timer             : (type === 'success') ? 2500 : 0,
                showConfirmButton : (type !== 'success'),
                confirmButtonColor: '#206bc4'
            });
            return;
        }
        if (type === 'success' && typeof glpi_toast_info !== 'undefined') {
            glpi_toast_info(msg);
        } else if (type === 'error' && typeof glpi_toast_error !== 'undefined') {
            glpi_toast_error(msg);
        } else {
            alert(msg);
        }
    }

    // ── Injetar CSS de animação spin ─────────────────────────────
    if (!document.getElementById('nb-sys-spin-style')) {
        var s = document.createElement('style');
        s.id  = 'nb-sys-spin-style';
        s.textContent = '@keyframes nb-spin-anim{to{transform:rotate(360deg)}}'
                      + '.nb-spin{animation:nb-spin-anim .8s linear infinite;display:inline-block}';
        document.head.appendChild(s);
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
        return "@newbase/companydata/systems_tab.html.twig";
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
        return array (  785 => 531,  782 => 527,  772 => 518,  769 => 514,  766 => 512,  757 => 505,  744 => 495,  732 => 486,  709 => 465,  706 => 461,  697 => 454,  683 => 443,  668 => 431,  645 => 410,  642 => 406,  638 => 403,  632 => 399,  618 => 388,  603 => 376,  590 => 366,  575 => 354,  563 => 345,  551 => 336,  539 => 327,  527 => 318,  500 => 293,  497 => 289,  494 => 287,  483 => 277,  456 => 266,  450 => 265,  444 => 264,  438 => 263,  432 => 262,  426 => 261,  420 => 260,  414 => 259,  411 => 258,  394 => 257,  373 => 238,  362 => 228,  335 => 215,  331 => 214,  322 => 208,  318 => 207,  314 => 206,  306 => 201,  302 => 200,  293 => 194,  289 => 193,  280 => 187,  276 => 186,  266 => 179,  262 => 178,  253 => 172,  249 => 171,  243 => 167,  226 => 166,  206 => 148,  203 => 146,  197 => 142,  183 => 131,  179 => 129,  166 => 118,  153 => 108,  149 => 106,  136 => 95,  124 => 86,  112 => 77,  100 => 68,  88 => 59,  77 => 50,  59 => 33,  55 => 28,  50 => 23,  46 => 22,  43 => 21,  38 => 15,);
    }

    public function getSourceContext()
    {
        return new Source("", "@newbase/companydata/systems_tab.html.twig", "D:\\laragon\\www\\glpi\\plugins\\newbase\\templates\\companydata\\systems_tab.html.twig");
    }
}
