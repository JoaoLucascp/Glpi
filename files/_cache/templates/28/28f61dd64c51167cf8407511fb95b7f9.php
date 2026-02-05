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

/* pages/management/softwarelicense.html.twig */
class __TwigTemplate_ca932dd004ecd219de179a2e89adf4fd extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->blocks = [
            'form_fields' => [$this, 'block_form_fields'],
            'more_fields' => [$this, 'block_more_fields'],
        ];
    }

    protected function doGetParent(array $context)
    {
        // line 33
        return "generic_show_form.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 34
        $macros["fields"] = $this->macros["fields"] = $this->loadTemplate("components/form/fields_macros.html.twig", "pages/management/softwarelicense.html.twig", 34)->unwrap();
        // line 35
        $context["params"] = (($context["params"]) ?? ([]));
        // line 33
        $this->parent = $this->loadTemplate("generic_show_form.html.twig", "pages/management/softwarelicense.html.twig", 33);
        yield from $this->parent->unwrap()->yield($context, array_merge($this->blocks, $blocks));
    }

    // line 37
    public function block_form_fields($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 38
        yield "

   ";
        // line 40
        if (((($__internal_compile_0 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, false, false, 40)) && is_array($__internal_compile_0) || $__internal_compile_0 instanceof ArrayAccess ? ($__internal_compile_0["id"] ?? null) : null) > 0)) {
            // line 41
            yield "
      <input type=\"hidden\" name=\"softwares_id\" value=\"";
            // line 42
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((($__internal_compile_1 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, false, false, 42)) && is_array($__internal_compile_1) || $__internal_compile_1 instanceof ArrayAccess ? ($__internal_compile_1["softwares_id"] ?? null) : null), "html", null, true);
            yield "\" />

      ";
            // line 44
            $context["software_link"] = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
                // line 45
                yield "         <a href=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Glpi\Application\View\Extension\PhpExtension']->call("Software::getFormURLWithID", [(($__internal_compile_2 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, false, false, 45)) && is_array($__internal_compile_2) || $__internal_compile_2 instanceof ArrayAccess ? ($__internal_compile_2["softwares_id"] ?? null) : null)]), "html", null, true);
                yield "\">
         ";
                // line 46
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Glpi\Application\View\Extension\PhpExtension']->call("Dropdown::getDropdownName", ["glpi_softwares", (($__internal_compile_3 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, false, false, 46)) && is_array($__internal_compile_3) || $__internal_compile_3 instanceof ArrayAccess ? ($__internal_compile_3["softwares_id"] ?? null) : null)]), "html", null, true);
                yield " </a>
      ";
                return; yield '';
            })())) ? '' : new Markup($tmp, $this->env->getCharset());
            // line 48
            yield "
      ";
            // line 49
            yield CoreExtension::callMacro($macros["fields"], "macro_field", ["software",             // line 51
($context["software_link"] ?? null), _n("Software", "Software", Session::getPluralNumber()), ["width" => "100%", "height" => "100%", "input_class" => "d-flex col-xxl-7 field-container align-items-center"]], 49, $context, $this->getSourceContext());
            // line 58
            yield "

   ";
        } else {
            // line 61
            yield "
      ";
            // line 62
            yield CoreExtension::callMacro($macros["fields"], "macro_dropdownField", ["Software", "softwares_id", (($__internal_compile_4 = CoreExtension::getAttribute($this->env, $this->source,             // line 65
($context["item"] ?? null), "fields", [], "any", false, false, false, 65)) && is_array($__internal_compile_4) || $__internal_compile_4 instanceof ArrayAccess ? ($__internal_compile_4["softwares_id"] ?? null) : null), $this->extensions['Glpi\Application\View\Extension\ItemtypeExtension']->getItemtypeName("Software"), ["entity" => (($__internal_compile_5 = CoreExtension::getAttribute($this->env, $this->source,             // line 68
($context["item"] ?? null), "fields", [], "any", false, false, false, 68)) && is_array($__internal_compile_5) || $__internal_compile_5 instanceof ArrayAccess ? ($__internal_compile_5["entities_id"] ?? null) : null), "condition" => ["is_template" => 0, "is_deleted" => 0], "on_change" => "this.form.submit()"]], 62, $context, $this->getSourceContext());
            // line 75
            yield "

   ";
        }
        // line 78
        yield "


   ";
        // line 81
        yield CoreExtension::callMacro($macros["fields"], "macro_nullField", [], 81, $context, $this->getSourceContext());
        yield "

   ";
        // line 83
        yield from $this->yieldParentBlock("form_fields", $context, $blocks);
        yield "
