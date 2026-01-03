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

/* install/step3.html.twig */
class __TwigTemplate_4aa16e3ad95e970cd8e674a51a058dee extends Template
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
        // line 32
        yield "
";
        // line 33
        $macros["alerts"] = $this->macros["alerts"] = $this->loadTemplate("components/alerts_macros.html.twig", "install/step3.html.twig", 33)->unwrap();
        // line 34
        yield "
<h3>";
        // line 35
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(__("Test of the connection at the database"), "html", null, true);
        yield "</h3>

";
        // line 37
        if ((((Twig\Extension\CoreExtension::length($this->env->getCharset(), ($context["host"] ?? null)) == 0) || (Twig\Extension\CoreExtension::length($this->env->getCharset(), ($context["user"] ?? null)) == 0)) || CoreExtension::getAttribute($this->env, $this->source, ($context["link"] ?? null), "connect_error", [], "any", false, false, false, 37))) {
            // line 38
            yield "
   ";
            // line 39
            yield CoreExtension::callMacro($macros["alerts"], "macro_alert_danger", [__("Can't connect to the database"), Twig\Extension\CoreExtension::sprintf(__("The server answered: %s"), CoreExtension::getAttribute($this->env, $this->source,             // line 41
($context["link"] ?? null), "connect_error", [], "any", false, false, false, 41))], 39, $context, $this->getSourceContext());
            // line 42
            yield "

   <form action=\"install.php\" method=\"post\" class=\"d-inline\" data-submit-once>
      <button type=\"submit\" name=\"submit\" class=\"btn btn-warning\">
         <i class=\"fas fa-chevron-left me-1 fa-2x alert-icon\"></i>
         ";
            // line 47
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(__("Back"), "html", null, true);
            yield "
      </button>

      <input type=\"hidden\" name=\"update\" value=\"";
            // line 50
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["update"] ?? null), "html", null, true);
            yield "\">
      <input type=\"hidden\" name=\"install\" value=\"Etape_1\">
      <input type=\"hidden\" name=\"_glpi_csrf_token\" value=\"";
            // line 52
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Session::getNewCSRFToken(), "html", null, true);
            yield "\">
   </form>
