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
        // line 23
        yield "
<div class=\"newbase-section-wrapper\">

    <form id=\"nb-form-chatbot\" class=\"nb-section-form\" method=\"post\" novalidate
          data-section-key=\"chatbot\"
          data-save-label=\"Salvar Chatbot\">

        <input type=\"hidden\" name=\"id\"               value=\"";
        // line 30
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["item_id"] ?? null), "html", null, true);
        yield "\">
        <input type=\"hidden\" name=\"section_key\"      value=\"";
        // line 31
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["section_key"] ?? null), "html", null, true);
        yield "\">
        <input type=\"hidden\" name=\"_glpi_csrf_token\" value=\"";
        // line 32
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["csrf_token"] ?? null), "html", null, true);
        yield "\">

        ";
        // line 37
        yield "        <div class=\"nb-card mb-4\">
            <div class=\"nb-card-header\">
                <i class=\"ti ti-robot me-2 text-green\"></i>
                <strong>Configurações do Chatbot</strong>
            </div>
            <div class=\"nb-card-body\">
                <div class=\"row g-3\">

                    <div class=\"col-12 col-md-6\">
                        <label class=\"form-label\">Modelo / Plataforma</label>
                        <input type=\"text\"
                               name=\"modelo\"
                               value=\"";
        // line 49
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "modelo", [], "any", false, false, false, 49));
        yield "\"
                               class=\"form-control\"
                               placeholder=\"Ex: Chatwoot, Zenvia, Take Blip\">
                    </div>

                    <div class=\"col-12 col-md-6\">
                        <label class=\"form-label\">ID do Chatbot</label>
                        <input type=\"text\"
                               name=\"chatbot_id\"
                               value=\"";
        // line 58
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "chatbot_id", [], "any", false, false, false, 58));
        yield "\"
                               class=\"form-control\"
                               placeholder=\"Identificador único na plataforma\">
                    </div>

                    <div class=\"col-12 col-md-4\">
                        <label class=\"form-label\">Data de Ativação</label>
                        <input type=\"date\"
                               name=\"data_ativacao\"
                               value=\"";
        // line 67
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "data_ativacao", [], "any", false, false, false, 67));
        yield "\"
                               class=\"form-control\">
                    </div>

                    <div class=\"col-12 col-md-4\">
                        <label class=\"form-label\">Número de Telefone</label>
                        <input type=\"text\"
                               name=\"numero_telefone\"
                               value=\"";
        // line 75
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "numero_telefone", [], "any", false, false, false, 75));
        yield "\"
                               class=\"form-control\"
                               placeholder=\"Ex: (27) 99999-0000\">
                    </div>

                    <div class=\"col-12 col-md-4\">
                        <label class=\"form-label\">Plano</label>
                        <input type=\"text\"
                               name=\"plano\"
                               value=\"";
        // line 84
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "plano", [], "any", false, false, false, 84));
        yield "\"
                               class=\"form-control\"
                               placeholder=\"Ex: Básico, Pro, Enterprise\">
                    </div>

                    <div class=\"col-12 col-md-12\">
                        <label class=\"form-label\">Link de Acesso</label>
                        <div class=\"input-group\">
                            <input type=\"text\"
                                   name=\"link_acesso\"
                                   value=\"";
        // line 94
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "link_acesso", [], "any", false, false, false, 94));
        yield "\"
                                   class=\"form-control\"
                                   placeholder=\"https://app.plataforma.com.br/empresa\"
                                   id=\"nb-chatbot-link\">
                            ";
        // line 98
        if ( !Twig\Extension\CoreExtension::testEmpty(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "link_acesso", [], "any", false, false, false, 98))) {
            // line 99
            yield "                            <a href=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "link_acesso", [], "any", false, false, false, 99));
            yield "\"
                               target=\"_blank\"
                               rel=\"noopener noreferrer\"
                               class=\"btn btn-outline-secondary\"
                               title=\"Abrir link\">
                                <i class=\"ti ti-external-link\"></i>
                            </a>
                            ";
        } else {
            // line 107
            yield "                            <button type=\"button\"
                                    class=\"btn btn-outline-secondary\"
                                    id=\"nb-btn-open-link\"
                                    title=\"Abrir link\">
                                <i class=\"ti ti-external-link\"></i>
                            </button>
                            ";
        }
        // line 114
        yield "                        </div>
                    </div>

                </div>";
        // line 118
        yield "            </div>";
        // line 119
        yield "        </div>";
        // line 120
        yield "
        ";
        // line 124
        yield "        <div class=\"nb-card mb-4\">
            <div class=\"nb-card-header\">
                <i class=\"ti ti-users me-2 text-blue\"></i>
                <strong>Quantidades e Acessos</strong>
            </div>
            <div class=\"nb-card-body\">
                <div class=\"row g-3\">

                    <div class=\"col-6 col-md-4\">
                        <label class=\"form-label\">Qtd. Usuários</label>
                        <input type=\"number\"
                               name=\"qtd_usuarios\"
                               value=\"";
        // line 136
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "qtd_usuarios", [], "any", true, true, false, 136)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "qtd_usuarios", [], "any", false, false, false, 136), 0)) : (0)), "html", null, true);
        yield "\"
                               class=\"form-control\"
                               min=\"0\"
                               placeholder=\"0\">
                    </div>

                    <div class=\"col-6 col-md-4\">
                        <label class=\"form-label\">Qtd. Supervisores</label>
                        <input type=\"number\"
                               name=\"qtd_supervisores\"
                               value=\"";
        // line 146
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "qtd_supervisores", [], "any", true, true, false, 146)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "qtd_supervisores", [], "any", false, false, false, 146), 0)) : (0)), "html", null, true);
        yield "\"
                               class=\"form-control\"
                               min=\"0\"
                               placeholder=\"0\">
                    </div>

                    <div class=\"col-6 col-md-4\">
                        <label class=\"form-label\">Qtd. Administradores</label>
                        <input type=\"number\"
                               name=\"qtd_administradores\"
                               value=\"";
        // line 156
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "qtd_administradores", [], "any", true, true, false, 156)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "qtd_administradores", [], "any", false, false, false, 156), 0)) : (0)), "html", null, true);
        yield "\"
                               class=\"form-control\"
                               min=\"0\"
                               placeholder=\"0\">
                    </div>

                    <div class=\"col-12 col-md-6\">
                        <label class=\"form-label\">Login Admin</label>
                        <input type=\"text\"
                               name=\"login_admin\"
                               value=\"";
        // line 166
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "login_admin", [], "any", false, false, false, 166));
        yield "\"
                               class=\"form-control\"
                               placeholder=\"Usuário administrador\">
                    </div>

                    <div class=\"col-12 col-md-6\">
                        <label class=\"form-label\">
                            Senha Admin
                            <span class=\"badge bg-warning-lt text-warning ms-1 fw-normal fs-6\">visível</span>
                        </label>
                        <input type=\"text\"
                               name=\"senha_admin\"
                               value=\"";
        // line 178
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "senha_admin", [], "any", false, false, false, 178));
        yield "\"
                               class=\"form-control nb-pwd\"
                               placeholder=\"Senha do administrador\"
                               autocomplete=\"off\">
                    </div>

                    <div class=\"col-12 col-md-6\">
                        <label class=\"form-label\">Login Super-Admin</label>
                        <input type=\"text\"
                               name=\"login_superadmin\"
                               value=\"";
        // line 188
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "login_superadmin", [], "any", false, false, false, 188));
        yield "\"
                               class=\"form-control\"
                               placeholder=\"Usuário super-administrador\">
                    </div>

                    <div class=\"col-12 col-md-6\">
                        <label class=\"form-label\">
                            Senha Super-Admin
                            <span class=\"badge bg-warning-lt text-warning ms-1 fw-normal fs-6\">visível</span>
                        </label>
                        <input type=\"text\"
                               name=\"senha_superadmin\"
                               value=\"";
        // line 200
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "senha_superadmin", [], "any", false, false, false, 200));
        yield "\"
                               class=\"form-control nb-pwd\"
                               placeholder=\"Senha do super-administrador\"
                               autocomplete=\"off\">
                    </div>

                </div>";
        // line 207
        yield "            </div>";
        // line 208
        yield "        </div>";
        // line 209
        yield "
        ";
        // line 213
        yield "        <div class=\"nb-card mb-4\">
            <div class=\"nb-card-header\">
                <i class=\"ti ti-user-check me-2 text-orange\"></i>
                <strong>Responsável</strong>
            </div>
            <div class=\"nb-card-body\">
                <div class=\"row g-3\">

                    <div class=\"col-12 col-md-4\">
                        <label class=\"form-label\">Nome do Responsável</label>
                        <input type=\"text\"
                               name=\"nome_responsavel\"
                               value=\"";
        // line 225
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "nome_responsavel", [], "any", false, false, false, 225));
        yield "\"
                               class=\"form-control\"
                               placeholder=\"Nome completo\">
                    </div>

                    <div class=\"col-12 col-md-4\">
                        <label class=\"form-label\">Número do Responsável</label>
                        <input type=\"text\"
                               name=\"numero_responsavel\"
                               value=\"";
        // line 234
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "numero_responsavel", [], "any", false, false, false, 234));
        yield "\"
                               class=\"form-control\"
                               placeholder=\"Ex: (27) 99999-0000\">
                    </div>

                    <div class=\"col-12 col-md-4\">
                        <label class=\"form-label\">E-mail do Responsável</label>
                        <input type=\"text\"
                               name=\"email_responsavel\"
                               value=\"";
        // line 243
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "email_responsavel", [], "any", false, false, false, 243));
        yield "\"
                               class=\"form-control\"
                               placeholder=\"email@empresa.com.br\">
                    </div>

                    <div class=\"col-12\">
                        <label class=\"form-label\">Redes Sociais</label>
                        <input type=\"text\"
                               name=\"redes_sociais\"
                               value=\"";
        // line 252
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "redes_sociais", [], "any", false, false, false, 252));
        yield "\"
                               class=\"form-control\"
                               placeholder=\"Ex: @empresa_chatbot, facebook.com/empresa\">
                    </div>

                </div>";
        // line 258
        yield "            </div>";
        // line 259
        yield "        </div>";
        // line 260
        yield "
        ";
        // line 264
        yield "        <div class=\"nb-card mb-4\">
            <div class=\"nb-card-header\">
                <i class=\"ti ti-messages me-2 text-cyan\"></i>
                <strong>Comunicação em Massa</strong>
                <button type=\"button\"
                        class=\"btn btn-sm btn-outline-cyan ms-auto nb-btn-add-row\"
                        data-target-body=\"nb-comun-body\"
                        data-template=\"nb-tpl-comun\">
                    <i class=\"ti ti-plus me-1\"></i> Adicionar
                </button>
            </div>
            <div class=\"nb-card-body p-0\">
                <div class=\"table-responsive\">
                    <table class=\"table table-sm nb-table align-middle mb-0\">
                        <thead class=\"table-light\">
                            <tr>
                                <th>Nome Sistema</th>
                                <th>Data Ativação</th>
                                <th>Nº Autenticado</th>
                                <th>Tipo Homologação</th>
                                <th>Link Acesso</th>
                                <th>Login</th>
                                <th>Senha</th>
                                <th>Responsável</th>
                                <th class=\"nb-col-action\"></th>
                            </tr>
                        </thead>
                        <tbody id=\"nb-comun-body\">
                            ";
        // line 292
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(($context["comunicacao_massa"] ?? null));
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
            // line 293
            yield "                            <tr class=\"nb-dyn-row\">
                                <td><input type=\"text\"   class=\"form-control form-control-sm\" name=\"comunicacao_massa[";
            // line 294
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 294), "html", null, true);
            yield "][nome_sistema]\"       value=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["row"], "nome_sistema", [], "any", true, true, false, 294)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["row"], "nome_sistema", [], "any", false, false, false, 294), "")) : ("")));
            yield "\"       placeholder=\"Ex: Zenvia\"></td>
                                <td><input type=\"date\"   class=\"form-control form-control-sm\" name=\"comunicacao_massa[";
            // line 295
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 295), "html", null, true);
            yield "][data_ativacao]\"      value=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["row"], "data_ativacao", [], "any", true, true, false, 295)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["row"], "data_ativacao", [], "any", false, false, false, 295), "")) : ("")));
            yield "\"></td>
                                <td><input type=\"text\"   class=\"form-control form-control-sm\" name=\"comunicacao_massa[";
            // line 296
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 296), "html", null, true);
            yield "][numero_autenticado]\" value=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["row"], "numero_autenticado", [], "any", true, true, false, 296)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["row"], "numero_autenticado", [], "any", false, false, false, 296), "")) : ("")));
            yield "\" placeholder=\"(27) 99999-0000\"></td>
                                <td><input type=\"text\"   class=\"form-control form-control-sm\" name=\"comunicacao_massa[";
            // line 297
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 297), "html", null, true);
            yield "][tipo_homologacao]\"   value=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["row"], "tipo_homologacao", [], "any", true, true, false, 297)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["row"], "tipo_homologacao", [], "any", false, false, false, 297), "")) : ("")));
            yield "\"   placeholder=\"Ex: WhatsApp Business\"></td>
                                <td><input type=\"text\"   class=\"form-control form-control-sm\" name=\"comunicacao_massa[";
            // line 298
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 298), "html", null, true);
            yield "][link_acesso]\"        value=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["row"], "link_acesso", [], "any", true, true, false, 298)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["row"], "link_acesso", [], "any", false, false, false, 298), "")) : ("")));
            yield "\"        placeholder=\"https://...\"></td>
                                <td><input type=\"text\"   class=\"form-control form-control-sm\" name=\"comunicacao_massa[";
            // line 299
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 299), "html", null, true);
            yield "][login]\"              value=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["row"], "login", [], "any", true, true, false, 299)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["row"], "login", [], "any", false, false, false, 299), "")) : ("")));
            yield "\"              placeholder=\"login\"></td>
                                <td><input type=\"text\"   class=\"form-control form-control-sm nb-pwd\" name=\"comunicacao_massa[";
            // line 300
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 300), "html", null, true);
            yield "][senha]\"       value=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["row"], "senha", [], "any", true, true, false, 300)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["row"], "senha", [], "any", false, false, false, 300), "")) : ("")));
            yield "\"              placeholder=\"senha\" autocomplete=\"off\"></td>
                                <td><input type=\"text\"   class=\"form-control form-control-sm\" name=\"comunicacao_massa[";
            // line 301
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 301), "html", null, true);
            yield "][responsavel]\"        value=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["row"], "responsavel", [], "any", true, true, false, 301)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["row"], "responsavel", [], "any", false, false, false, 301), "")) : ("")));
            yield "\"        placeholder=\"Nome\"></td>
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
            // line 309
            yield "                            <tr id=\"nb-comun-empty\" class=\"nb-empty-row\">
                                <td colspan=\"9\" class=\"text-center text-muted py-3\">
                                    <i class=\"ti ti-inbox me-1\"></i> Nenhum registro. Clique em \"+ Adicionar\".
                                </td>
                            </tr>
                            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['row'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 315
        yield "                        </tbody>
                    </table>
                </div>
            </div>
        </div>";
        // line 320
        yield "
        ";
        // line 324
        yield "        <div class=\"nb-card mb-4\">
            <div class=\"nb-card-header\">
                <i class=\"ti ti-ban me-2 text-red\"></i>
                <strong>Restrições</strong>
                <button type=\"button\"
                        class=\"btn btn-sm btn-outline-danger ms-auto nb-btn-add-row\"
                        data-target-body=\"nb-restricoes-body\"
                        data-template=\"nb-tpl-restricao\">
                    <i class=\"ti ti-plus me-1\"></i> Adicionar
                </button>
            </div>
            <div class=\"nb-card-body p-0\">
                <div class=\"table-responsive\">
                    <table class=\"table table-sm nb-table align-middle mb-0\">
                        <thead class=\"table-light\">
                            <tr>
                                <th style=\"min-width:140px\">Data</th>
                                <th style=\"min-width:140px\">Duração</th>
                                <th>Número Restrito</th>
                                <th class=\"nb-col-action\"></th>
                            </tr>
                        </thead>
                        <tbody id=\"nb-restricoes-body\">
                            ";
        // line 347
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(($context["restricoes"] ?? null));
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
            // line 348
            yield "                            <tr class=\"nb-dyn-row\">
                                <td><input type=\"date\" class=\"form-control form-control-sm\" name=\"restricoes[";
            // line 349
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 349), "html", null, true);
            yield "][data]\"            value=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["row"], "data", [], "any", true, true, false, 349)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["row"], "data", [], "any", false, false, false, 349), "")) : ("")));
            yield "\"></td>
                                <td><input type=\"text\" class=\"form-control form-control-sm\" name=\"restricoes[";
            // line 350
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 350), "html", null, true);
            yield "][duracao]\"         value=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["row"], "duracao", [], "any", true, true, false, 350)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["row"], "duracao", [], "any", false, false, false, 350), "")) : ("")));
            yield "\"         placeholder=\"Ex: 24h, 7 dias\"></td>
                                <td><input type=\"text\" class=\"form-control form-control-sm\" name=\"restricoes[";
            // line 351
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 351), "html", null, true);
            yield "][numero_restrito]\" value=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["row"], "numero_restrito", [], "any", true, true, false, 351)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["row"], "numero_restrito", [], "any", false, false, false, 351), "")) : ("")));
            yield "\" placeholder=\"(27) 99999-0000\"></td>
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
            // line 359
            yield "                            <tr id=\"nb-restricoes-empty\" class=\"nb-empty-row\">
                                <td colspan=\"4\" class=\"text-center text-muted py-3\">
                                    <i class=\"ti ti-inbox me-1\"></i> Nenhuma restrição cadastrada. Clique em \"+ Adicionar\".
                                </td>
                            </tr>
                            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['row'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 365
        yield "                        </tbody>
                    </table>
                </div>
            </div>
        </div>";
        // line 370
        yield "
        ";
        // line 374
        yield "        <div class=\"nb-card mb-4\">
            <div class=\"nb-card-header\">
                <i class=\"ti ti-users me-2 text-purple\"></i>
                <strong>Usuários</strong>
                <button type=\"button\"
                        class=\"btn btn-sm btn-outline-purple ms-auto nb-btn-add-row\"
                        data-target-body=\"nb-usuarios-body\"
                        data-template=\"nb-tpl-usuario\">
                    <i class=\"ti ti-plus me-1\"></i> Adicionar Usuário
                </button>
            </div>
            <div class=\"nb-card-body p-0\">
                <div class=\"table-responsive\">
                    <table class=\"table table-sm nb-table align-middle mb-0\">
                        <thead class=\"table-light\">
                            <tr>
                                <th>Nome</th>
                                <th>Login</th>
                                <th>Senha</th>
                                <th>E-mail</th>
                                <th>Tipo</th>
                                <th>Observações</th>
                                <th class=\"nb-col-action\"></th>
                            </tr>
                        </thead>
                        <tbody id=\"nb-usuarios-body\">
                            ";
        // line 400
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(($context["usuarios"] ?? null));
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
            // line 401
            yield "                            <tr class=\"nb-dyn-row\">
                                <td><input type=\"text\" class=\"form-control form-control-sm\" name=\"usuarios[";
            // line 402
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 402), "html", null, true);
            yield "][nome]\"        value=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["row"], "nome", [], "any", true, true, false, 402)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["row"], "nome", [], "any", false, false, false, 402), "")) : ("")));
            yield "\"        placeholder=\"Nome completo\"></td>
                                <td><input type=\"text\" class=\"form-control form-control-sm\" name=\"usuarios[";
            // line 403
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 403), "html", null, true);
            yield "][login]\"       value=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["row"], "login", [], "any", true, true, false, 403)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["row"], "login", [], "any", false, false, false, 403), "")) : ("")));
            yield "\"       placeholder=\"login\"></td>
                                <td><input type=\"text\" class=\"form-control form-control-sm nb-pwd\" name=\"usuarios[";
            // line 404
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 404), "html", null, true);
            yield "][senha]\" value=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["row"], "senha", [], "any", true, true, false, 404)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["row"], "senha", [], "any", false, false, false, 404), "")) : ("")));
            yield "\"       placeholder=\"senha\" autocomplete=\"off\"></td>
                                <td><input type=\"text\" class=\"form-control form-control-sm\" name=\"usuarios[";
            // line 405
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 405), "html", null, true);
            yield "][email]\"       value=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["row"], "email", [], "any", true, true, false, 405)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["row"], "email", [], "any", false, false, false, 405), "")) : ("")));
            yield "\"       placeholder=\"email@empresa.com\"></td>
                                <td>
                                    <select class=\"form-select form-select-sm\" name=\"usuarios[";
            // line 407
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 407), "html", null, true);
            yield "][tipo]\">
                                        <option value=\"usuario\"       ";
            // line 408
            yield (((((CoreExtension::getAttribute($this->env, $this->source, $context["row"], "tipo", [], "any", true, true, false, 408)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["row"], "tipo", [], "any", false, false, false, 408), "usuario")) : ("usuario")) == "usuario")) ? ("selected") : (""));
            yield ">Usuário</option>
                                        <option value=\"supervisor\"    ";
            // line 409
            yield (((((CoreExtension::getAttribute($this->env, $this->source, $context["row"], "tipo", [], "any", true, true, false, 409)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["row"], "tipo", [], "any", false, false, false, 409), "")) : ("")) == "supervisor")) ? ("selected") : (""));
            yield ">Supervisor</option>
                                        <option value=\"administrador\" ";
            // line 410
            yield (((((CoreExtension::getAttribute($this->env, $this->source, $context["row"], "tipo", [], "any", true, true, false, 410)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["row"], "tipo", [], "any", false, false, false, 410), "")) : ("")) == "administrador")) ? ("selected") : (""));
            yield ">Administrador</option>
                                    </select>
                                </td>
                                <td><input type=\"text\" class=\"form-control form-control-sm\" name=\"usuarios[";
            // line 413
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 413), "html", null, true);
            yield "][observacoes]\" value=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["row"], "observacoes", [], "any", true, true, false, 413)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["row"], "observacoes", [], "any", false, false, false, 413), "")) : ("")));
            yield "\" placeholder=\"...\"></td>
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
            // line 421
            yield "                            <tr id=\"nb-usuarios-empty\" class=\"nb-empty-row\">
                                <td colspan=\"7\" class=\"text-center text-muted py-3\">
                                    <i class=\"ti ti-inbox me-1\"></i> Nenhum usuário cadastrado. Clique em \"+ Adicionar Usuário\".
                                </td>
                            </tr>
                            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['row'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 427
        yield "                        </tbody>
                    </table>
                </div>
            </div>
        </div>";
        // line 432
        yield "
        ";
        // line 436
        yield "        <div class=\"nb-card mb-4\">
            <div class=\"nb-card-header\">
                <i class=\"ti ti-notes me-2 text-muted\"></i>
                <strong>Observações</strong>
            </div>
            <div class=\"nb-card-body\">
                <textarea name=\"observacoes\"
                          class=\"form-control\"
                          rows=\"4\"
                          placeholder=\"Observações gerais sobre o chatbot...\">";
        // line 445
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["data"] ?? null), "observacoes", [], "any", false, false, false, 445));
        yield "</textarea>
            </div>
        </div>

        ";
        // line 452
        yield "        <div class=\"d-flex justify-content-end mt-2 pb-3\">
            <button type=\"submit\" class=\"btn btn-primary nb-btn-save\">
                <i class=\"ti ti-device-floppy me-2\"></i>
                Salvar Chatbot
            </button>
        </div>

    </form>

    ";
        // line 464
        yield "
    ";
        // line 466
        yield "    <template id=\"nb-tpl-comun\">
        <tr class=\"nb-dyn-row\">
            <td><input type=\"text\" class=\"form-control form-control-sm\" name=\"comunicacao_massa[__IDX__][nome_sistema]\"       placeholder=\"Ex: Zenvia\"></td>
            <td><input type=\"date\" class=\"form-control form-control-sm\" name=\"comunicacao_massa[__IDX__][data_ativacao]\"></td>
            <td><input type=\"text\" class=\"form-control form-control-sm\" name=\"comunicacao_massa[__IDX__][numero_autenticado]\" placeholder=\"(27) 99999-0000\"></td>
            <td><input type=\"text\" class=\"form-control form-control-sm\" name=\"comunicacao_massa[__IDX__][tipo_homologacao]\"   placeholder=\"Ex: WhatsApp Business\"></td>
            <td><input type=\"text\" class=\"form-control form-control-sm\" name=\"comunicacao_massa[__IDX__][link_acesso]\"        placeholder=\"https://...\"></td>
            <td><input type=\"text\" class=\"form-control form-control-sm\" name=\"comunicacao_massa[__IDX__][login]\"              placeholder=\"login\"></td>
            <td><input type=\"text\" class=\"form-control form-control-sm nb-pwd\" name=\"comunicacao_massa[__IDX__][senha]\"       placeholder=\"senha\" autocomplete=\"off\"></td>
            <td><input type=\"text\" class=\"form-control form-control-sm\" name=\"comunicacao_massa[__IDX__][responsavel]\"        placeholder=\"Nome\"></td>
            <td>
                <button type=\"button\" class=\"btn btn-sm btn-ghost-danger nb-btn-remove-row\" title=\"Remover\">
                    <i class=\"ti ti-trash\"></i>
                </button>
            </td>
        </tr>
    </template>

    ";
        // line 485
        yield "    <template id=\"nb-tpl-restricao\">
        <tr class=\"nb-dyn-row\">
            <td><input type=\"date\" class=\"form-control form-control-sm\" name=\"restricoes[__IDX__][data]\"></td>
            <td><input type=\"text\" class=\"form-control form-control-sm\" name=\"restricoes[__IDX__][duracao]\"         placeholder=\"Ex: 24h, 7 dias\"></td>
            <td><input type=\"text\" class=\"form-control form-control-sm\" name=\"restricoes[__IDX__][numero_restrito]\" placeholder=\"(27) 99999-0000\"></td>
            <td>
                <button type=\"button\" class=\"btn btn-sm btn-ghost-danger nb-btn-remove-row\" title=\"Remover\">
                    <i class=\"ti ti-trash\"></i>
                </button>
            </td>
        </tr>
    </template>

    ";
        // line 499
        yield "    <template id=\"nb-tpl-usuario\">
        <tr class=\"nb-dyn-row\">
            <td><input type=\"text\" class=\"form-control form-control-sm\" name=\"usuarios[__IDX__][nome]\"        placeholder=\"Nome completo\"></td>
            <td><input type=\"text\" class=\"form-control form-control-sm\" name=\"usuarios[__IDX__][login]\"       placeholder=\"login\"></td>
            <td><input type=\"text\" class=\"form-control form-control-sm nb-pwd\" name=\"usuarios[__IDX__][senha]\" placeholder=\"senha\" autocomplete=\"off\"></td>
            <td><input type=\"text\" class=\"form-control form-control-sm\" name=\"usuarios[__IDX__][email]\"       placeholder=\"email@empresa.com\"></td>
            <td>
                <select class=\"form-select form-select-sm\" name=\"usuarios[__IDX__][tipo]\">
                    <option value=\"usuario\" selected>Usuário</option>
                    <option value=\"supervisor\">Supervisor</option>
                    <option value=\"administrador\">Administrador</option>
                </select>
            </td>
            <td><input type=\"text\" class=\"form-control form-control-sm\" name=\"usuarios[__IDX__][observacoes]\" placeholder=\"...\"></td>
            <td>
                <button type=\"button\" class=\"btn btn-sm btn-ghost-danger nb-btn-remove-row\" title=\"Remover\">
                    <i class=\"ti ti-trash\"></i>
                </button>
            </td>
        </tr>
    </template>

