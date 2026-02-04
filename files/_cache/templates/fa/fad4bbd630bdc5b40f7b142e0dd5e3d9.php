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

/* components/form/header.html.twig */
class __TwigTemplate_ad62bb02552e0fddef72387c996c32ea extends Template
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
        $context["target"] = (((CoreExtension::getAttribute($this->env, $this->source, ($context["params"] ?? null), "target", [], "array", true, true, false, 33) &&  !(null === (($__internal_compile_0 = ($context["params"] ?? null)) && is_array($__internal_compile_0) || $__internal_compile_0 instanceof ArrayAccess ? ($__internal_compile_0["target"] ?? null) : null)))) ? ((($__internal_compile_1 = ($context["params"] ?? null)) && is_array($__internal_compile_1) || $__internal_compile_1 instanceof ArrayAccess ? ($__internal_compile_1["target"] ?? null) : null)) : (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "getFormURL", [], "method", false, false, false, 33)));
        // line 34
        $context["withtemplate"] = (((CoreExtension::getAttribute($this->env, $this->source, ($context["params"] ?? null), "withtemplate", [], "array", true, true, false, 34) &&  !(null === (($__internal_compile_2 = ($context["params"] ?? null)) && is_array($__internal_compile_2) || $__internal_compile_2 instanceof ArrayAccess ? ($__internal_compile_2["withtemplate"] ?? null) : null)))) ? ((($__internal_compile_3 = ($context["params"] ?? null)) && is_array($__internal_compile_3) || $__internal_compile_3 instanceof ArrayAccess ? ($__internal_compile_3["withtemplate"] ?? null) : null)) : (""));
        // line 35
        $context["rand"] = Twig\Extension\CoreExtension::random($this->env->getCharset());
        // line 36
        $context["nametype"] = (((CoreExtension::getAttribute($this->env, $this->source, ($context["params"] ?? null), "formtitle", [], "array", true, true, false, 36) &&  !(null === (($__internal_compile_4 = ($context["params"] ?? null)) && is_array($__internal_compile_4) || $__internal_compile_4 instanceof ArrayAccess ? ($__internal_compile_4["formtitle"] ?? null) : null)))) ? ((($__internal_compile_5 = ($context["params"] ?? null)) && is_array($__internal_compile_5) || $__internal_compile_5 instanceof ArrayAccess ? ($__internal_compile_5["formtitle"] ?? null) : null)) : (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "getTypeName", [1], "method", false, false, false, 36)));
        // line 37
        $context["no_id"] = (((CoreExtension::getAttribute($this->env, $this->source, ($context["params"] ?? null), "noid", [], "array", true, true, false, 37) &&  !(null === (($__internal_compile_6 = ($context["params"] ?? null)) && is_array($__internal_compile_6) || $__internal_compile_6 instanceof ArrayAccess ? ($__internal_compile_6["noid"] ?? null) : null)))) ? ((($__internal_compile_7 = ($context["params"] ?? null)) && is_array($__internal_compile_7) || $__internal_compile_7 instanceof ArrayAccess ? ($__internal_compile_7["noid"] ?? null) : null)) : (false));
        // line 38
        $context["formoptions"] = (((CoreExtension::getAttribute($this->env, $this->source, ($context["params"] ?? null), "formoptions", [], "array", true, true, false, 38) &&  !(null === (($__internal_compile_8 = ($context["params"] ?? null)) && is_array($__internal_compile_8) || $__internal_compile_8 instanceof ArrayAccess ? ($__internal_compile_8["formoptions"] ?? null) : null)))) ? ((($__internal_compile_9 = ($context["params"] ?? null)) && is_array($__internal_compile_9) || $__internal_compile_9 instanceof ArrayAccess ? ($__internal_compile_9["formoptions"] ?? null) : null)) : (""));
        // line 39
        yield "
";
        // line 40
        $context["entity_id"] = 0;
        // line 41
        $context["entity_name"] = "";
        // line 42
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isEntityAssign", [], "method", false, false, false, 42)) {
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
                $context["entity_name"] = $this->extensions['Glpi\Application\View\Extension\ItemtypeExtension']->getItemName("Entity", (( !Twig\Extension\CoreExtension::testEmpty(($context["entity_id"] ?? null))) ? (($context["entity_id"] ?? null)) : (null)));
                // line 51
                yield "   ";
            }
        }
        // line 53
        yield "
