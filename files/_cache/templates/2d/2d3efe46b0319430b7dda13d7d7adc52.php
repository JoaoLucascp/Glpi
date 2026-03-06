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

/* @newbase/companydata/sections/empresa.html.twig */
class __TwigTemplate_80eaef8929a0611eacbcd6d6f343f67e extends Template
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
        // line 2
        yield "<form method=\"post\" action=\"";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["form_url"] ?? null), "html", null, true);
        yield "\">
\t<input type=\"hidden\" name=\"id\"               value=\"";
        // line 3
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["item_id"] ?? null), "html", null, true);
        yield "\">
\t<input type=\"hidden\" name=\"_glpi_csrf_token\" value=\"";
        // line 4
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["csrf_token"] ?? null), "html", null, true);
        yield "\">
\t";
        // line 6
        yield "\t<input type=\"hidden\" name=\"entities_id\"      value=\"";
        (((CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "entities_id", [], "any", true, true, false, 6) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "entities_id", [], "any", false, false, false, 6)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "entities_id", [], "any", false, false, false, 6), "html", null, true)) : (yield 0));
        yield "\">

\t";
        // line 9
        yield "\t";
        if ((($context["item_id"] ?? null) > 0)) {
            // line 10
            yield "\t\t<input type=\"hidden\" name=\"update\" value=\"1\">
\t";
        } else {
            // line 12
            yield "\t\t<input type=\"hidden\" name=\"add\" value=\"1\">
\t";
        }
        // line 14
        yield "
\t";
        // line 16
        yield "\t<div class=\"card mb-3\">
\t\t<div class=\"card-header\">
\t\t\t<i class=\"ti ti-building me-2\"></i>
\t\t\t<strong>Informações Gerais</strong>
\t\t</div>
\t\t<div class=\"card-body\">
\t\t\t<div class=\"row g-3\">
\t\t\t\t<div class=\"col-12 col-lg-1\">
\t\t\t\t\t<label class=\"form-label\">ID</label>
\t\t\t\t\t<input type=\"text\" class=\"form-control\" value=\"";
        // line 25
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["item_id"] ?? null), "html", null, true);
        yield "\" readonly disabled>
\t\t\t\t</div>
\t\t\t\t<div class=\"col-12 col-lg-5\">
\t\t\t\t\t<label class=\"form-label\">Nome Fantasia</label>
\t\t\t\t\t<input type=\"text\" name=\"fantasy_name\" value=\"";
        // line 29
        (((CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "fantasy_name", [], "any", true, true, false, 29) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "fantasy_name", [], "any", false, false, false, 29)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "fantasy_name", [], "any", false, false, false, 29), "html", null, true)) : (yield ""));
        yield "\" class=\"form-control\" placeholder=\"Ex: NEWTEL SOLUCOES\">
\t\t\t\t</div>
\t\t\t\t<div class=\"col-12 col-lg-6\">
\t\t\t\t\t<label class=\"form-label\">CNPJ</label>
\t\t\t\t\t<div class=\"input-group\">
\t\t\t\t\t\t<input type=\"text\" name=\"cnpj\" value=\"";
        // line 34
        (((CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "cnpj", [], "any", true, true, false, 34) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "cnpj", [], "any", false, false, false, 34)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "cnpj", [], "any", false, false, false, 34), "html", null, true)) : (yield ""));
        yield "\" class=\"form-control\" placeholder=\"00.000.000/0000-00\" maxlength=\"18\">
\t\t\t\t\t\t<button type=\"button\" class=\"btn btn-secondary\" data-action=\"search-cnpj\">
\t\t\t\t\t\t\t<i class=\"ti ti-search\"></i>
\t\t\t\t\t\t</button>
\t\t\t\t\t</div>
\t\t\t\t</div>
\t\t\t\t<div class=\"col-12 col-lg-6\">
\t\t\t\t\t<label class=\"form-label\">Razão Social
\t\t\t\t\t\t<span class=\"text-red\">*</span>
\t\t\t\t\t</label>
\t\t\t\t\t<input type=\"text\" name=\"corporate_name\" value=\"";
        // line 44
        (((CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "corporate_name", [], "any", true, true, false, 44) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "corporate_name", [], "any", false, false, false, 44)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "corporate_name", [], "any", false, false, false, 44), "html", null, true)) : (yield ""));
        yield "\" class=\"form-control\" placeholder=\"Razão Social completa\">
