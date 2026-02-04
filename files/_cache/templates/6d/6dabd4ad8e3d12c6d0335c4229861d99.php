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

/* components/form/header_content.html.twig */
class __TwigTemplate_d3367dde0c812ff6eb7a241199a07eba extends Template
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
        $context["canedit"] = (((CoreExtension::getAttribute($this->env, $this->source, ($context["params"] ?? null), "canedit", [], "array", true, true, false, 33) &&  !(null === (($__internal_compile_0 = ($context["params"] ?? null)) && is_array($__internal_compile_0) || $__internal_compile_0 instanceof ArrayAccess ? ($__internal_compile_0["canedit"] ?? null) : null)))) ? ((($__internal_compile_1 = ($context["params"] ?? null)) && is_array($__internal_compile_1) || $__internal_compile_1 instanceof ArrayAccess ? ($__internal_compile_1["canedit"] ?? null) : null)) : (true));
        // line 34
        $context["withtemplate"] = (((CoreExtension::getAttribute($this->env, $this->source, ($context["params"] ?? null), "withtemplate", [], "array", true, true, false, 34) &&  !(null === (($__internal_compile_2 = ($context["params"] ?? null)) && is_array($__internal_compile_2) || $__internal_compile_2 instanceof ArrayAccess ? ($__internal_compile_2["withtemplate"] ?? null) : null)))) ? ((($__internal_compile_3 = ($context["params"] ?? null)) && is_array($__internal_compile_3) || $__internal_compile_3 instanceof ArrayAccess ? ($__internal_compile_3["withtemplate"] ?? null) : null)) : (""));
        // line 35
        $context["rand"] = ((array_key_exists("rand", $context)) ? (Twig\Extension\CoreExtension::default(($context["rand"] ?? null), Twig\Extension\CoreExtension::random($this->env->getCharset()))) : (Twig\Extension\CoreExtension::random($this->env->getCharset())));
        // line 36
        $context["nametype"] = (((CoreExtension::getAttribute($this->env, $this->source, ($context["params"] ?? null), "formtitle", [], "array", true, true, false, 36) &&  !(null === (($__internal_compile_4 = ($context["params"] ?? null)) && is_array($__internal_compile_4) || $__internal_compile_4 instanceof ArrayAccess ? ($__internal_compile_4["formtitle"] ?? null) : null)))) ? ((($__internal_compile_5 = ($context["params"] ?? null)) && is_array($__internal_compile_5) || $__internal_compile_5 instanceof ArrayAccess ? ($__internal_compile_5["formtitle"] ?? null) : null)) : (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "getTypeName", [1], "method", false, false, false, 36)));
        // line 37
        $context["friendlyname"] = (((CoreExtension::getAttribute($this->env, $this->source, ($context["params"] ?? null), "friendlyname", [], "array", true, true, false, 37) &&  !(null === (($__internal_compile_6 = ($context["params"] ?? null)) && is_array($__internal_compile_6) || $__internal_compile_6 instanceof ArrayAccess ? ($__internal_compile_6["friendlyname"] ?? null) : null)))) ? ((($__internal_compile_7 = ($context["params"] ?? null)) && is_array($__internal_compile_7) || $__internal_compile_7 instanceof ArrayAccess ? ($__internal_compile_7["friendlyname"] ?? null) : null)) : (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "getHeaderName", [], "method", false, false, false, 37)));
        // line 38
        $context["id"] = (((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, true, false, 38), "id", [], "array", true, true, false, 38) &&  !(null === (($__internal_compile_8 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, true, false, 38)) && is_array($__internal_compile_8) || $__internal_compile_8 instanceof ArrayAccess ? ($__internal_compile_8["id"] ?? null) : null)))) ? ((($__internal_compile_9 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, true, false, 38)) && is_array($__internal_compile_9) || $__internal_compile_9 instanceof ArrayAccess ? ($__internal_compile_9["id"] ?? null) : null)) : ( -1));
        // line 39
        $context["in_navheader"] = ((array_key_exists("in_navheader", $context)) ? (Twig\Extension\CoreExtension::default(($context["in_navheader"] ?? null), false)) : (false));
        // line 40
        yield "