";
        } else {
            // line 55
            yield "   ";
            yield CoreExtension::callMacro($macros["alerts"], "macro_alert_success", [__("Database connection successful")], 55, $context, $this->getSourceContext());
            // line 57
            yield "

   ";
            // line 59
            if ( !CoreExtension::getAttribute($this->env, $this->source, ($context["engine_requirement"] ?? null), "isValidated", [], "method", false, false, false, 59)) {
                // line 60
                yield "      ";
                yield CoreExtension::callMacro($macros["alerts"], "macro_alert_danger", [__("Database engine is not supported."), CoreExtension::getAttribute($this->env, $this->source,                 // line 62
($context["engine_requirement"] ?? null), "getValidationMessages", [], "method", false, false, false, 62)], 60, $context, $this->getSourceContext());
                // line 63
                yield "
   ";
            }
            // line 65
            yield "
   ";
            // line 66
            if ( !CoreExtension::getAttribute($this->env, $this->source, ($context["config_requirement"] ?? null), "isValidated", [], "method", false, false, false, 66)) {
                // line 67
                yield "      ";
                yield CoreExtension::callMacro($macros["alerts"], "macro_alert_danger", [__("Database configuration is not supported."), CoreExtension::getAttribute($this->env, $this->source,                 // line 69
($context["config_requirement"] ?? null), "getValidationMessages", [], "method", false, false, false, 69)], 67, $context, $this->getSourceContext());
                // line 70
                yield "
   ";
            }
            // line 72
            yield "
   ";
            // line 73
            if ((CoreExtension::getAttribute($this->env, $this->source, ($context["engine_requirement"] ?? null), "isValidated", [], "method", false, false, false, 73) && CoreExtension::getAttribute($this->env, $this->source, ($context["config_requirement"] ?? null), "isValidated", [], "method", false, false, false, 73))) {
                // line 74
                yield "      <div class=\"container mt-4\">
         <form action='install.php' method='post'>
            <div class=\"mb-3\">
               <h3>
                  ";
                // line 78
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((((($context["update"] ?? null) == "no")) ? (__("Please select a database:")) : (__("Please select a database to update:"))), "html", null, true);
                yield "
               </h3>
               <div class=\"form-selectgroup form-selectgroup-boxes d-flex flex-column\">
                  ";
                // line 81
                if ((($context["update"] ?? null) == "no")) {
                    // line 82
                    yield "                     <label class=\"form-selectgroup-item flex-fill\">
                        <input type=\"radio\" name=\"databasename\" value=\"0\" class=\"form-selectgroup-input\" checked=\"checked\">
                        <div class=\"form-selectgroup-label d-flex align-items-center p-3\">
                           <div class=\"me-3\">
                              <span class=\"form-selectgroup-check\"></span>
                           </div>
                           <div>
                              <label for=\"new_db\" class=\"form-label\">
                                 ";
                    // line 90
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(__("Create a new database or use an existing one:"), "html", null, true);
                    yield "
                              </label>
                              <input type=\"text\" name=\"newdatabasename\" id=\"new_db\" class=\"form-control\" autofocus>
                           </div>
                        </div>
                     </label>
                  ";
                }
                // line 97
                yield "
                  ";
                // line 98
                $context['_parent'] = $context;
                $context['_seq'] = CoreExtension::ensureTraversable(($context["databases"] ?? null));
                foreach ($context['_seq'] as $context["_key"] => $context["database"]) {
                    // line 99
                    yield "                     <label class=\"form-selectgroup-item flex-fill\">
                        <input type=\"radio\" name=\"databasename\" value=\"";
                    // line 100
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($context["database"], "html", null, true);
                    yield "\" class=\"form-selectgroup-input\">
                        <div class=\"form-selectgroup-label d-flex align-items-center p-3\">
                           <div class=\"me-3\">
                              <span class=\"form-selectgroup-check\"></span>
                           </div>
                           <div>
                              ";
                    // line 106
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($context["database"], "html", null, true);
                    yield "
                           </div>
                        </div>
                     </label>
                  ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['database'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 111
                yield "               </div>
            </div>

            <button type=\"submit\" name=\"submit\" class=\"btn btn-primary\">
               ";
                // line 115
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(__("Continue"), "html", null, true);
                yield "
               <i class=\"fas fa-chevron-right ms-1\"></i>
            </button>

            ";
                // line 119
                if ((($context["update"] ?? null) == "no")) {
                    // line 120
                    yield "               <input type=\"hidden\" name=\"install\" value=\"Etape_3\">
            ";
                } else {
                    // line 122
                    yield "               <input type=\"hidden\" name=\"install\" value=\"update_1\">
            ";
                }
                // line 124
                yield "
            <input type=\"hidden\" name=\"_glpi_csrf_token\" value=\"";
                // line 125
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Session::getNewCSRFToken(), "html", null, true);
                yield "\">
         </form>
      </div>
   ";
            }
        }
        return; yield '';
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "install/step3.html.twig";
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
        return array (  208 => 125,  205 => 124,  201 => 122,  197 => 120,  195 => 119,  188 => 115,  182 => 111,  171 => 106,  162 => 100,  159 => 99,  155 => 98,  152 => 97,  142 => 90,  132 => 82,  130 => 81,  124 => 78,  118 => 74,  116 => 73,  113 => 72,  109 => 70,  107 => 69,  105 => 67,  103 => 66,  100 => 65,  96 => 63,  94 => 62,  92 => 60,  90 => 59,  86 => 57,  83 => 55,  77 => 52,  72 => 50,  66 => 47,  59 => 42,  57 => 41,  56 => 39,  53 => 38,  51 => 37,  46 => 35,  43 => 34,  41 => 33,  38 => 32,);
    }

    public function getSourceContext()
    {
        return new Source("{#
 # ---------------------------------------------------------------------
 #
 # GLPI - Gestionnaire Libre de Parc Informatique
 #
 # http://glpi-project.org
 #
 # @copyright 2015-2025 Teclib' and contributors.
 # @licence   https://www.gnu.org/licenses/gpl-3.0.html
 #
 # ---------------------------------------------------------------------
 #
 # LICENSE
 #
 # This file is part of GLPI.
 #
 # This program is free software: you can redistribute it and/or modify
 # it under the terms of the GNU General Public License as published by
 # the Free Software Foundation, either version 3 of the License, or
 # (at your option) any later version.
 #
 # This program is distributed in the hope that it will be useful,
 # but WITHOUT ANY WARRANTY; without even the implied warranty of
 # MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 # GNU General Public License for more details.
 #
 # You should have received a copy of the GNU General Public License
 # along with this program.  If not, see <https://www.gnu.org/licenses/>.
 #
 # ---------------------------------------------------------------------
 #}

{% import 'components/alerts_macros.html.twig' as alerts %}

<h3>{{ __('Test of the connection at the database') }}</h3>

{% if host|length == 0 or user|length == 0 or link.connect_error %}

   {{ alerts.alert_danger(
      __(\"Can't connect to the database\"),
      __('The server answered: %s')|format(link.connect_error)
   ) }}

   <form action=\"install.php\" method=\"post\" class=\"d-inline\" data-submit-once>
      <button type=\"submit\" name=\"submit\" class=\"btn btn-warning\">
         <i class=\"fas fa-chevron-left me-1 fa-2x alert-icon\"></i>
         {{ __(\"Back\") }}
      </button>

      <input type=\"hidden\" name=\"update\" value=\"{{ update }}\">
      <input type=\"hidden\" name=\"install\" value=\"Etape_1\">
      <input type=\"hidden\" name=\"_glpi_csrf_token\" value=\"{{ csrf_token() }}\">
   </form>
{% else %}
   {{ alerts.alert_success(
      __('Database connection successful'),
   ) }}

   {% if not engine_requirement.isValidated() %}
      {{ alerts.alert_danger(
         __('Database engine is not supported.'),
         engine_requirement.getValidationMessages()
      ) }}
   {% endif %}

   {% if not config_requirement.isValidated() %}
      {{ alerts.alert_danger(
         __('Database configuration is not supported.'),
         config_requirement.getValidationMessages()
      ) }}
   {% endif %}

   {% if engine_requirement.isValidated() and config_requirement.isValidated() %}
      <div class=\"container mt-4\">
         <form action='install.php' method='post'>
            <div class=\"mb-3\">
               <h3>
                  {{ update == \"no\" ? __('Please select a database:') : __('Please select a database to update:') }}
               </h3>
               <div class=\"form-selectgroup form-selectgroup-boxes d-flex flex-column\">
                  {% if update == \"no\" %}
                     <label class=\"form-selectgroup-item flex-fill\">
                        <input type=\"radio\" name=\"databasename\" value=\"0\" class=\"form-selectgroup-input\" checked=\"checked\">
                        <div class=\"form-selectgroup-label d-flex align-items-center p-3\">
                           <div class=\"me-3\">
                              <span class=\"form-selectgroup-check\"></span>
                           </div>
                           <div>
                              <label for=\"new_db\" class=\"form-label\">
                                 {{ __('Create a new database or use an existing one:') }}
                              </label>
                              <input type=\"text\" name=\"newdatabasename\" id=\"new_db\" class=\"form-control\" autofocus>
                           </div>
                        </div>
                     </label>
                  {% endif %}

                  {% for database in databases %}
                     <label class=\"form-selectgroup-item flex-fill\">
                        <input type=\"radio\" name=\"databasename\" value=\"{{ database }}\" class=\"form-selectgroup-input\">
                        <div class=\"form-selectgroup-label d-flex align-items-center p-3\">
                           <div class=\"me-3\">
                              <span class=\"form-selectgroup-check\"></span>
                           </div>
                           <div>
                              {{ database }}
                           </div>
                        </div>
                     </label>
                  {% endfor %}
               </div>
            </div>

            <button type=\"submit\" name=\"submit\" class=\"btn btn-primary\">
               {{ __(\"Continue\") }}
               <i class=\"fas fa-chevron-right ms-1\"></i>
            </button>

            {% if update == \"no\" %}
               <input type=\"hidden\" name=\"install\" value=\"Etape_3\">
            {% else %}
               <input type=\"hidden\" name=\"install\" value=\"update_1\">
            {% endif %}

            <input type=\"hidden\" name=\"_glpi_csrf_token\" value=\"{{ csrf_token() }}\">
         </form>
      </div>
   {% endif %}
{% endif %}
", "install/step3.html.twig", "D:\\laragon\\www\\glpi\\templates\\install\\step3.html.twig");
    }
}