</div>";
        // line 522
        yield "
";
        // line 524
        yield "<script>
(function () {
    var btn = document.getElementById('nb-btn-open-link');
    if (!btn) return;
    btn.addEventListener('click', function () {
        var val = document.getElementById('nb-chatbot-link').value.trim();
        if (val) { window.open(val, '_blank', 'noopener,noreferrer'); }
    });
}());
</script>

";
        // line 535
        yield from         $this->loadTemplate("@newbase/companydata/sections/_shared_js.html.twig", "@newbase/companydata/sections/chatbot.html.twig", 535)->unwrap()->yield($context);
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
        return array (  832 => 535,  819 => 524,  816 => 522,  792 => 499,  777 => 485,  757 => 466,  754 => 464,  743 => 452,  736 => 445,  725 => 436,  722 => 432,  716 => 427,  705 => 421,  682 => 413,  676 => 410,  672 => 409,  668 => 408,  664 => 407,  657 => 405,  651 => 404,  645 => 403,  639 => 402,  636 => 401,  618 => 400,  590 => 374,  587 => 370,  581 => 365,  570 => 359,  547 => 351,  541 => 350,  535 => 349,  532 => 348,  514 => 347,  489 => 324,  486 => 320,  480 => 315,  469 => 309,  446 => 301,  440 => 300,  434 => 299,  428 => 298,  422 => 297,  416 => 296,  410 => 295,  404 => 294,  401 => 293,  383 => 292,  353 => 264,  350 => 260,  348 => 259,  346 => 258,  338 => 252,  326 => 243,  314 => 234,  302 => 225,  288 => 213,  285 => 209,  283 => 208,  281 => 207,  272 => 200,  257 => 188,  244 => 178,  229 => 166,  216 => 156,  203 => 146,  190 => 136,  176 => 124,  173 => 120,  171 => 119,  169 => 118,  164 => 114,  155 => 107,  143 => 99,  141 => 98,  134 => 94,  121 => 84,  109 => 75,  98 => 67,  86 => 58,  74 => 49,  60 => 37,  55 => 32,  51 => 31,  47 => 30,  38 => 23,);
    }

    public function getSourceContext()
    {
        return new Source("", "@newbase/companydata/sections/chatbot.html.twig", "D:\\laragon\\www\\glpi\\plugins\\newbase\\templates\\companydata\\sections\\chatbot.html.twig");
    }
}