";
        // line 41
        $context["entity_name"] = ((array_key_exists("entity_name", $context)) ? (Twig\Extension\CoreExtension::default(($context["entity_name"] ?? null), "")) : (""));
        // line 42
        if (( !array_key_exists("entity_id", $context) && CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isEntityAssign", [], "method", false, false, false, 42))) {
            // line 43
            yield "   ";
            if (((CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "getType", [], "method", false, false, false, 43) == "Entity") && ((($__internal_compile_10 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, false, false, 43)) && is_array($__internal_compile_10) || $__internal_compile_10 instanceof ArrayAccess ? ($__internal_compile_10["id"] ?? null) : null) == 0))) {
                // line 44
                yield "      ";
                $context["entity_id"] = null;
                // line 45
                yield "   ";
            } else {
                // line 46
                yield "      ";
                $context["entity_id"] = (((CoreExtension::getAttribute($this->env, $this->source, ($context["params"] ?? null), "entities_id", [], "array", true, true, false, 46) &&  !(null === (($__internal_compile_11 = ($context["params"] ?? null)) && is_array($__internal_compile_11) || $__internal_compile_11 instanceof ArrayAccess ? ($__internal_compile_11["entities_id"] ?? null) : null)))) ? ((($__internal_compile_12 = ($context["params"] ?? null)) && is_array($__internal_compile_12) || $__internal_compile_12 instanceof ArrayAccess ? ($__internal_compile_12["entities_id"] ?? null) : null)) : ((((CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "getEntityID", [], "method", true, true, false, 46) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "getEntityID", [], "method", false, false, false, 46)))) ? (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "getEntityID", [], "method", false, false, false, 46)) : ($this->extensions['Glpi\Application\View\Extension\SessionExtension']->session("glpiactive_entity")))));
                // line 47
                yield "   ";
            }
            // line 48
            yield "
   ";
            // line 49
            if (Session::isMultiEntitiesMode()) {
                // line 50
                yield "      ";
                $context["entity_name"] = $this->extensions['Glpi\Application\View\Extension\ItemtypeExtension']->getItemName("Entity", CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "getEntityID", [], "method", false, false, false, 50));
                // line 51
                yield "   ";
            }
        } elseif ( !        // line 52
array_key_exists("entity_id", $context)) {
            // line 53
            yield "   ";
            $context["entity_id"] = 0;
        }
        // line 55
        yield "
";
        // line 56
        $context["template_name"] = $this->extensions['Glpi\Application\View\Extension\DataHelpersExtension']->getVerbatimValue((($__internal_compile_13 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, false, false, 56)) && is_array($__internal_compile_13) || $__internal_compile_13 instanceof ArrayAccess ? ($__internal_compile_13["template_name"] ?? null) : null));
        // line 57
        if (((($context["withtemplate"] ?? null) == 2) &&  !CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isNewItem", [], "method", false, false, false, 57))) {
            // line 58
            yield "   <input type=\"hidden\" name=\"template_name\" value=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["template_name"] ?? null), "html", null, true);
            yield "\" />
   ";
            // line 59
            $context["nametype"] = Twig\Extension\CoreExtension::sprintf(__("Created from the template %s"), ($context["template_name"] ?? null));
        } elseif ((        // line 60
($context["withtemplate"] ?? null) == 1)) {
            // line 61
            yield "   <input type=\"hidden\" name=\"is_template\" value=\"1\" />
";
        } elseif (CoreExtension::getAttribute($this->env, $this->source,         // line 62
($context["item"] ?? null), "isNewItem", [], "method", false, false, false, 62)) {
            // line 63
            yield "   ";
            $context["nametype"] = Twig\Extension\CoreExtension::sprintf(__("%1\$s - %2\$s"), __("New item"), ($context["nametype"] ?? null));
        } else {
            // line 65
            yield "   ";
            if (((($context["noid"] ?? null) == false) && ($this->extensions['Glpi\Application\View\Extension\SessionExtension']->session("glpiis_ids_visible") || (Twig\Extension\CoreExtension::length($this->env->getCharset(), ($context["nametype"] ?? null)) == 0)))) {
                // line 66
                yield "      ";
                $context["nametype"] = Twig\Extension\CoreExtension::sprintf(__("%1\$s - %2\$s"), ($context["nametype"] ?? null), (($__internal_compile_14 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, false, false, 66)) && is_array($__internal_compile_14) || $__internal_compile_14 instanceof ArrayAccess ? ($__internal_compile_14["id"] ?? null) : null));
                // line 67
                yield "   ";
            }
        }
        // line 69
        yield "