\t\t\t\t</div>
\t\t\t\t<div class=\"col-12 col-lg-6\">
\t\t\t\t\t<label class=\"form-label\">E-mail</label>
\t\t\t\t\t<input type=\"email\" name=\"email\" value=\"";
        // line 48
        (((CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "email", [], "any", true, true, false, 48) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "email", [], "any", false, false, false, 48)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "email", [], "any", false, false, false, 48), "html", null, true)) : (yield ""));
        yield "\" class=\"form-control\" placeholder=\"contato@empresa.com.br\">
\t\t\t\t</div>
\t\t\t\t<div class=\"col-12 col-lg-6\">
\t\t\t\t\t<label class=\"form-label\">Telefone</label>
\t\t\t\t\t<input type=\"text\" name=\"phone\" value=\"";
        // line 52
        (((CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "phone", [], "any", true, true, false, 52) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "phone", [], "any", false, false, false, 52)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "phone", [], "any", false, false, false, 52), "html", null, true)) : (yield ""));
        yield "\" class=\"form-control\" placeholder=\"(27) 3372-7000\">
\t\t\t\t</div>
\t\t\t</div>
\t\t</div>
\t</div>

\t";
        // line 59
        yield "\t<div class=\"card mb-3\">
\t\t<div class=\"card-header\">
\t\t\t<i class=\"ti ti-map-pin me-2\"></i>
\t\t\t<strong>Endereço</strong>
\t\t</div>
\t\t<div class=\"card-body\">
\t\t\t<div class=\"row g-3\">
\t\t\t\t<div class=\"col-12 col-lg-4\">
\t\t\t\t\t<label class=\"form-label\">CEP</label>
\t\t\t\t\t<div class=\"input-group\">
\t\t\t\t\t\t<input type=\"text\" name=\"cep\" value=\"";
        // line 69
        (((CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "cep", [], "any", true, true, false, 69) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "cep", [], "any", false, false, false, 69)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "cep", [], "any", false, false, false, 69), "html", null, true)) : (yield ""));
        yield "\" class=\"form-control\" placeholder=\"00000-000\">
\t\t\t\t\t\t<button type=\"button\" class=\"btn btn-secondary\" data-action=\"search-cep\">
\t\t\t\t\t\t\t<i class=\"ti ti-search\"></i>
\t\t\t\t\t\t</button>
\t\t\t\t\t</div>
\t\t\t\t</div>
\t\t\t\t<div class=\"col-12 col-lg-6\">
\t\t\t\t\t<label class=\"form-label\">Rua</label>
\t\t\t\t\t<input type=\"text\" name=\"street\" value=\"";
        // line 77
        (((CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "street", [], "any", true, true, false, 77) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "street", [], "any", false, false, false, 77)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "street", [], "any", false, false, false, 77), "html", null, true)) : (yield ""));
        yield "\" class=\"form-control\" placeholder=\"Nome da rua\">
\t\t\t\t</div>
\t\t\t\t<div class=\"col-12 col-lg-2\">
\t\t\t\t\t<label class=\"form-label\">Número</label>
\t\t\t\t\t<input type=\"text\" name=\"number\" value=\"";
        // line 81
        (((CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "number", [], "any", true, true, false, 81) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "number", [], "any", false, false, false, 81)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "number", [], "any", false, false, false, 81), "html", null, true)) : (yield ""));
        yield "\" class=\"form-control\" placeholder=\"Ex: 224\">
\t\t\t\t</div>
\t\t\t\t<div class=\"col-12 col-lg-4\">
\t\t\t\t\t<label class=\"form-label\">Complemento</label>
\t\t\t\t\t<input type=\"text\" name=\"complement\" value=\"";
        // line 85
        (((CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "complement", [], "any", true, true, false, 85) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "complement", [], "any", false, false, false, 85)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "complement", [], "any", false, false, false, 85), "html", null, true)) : (yield ""));
        yield "\" class=\"form-control\" placeholder=\"Loja, Sala, Apto...\">