";
        // line 54
        $context["no_header"] = ((array_key_exists("no_header", $context)) ? (Twig\Extension\CoreExtension::default(($context["no_header"] ?? null), false)) : (false));
        // line 55
        $context["open_form"] = ((($context["no_header"] ?? null) || CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isNewID", [(($__internal_compile_13 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, false, false, 55)) && is_array($__internal_compile_13) || $__internal_compile_13 instanceof ArrayAccess ? ($__internal_compile_13["id"] ?? null) : null)], "method", false, false, false, 55)) ||  !array_key_exists("in_twig", $context));
        // line 59
        $context["include_header_content"] = ((($context["no_header"] ?? null) == false) && ((array_key_exists("in_twig", $context) || ((CoreExtension::getAttribute($this->env, $this->source, ($context["_get"] ?? null), "_in_modal", [], "any", true, true, false, 59)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, ($context["_get"] ?? null), "_in_modal", [], "any", false, false, false, 59), false)) : (false))) || ( !array_key_exists("in_twig", $context) && CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isNewID", [(($__internal_compile_14 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, false, false, 59)) && is_array($__internal_compile_14) || $__internal_compile_14 instanceof ArrayAccess ? ($__internal_compile_14["id"] ?? null) : null)], "method", false, false, false, 59))));
        // line 60
        yield "
";
        // line 61
        if ((($context["open_form"] ?? null) && CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "canEdit", [(($__internal_compile_15 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, false, false, 61)) && is_array($__internal_compile_15) || $__internal_compile_15 instanceof ArrayAccess ? ($__internal_compile_15["id"] ?? null) : null)], "method", false, false, false, 61))) {
            // line 62
            yield "<form name=\"asset_form\" method=\"post\" action=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["target"] ?? null), "html", null, true);
            yield "\" ";
            yield ($context["formoptions"] ?? null);
            yield " enctype=\"multipart/form-data\" data-submit-once>
   ";
            // line 63
            if (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isField", ["entities_id"], "method", false, false, false, 63)) {
                // line 64
                yield "       <input type=\"hidden\" name=\"entities_id\" value=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["entity_id"] ?? null), "html", null, true);
                yield "\" />
   ";
            }
            // line 66
            yield "   ";
            if ((CoreExtension::getAttribute($this->env, $this->source, ($context["_request"] ?? null), "_in_modal", [], "array", true, true, false, 66) && ((($__internal_compile_16 = ($context["_request"] ?? null)) && is_array($__internal_compile_16) || $__internal_compile_16 instanceof ArrayAccess ? ($__internal_compile_16["_in_modal"] ?? null) : null) == "1"))) {
                // line 67
                yield "      <input type=\"hidden\" name=\"_in_modal\" value=\"1\"/>
   ";
            }
        }
        // line 70
        yield "   <div id=\"mainformtable\">
      ";
        // line 71
        if (($context["include_header_content"] ?? null)) {
            // line 72
            yield "         ";
            yield Twig\Extension\CoreExtension::include($this->env, $context, "components/form/header_content.html.twig", ["inside_main" => true]);
            yield "
      ";
        } else {
            // line 74
            yield "         <input type=\"hidden\" name=\"is_recursive\" value=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, true, false, 74), "is_recursive", [], "array", true, true, false, 74)) ? (Twig\Extension\CoreExtension::default((($__internal_compile_17 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, true, false, 74)) && is_array($__internal_compile_17) || $__internal_compile_17 instanceof ArrayAccess ? ($__internal_compile_17["is_recursive"] ?? null) : null), 1)) : (1)), "html", null, true);
            yield "\" />
      ";
        }
        // line 76
        yield "
      ";
        // line 77
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Glpi\Application\View\Extension\PluginExtension']->callPluginHook(Twig\Extension\CoreExtension::constant("Glpi\\Plugin\\Hooks::PRE_ITEM_FORM"), ["item" => ($context["item"] ?? null), "options" => ($context["params"] ?? null)]), "html", null, true);
        yield "

      ";
        // line 80
        yield "      ";
        if ((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["app"] ?? null), "request", [], "any", false, false, false, 80), "request", ["in_modal"], "method", false, false, false, 80) == true)) {
            // line 81
            yield "      <input type=\"hidden\" name=\"_no_message_link\" value=\"1\" />
      ";
        }
        return; yield '';
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "components/form/header.html.twig";
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
        return array (  154 => 81,  151 => 80,  146 => 77,  143 => 76,  137 => 74,  131 => 72,  129 => 71,  126 => 70,  121 => 67,  118 => 66,  112 => 64,  110 => 63,  103 => 62,  101 => 61,  98 => 60,  96 => 59,  94 => 55,  92 => 54,  89 => 53,  85 => 51,  82 => 50,  80 => 49,  77 => 48,  74 => 47,  71 => 46,  68 => 45,  65 => 44,  62 => 43,  60 => 42,  58 => 41,  56 => 40,  53 => 39,  51 => 38,  49 => 37,  47 => 36,  45 => 35,  43 => 34,  41 => 33,  38 => 32,);
    }

    public function getSourceContext()
    {
        return new Source("", "components/form/header.html.twig", "D:\\laragon\\www\\glpi\\templates\\components\\form\\header.html.twig");
    }
}