";
        return; yield '';
    }

    // line 86
    public function block_more_fields($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 87
        yield "   ";
        $context["field"] = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
            // line 88
            yield "      ";
            $this->extensions['Glpi\Application\View\Extension\PhpExtension']->call("SoftwareVersion::dropdownForOneSoftware", [["name" => "softwareversions_id_use", "softwares_id" => (($__internal_compile_6 = CoreExtension::getAttribute($this->env, $this->source,             // line 90
($context["item"] ?? null), "fields", [], "any", false, false, false, 90)) && is_array($__internal_compile_6) || $__internal_compile_6 instanceof ArrayAccess ? ($__internal_compile_6["softwares_id"] ?? null) : null), "value" => (($__internal_compile_7 = CoreExtension::getAttribute($this->env, $this->source,             // line 91
($context["item"] ?? null), "fields", [], "any", false, false, false, 91)) && is_array($__internal_compile_7) || $__internal_compile_7 instanceof ArrayAccess ? ($__internal_compile_7["softwareversions_id_use"] ?? null) : null), "width" => "100%"]]);
            // line 94
            yield "   ";
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
        // line 95
        yield "   ";
        yield CoreExtension::callMacro($macros["fields"], "macro_field", ["softwareversions_id_use", ($context["field"] ?? null), __("Version in use")], 95, $context, $this->getSourceContext());
        yield "

   ";
        // line 97
        $context["field"] = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
            // line 98
            yield "      ";
            $this->extensions['Glpi\Application\View\Extension\PhpExtension']->call("SoftwareVersion::dropdownForOneSoftware", [["name" => "softwareversions_id_buy", "softwares_id" => (($__internal_compile_8 = CoreExtension::getAttribute($this->env, $this->source,             // line 100
($context["item"] ?? null), "fields", [], "any", false, false, false, 100)) && is_array($__internal_compile_8) || $__internal_compile_8 instanceof ArrayAccess ? ($__internal_compile_8["softwares_id"] ?? null) : null), "value" => (($__internal_compile_9 = CoreExtension::getAttribute($this->env, $this->source,             // line 101
($context["item"] ?? null), "fields", [], "any", false, false, false, 101)) && is_array($__internal_compile_9) || $__internal_compile_9 instanceof ArrayAccess ? ($__internal_compile_9["softwareversions_id_buy"] ?? null) : null), "width" => "100%"]]);
            // line 104
            yield "   ";
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
        // line 105
        yield "   ";
        yield CoreExtension::callMacro($macros["fields"], "macro_field", ["softwareversions_id_buy", ($context["field"] ?? null), __("Purchase version")], 105, $context, $this->getSourceContext());
        yield "

   ";
        // line 107
        $context["validity_msg"] = null;
        // line 108
        yield "   ";
        if ((($context["item_type"] ?? null) == "SoftwareLicense")) {
            // line 109
            yield "      ";
            $context["validity_msg"] = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
                // line 110
                yield "         ";
                if ((($__internal_compile_10 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, false, false, 110)) && is_array($__internal_compile_10) || $__internal_compile_10 instanceof ArrayAccess ? ($__internal_compile_10["is_valid"] ?? null) : null)) {
                    // line 111
                    yield "            <span class=\"green\">";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(_x("adjective", "Valid"), "html", null, true);
                    yield "</span>
         ";
                } elseif (( !(($__internal_compile_11 = CoreExtension::getAttribute($this->env, $this->source,                 // line 112
($context["item"] ?? null), "fields", [], "any", false, false, false, 112)) && is_array($__internal_compile_11) || $__internal_compile_11 instanceof ArrayAccess ? ($__internal_compile_11["is_valid"] ?? null) : null) && (($__internal_compile_12 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, false, false, 112)) && is_array($__internal_compile_12) || $__internal_compile_12 instanceof ArrayAccess ? ($__internal_compile_12["allow_overquota"] ?? null) : null))) {
                    // line 113
                    yield "            <span class=\"green\">";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(_x("adjective", "Valid (Over Quota)"), "html", null, true);
                    yield "</span>
         ";
                } else {
                    // line 115
                    yield "            <span class=\"red\">";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(_x("adjective", "Invalid"), "html", null, true);
                    yield "</span>
         ";
                }
                // line 117
                yield "      ";
                return; yield '';
            })())) ? '' : new Markup($tmp, $this->env->getCharset());
            // line 118
            yield "   ";
        }
        // line 119
        yield "   ";
        yield CoreExtension::callMacro($macros["fields"], "macro_dropdownNumberField", ["number", (($__internal_compile_13 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, false, false, 119)) && is_array($__internal_compile_13) || $__internal_compile_13 instanceof ArrayAccess ? ($__internal_compile_13["number"] ?? null) : null), _x("quantity", "Number"), Twig\Extension\CoreExtension::merge(["min" => 1, "max" => 10000, "step" => 1, "toadd" => ["-1" => __("Unlimited")]], ((CoreExtension::getAttribute($this->env, $this->source,         // line 124
($context["item"] ?? null), "isNewItem", [], "method", false, false, false, 124)) ? ([]) : (["add_field_html" => ($context["validity_msg"] ?? null)])))], 119, $context, $this->getSourceContext());
        yield "

   ";
        // line 126
        yield CoreExtension::callMacro($macros["fields"], "macro_dropdownYesNo", ["allow_overquota", (($__internal_compile_14 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, false, false, 126)) && is_array($__internal_compile_14) || $__internal_compile_14 instanceof ArrayAccess ? ($__internal_compile_14["allow_overquota"] ?? null) : null), __("Allow Over-Quota")], 126, $context, $this->getSourceContext());
        yield "

   ";
        // line 128
        yield CoreExtension::callMacro($macros["fields"], "macro_datetimeField", ["expire", (($__internal_compile_15 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, false, false, 128)) && is_array($__internal_compile_15) || $__internal_compile_15 instanceof ArrayAccess ? ($__internal_compile_15["expire"] ?? null) : null), __("Expiration"), ["helper" => __("On search engine, use \"Expiration contains NULL\" to search licenses with no expiration date")]], 128, $context, $this->getSourceContext());
        // line 130
        yield "

   ";
        // line 132
        if (((($__internal_compile_16 = ($context["params"] ?? null)) && is_array($__internal_compile_16) || $__internal_compile_16 instanceof ArrayAccess ? ($__internal_compile_16["withtemplate"] ?? null) : null) == 1)) {
            // line 133
            yield "      ";
            yield CoreExtension::callMacro($macros["fields"], "macro_hiddenField", ["withtemplate", "1"], 133, $context, $this->getSourceContext());
            yield "
   ";
        }
        return; yield '';
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "pages/management/softwarelicense.html.twig";
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
        return array (  220 => 133,  218 => 132,  214 => 130,  212 => 128,  207 => 126,  202 => 124,  200 => 119,  197 => 118,  193 => 117,  187 => 115,  181 => 113,  179 => 112,  174 => 111,  171 => 110,  168 => 109,  165 => 108,  163 => 107,  157 => 105,  153 => 104,  151 => 101,  150 => 100,  148 => 98,  146 => 97,  140 => 95,  136 => 94,  134 => 91,  133 => 90,  131 => 88,  128 => 87,  124 => 86,  117 => 83,  112 => 81,  107 => 78,  102 => 75,  100 => 68,  99 => 65,  98 => 62,  95 => 61,  90 => 58,  88 => 51,  87 => 49,  84 => 48,  78 => 46,  73 => 45,  71 => 44,  66 => 42,  63 => 41,  61 => 40,  57 => 38,  53 => 37,  48 => 33,  46 => 35,  44 => 34,  37 => 33,);
    }

    public function getSourceContext()
    {
        return new Source("", "pages/management/softwarelicense.html.twig", "D:\\laragon\\www\\glpi\\templates\\pages\\management\\softwarelicense.html.twig");
    }
}