\t\t\t\t</div>
\t\t\t\t<div class=\"col-12 col-lg-4\">
\t\t\t\t\t<label class=\"form-label\">Bairro</label>
\t\t\t\t\t<input type=\"text\" name=\"neighborhood\" value=\"";
        // line 89
        (((CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "neighborhood", [], "any", true, true, false, 89) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "neighborhood", [], "any", false, false, false, 89)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "neighborhood", [], "any", false, false, false, 89), "html", null, true)) : (yield ""));
        yield "\" class=\"form-control\" placeholder=\"Nome do bairro\">
\t\t\t\t</div>
\t\t\t\t<div class=\"col-12 col-lg-4\">
\t\t\t\t\t<label class=\"form-label\">Cidade</label>
\t\t\t\t\t<input type=\"text\" name=\"city\" value=\"";
        // line 93
        (((CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "city", [], "any", true, true, false, 93) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "city", [], "any", false, false, false, 93)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "city", [], "any", false, false, false, 93), "html", null, true)) : (yield ""));
        yield "\" class=\"form-control\" placeholder=\"Cidade\">
\t\t\t\t</div>
\t\t\t\t<div class=\"col-12 col-lg-2\">
\t\t\t\t\t<label class=\"form-label\">Estado</label>
\t\t\t\t\t<input type=\"text\" name=\"state\" value=\"";
        // line 97
        (((CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "state", [], "any", true, true, false, 97) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "state", [], "any", false, false, false, 97)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "state", [], "any", false, false, false, 97), "html", null, true)) : (yield ""));
        yield "\" class=\"form-control\" placeholder=\"ES\" maxlength=\"2\">
\t\t\t\t</div>
\t\t\t\t<div class=\"col-12 col-lg-4\">
\t\t\t\t\t<label class=\"form-label\">País</label>
\t\t\t\t\t<input type=\"text\" name=\"country\" value=\"";
        // line 101
        (((CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "country", [], "any", true, true, false, 101) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "country", [], "any", false, false, false, 101)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "country", [], "any", false, false, false, 101), "html", null, true)) : (yield "Brasil"));
        yield "\" class=\"form-control\">
\t\t\t\t</div>
\t\t\t\t<div class=\"col-12 col-lg-3\">
\t\t\t\t\t<label class=\"form-label\">Latitude</label>
\t\t\t\t\t<input type=\"text\" name=\"latitude\" value=\"";
        // line 105
        (((CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "latitude", [], "any", true, true, false, 105) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "latitude", [], "any", false, false, false, 105)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "latitude", [], "any", false, false, false, 105), "html", null, true)) : (yield ""));
        yield "\" class=\"form-control\" placeholder=\"-20.12345678\">
\t\t\t\t</div>
\t\t\t\t<div class=\"col-12 col-lg-3\">
\t\t\t\t\t<label class=\"form-label\">Longitude</label>
\t\t\t\t\t<input type=\"text\" name=\"longitude\" value=\"";
        // line 109
        (((CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "longitude", [], "any", true, true, false, 109) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "longitude", [], "any", false, false, false, 109)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "longitude", [], "any", false, false, false, 109), "html", null, true)) : (yield ""));
        yield "\" class=\"form-control\" placeholder=\"-40.12345678\">
\t\t\t\t</div>
\t\t\t</div>
\t\t</div>
\t</div>

\t";
        // line 116
        yield "\t<div class=\"card mb-3\">
\t\t<div class=\"card-header\">
\t\t\t<i class=\"ti ti-file-check me-2\"></i>
\t\t\t<strong>Status da Empresa</strong>
\t\t</div>
\t\t<div class=\"card-body\">
\t\t\t<div class=\"row g-3\">
\t\t\t\t<div class=\"col-12 col-lg-4\">
\t\t\t\t\t<label class=\"form-label\">Status do Contrato</label>
\t\t\t\t\t<select name=\"contract_status\" class=\"form-select\">
\t\t\t\t\t\t<option value=\"active\"    ";
        // line 126
        yield ((((((CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "contract_status", [], "any", true, true, false, 126) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "contract_status", [], "any", false, false, false, 126)))) ? (CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "contract_status", [], "any", false, false, false, 126)) : ("active")) == "active")) ? ("selected") : (""));
        yield ">Com contrato ativo</option>