";
        // line 70
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "canEdit", [(($__internal_compile_15 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, false, false, 70)) && is_array($__internal_compile_15) || $__internal_compile_15 instanceof ArrayAccess ? ($__internal_compile_15["id"] ?? null) : null)], "method", false, false, false, 70)) {
            // line 71
            yield "   <form name=\"massaction_";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["rand"] ?? null), "html", null, true);
            yield "\" id=\"massaction_";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["rand"] ?? null), "html", null, true);
            yield "\" method=\"post\"
         action=\"";
            // line 72
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Glpi\Application\View\Extension\RoutingExtension']->path("/front/massiveaction.php"), "html", null, true);
            yield "\" data-submit-once>
      <div id=\"massive_container_";
            // line 73
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["rand"] ?? null), "html", null, true);
            yield "\"></div>
      <input type=\"hidden\" name=\"_glpi_csrf_token\" value=\"";
            // line 74
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Session::getNewCSRFToken(), "html", null, true);
            yield "\" />
   </form>
";
        }
        // line 77
        yield "
<div id=\"header_";
        // line 78
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["rand"] ?? null), "html", null, true);
        yield "\"
     class=\"card-header main-header d-flex flex-wrap mx-n2 mt-n2 align-items-stretch ";
        // line 79
        if (($context["in_navheader"] ?? null)) {
            yield " align-self-end ";
        }
        yield " flex-grow-1\">
   ";
        // line 80
        if (((($context["withtemplate"] ?? null) == 1) && CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isNewItem", [], "method", false, false, false, 80))) {
            // line 81
            yield "      <input type=\"text\" class=\"form-control ms-4 mb-2\" placeholder=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(__("Template name"), "html", null, true);
            yield "\"
             name=\"template_name\" id=\"textfield_template_name";
            // line 82
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["rand"] ?? null), "html", null, true);
            yield "\"
             value=\"";
            // line 83
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["template_name"] ?? null), "html", null, true);
            yield "\" />
   ";
        }
        // line 85
        yield "   <h3 class=\"card-title d-flex align-items-center ";
        yield ((($context["in_navheader"] ?? null)) ? ("ps-5") : ("ps-4"));
        yield "\">
      ";
        // line 86
        $context["icon"] = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "getIcon", [], "method", false, false, false, 86);
        // line 87
        yield "      ";
        if (( !($context["in_navheader"] ?? null) && (Twig\Extension\CoreExtension::length($this->env->getCharset(), ($context["icon"] ?? null)) > 0))) {
            // line 88
            yield "         <div class=\"ribbon ribbon-bookmark ribbon-top ribbon-start bg-blue s-1\">
            <i class=\"";
            // line 89
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["icon"] ?? null), "html", null, true);
            yield " fa-2x\"></i>
         </div>
      ";
        }
        // line 92
        yield "      <span ";
        if (($context["in_navheader"] ?? null)) {
            yield " class=\"status rounded-1\" ";
        }
        yield ">
         ";
        // line 93
        if (($context["in_navheader"] ?? null)) {
            // line 94
            yield "            <i class=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["icon"] ?? null), "html", null, true);
            yield "\"></i>
         ";
        }
        // line 96
        yield "         ";
        if (((($context["withtemplate"] ?? null) == 1) && (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "id", [], "any", false, false, false, 96) > 0))) {
            // line 97
            yield "            ";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(_n("Template", "Templates", 1), "html", null, true);
            yield " - ";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["nametype"] ?? null), "html", null, true);
            yield " - ";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["template_name"] ?? null), "html", null, true);
            yield "
         ";
        } elseif ( !CoreExtension::getAttribute($this->env, $this->source,         // line 98
($context["item"] ?? null), "isNewItem", [], "method", false, false, false, 98)) {
            // line 99
            yield "            ";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["nametype"] ?? null), "html", null, true);
            yield " - ";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["friendlyname"] ?? null), "html", null, true);
            yield "
         ";
        } else {
            // line 101
            yield "            ";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["nametype"] ?? null), "html", null, true);
            yield "
         ";
        }
        // line 103
        yield "      </span>
      ";
        // line 104
        if (((($context["in_navheader"] ?? null) && CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isField", ["is_dynamic"], "method", false, false, false, 104)) && (($__internal_compile_16 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, false, false, 104)) && is_array($__internal_compile_16) || $__internal_compile_16 instanceof ArrayAccess ? ($__internal_compile_16["is_dynamic"] ?? null) : null))) {
            // line 105
            yield "      <span class=\"mx-1 bg-secondary status rounded-1\" title=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(__("Automatic Inventory"), "html", null, true);
            yield "\" data-bs-toggle=\"tooltip\">
         <i class=\"";
            // line 106
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Glpi\Application\View\Extension\PhpExtension']->call("Agent::getIcon"), "html", null, true);
            yield "\"></i>
      </span>
      ";
        }
        // line 109
        yield "      ";
        if (((($context["in_navheader"] ?? null) && CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isField", ["is_deleted"], "method", false, false, false, 109)) && (($__internal_compile_17 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, false, false, 109)) && is_array($__internal_compile_17) || $__internal_compile_17 instanceof ArrayAccess ? ($__internal_compile_17["is_deleted"] ?? null) : null))) {
            // line 110
            yield "      ";
            $context["title"] = ((CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isField", ["date_mod"], "method", false, false, false, 110)) ? (Twig\Extension\CoreExtension::sprintf(__("Item has been deleted on %s"), (($__internal_compile_18 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, false, false, 110)) && is_array($__internal_compile_18) || $__internal_compile_18 instanceof ArrayAccess ? ($__internal_compile_18["date_mod"] ?? null) : null))) : (__("Deleted")));
            // line 111
            yield "      <span class=\"mx-1 bg-danger status rounded-1\" title=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["title"] ?? null), "html", null, true);
            yield "\" data-bs-toggle=\"tooltip\">
         <i class=\"ti ti-trash\"></i>
         ";
            // line 113
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(__("Deleted"), "html", null, true);
            yield "
      </span>
      ";
        }
        // line 116
        yield "
      ";
        // line 117
        if (($context["header_toolbar"] ?? null)) {
            // line 118
            yield "         <div class=\"d-inline-block toolbar ms-2\">
            ";
            // line 119
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(($context["header_toolbar"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["raw_element"]) {
                // line 120
                yield "               ";
                yield $context["raw_element"];
                yield "
            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['raw_element'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 122
            yield "         </div>
      ";
        }
        // line 124
        yield "   </h3>

   ";
        // line 126
        $context["single_actions_ms_auto"] = true;
        // line 127
        yield "   ";
        if (((CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isEntityAssign", [], "method", false, false, false, 127) && Session::isMultiEntitiesMode()) &&  !$this->extensions['Glpi\Application\View\Extension\PhpExtension']->isInstanceOf(($context["item"] ?? null), "Entity"))) {
            // line 128
            yield "      ";
            $context["single_actions_ms_auto"] = false;
            // line 129
            yield "      <span class=\"badge entity-name mx-1 px-2 ms-auto align-items-center\" title=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["entity_name"] ?? null), "html", null, true);
            yield "\">
                  <i class=\"ti ti-stack me-2\"></i>
                  ";
            // line 131
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["entity_name"] ?? null), "html", null, true);
            yield "
               </span>

      ";
            // line 134
            if (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "maybeRecursive", [], "method", false, false, false, 134)) {
                // line 135
                yield "         <span class=\"badge is_recursive-toggle mx-1 px-2 align-items-center\">
            <label class=\"form-check d-flex align-items-center mb-0\">
               ";
                // line 137
                $context["disabled"] = (($context["canedit"] ?? null) == false);
                // line 138
                yield "               ";
                $context["comment"] = __("Change visibility in child entities.");
                // line 139
                yield "
               ";
                // line 140
                if ($this->extensions['Glpi\Application\View\Extension\PhpExtension']->isInstanceOf(($context["item"] ?? null), "CommonDBChild")) {
                    // line 141
                    yield "                  ";
                    $context["comment"] = __("Can՛t change this attribute. It՛s inherited from its parent.");
                    // line 142
                    yield "                  ";
                    $context["disabled"] = true;
                    // line 143
                    yield "               ";
                } elseif ( !CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "can", [($context["id"] ?? null), "recursive"], "method", false, false, false, 143)) {
                    // line 144
                    yield "                  ";
                    $context["comment"] = __("You are not allowed to change the visibility flag for child entities.");
                    // line 145
                    yield "                  ";
                    $context["disabled"] = true;
                    // line 146
                    yield "               ";
                } elseif ( !CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "canUnrecurs", [], "method", false, false, false, 146)) {
                    // line 147
                    yield "                  ";
                    $context["comment"] = __("Flag change forbidden. Linked items found.");
                    // line 148
                    yield "                  ";
                    $context["disabled"] = true;
                    // line 149
                    yield "               ";
                }
                // line 150
                yield "
               ";
                // line 151
                if ( !($context["disabled"] ?? null)) {
                    yield "<input type=\"hidden\" name=\"is_recursive\" value=\"0\" />";
                }
                // line 152
                yield "               <input class=\"form-check-input\" type=\"checkbox\" name=\"is_recursive\" value=\"1\"
                  ";
                // line 153
                if (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isRecursive", [], "method", false, false, false, 153)) {
                    yield "checked=\"checked\"";
                }
                // line 154
                yield "                  ";
                if (($context["disabled"] ?? null)) {
                    yield "disabled=\"disabled\"";
                }
                yield " />
               ";
                // line 155
                if ((($this->extensions['Glpi\Application\View\Extension\PhpExtension']->isInstanceOf(($context["item"] ?? null), "CommonDBChild") && CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isNewItem", [], "method", false, false, false, 155)) && CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isRecursive", [], "method", false, false, false, 155))) {
                    // line 156
                    yield "                                    <input type=\"hidden\" name=\"is_recursive\" value=\"1\" />
               ";
                }
                // line 159
                yield "               <span class=\"form-check-label mb-0 mx-2\">
                  ";
                // line 160
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(__("Child entities"), "html", null, true);
                yield "
                  <i class=\"fas fa-info ms-1\" title=\"";
                // line 161
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["comment"] ?? null), "html", null, true);
                yield "\"></i>
               </span>
            </label>
         </span>
      ";
            }
            // line 166
            yield "   ";
        }
        // line 167
        yield "
   ";
        // line 168
        yield Twig\Extension\CoreExtension::include($this->env, $context, "components/form/single-action.html.twig");
        yield "

   ";
        // line 170
        if ( !array_key_exists("inside_main", $context)) {
            // line 171
            yield "      <script>
         \$(\"#header_";
            // line 172
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["rand"] ?? null), "html", null, true);
            yield " input[name='is_recursive']\").on('change', function(e) {
             const asset_form = \$(\"form[name='asset_form']\");
             // If asset form has an is_recursive checkbox, we need set the value to the one in the header
             if (asset_form.length) {
                 const chk = asset_form.find(\"input[name='is_recursive']\");
                 if (chk.length) {
                     chk.val(e.target.checked ? 1 : 0);
                 }
             }
         });
      </script>
   ";
        }
        // line 184
        yield "</div>
";
        return; yield '';
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "components/form/header_content.html.twig";
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
        return array (  445 => 184,  430 => 172,  427 => 171,  425 => 170,  420 => 168,  417 => 167,  414 => 166,  406 => 161,  402 => 160,  399 => 159,  395 => 156,  393 => 155,  386 => 154,  382 => 153,  379 => 152,  375 => 151,  372 => 150,  369 => 149,  366 => 148,  363 => 147,  360 => 146,  357 => 145,  354 => 144,  351 => 143,  348 => 142,  345 => 141,  343 => 140,  340 => 139,  337 => 138,  335 => 137,  331 => 135,  329 => 134,  323 => 131,  317 => 129,  314 => 128,  311 => 127,  309 => 126,  305 => 124,  301 => 122,  292 => 120,  288 => 119,  285 => 118,  283 => 117,  280 => 116,  274 => 113,  268 => 111,  265 => 110,  262 => 109,  256 => 106,  251 => 105,  249 => 104,  246 => 103,  240 => 101,  232 => 99,  230 => 98,  221 => 97,  218 => 96,  212 => 94,  210 => 93,  203 => 92,  197 => 89,  194 => 88,  191 => 87,  189 => 86,  184 => 85,  179 => 83,  175 => 82,  170 => 81,  168 => 80,  162 => 79,  158 => 78,  155 => 77,  149 => 74,  145 => 73,  141 => 72,  134 => 71,  132 => 70,  129 => 69,  125 => 67,  122 => 66,  119 => 65,  115 => 63,  113 => 62,  110 => 61,  108 => 60,  106 => 59,  101 => 58,  99 => 57,  97 => 56,  94 => 55,  90 => 53,  88 => 52,  85 => 51,  82 => 50,  80 => 49,  77 => 48,  74 => 47,  71 => 46,  68 => 45,  65 => 44,  62 => 43,  60 => 42,  58 => 41,  55 => 40,  53 => 39,  51 => 38,  49 => 37,  47 => 36,  45 => 35,  43 => 34,  41 => 33,  38 => 32,);
    }

    public function getSourceContext()
    {
        return new Source("", "components/form/header_content.html.twig", "D:\\laragon\\www\\glpi\\templates\\components\\form\\header_content.html.twig");
    }
}
