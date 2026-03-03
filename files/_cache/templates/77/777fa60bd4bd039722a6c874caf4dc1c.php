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

    <form id=\"nb-form-ipbx-cloud\" class=\"nb-section-form\" method=\"post\" novalidate
          data-section-key=\"ipbx_cloud\"
          data-save-label=\"Salvar IPBX Cloud\">

        <input type=\"hidden\" name=\"id\"               value=\"";
        // line 28
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["item_id"] ?? null), "html", null, true);
        yield "\">
        <input type=\"hidden\" name=\"section_key\"      value=\"";
        // line 29
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["section_key"] ?? null), "html", null, true);
        yield "\">
        <input type=\"hidden\" name=\"_glpi_csrf_token\" value=\"";
        // line 30
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["csrf_token"] ?? null), "html", null, true);
        yield "\">

        ";
        // line 35
        yield "        <div class=\"nb-card mb-4\">
            <div class=\"nb-card-header\">
                <i class=\"ti ti-cloud me-2 text-cyan\"></i>
                <strong>Informações do Servidor Cloud</strong>
            </div>
            <div class=\"nb-card-body\">
                <div class=\"row g-3\">

                    <div class=\"col-12 col-md-6\">
                        <label class=\"form-label\">Modelo</label>
                        <input type=\"text\"
                               name=\"modelo\"
                               value=\"";
        // line 47
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "modelo", [], "any", false, false, false, 47));
        yield "\"
                               class=\"form-control\"
                               placeholder=\"Ex: 3CX Cloud, VoIP.ms, Twilio\">
                    </div>

                    <div class=\"col-12 col-md-6\">
                        <label class=\"form-label\">Versão</label>
                        <input type=\"text\"
                               name=\"versao\"
                               value=\"";
        // line 56
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "versao", [], "any", false, false, false, 56));
        yield "\"
                               class=\"form-control\"
                               placeholder=\"Ex: 19.0\">
                    </div>

                    <div class=\"col-12 col-md-6\">
                        <label class=\"form-label\">IP Interno</label>
                        <input type=\"text\"
                               name=\"ip_interno\"
                               value=\"";
        // line 65
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "ip_interno", [], "any", false, false, false, 65));
        yield "\"
                               class=\"form-control\"
                               placeholder=\"Ex: 10.0.0.1\">
                    </div>

                    <div class=\"col-12 col-md-6\">
                        <label class=\"form-label\">Host / IP Externo</label>
                        <input type=\"text\"
                               name=\"ip_externo\"
                               value=\"";
        // line 74
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "ip_externo", [], "any", false, false, false, 74));
        yield "\"
                               class=\"form-control\"
                               placeholder=\"Ex: pbx.empresa.com ou 200.x.x.x\">
                    </div>

                    <div class=\"col-12 col-md-3\">
                        <label class=\"form-label\">Porta Web</label>
                        <input type=\"text\"
                               name=\"porta_web\"
                               value=\"";
        // line 83
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "porta_web", [], "any", false, false, false, 83));
        yield "\"
                               class=\"form-control\"
                               placeholder=\"Ex: 5001\">
                    </div>

                    <div class=\"col-12 col-md-3\">
                        <label class=\"form-label\">
                            Senha Web
                            <span class=\"badge bg-warning-lt text-warning ms-1 fw-normal fs-6\">visível</span>
                        </label>
                        <input type=\"text\"
                               name=\"senha_web\"
                               value=\"";
        // line 95
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "senha_web", [], "any", false, false, false, 95));
        yield "\"
                               class=\"form-control nb-pwd\"
                               placeholder=\"Senha de acesso web\"
                               autocomplete=\"off\">
                    </div>

                    <div class=\"col-12 col-md-3\">
                        <label class=\"form-label\">Porta SSH</label>
                        <input type=\"text\"
                               name=\"porta_ssh\"
                               value=\"";
        // line 105
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "porta_ssh", [], "any", false, false, false, 105));
        yield "\"
                               class=\"form-control\"
                               placeholder=\"Ex: 22\">
                    </div>

                    <div class=\"col-12 col-md-3\">
                        <label class=\"form-label\">
                            Senha SSH
                            <span class=\"badge bg-warning-lt text-warning ms-1 fw-normal fs-6\">visível</span>
                        </label>
                        <input type=\"text\"
                               name=\"senha_ssh\"
                               value=\"";
        // line 117
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "senha_ssh", [], "any", false, false, false, 117));
        yield "\"
                               class=\"form-control nb-pwd\"
                               placeholder=\"Senha SSH\"
                               autocomplete=\"off\">
                    </div>

                    <div class=\"col-12\">
                        <label class=\"form-label\">Observações</label>
                        <textarea name=\"observacoes\"
                                  class=\"form-control\"
                                  rows=\"3\"
                                  placeholder=\"Observações adicionais sobre o servidor em nuvem...\">";
        // line 128
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "observacoes", [], "any", false, false, false, 128));
        yield "</textarea>
                    </div>

                </div>";
        // line 132
        yield "            </div>";
        // line 133
        yield "        </div>";
        // line 134
        yield "
        ";
        // line 138
        yield "        <div class=\"nb-card mb-4\">
            <div class=\"nb-card-header\">
                <i class=\"ti ti-device-mobile me-2 text-blue\"></i>
                <strong>Ramais</strong>
                <button type=\"button\"
                        class=\"btn btn-sm btn-outline-primary ms-auto nb-btn-add-row\"
                        data-target-body=\"nb-cloud-ramais-body\"
                        data-template=\"nb-tpl-cloud-ramal\">
                    <i class=\"ti ti-plus me-1\"></i> Adicionar Ramal
                </button>
            </div>
            <div class=\"nb-card-body p-0\">
                <div class=\"table-responsive\">
                    <table class=\"table table-sm nb-table align-middle mb-0\">
                        <thead class=\"table-light\">
                            <tr>
                                <th>Ramal</th>
                                <th>Senha</th>
                                <th>IP Aparelho</th>
                                <th>Nome</th>
                                <th>Localidade</th>
                                <th>Gravação</th>
                                <th>Observações</th>
                                <th class=\"nb-col-action\"></th>
                            </tr>
                        </thead>
                        <tbody id=\"nb-cloud-ramais-body\">
                            ";
        // line 165
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
            // line 166
            yield "                            <tr class=\"nb-dyn-row\">
                                <td><input type=\"text\"   class=\"form-control form-control-sm\" name=\"ramais[";
            // line 167
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 167), "html", null, true);
            yield "][ramal]\"      value=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "ramal", [], "any", true, true, false, 167)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "ramal", [], "any", false, false, false, 167), "")) : ("")));
            yield "\"      placeholder=\"2001\"></td>
                                <td><input type=\"text\"   class=\"form-control form-control-sm nb-pwd\" name=\"ramais[";
            // line 168
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 168), "html", null, true);
            yield "][senha]\" value=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "senha", [], "any", true, true, false, 168)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "senha", [], "any", false, false, false, 168), "")) : ("")));
            yield "\"      placeholder=\"senha\" autocomplete=\"off\"></td>
                                <td><input type=\"text\"   class=\"form-control form-control-sm\" name=\"ramais[";
            // line 169
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 169), "html", null, true);
            yield "][ip]\"         value=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "ip", [], "any", true, true, false, 169)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "ip", [], "any", false, false, false, 169), "")) : ("")));
            yield "\"         placeholder=\"192.168.x.x\"></td>
                                <td><input type=\"text\"   class=\"form-control form-control-sm\" name=\"ramais[";
            // line 170
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 170), "html", null, true);
            yield "][nome]\"       value=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "nome", [], "any", true, true, false, 170)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "nome", [], "any", false, false, false, 170), "")) : ("")));
            yield "\"       placeholder=\"Ex: João\"></td>
                                <td><input type=\"text\"   class=\"form-control form-control-sm\" name=\"ramais[";
            // line 171
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 171), "html", null, true);
            yield "][localidade]\" value=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "localidade", [], "any", true, true, false, 171)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "localidade", [], "any", false, false, false, 171), "")) : ("")));
            yield "\" placeholder=\"Financeiro\"></td>
                                <td>
                                    <select class=\"form-select form-select-sm\" name=\"ramais[";
            // line 173
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 173), "html", null, true);
            yield "][gravacao]\">
                                        <option value=\"nao\" ";
            // line 174
            yield (((((CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "gravacao", [], "any", true, true, false, 174)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "gravacao", [], "any", false, false, false, 174), "nao")) : ("nao")) == "nao")) ? ("selected") : (""));
            yield ">Não</option>
                                        <option value=\"sim\" ";
            // line 175
            yield (((((CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "gravacao", [], "any", true, true, false, 175)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "gravacao", [], "any", false, false, false, 175), "")) : ("")) == "sim")) ? ("selected") : (""));
            yield ">Sim</option>
                                    </select>
                                </td>
                                <td><input type=\"text\"   class=\"form-control form-control-sm\" name=\"ramais[";
            // line 178
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 178), "html", null, true);
            yield "][obs]\"        value=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "obs", [], "any", true, true, false, 178)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["ramal"], "obs", [], "any", false, false, false, 178), "")) : ("")));
            yield "\"        placeholder=\"...\"></td>
                                <td>
                                    <button type=\"button\" class=\"btn btn-sm btn-ghost-danger nb-btn-remove-row\" title=\"Remover\">
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
            // line 186
            yield "                            <tr id=\"nb-cloud-ramais-empty\" class=\"nb-empty-row\">
                                <td colspan=\"8\" class=\"text-center text-muted py-3\">
                                    <i class=\"ti ti-inbox me-1\"></i> Nenhum ramal cadastrado. Clique em \"+ Adicionar Ramal\".
                                </td>
                            </tr>
                            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['ramal'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 192
        yield "                        </tbody>
                    </table>
                </div>
            </div>";
        // line 196
        yield "        </div>";
        // line 197
        yield "
        ";
        // line 201
        yield "        <div class=\"nb-card mb-4\">
            <div class=\"nb-card-header\">
                <i class=\"ti ti-building-broadcast-tower me-2 text-teal\"></i>
                <strong>Operadoras / Troncos</strong>
                <button type=\"button\"
                        class=\"btn btn-sm btn-outline-teal ms-auto nb-btn-add-row\"
                        data-target-body=\"nb-cloud-operadoras-body\"
                        data-template=\"nb-tpl-cloud-operadora\">
                    <i class=\"ti ti-plus me-1\"></i> Adicionar Operadora
                </button>
            </div>
            <div class=\"nb-card-body p-0\">
                <div class=\"table-responsive\">
                    <table class=\"table table-sm nb-table align-middle mb-0\">
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
                                <th class=\"nb-col-action\"></th>
                            </tr>
                        </thead>
                        <tbody id=\"nb-cloud-operadoras-body\">
                            ";
        // line 229
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(($context["operadoras"] ?? null));
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
        foreach ($context['_seq'] as $context["_key"] => $context["op"]) {
            // line 230
            yield "                            <tr class=\"nb-dyn-row\">
                                <td><input type=\"text\"   class=\"form-control form-control-sm\" name=\"operadoras[";
            // line 231
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 231), "html", null, true);
            yield "][numero_piloto]\" value=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["op"], "numero_piloto", [], "any", true, true, false, 231)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["op"], "numero_piloto", [], "any", false, false, false, 231), "")) : ("")));
            yield "\" placeholder=\"(27)3xxx-xxxx\"></td>
                                <td><input type=\"text\"   class=\"form-control form-control-sm\" name=\"operadoras[";
            // line 232
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 232), "html", null, true);
            yield "][tipo_tronco]\"   value=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["op"], "tipo_tronco", [], "any", true, true, false, 232)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["op"], "tipo_tronco", [], "any", false, false, false, 232), "")) : ("")));
            yield "\"   placeholder=\"SIP\"></td>
                                <td><input type=\"text\"   class=\"form-control form-control-sm\" name=\"operadoras[";
            // line 233
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 233), "html", null, true);
            yield "][operadora]\"     value=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["op"], "operadora", [], "any", true, true, false, 233)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["op"], "operadora", [], "any", false, false, false, 233), "")) : ("")));
            yield "\"     placeholder=\"Vivo\"></td>
                                <td><input type=\"text\"   class=\"form-control form-control-sm\" name=\"operadoras[";
            // line 234
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 234), "html", null, true);
            yield "][ddr]\"           value=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["op"], "ddr", [], "any", true, true, false, 234)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["op"], "ddr", [], "any", false, false, false, 234), "")) : ("")));
            yield "\"           placeholder=\"3300-0000\"></td>
                                <td><input type=\"number\" class=\"form-control form-control-sm\" name=\"operadoras[";
            // line 235
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 235), "html", null, true);
            yield "][canais]\"        value=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["op"], "canais", [], "any", true, true, false, 235)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["op"], "canais", [], "any", false, false, false, 235), "")) : ("")));
            yield "\"        placeholder=\"10\" min=\"1\"></td>
                                <td><input type=\"text\"   class=\"form-control form-control-sm\" name=\"operadoras[";
            // line 236
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 236), "html", null, true);
            yield "][ip_proxy]\"      value=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["op"], "ip_proxy", [], "any", true, true, false, 236)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["op"], "ip_proxy", [], "any", false, false, false, 236), "")) : ("")));
            yield "\"      placeholder=\"192.x.x.x\"></td>
                                <td><input type=\"text\"   class=\"form-control form-control-sm\" name=\"operadoras[";
            // line 237
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 237), "html", null, true);
            yield "][porta_proxy]\"   value=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["op"], "porta_proxy", [], "any", true, true, false, 237)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["op"], "porta_proxy", [], "any", false, false, false, 237), "")) : ("")));
            yield "\"   placeholder=\"5060\"></td>
                                <td><input type=\"text\"   class=\"form-control form-control-sm\" name=\"operadoras[";
            // line 238
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 238), "html", null, true);
            yield "][ip_audio]\"      value=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["op"], "ip_audio", [], "any", true, true, false, 238)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["op"], "ip_audio", [], "any", false, false, false, 238), "")) : ("")));
            yield "\"      placeholder=\"192.x.x.x\"></td>
                                <td>
                                    <button type=\"button\" class=\"btn btn-sm btn-ghost-danger nb-btn-remove-row\" title=\"Remover\">
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
            // line 246
            yield "                            <tr id=\"nb-cloud-operadoras-empty\" class=\"nb-empty-row\">
                                <td colspan=\"9\" class=\"text-center text-muted py-3\">
                                    <i class=\"ti ti-inbox me-1\"></i> Nenhuma operadora cadastrada. Clique em \"+ Adicionar Operadora\".
                                </td>
                            </tr>
                            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['op'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 252
        yield "                        </tbody>
                    </table>
                </div>
            </div>";
        // line 256
        yield "        </div>";
        // line 257
        yield "
        ";
        // line 261
        yield "        <div class=\"d-flex justify-content-end mt-2 pb-3\">
            <button type=\"submit\" class=\"btn btn-primary nb-btn-save\">
                <i class=\"ti ti-device-floppy me-2\"></i>
                Salvar IPBX Cloud
            </button>
        </div>

    </form>

    ";
        // line 274
        yield "
    ";
        // line 276
        yield "    <template id=\"nb-tpl-cloud-ramal\">
        <tr class=\"nb-dyn-row\">
            <td><input type=\"text\"   class=\"form-control form-control-sm\" name=\"ramais[__IDX__][ramal]\"      placeholder=\"2001\"></td>
            <td><input type=\"text\"   class=\"form-control form-control-sm nb-pwd\" name=\"ramais[__IDX__][senha]\" placeholder=\"senha\" autocomplete=\"off\"></td>
            <td><input type=\"text\"   class=\"form-control form-control-sm\" name=\"ramais[__IDX__][ip]\"         placeholder=\"192.168.x.x\"></td>
            <td><input type=\"text\"   class=\"form-control form-control-sm\" name=\"ramais[__IDX__][nome]\"       placeholder=\"Ex: João\"></td>
            <td><input type=\"text\"   class=\"form-control form-control-sm\" name=\"ramais[__IDX__][localidade]\" placeholder=\"Financeiro\"></td>
            <td>
                <select class=\"form-select form-select-sm\" name=\"ramais[__IDX__][gravacao]\">
                    <option value=\"nao\" selected>Não</option>
                    <option value=\"sim\">Sim</option>
                </select>
            </td>
            <td><input type=\"text\"   class=\"form-control form-control-sm\" name=\"ramais[__IDX__][obs]\"        placeholder=\"...\"></td>
            <td>
                <button type=\"button\" class=\"btn btn-sm btn-ghost-danger nb-btn-remove-row\" title=\"Remover\">
                    <i class=\"ti ti-trash\"></i>
                </button>
            </td>
        </tr>
    </template>

    ";
        // line 299
        yield "    <template id=\"nb-tpl-cloud-operadora\">
        <tr class=\"nb-dyn-row\">
            <td><input type=\"text\"   class=\"form-control form-control-sm\" name=\"operadoras[__IDX__][numero_piloto]\" placeholder=\"(27)3xxx-xxxx\"></td>
            <td><input type=\"text\"   class=\"form-control form-control-sm\" name=\"operadoras[__IDX__][tipo_tronco]\"   placeholder=\"SIP\"></td>
            <td><input type=\"text\"   class=\"form-control form-control-sm\" name=\"operadoras[__IDX__][operadora]\"     placeholder=\"Vivo\"></td>
            <td><input type=\"text\"   class=\"form-control form-control-sm\" name=\"operadoras[__IDX__][ddr]\"           placeholder=\"3300-0000\"></td>
            <td><input type=\"number\" class=\"form-control form-control-sm\" name=\"operadoras[__IDX__][canais]\"        placeholder=\"10\" min=\"1\"></td>
            <td><input type=\"text\"   class=\"form-control form-control-sm\" name=\"operadoras[__IDX__][ip_proxy]\"      placeholder=\"192.x.x.x\"></td>
            <td><input type=\"text\"   class=\"form-control form-control-sm\" name=\"operadoras[__IDX__][porta_proxy]\"   placeholder=\"5060\"></td>
            <td><input type=\"text\"   class=\"form-control form-control-sm\" name=\"operadoras[__IDX__][ip_audio]\"      placeholder=\"192.x.x.x\"></td>
            <td>
                <button type=\"button\" class=\"btn btn-sm btn-ghost-danger nb-btn-remove-row\" title=\"Remover\">
                    <i class=\"ti ti-trash\"></i>
                </button>
            </td>
        </tr>
    </template>

</div>";
        // line 318
        yield "
";
        // line 319
        yield from         $this->loadTemplate("@newbase/companydata/sections/_shared_js.html.twig", "@newbase/companydata/sections/ipbx_cloud.html.twig", 319)->unwrap()->yield($context);
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
        return array (  529 => 319,  526 => 318,  506 => 299,  482 => 276,  479 => 274,  468 => 261,  465 => 257,  463 => 256,  458 => 252,  447 => 246,  424 => 238,  418 => 237,  412 => 236,  406 => 235,  400 => 234,  394 => 233,  388 => 232,  382 => 231,  379 => 230,  361 => 229,  331 => 201,  328 => 197,  326 => 196,  321 => 192,  310 => 186,  287 => 178,  281 => 175,  277 => 174,  273 => 173,  266 => 171,  260 => 170,  254 => 169,  248 => 168,  242 => 167,  239 => 166,  221 => 165,  192 => 138,  189 => 134,  187 => 133,  185 => 132,  179 => 128,  165 => 117,  150 => 105,  137 => 95,  122 => 83,  110 => 74,  98 => 65,  86 => 56,  74 => 47,  60 => 35,  55 => 30,  51 => 29,  47 => 28,  38 => 21,);
    }

    public function getSourceContext()
    {
        return new Source("", "@newbase/companydata/sections/ipbx_cloud.html.twig", "D:\\laragon\\www\\glpi\\plugins\\newbase\\templates\\companydata\\sections\\ipbx_cloud.html.twig");
    }
}