\t\t\t\t\t\t<option value=\"inactive\"  ";
        // line 127
        yield ((((((CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "contract_status", [], "any", true, true, false, 127) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "contract_status", [], "any", false, false, false, 127)))) ? (CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "contract_status", [], "any", false, false, false, 127)) : ("")) == "inactive")) ? ("selected") : (""));
        yield ">Sem contrato</option>
\t\t\t\t\t\t<option value=\"cancelled\" ";
        // line 128
        yield ((((((CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "contract_status", [], "any", true, true, false, 128) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "contract_status", [], "any", false, false, false, 128)))) ? (CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "contract_status", [], "any", false, false, false, 128)) : ("")) == "cancelled")) ? ("selected") : (""));
        yield ">Contrato cancelado</option>
\t\t\t\t\t</select>
\t\t\t\t</div>
\t\t\t\t<div class=\"col-12 col-lg-4\">
\t\t\t\t\t<label class=\"form-label\">Data de Cadastro</label>
\t\t\t\t\t";
        // line 138
        yield "\t\t\t\t\t<input type=\"date\"
\t\t\t\t\t       value=\"";
        // line 139
        (( !Twig\Extension\CoreExtension::testEmpty(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "date_creation", [], "any", false, false, false, 139))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::slice($this->env->getCharset(), CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "date_creation", [], "any", false, false, false, 139), 0, 10), "html", null, true)) : (yield ""));
        yield "\"
\t\t\t\t\t       class=\"form-control\"
\t\t\t\t\t       readonly>
\t\t\t\t</div>
\t\t\t\t<div class=\"col-12 col-lg-4\">
\t\t\t\t\t<label class=\"form-label\">Data de Encerramento</label>
\t\t\t\t\t<input type=\"date\" name=\"date_end\"
\t\t\t\t\t       value=\"";
        // line 146
        (( !Twig\Extension\CoreExtension::testEmpty(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "date_end", [], "any", false, false, false, 146))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::slice($this->env->getCharset(), CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "date_end", [], "any", false, false, false, 146), 0, 10), "html", null, true)) : (yield ""));
        yield "\"
\t\t\t\t\t       class=\"form-control\">
\t\t\t\t</div>
\t\t\t\t<div class=\"col-12\">
\t\t\t\t\t<label class=\"form-label\">Observações</label>
\t\t\t\t\t<textarea name=\"notes\" class=\"form-control\" rows=\"4\" placeholder=\"Observações adicionais...\">";
        // line 151
        (((CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "notes", [], "any", true, true, false, 151) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "notes", [], "any", false, false, false, 151)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "notes", [], "any", false, false, false, 151), "html", null, true)) : (yield ""));
        yield "</textarea>
\t\t\t\t</div>
\t\t\t</div>
\t\t</div>
\t</div>

\t<div class=\"text-end mt-3 pb-2\">
\t\t<button type=\"submit\" class=\"btn btn-primary\">
\t\t\t<i class=\"ti ti-device-floppy me-2\"></i>
\t\t\tSalvar Empresa
\t\t</button>
\t</div>
</form>
";
        return; yield '';
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "@newbase/companydata/sections/empresa.html.twig";
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
        return array (  270 => 151,  262 => 146,  252 => 139,  249 => 138,  241 => 128,  237 => 127,  233 => 126,  221 => 116,  212 => 109,  205 => 105,  198 => 101,  191 => 97,  184 => 93,  177 => 89,  170 => 85,  163 => 81,  156 => 77,  145 => 69,  133 => 59,  124 => 52,  117 => 48,  110 => 44,  97 => 34,  89 => 29,  82 => 25,  71 => 16,  68 => 14,  64 => 12,  60 => 10,  57 => 9,  51 => 6,  47 => 4,  43 => 3,  38 => 2,);
    }

    public function getSourceContext()
    {
        return new Source("", "@newbase/companydata/sections/empresa.html.twig", "D:\\laragon\\www\\glpi\\plugins\\newbase\\templates\\companydata\\sections\\empresa.html.twig");
    }
}
