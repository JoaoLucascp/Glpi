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

/* components/form/inventory_info.html.twig */
class __TwigTemplate_e2e1924cd407fa0bee86c1920c07cf06 extends Template
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
        if ((Session::haveRight("inventory", Twig\Extension\CoreExtension::constant("READ")) && (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isDynamic", [], "method", false, false, false, 33) != false))) {
            // line 34
            yield "<div class=\"card m-n2 border-0 shadow-none\">
   <div class=\"card-header\">
      <div class=\"ribbon ribbon-bookmark ribbon-top ribbon-start bg-blue s-1\">
         <i class=\"fas fa-cloud-download-alt\"></i>
      </div>
      <h4 class=\"card-title ps-4\">
         ";
            // line 40
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(__("Inventory information"), "html", null, true);
            yield "
      </h4>
      ";
            // line 42
            $context["inventory_filename"] = (($this->extensions['Glpi\Application\View\Extension\PhpExtension']->isUsingTrait(($context["item"] ?? null), "Glpi\\Features\\Inventoriable")) ? (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "getInventoryFileName", [false], "method", false, false, false, 42)) : (null));
            // line 43
            yield "      ";
            if ( !(null === ($context["inventory_filename"] ?? null))) {
                // line 44
                yield "         <a href=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Glpi\Application\View\Extension\RoutingExtension']->path(("front/document.send.php?file=_inventory/" . ($context["inventory_filename"] ?? null))), "html", null, true);
                yield "\"
            class=\"btn btn-sm btn-secondary ms-auto\" target=\"_blank\"
            data-bs-toggle=\"tooltip\" data-bs-placement=\"right\"
            title=\"";
                // line 47
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::sprintf(__("Download \"%1\$s\" inventory file"), $this->extensions['Glpi\Application\View\Extension\ItemtypeExtension']->getItemName(($context["item"] ?? null))), "html", null, true);
                yield "\">
            <i class=\"fas fa-download\"></i>
         </a>
      ";
            } else {
                // line 51
                yield "         <span class=\"ms-auto\" title=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(__("Inventory file missing"), "html", null, true);
                yield "\">
            <i class=\"fas fa-ban\"></i>
            <span class=\"visually-hidden\">";
                // line 53
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(__("Inventory file missing"), "html", null, true);
                yield "</span>
         </span>
      ";
            }
            // line 56
            yield "   </div>

   ";
            // line 58
            $context["agent"] = (($this->extensions['Glpi\Application\View\Extension\PhpExtension']->isUsingTrait(($context["item"] ?? null), "Glpi\\Features\\Inventoriable")) ? (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "getInventoryAgent", [], "method", false, false, false, 58)) : (null));
            // line 59
            yield "   ";
            if ( !(null === ($context["agent"] ?? null))) {
                // line 60
                yield "      <div class=\"card-body row\">
         <div class=\"mb-3 col-12 col-sm-4\">
            <label class=\"form-label\" >";
                // line 62
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["agent"] ?? null), "getTypeName", [], "method", false, false, false, 62), "html", null, true);
                yield "</label>
            <span>
               <i class=\"";
                // line 64
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["agent"] ?? null), "getIcon", [], "method", false, false, false, 64), "html", null, true);
                yield "\"></i>
               ";
                // line 65
                yield $this->extensions['Glpi\Application\View\Extension\ItemtypeExtension']->getItemLink(($context["agent"] ?? null));
                yield "
            </span>
         </div>

         <div class=\"mb-3 col-12 col-sm-4\">
            <label class=\"form-label\" >";
                // line 70
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(__("Useragent"), "html", null, true);
                yield "</label>
            <span>";
                // line 71
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Glpi\Application\View\Extension\DataHelpersExtension']->getVerbatimValue((($__internal_compile_0 = CoreExtension::getAttribute($this->env, $this->source, ($context["agent"] ?? null), "fields", [], "any", false, false, false, 71)) && is_array($__internal_compile_0) || $__internal_compile_0 instanceof ArrayAccess ? ($__internal_compile_0["useragent"] ?? null) : null)), "html", null, true);
                yield "</span>
         </div>

         <div class=\"mb-3 col-12 col-sm-4\">
            <label class=\"form-label\" >";
                // line 75
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(__("Inventory tag"), "html", null, true);
                yield "</label>
            <span>";
                // line 76
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Glpi\Application\View\Extension\DataHelpersExtension']->getVerbatimValue((($__internal_compile_1 = CoreExtension::getAttribute($this->env, $this->source, ($context["agent"] ?? null), "fields", [], "any", false, false, false, 76)) && is_array($__internal_compile_1) || $__internal_compile_1 instanceof ArrayAccess ? ($__internal_compile_1["tag"] ?? null) : null)), "html", null, true);
                yield "</span>
         </div>

         <div class=\"mb-3 col-12 col-sm-4\">
            <label class=\"form-label\" >";
                // line 80
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(__("Public contact address"), "html", null, true);
                yield "</label>
            <span>";
                // line 81
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Glpi\Application\View\Extension\DataHelpersExtension']->getVerbatimValue((($__internal_compile_2 = CoreExtension::getAttribute($this->env, $this->source, ($context["agent"] ?? null), "fields", [], "any", false, false, false, 81)) && is_array($__internal_compile_2) || $__internal_compile_2 instanceof ArrayAccess ? ($__internal_compile_2["remote_addr"] ?? null) : null)), "html", null, true);
                yield "</span>
         </div>

         <div class=\"mb-3 col-12 col-sm-4\">
            <label class=\"form-label\" >";
                // line 85
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(__("Last contact"), "html", null, true);
                yield "</label>
            <span>";
                // line 86
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Glpi\Application\View\Extension\DataHelpersExtension']->getFormattedDatetime((($__internal_compile_3 = CoreExtension::getAttribute($this->env, $this->source, ($context["agent"] ?? null), "fields", [], "any", false, false, false, 86)) && is_array($__internal_compile_3) || $__internal_compile_3 instanceof ArrayAccess ? ($__internal_compile_3["last_contact"] ?? null) : null)), "html", null, true);
                yield "</span>
         </div>

         ";
                // line 89
                if (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isField", ["last_inventory_update"], "method", false, false, false, 89)) {
                    // line 90
                    yield "            <div class=\"mb-3 col-12 col-sm-4\">
               <label class=\"form-label\" >";
                    // line 91
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(__("Last inventory update"), "html", null, true);
                    yield "</label>
               <span>";
                    // line 92
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Glpi\Application\View\Extension\DataHelpersExtension']->getFormattedDatetime((($__internal_compile_4 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, false, false, 92)) && is_array($__internal_compile_4) || $__internal_compile_4 instanceof ArrayAccess ? ($__internal_compile_4["last_inventory_update"] ?? null) : null)), "html", null, true);
                    yield "</span>
            </div>
         ";
                } else {
                    // line 95
                    yield "            <div class=\"mb-3 col-12 col-sm-4\">
            </div>
         ";
                }
                // line 98
                yield "
         <div class=\"mb-3 col-12 col-sm-4\">
            <label class=\"form-label\" >
               ";
                // line 101
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(__("Agent status"), "html", null, true);
                yield "
               <i id=\"update-status\" class=\"fas fa-sync\" role=\"button\" title=\"";
                // line 102
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(__("Ask agent about its current status"), "html", null, true);
                yield "\"></i>
            </label>
            <span id='agent_status'>";
                // line 104
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(__("Unknown"), "html", null, true);
                yield "</span>
         </div>

         <div class=\"mb-3 col-12 col-sm-4\">
            <label class=\"form-label\" >
               ";
                // line 109
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(__("Request inventory"), "html", null, true);
                yield "
               <i id=\"update-inventory\" class=\"fas fa-sync\" role=\"button\" title=\"";
                // line 110
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(__("Request agent to proceed an new inventory"), "html", null, true);
                yield "\"></i>
            </label>
            <span id='inventory_status'>";
                // line 112
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(__("Unknown"), "html", null, true);
                yield "</span>
         </div>
      </div>

      <script>
      \$(function () {
         \$('#update-status').on('click', function() {
            var icon = \$(this);
            icon.addClass('fa-spin');
            \$.ajax({
               type: 'POST',
               url: '";
                // line 123
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Glpi\Application\View\Extension\RoutingExtension']->path("/ajax/agent.php"), "html", null, true);
                yield "',
               timeout: 30000, // 30 seconds timeout
               dataType: 'json',
               data: {
                  action: '";
                // line 127
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::constant("Agent::ACTION_STATUS"), "html", null, true);
                yield "',
                  id: '";
                // line 128
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((($__internal_compile_5 = CoreExtension::getAttribute($this->env, $this->source, ($context["agent"] ?? null), "fields", [], "any", false, false, false, 128)) && is_array($__internal_compile_5) || $__internal_compile_5 instanceof ArrayAccess ? ($__internal_compile_5["id"] ?? null) : null), "html", null, true);
                yield "'
               },
               success: function(json) {
                  \$('#agent_status').text(json.answer);
               },
               complete: function() {
                  icon.removeClass('fa-spin');
               }
            });
         });

         \$('#update-inventory').on('click', function() {
            var icon = \$(this);
            icon.addClass(\"fa-spin\");
            \$.ajax({
               type: 'POST',
               url: '";
                // line 144
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Glpi\Application\View\Extension\RoutingExtension']->path("/ajax/agent.php"), "html", null, true);
                yield "',
               timeout: 30000, // 30 seconds timeout
               dataType: 'json',
               data: {
                  action: '";
                // line 148
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::constant("Agent::ACTION_INVENTORY"), "html", null, true);
                yield "',
                  id: '";
                // line 149
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((($__internal_compile_6 = CoreExtension::getAttribute($this->env, $this->source, ($context["agent"] ?? null), "fields", [], "any", false, false, false, 149)) && is_array($__internal_compile_6) || $__internal_compile_6 instanceof ArrayAccess ? ($__internal_compile_6["id"] ?? null) : null), "html", null, true);
                yield "'
               },
               success: function(json) {
                  \$('#inventory_status').text(json.answer);
               },
               complete: function() {
                  icon.removeClass('fa-spin');
               }
            });
         });
      });
      </script>
   ";
            } else {
                // line 162
                yield "   <div class=\"card-body row\">
      <div class=\"mb-3 col-12 col-sm-6\">
         ";
                // line 164
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(__("Agent information is not available."), "html", null, true);
                yield "
      </div>
   </div>
   ";
            }
            // line 168
            yield "
   ";
            // line 170
            yield "   ";
            // line 171
            yield "   ";
            if ((($this->extensions['Glpi\Application\View\Extension\PhpExtension']->isUsingTrait(($context["item"] ?? null), "Glpi\\Features\\Inventoriable") && (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "getInventoryAgent", [], "method", false, false, false, 171) == null)) && CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isField", ["last_inventory_update"], "method", false, false, false, 171))) {
                // line 172
                yield "      <div class=\"card-body row\">
         <div class=\"mb-3 col-12 col-sm-6\">
            <label class=\"form-label\" >";
                // line 174
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(__("Last inventory"), "html", null, true);
                yield "</label>
            <span>";
                // line 175
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Glpi\Application\View\Extension\DataHelpersExtension']->getFormattedDatetime((($__internal_compile_7 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, false, false, 175)) && is_array($__internal_compile_7) || $__internal_compile_7 instanceof ArrayAccess ? ($__internal_compile_7["last_inventory_update"] ?? null) : null)), "html", null, true);
                yield "</span>
         </div>
      </div>
   ";
            }
            // line 179
            yield "
   ";
            // line 180
            if ((((($__internal_compile_8 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, false, false, 180)) && is_array($__internal_compile_8) || $__internal_compile_8 instanceof ArrayAccess ? ($__internal_compile_8["id"] ?? null) : null) > 0) && CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isField", ["is_dynamic"], "method", false, false, false, 180))) {
                // line 181
                yield "      ";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Glpi\Application\View\Extension\PluginExtension']->callPluginHook(Twig\Extension\CoreExtension::constant("Glpi\\Plugin\\Hooks::AUTOINVENTORY_INFORMATION"), ($context["item"] ?? null)), "html", null, true);
                yield "
   ";
            }
            // line 183
            yield "</div>
";
        }
        return; yield '';
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "components/form/inventory_info.html.twig";
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
        return array (  327 => 183,  321 => 181,  319 => 180,  316 => 179,  309 => 175,  305 => 174,  301 => 172,  298 => 171,  296 => 170,  293 => 168,  286 => 164,  282 => 162,  266 => 149,  262 => 148,  255 => 144,  236 => 128,  232 => 127,  225 => 123,  211 => 112,  206 => 110,  202 => 109,  194 => 104,  189 => 102,  185 => 101,  180 => 98,  175 => 95,  169 => 92,  165 => 91,  162 => 90,  160 => 89,  154 => 86,  150 => 85,  143 => 81,  139 => 80,  132 => 76,  128 => 75,  121 => 71,  117 => 70,  109 => 65,  105 => 64,  100 => 62,  96 => 60,  93 => 59,  91 => 58,  87 => 56,  81 => 53,  75 => 51,  68 => 47,  61 => 44,  58 => 43,  56 => 42,  51 => 40,  43 => 34,  41 => 33,  38 => 32,);
    }

    public function getSourceContext()
    {
        return new Source("", "components/form/inventory_info.html.twig", "D:\\laragon\\www\\glpi\\templates\\components\\form\\inventory_info.html.twig");
    }
}
