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

/* dropdown_form.html.twig */
class __TwigTemplate_18c99639b98b31af0781dc4812d46df9 extends Template
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
        $macros["fields"] = $this->macros["fields"] = $this->loadTemplate("components/form/fields_macros.html.twig", "dropdown_form.html.twig", 33)->unwrap();
        // line 34
        yield "
<div class=\"asset\">
    ";
        // line 36
        yield Twig\Extension\CoreExtension::include($this->env, $context, "components/form/header.html.twig");
        yield "
    ";
        // line 37
        $context["rand"] = Twig\Extension\CoreExtension::random($this->env->getCharset());
        // line 38
        yield "    ";
        $context["base_fields_params"] = (($context["fields_params"]) ?? ([]));
        // line 39
        yield "    ";
        $context["params"] = (($context["params"]) ?? ([]));
        // line 40
        yield "    ";
        $context["target"] = (((CoreExtension::getAttribute($this->env, $this->source, ($context["params"] ?? null), "target", [], "array", true, true, false, 40) &&  !(null === (($__internal_compile_0 = ($context["params"] ?? null)) && is_array($__internal_compile_0) || $__internal_compile_0 instanceof ArrayAccess ? ($__internal_compile_0["target"] ?? null) : null)))) ? ((($__internal_compile_1 = ($context["params"] ?? null)) && is_array($__internal_compile_1) || $__internal_compile_1 instanceof ArrayAccess ? ($__internal_compile_1["target"] ?? null) : null)) : (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "getFormURL", [], "method", false, false, false, 40)));
        // line 41
        yield "    ";
        $context["withtemplate"] = (((CoreExtension::getAttribute($this->env, $this->source, ($context["params"] ?? null), "withtemplate", [], "array", true, true, false, 41) &&  !(null === (($__internal_compile_2 = ($context["params"] ?? null)) && is_array($__internal_compile_2) || $__internal_compile_2 instanceof ArrayAccess ? ($__internal_compile_2["withtemplate"] ?? null) : null)))) ? ((($__internal_compile_3 = ($context["params"] ?? null)) && is_array($__internal_compile_3) || $__internal_compile_3 instanceof ArrayAccess ? ($__internal_compile_3["withtemplate"] ?? null) : null)) : (""));
        // line 42
        yield "
    <div class=\"card-body row\">
        ";
        // line 44
        $context["picture_fields"] = ["picture_front", "picture_rear", "pictures"];
        // line 45
        yield "        ";
        $context["has_picture_field"] = false;
        // line 46
        yield "
        ";
        // line 48
        yield "        ";
        yield CoreExtension::callMacro($macros["fields"], "macro_autoNameField", [((CoreExtension::getAttribute($this->env, $this->source,         // line 49
($context["item"] ?? null), "isField", ["designation"], "method", false, false, false, 49)) ? ("designation") : ("name")),         // line 50
($context["item"] ?? null), __("Name"),         // line 52
($context["withtemplate"] ?? null)], 48, $context, $this->getSourceContext());
        // line 53
        yield "

        ";
        // line 55
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isField", ["comment"], "method", false, false, false, 55)) {
            // line 56
            yield "            ";
            yield CoreExtension::callMacro($macros["fields"], "macro_textareaField", ["comment", (($__internal_compile_4 = CoreExtension::getAttribute($this->env, $this->source,             // line 58
($context["item"] ?? null), "fields", [], "any", false, false, false, 58)) && is_array($__internal_compile_4) || $__internal_compile_4 instanceof ArrayAccess ? ($__internal_compile_4["comment"] ?? null) : null), _n("Comment", "Comments", Session::getPluralNumber())], 56, $context, $this->getSourceContext());
            // line 60
            yield "
        ";
        }
        // line 62
        yield "
        ";
        // line 64
        yield "        ";
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(($context["additional_fields"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["field"]) {
            // line 65
            yield "            ";
            $context["fields_params"] = (((CoreExtension::getAttribute($this->env, $this->source, $context["field"], "form_params", [], "array", true, true, false, 65) &&  !(null === (($__internal_compile_5 = $context["field"]) && is_array($__internal_compile_5) || $__internal_compile_5 instanceof ArrayAccess ? ($__internal_compile_5["form_params"] ?? null) : null)))) ? ((($__internal_compile_6 = $context["field"]) && is_array($__internal_compile_6) || $__internal_compile_6 instanceof ArrayAccess ? ($__internal_compile_6["form_params"] ?? null) : null)) : (($context["base_fields_params"] ?? null)));
            // line 66
            yield "            ";
            $context["type"] = ((CoreExtension::getAttribute($this->env, $this->source, $context["field"], "type", [], "array", true, true, false, 66)) ? (Twig\Extension\CoreExtension::default((($__internal_compile_7 = $context["field"]) && is_array($__internal_compile_7) || $__internal_compile_7 instanceof ArrayAccess ? ($__internal_compile_7["type"] ?? null) : null), "")) : (""));
            // line 67
            yield "            ";
            $context["show_field"] = true;
            // line 68
            yield "            ";
            if ((((($__internal_compile_8 = $context["field"]) && is_array($__internal_compile_8) || $__internal_compile_8 instanceof ArrayAccess ? ($__internal_compile_8["name"] ?? null) : null) == "entities_id") && ((($context["type"] ?? null) != "parent") || ((($__internal_compile_9 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, false, false, 68)) && is_array($__internal_compile_9) || $__internal_compile_9 instanceof ArrayAccess ? ($__internal_compile_9["id"] ?? null) : null) == 0)))) {
                // line 69
                yield "                ";
                // line 70
                yield "                ";
                $context["show_field"] = false;
                // line 71
                yield "            ";
            }
            // line 72
            yield "            ";
            if (CoreExtension::inFilter((($__internal_compile_10 = $context["field"]) && is_array($__internal_compile_10) || $__internal_compile_10 instanceof ArrayAccess ? ($__internal_compile_10["name"] ?? null) : null), ($context["picture_fields"] ?? null))) {
                // line 73
                yield "                ";
                $context["show_field"] = false;
                // line 74
                yield "            ";
            }
            // line 75
            yield "            ";
            if (($context["show_field"] ?? null)) {
                // line 76
                yield "
                ";
                // line 77
                if (((($__internal_compile_11 = $context["field"]) && is_array($__internal_compile_11) || $__internal_compile_11 instanceof ArrayAccess ? ($__internal_compile_11["name"] ?? null) : null) == "header")) {
                    // line 78
                    yield "                    ";
                    yield CoreExtension::callMacro($macros["fields"], "macro_largeTitle", [(($__internal_compile_12 = $context["field"]) && is_array($__internal_compile_12) || $__internal_compile_12 instanceof ArrayAccess ? ($__internal_compile_12["label"] ?? null) : null)], 78, $context, $this->getSourceContext());
                    yield "
                ";
                } elseif (((($__internal_compile_13 =                 // line 79
$context["field"]) && is_array($__internal_compile_13) || $__internal_compile_13 instanceof ArrayAccess ? ($__internal_compile_13["name"] ?? null) : null) == "content")) {
                    // line 80
                    yield "                    ";
                    yield CoreExtension::callMacro($macros["fields"], "macro_textareaField", ["content", (($__internal_compile_14 = CoreExtension::getAttribute($this->env, $this->source,                     // line 82
($context["item"] ?? null), "fields", [], "any", false, false, false, 82)) && is_array($__internal_compile_14) || $__internal_compile_14 instanceof ArrayAccess ? ($__internal_compile_14["content"] ?? null) : null), (($__internal_compile_15 =                     // line 83
$context["field"]) && is_array($__internal_compile_15) || $__internal_compile_15 instanceof ArrayAccess ? ($__internal_compile_15["label"] ?? null) : null), ["full_width" => true, "full_width_adapt_column" => false, "is_horizontal" => false, "enable_richtext" => ((CoreExtension::getAttribute($this->env, $this->source,                     // line 88
$context["field"], "enable_richtext", [], "array", true, true, false, 88)) ? ((($__internal_compile_16 = $context["field"]) && is_array($__internal_compile_16) || $__internal_compile_16 instanceof ArrayAccess ? ($__internal_compile_16["enable_richtext"] ?? null) : null)) : (true))]], 80, $context, $this->getSourceContext());
                    // line 90
                    yield "
                ";
                } elseif ((                // line 91
($context["type"] ?? null) == "UserDropdown")) {
                    // line 92
                    yield "                    ";
                    yield CoreExtension::callMacro($macros["fields"], "macro_dropdownField", ["User", (($__internal_compile_17 =                     // line 94
$context["field"]) && is_array($__internal_compile_17) || $__internal_compile_17 instanceof ArrayAccess ? ($__internal_compile_17["name"] ?? null) : null), (($__internal_compile_18 = CoreExtension::getAttribute($this->env, $this->source,                     // line 95
($context["item"] ?? null), "fields", [], "any", false, false, false, 95)) && is_array($__internal_compile_18) || $__internal_compile_18 instanceof ArrayAccess ? ($__internal_compile_18[(($__internal_compile_19 = $context["field"]) && is_array($__internal_compile_19) || $__internal_compile_19 instanceof ArrayAccess ? ($__internal_compile_19["name"] ?? null) : null)] ?? null) : null), (($__internal_compile_20 =                     // line 96
$context["field"]) && is_array($__internal_compile_20) || $__internal_compile_20 instanceof ArrayAccess ? ($__internal_compile_20["label"] ?? null) : null), ["entity" => (($__internal_compile_21 = CoreExtension::getAttribute($this->env, $this->source,                     // line 98
($context["item"] ?? null), "fields", [], "any", false, false, false, 98)) && is_array($__internal_compile_21) || $__internal_compile_21 instanceof ArrayAccess ? ($__internal_compile_21["entities_id"] ?? null) : null), "right" => ((CoreExtension::getAttribute($this->env, $this->source,                     // line 99
$context["field"], "right", [], "array", true, true, false, 99)) ? (Twig\Extension\CoreExtension::default((($__internal_compile_22 = $context["field"]) && is_array($__internal_compile_22) || $__internal_compile_22 instanceof ArrayAccess ? ($__internal_compile_22["right"] ?? null) : null), "interface")) : ("interface")), "rand" =>                     // line 100
($context["rand"] ?? null)]], 92, $context, $this->getSourceContext());
                    // line 102
                    yield "
                ";
                } elseif ((                // line 103
($context["type"] ?? null) == "dropdownValue")) {
                    // line 104
                    yield "                    ";
                    $context["dropdown_params"] = ["entity" => (($__internal_compile_23 = CoreExtension::getAttribute($this->env, $this->source,                     // line 105
($context["item"] ?? null), "fields", [], "any", false, false, false, 105)) && is_array($__internal_compile_23) || $__internal_compile_23 instanceof ArrayAccess ? ($__internal_compile_23["entities_id"] ?? null) : null)];
                    // line 107
                    yield "                    ";
                    if (CoreExtension::getAttribute($this->env, $this->source, $context["field"], "condition", [], "array", true, true, false, 107)) {
                        // line 108
                        yield "                        ";
                        $context["dropdown_params"] = Twig\Extension\CoreExtension::merge(($context["dropdown_params"] ?? null), ["condition" => (($__internal_compile_24 = $context["field"]) && is_array($__internal_compile_24) || $__internal_compile_24 instanceof ArrayAccess ? ($__internal_compile_24["condition"] ?? null) : null)]);
                        // line 109
                        yield "                    ";
                    }
                    // line 110
                    yield "                    ";
                    $context["dropdown_itemtype"] = $this->extensions['Glpi\Application\View\Extension\PhpExtension']->call("getItemtypeForForeignKeyField", [(($__internal_compile_25 = $context["field"]) && is_array($__internal_compile_25) || $__internal_compile_25 instanceof ArrayAccess ? ($__internal_compile_25["name"] ?? null) : null)]);
                    // line 111
                    yield "                    ";
                    yield CoreExtension::callMacro($macros["fields"], "macro_dropdownField", [($context["dropdown_itemtype"] ?? null), (($__internal_compile_26 = $context["field"]) && is_array($__internal_compile_26) || $__internal_compile_26 instanceof ArrayAccess ? ($__internal_compile_26["name"] ?? null) : null), (($__internal_compile_27 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, false, false, 111)) && is_array($__internal_compile_27) || $__internal_compile_27 instanceof ArrayAccess ? ($__internal_compile_27[(($__internal_compile_28 = $context["field"]) && is_array($__internal_compile_28) || $__internal_compile_28 instanceof ArrayAccess ? ($__internal_compile_28["name"] ?? null) : null)] ?? null) : null), (($__internal_compile_29 = $context["field"]) && is_array($__internal_compile_29) || $__internal_compile_29 instanceof ArrayAccess ? ($__internal_compile_29["label"] ?? null) : null), ($context["dropdown_params"] ?? null)], 111, $context, $this->getSourceContext());
                    yield "
                ";
                } elseif ((                // line 112
($context["type"] ?? null) == "text")) {
                    // line 113
                    yield "                    ";
                    yield CoreExtension::callMacro($macros["fields"], "macro_autoNameField", [(($__internal_compile_30 = $context["field"]) && is_array($__internal_compile_30) || $__internal_compile_30 instanceof ArrayAccess ? ($__internal_compile_30["name"] ?? null) : null), ($context["item"] ?? null), (($__internal_compile_31 = $context["field"]) && is_array($__internal_compile_31) || $__internal_compile_31 instanceof ArrayAccess ? ($__internal_compile_31["label"] ?? null) : null), ($context["withtemplate"] ?? null), ($context["fields_params"] ?? null)], 113, $context, $this->getSourceContext());
                    yield "
                ";
                } elseif ((                // line 114
($context["type"] ?? null) == "textarea")) {
                    // line 115
                    yield "                    ";
                    yield CoreExtension::callMacro($macros["fields"], "macro_textareaField", [(($__internal_compile_32 = $context["field"]) && is_array($__internal_compile_32) || $__internal_compile_32 instanceof ArrayAccess ? ($__internal_compile_32["name"] ?? null) : null), (($__internal_compile_33 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, false, false, 115)) && is_array($__internal_compile_33) || $__internal_compile_33 instanceof ArrayAccess ? ($__internal_compile_33[(($__internal_compile_34 = $context["field"]) && is_array($__internal_compile_34) || $__internal_compile_34 instanceof ArrayAccess ? ($__internal_compile_34["name"] ?? null) : null)] ?? null) : null), (($__internal_compile_35 = $context["field"]) && is_array($__internal_compile_35) || $__internal_compile_35 instanceof ArrayAccess ? ($__internal_compile_35["label"] ?? null) : null), ($context["fields_params"] ?? null)], 115, $context, $this->getSourceContext());
                    yield "
                ";
                } elseif ((                // line 116
($context["type"] ?? null) == "integer")) {
                    // line 117
                    yield "                    ";
                    $context["fields_params"] = ["value" => (($__internal_compile_36 = CoreExtension::getAttribute($this->env, $this->source,                     // line 118
($context["item"] ?? null), "fields", [], "any", false, false, false, 118)) && is_array($__internal_compile_36) || $__internal_compile_36 instanceof ArrayAccess ? ($__internal_compile_36[(($__internal_compile_37 = $context["field"]) && is_array($__internal_compile_37) || $__internal_compile_37 instanceof ArrayAccess ? ($__internal_compile_37["name"] ?? null) : null)] ?? null) : null)];
                    // line 120
                    yield "                    ";
                    if (CoreExtension::getAttribute($this->env, $this->source, $context["field"], "min", [], "array", true, true, false, 120)) {
                        // line 121
                        yield "                        ";
                        $context["fields_params"] = Twig\Extension\CoreExtension::merge(($context["fields_params"] ?? null), ["min" => (($__internal_compile_38 = $context["field"]) && is_array($__internal_compile_38) || $__internal_compile_38 instanceof ArrayAccess ? ($__internal_compile_38["min"] ?? null) : null)]);
                        // line 122
                        yield "                    ";
                    }
                    // line 123
                    yield "                    ";
                    if (CoreExtension::getAttribute($this->env, $this->source, $context["field"], "step", [], "array", true, true, false, 123)) {
                        // line 124
                        yield "                        ";
                        $context["fields_params"] = Twig\Extension\CoreExtension::merge(($context["fields_params"] ?? null), ["step" => (($__internal_compile_39 = $context["field"]) && is_array($__internal_compile_39) || $__internal_compile_39 instanceof ArrayAccess ? ($__internal_compile_39["step"] ?? null) : null)]);
                        // line 125
                        yield "                    ";
                    }
                    // line 126
                    yield "                    ";
                    if (CoreExtension::getAttribute($this->env, $this->source, $context["field"], "max", [], "array", true, true, false, 126)) {
                        // line 127
                        yield "                        ";
                        $context["fields_params"] = Twig\Extension\CoreExtension::merge(($context["fields_params"] ?? null), ["max" => (($__internal_compile_40 = $context["field"]) && is_array($__internal_compile_40) || $__internal_compile_40 instanceof ArrayAccess ? ($__internal_compile_40["max"] ?? null) : null)]);
                        // line 128
                        yield "                    ";
                    }
                    // line 129
                    yield "
                    ";
                    // line 130
                    $context["fields_params"] = Twig\Extension\CoreExtension::merge(($context["fields_params"] ?? null), ["type" => "number"]);
                    // line 131
                    yield "                    ";
                    yield CoreExtension::callMacro($macros["fields"], "macro_numberField", [(($__internal_compile_41 = $context["field"]) && is_array($__internal_compile_41) || $__internal_compile_41 instanceof ArrayAccess ? ($__internal_compile_41["name"] ?? null) : null), (($__internal_compile_42 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, false, false, 131)) && is_array($__internal_compile_42) || $__internal_compile_42 instanceof ArrayAccess ? ($__internal_compile_42[(($__internal_compile_43 = $context["field"]) && is_array($__internal_compile_43) || $__internal_compile_43 instanceof ArrayAccess ? ($__internal_compile_43["name"] ?? null) : null)] ?? null) : null), (($__internal_compile_44 = $context["field"]) && is_array($__internal_compile_44) || $__internal_compile_44 instanceof ArrayAccess ? ($__internal_compile_44["label"] ?? null) : null), ($context["fields_params"] ?? null)], 131, $context, $this->getSourceContext());
                    yield "
                ";
                } elseif ((                // line 132
($context["type"] ?? null) == "timestamp")) {
                    // line 133
                    yield "                    ";
                    $context["fields_params"] = ["value" => (($__internal_compile_45 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, false, false, 133)) && is_array($__internal_compile_45) || $__internal_compile_45 instanceof ArrayAccess ? ($__internal_compile_45[(($__internal_compile_46 = $context["field"]) && is_array($__internal_compile_46) || $__internal_compile_46 instanceof ArrayAccess ? ($__internal_compile_46["name"] ?? null) : null)] ?? null) : null)];
                    // line 134
                    yield "                    ";
                    if (CoreExtension::getAttribute($this->env, $this->source, $context["field"], "min", [], "array", true, true, false, 134)) {
                        // line 135
                        yield "                        ";
                        $context["fields_params"] = Twig\Extension\CoreExtension::merge(($context["fields_params"] ?? null), ["min" => (($__internal_compile_47 = $context["field"]) && is_array($__internal_compile_47) || $__internal_compile_47 instanceof ArrayAccess ? ($__internal_compile_47["min"] ?? null) : null)]);
                        // line 136
                        yield "                    ";
                    }
                    // line 137
                    yield "                    ";
                    if (CoreExtension::getAttribute($this->env, $this->source, $context["field"], "step", [], "array", true, true, false, 137)) {
                        // line 138
                        yield "                        ";
                        $context["fields_params"] = Twig\Extension\CoreExtension::merge(($context["fields_params"] ?? null), ["step" => (($__internal_compile_48 = $context["field"]) && is_array($__internal_compile_48) || $__internal_compile_48 instanceof ArrayAccess ? ($__internal_compile_48["step"] ?? null) : null)]);
                        // line 139
                        yield "                    ";
                    }
                    // line 140
                    yield "                    ";
                    if (CoreExtension::getAttribute($this->env, $this->source, $context["field"], "max", [], "array", true, true, false, 140)) {
                        // line 141
                        yield "                        ";
                        $context["fields_params"] = Twig\Extension\CoreExtension::merge(($context["fields_params"] ?? null), ["max" => (($__internal_compile_49 = $context["field"]) && is_array($__internal_compile_49) || $__internal_compile_49 instanceof ArrayAccess ? ($__internal_compile_49["max"] ?? null) : null)]);
                        // line 142
                        yield "                    ";
                    }
                    // line 143
                    yield "                    ";
                    yield CoreExtension::callMacro($macros["fields"], "macro_dropdownTimestampField", [(($__internal_compile_50 = $context["field"]) && is_array($__internal_compile_50) || $__internal_compile_50 instanceof ArrayAccess ? ($__internal_compile_50["name"] ?? null) : null), (($__internal_compile_51 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, false, false, 143)) && is_array($__internal_compile_51) || $__internal_compile_51 instanceof ArrayAccess ? ($__internal_compile_51[(($__internal_compile_52 = $context["field"]) && is_array($__internal_compile_52) || $__internal_compile_52 instanceof ArrayAccess ? ($__internal_compile_52["name"] ?? null) : null)] ?? null) : null), (($__internal_compile_53 = $context["field"]) && is_array($__internal_compile_53) || $__internal_compile_53 instanceof ArrayAccess ? ($__internal_compile_53["label"] ?? null) : null), ($context["fields_params"] ?? null)], 143, $context, $this->getSourceContext());
                    yield "
                ";
                } elseif ((                // line 144
($context["type"] ?? null) == "parent")) {
                    // line 145
                    yield "                    ";
                    $context["restrict"] = ((((($__internal_compile_54 = $context["field"]) && is_array($__internal_compile_54) || $__internal_compile_54 instanceof ArrayAccess ? ($__internal_compile_54["name"] ?? null) : null) == "entities_id")) ? ( -1) : (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "getEntityID", [], "method", false, false, false, 145)));
                    // line 146
                    yield "                    ";
                    $context["fields_params"] = Twig\Extension\CoreExtension::merge(($context["fields_params"] ?? null), ["entity" => ($context["restrict"] ?? null)]);
                    // line 147
                    yield "                    ";
                    $context["fields_params"] = Twig\Extension\CoreExtension::merge(($context["fields_params"] ?? null), ["used" => ((((($__internal_compile_55 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, false, false, 147)) && is_array($__internal_compile_55) || $__internal_compile_55 instanceof ArrayAccess ? ($__internal_compile_55["id"] ?? null) : null) > 0)) ? ($this->extensions['Glpi\Application\View\Extension\PhpExtension']->call("getSonsOf", [CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "getTable", [], "method", false, false, false, 147), (($__internal_compile_56 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, false, false, 147)) && is_array($__internal_compile_56) || $__internal_compile_56 instanceof ArrayAccess ? ($__internal_compile_56["id"] ?? null) : null)])) : ([]))]);
                    // line 148
                    yield "                    ";
                    yield CoreExtension::callMacro($macros["fields"], "macro_dropdownField", [($context["item"] ?? null), (($__internal_compile_57 = $context["field"]) && is_array($__internal_compile_57) || $__internal_compile_57 instanceof ArrayAccess ? ($__internal_compile_57["name"] ?? null) : null), (($__internal_compile_58 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, false, false, 148)) && is_array($__internal_compile_58) || $__internal_compile_58 instanceof ArrayAccess ? ($__internal_compile_58[(($__internal_compile_59 = $context["field"]) && is_array($__internal_compile_59) || $__internal_compile_59 instanceof ArrayAccess ? ($__internal_compile_59["name"] ?? null) : null)] ?? null) : null), (($__internal_compile_60 = $context["field"]) && is_array($__internal_compile_60) || $__internal_compile_60 instanceof ArrayAccess ? ($__internal_compile_60["label"] ?? null) : null), ($context["fields_params"] ?? null)], 148, $context, $this->getSourceContext());
                    yield "
                ";
                } elseif ((                // line 149
($context["type"] ?? null) == "icon")) {
                    // line 150
                    yield "                    ";
                    yield CoreExtension::callMacro($macros["fields"], "macro_dropdownIcons", [(($__internal_compile_61 = $context["field"]) && is_array($__internal_compile_61) || $__internal_compile_61 instanceof ArrayAccess ? ($__internal_compile_61["name"] ?? null) : null), (($__internal_compile_62 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, false, false, 150)) && is_array($__internal_compile_62) || $__internal_compile_62 instanceof ArrayAccess ? ($__internal_compile_62[(($__internal_compile_63 = $context["field"]) && is_array($__internal_compile_63) || $__internal_compile_63 instanceof ArrayAccess ? ($__internal_compile_63["name"] ?? null) : null)] ?? null) : null), (($__internal_compile_64 = $context["field"]) && is_array($__internal_compile_64) || $__internal_compile_64 instanceof ArrayAccess ? ($__internal_compile_64["label"] ?? null) : null), ($context["fields_params"] ?? null)], 150, $context, $this->getSourceContext());
                    yield "
                ";
                } elseif ((                // line 151
($context["type"] ?? null) == "bool")) {
                    // line 152
                    yield "                    ";
                    yield CoreExtension::callMacro($macros["fields"], "macro_dropdownYesNo", [(($__internal_compile_65 = $context["field"]) && is_array($__internal_compile_65) || $__internal_compile_65 instanceof ArrayAccess ? ($__internal_compile_65["name"] ?? null) : null), (($__internal_compile_66 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, false, false, 152)) && is_array($__internal_compile_66) || $__internal_compile_66 instanceof ArrayAccess ? ($__internal_compile_66[(($__internal_compile_67 = $context["field"]) && is_array($__internal_compile_67) || $__internal_compile_67 instanceof ArrayAccess ? ($__internal_compile_67["name"] ?? null) : null)] ?? null) : null), (($__internal_compile_68 = $context["field"]) && is_array($__internal_compile_68) || $__internal_compile_68 instanceof ArrayAccess ? ($__internal_compile_68["label"] ?? null) : null), ($context["fields_params"] ?? null)], 152, $context, $this->getSourceContext());
                    yield "
                ";
                } elseif ((                // line 153
($context["type"] ?? null) == "color")) {
                    // line 154
                    yield "                    ";
                    yield CoreExtension::callMacro($macros["fields"], "macro_colorField", [(($__internal_compile_69 = $context["field"]) && is_array($__internal_compile_69) || $__internal_compile_69 instanceof ArrayAccess ? ($__internal_compile_69["name"] ?? null) : null), (($__internal_compile_70 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, false, false, 154)) && is_array($__internal_compile_70) || $__internal_compile_70 instanceof ArrayAccess ? ($__internal_compile_70[(($__internal_compile_71 = $context["field"]) && is_array($__internal_compile_71) || $__internal_compile_71 instanceof ArrayAccess ? ($__internal_compile_71["name"] ?? null) : null)] ?? null) : null), (($__internal_compile_72 = $context["field"]) && is_array($__internal_compile_72) || $__internal_compile_72 instanceof ArrayAccess ? ($__internal_compile_72["label"] ?? null) : null), ($context["fields_params"] ?? null)], 154, $context, $this->getSourceContext());
                    yield "
                ";
                } elseif ((                // line 155
($context["type"] ?? null) == "date")) {
                    // line 156
                    yield "                    ";
                    yield CoreExtension::callMacro($macros["fields"], "macro_dateField", [(($__internal_compile_73 = $context["field"]) && is_array($__internal_compile_73) || $__internal_compile_73 instanceof ArrayAccess ? ($__internal_compile_73["name"] ?? null) : null), (($__internal_compile_74 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, false, false, 156)) && is_array($__internal_compile_74) || $__internal_compile_74 instanceof ArrayAccess ? ($__internal_compile_74[(($__internal_compile_75 = $context["field"]) && is_array($__internal_compile_75) || $__internal_compile_75 instanceof ArrayAccess ? ($__internal_compile_75["name"] ?? null) : null)] ?? null) : null), (($__internal_compile_76 = $context["field"]) && is_array($__internal_compile_76) || $__internal_compile_76 instanceof ArrayAccess ? ($__internal_compile_76["label"] ?? null) : null), ($context["fields_params"] ?? null)], 156, $context, $this->getSourceContext());
                    yield "
                ";
                } elseif ((                // line 157
($context["type"] ?? null) == "datetime")) {
                    // line 158
                    yield "                    ";
                    yield CoreExtension::callMacro($macros["fields"], "macro_datetimeField", [(($__internal_compile_77 = $context["field"]) && is_array($__internal_compile_77) || $__internal_compile_77 instanceof ArrayAccess ? ($__internal_compile_77["name"] ?? null) : null), (($__internal_compile_78 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, false, false, 158)) && is_array($__internal_compile_78) || $__internal_compile_78 instanceof ArrayAccess ? ($__internal_compile_78[(($__internal_compile_79 = $context["field"]) && is_array($__internal_compile_79) || $__internal_compile_79 instanceof ArrayAccess ? ($__internal_compile_79["name"] ?? null) : null)] ?? null) : null), (($__internal_compile_80 = $context["field"]) && is_array($__internal_compile_80) || $__internal_compile_80 instanceof ArrayAccess ? ($__internal_compile_80["label"] ?? null) : null), ($context["fields_params"] ?? null)], 158, $context, $this->getSourceContext());
                    yield "
                ";
                } elseif ((                // line 159
($context["type"] ?? null) == "picture")) {
                    // line 160
                    yield "                    ";
                    if ( !Twig\Extension\CoreExtension::testEmpty((($__internal_compile_81 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, false, false, 160)) && is_array($__internal_compile_81) || $__internal_compile_81 instanceof ArrayAccess ? ($__internal_compile_81[(($__internal_compile_82 = $context["field"]) && is_array($__internal_compile_82) || $__internal_compile_82 instanceof ArrayAccess ? ($__internal_compile_82["name"] ?? null) : null)] ?? null) : null))) {
                        // line 161
                        yield "                        ";
                        yield CoreExtension::callMacro($macros["fields"], "macro_imageField", [(($__internal_compile_83 = $context["field"]) && is_array($__internal_compile_83) || $__internal_compile_83 instanceof ArrayAccess ? ($__internal_compile_83["name"] ?? null) : null), $this->extensions['Glpi\Application\View\Extension\DataHelpersExtension']->getPictureUrl((($__internal_compile_84 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, false, false, 161)) && is_array($__internal_compile_84) || $__internal_compile_84 instanceof ArrayAccess ? ($__internal_compile_84[(($__internal_compile_85 = $context["field"]) && is_array($__internal_compile_85) || $__internal_compile_85 instanceof ArrayAccess ? ($__internal_compile_85["name"] ?? null) : null)] ?? null) : null)), (($__internal_compile_86 = $context["field"]) && is_array($__internal_compile_86) || $__internal_compile_86 instanceof ArrayAccess ? ($__internal_compile_86["label"] ?? null) : null), Twig\Extension\CoreExtension::merge(($context["fields_params"] ?? null), ["clearable" => ( !CoreExtension::getAttribute($this->env, $this->source,                         // line 162
($context["item"] ?? null), "isNewItem", [], "method", false, false, false, 162) && CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "canUpdateItem", [], "method", false, false, false, 162))])], 161, $context, $this->getSourceContext());
                        // line 163
                        yield "
                    ";
                    } else {
                        // line 165
                        yield "                        ";
                        yield CoreExtension::callMacro($macros["fields"], "macro_fileField", [(($__internal_compile_87 = $context["field"]) && is_array($__internal_compile_87) || $__internal_compile_87 instanceof ArrayAccess ? ($__internal_compile_87["name"] ?? null) : null), null, (($__internal_compile_88 = $context["field"]) && is_array($__internal_compile_88) || $__internal_compile_88 instanceof ArrayAccess ? ($__internal_compile_88["label"] ?? null) : null), ["onlyimages" => true]], 165, $context, $this->getSourceContext());
                        // line 167
                        yield "
                    ";
                    }
                    // line 169
                    yield "                ";
                } elseif ((($context["type"] ?? null) == "picture_gallery")) {
                    // line 170
                    yield "                    ";
                    $context["pictures"] = $this->extensions['Glpi\Application\View\Extension\PhpExtension']->call("importArrayFromDB", [(($__internal_compile_89 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, false, false, 170)) && is_array($__internal_compile_89) || $__internal_compile_89 instanceof ArrayAccess ? ($__internal_compile_89[(($__internal_compile_90 = $context["field"]) && is_array($__internal_compile_90) || $__internal_compile_90 instanceof ArrayAccess ? ($__internal_compile_90["name"] ?? null) : null)] ?? null) : null)]);
                    // line 171
                    yield "                    ";
                    $context["picture_urls"] = [];
                    // line 172
                    yield "                    ";
                    $context['_parent'] = $context;
                    $context['_seq'] = CoreExtension::ensureTraversable(($context["pictures"] ?? null));
                    foreach ($context['_seq'] as $context["_key"] => $context["picture"]) {
                        // line 173
                        yield "                        ";
                        $context["picture_urls"] = Twig\Extension\CoreExtension::merge(($context["picture_urls"] ?? null), [$this->extensions['Glpi\Application\View\Extension\DataHelpersExtension']->getPictureUrl($context["picture"])]);
                        // line 174
                        yield "                    ";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['_key'], $context['picture'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 175
                    yield "                    ";
                    yield CoreExtension::callMacro($macros["fields"], "macro_imageGalleryField", [(($__internal_compile_91 = $context["field"]) && is_array($__internal_compile_91) || $__internal_compile_91 instanceof ArrayAccess ? ($__internal_compile_91["name"] ?? null) : null), ($context["picture_urls"] ?? null), (($__internal_compile_92 = $context["field"]) && is_array($__internal_compile_92) || $__internal_compile_92 instanceof ArrayAccess ? ($__internal_compile_92["label"] ?? null) : null), Twig\Extension\CoreExtension::merge(($context["fields_params"] ?? null), ["clearable" => ( !CoreExtension::getAttribute($this->env, $this->source,                     // line 176
($context["item"] ?? null), "isNewItem", [], "method", false, false, false, 176) && CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "canUpdateItem", [], "method", false, false, false, 176))])], 175, $context, $this->getSourceContext());
                    // line 177
                    yield "
                ";
                } elseif ((                // line 178
($context["type"] ?? null) == "password")) {
                    // line 179
                    yield "                    ";
                    yield CoreExtension::callMacro($macros["fields"], "macro_passwordField", [(($__internal_compile_93 = $context["field"]) && is_array($__internal_compile_93) || $__internal_compile_93 instanceof ArrayAccess ? ($__internal_compile_93["name"] ?? null) : null), (($__internal_compile_94 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, false, false, 179)) && is_array($__internal_compile_94) || $__internal_compile_94 instanceof ArrayAccess ? ($__internal_compile_94[(($__internal_compile_95 = $context["field"]) && is_array($__internal_compile_95) || $__internal_compile_95 instanceof ArrayAccess ? ($__internal_compile_95["name"] ?? null) : null)] ?? null) : null), (($__internal_compile_96 = $context["field"]) && is_array($__internal_compile_96) || $__internal_compile_96 instanceof ArrayAccess ? ($__internal_compile_96["label"] ?? null) : null), ($context["fields_params"] ?? null)], 179, $context, $this->getSourceContext());
                    yield "
                ";
                } elseif ((                // line 180
($context["type"] ?? null) == "tinymce")) {
                    // line 181
                    yield "                    ";
                    $context["fields_params"] = Twig\Extension\CoreExtension::merge(($context["fields_params"] ?? null), ["enable_richtext" => true, "full_width" => true, "label_class" => "col-xxl-2", "input_class" => "col-xxl-10"]);
                    // line 182
                    yield "                    ";
                    yield CoreExtension::callMacro($macros["fields"], "macro_textareaField", [(($__internal_compile_97 = $context["field"]) && is_array($__internal_compile_97) || $__internal_compile_97 instanceof ArrayAccess ? ($__internal_compile_97["name"] ?? null) : null), (($__internal_compile_98 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, false, false, 182)) && is_array($__internal_compile_98) || $__internal_compile_98 instanceof ArrayAccess ? ($__internal_compile_98[(($__internal_compile_99 = $context["field"]) && is_array($__internal_compile_99) || $__internal_compile_99 instanceof ArrayAccess ? ($__internal_compile_99["name"] ?? null) : null)] ?? null) : null), (($__internal_compile_100 = $context["field"]) && is_array($__internal_compile_100) || $__internal_compile_100 instanceof ArrayAccess ? ($__internal_compile_100["label"] ?? null) : null), ($context["fields_params"] ?? null)], 182, $context, $this->getSourceContext());
                    yield "
                ";
                } elseif ((                // line 183
($context["type"] ?? null) == "duration")) {
                    // line 184
                    yield "                    ";
                    $context["toadd"] = [];
                    // line 185
                    yield "                    ";
                    $context['_parent'] = $context;
                    $context['_seq'] = CoreExtension::ensureTraversable(range(9, 100));
                    foreach ($context['_seq'] as $context["_key"] => $context["i"]) {
                        // line 186
                        yield "                        ";
                        $context["toadd"] = Twig\Extension\CoreExtension::merge(($context["toadd"] ?? null), [($context["i"] * Twig\Extension\CoreExtension::constant("HOUR_TIMESTAMP"))]);
                        // line 187
                        yield "                    ";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['_key'], $context['i'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 188
                    yield "                    ";
                    yield CoreExtension::callMacro($macros["fields"], "macro_dropdownTimestampField", [(($__internal_compile_101 = $context["field"]) && is_array($__internal_compile_101) || $__internal_compile_101 instanceof ArrayAccess ? ($__internal_compile_101["name"] ?? null) : null), (($__internal_compile_102 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, false, false, 188)) && is_array($__internal_compile_102) || $__internal_compile_102 instanceof ArrayAccess ? ($__internal_compile_102[(($__internal_compile_103 = $context["field"]) && is_array($__internal_compile_103) || $__internal_compile_103 instanceof ArrayAccess ? ($__internal_compile_103["name"] ?? null) : null)] ?? null) : null), (($__internal_compile_104 = $context["field"]) && is_array($__internal_compile_104) || $__internal_compile_104 instanceof ArrayAccess ? ($__internal_compile_104["label"] ?? null) : null), ($context["fields_params"] ?? null)], 188, $context, $this->getSourceContext());
                    yield "
                ";
                } elseif ((                // line 189
($context["type"] ?? null) == "itemtypename")) {
                    // line 190
                    yield "                    ";
                    if (CoreExtension::getAttribute($this->env, $this->source, $context["field"], "itemtype_list", [], "array", true, true, false, 190)) {
                        // line 191
                        yield "                        ";
                        $context["fields_params"] = Twig\Extension\CoreExtension::merge(($context["fields_params"] ?? null), ["types" => $this->extensions['Glpi\Application\View\Extension\ConfigExtension']->config((($__internal_compile_105 = $context["field"]) && is_array($__internal_compile_105) || $__internal_compile_105 instanceof ArrayAccess ? ($__internal_compile_105["itemtype_list"] ?? null) : null))]);
                        // line 192
                        yield "                    ";
                    }
                    // line 193
                    yield "                    ";
                    yield CoreExtension::callMacro($macros["fields"], "macro_dropdownItemTypes", [(($__internal_compile_106 = $context["field"]) && is_array($__internal_compile_106) || $__internal_compile_106 instanceof ArrayAccess ? ($__internal_compile_106["name"] ?? null) : null), (($__internal_compile_107 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, false, false, 193)) && is_array($__internal_compile_107) || $__internal_compile_107 instanceof ArrayAccess ? ($__internal_compile_107[(($__internal_compile_108 = $context["field"]) && is_array($__internal_compile_108) || $__internal_compile_108 instanceof ArrayAccess ? ($__internal_compile_108["name"] ?? null) : null)] ?? null) : null), (($__internal_compile_109 = $context["field"]) && is_array($__internal_compile_109) || $__internal_compile_109 instanceof ArrayAccess ? ($__internal_compile_109["label"] ?? null) : null), ($context["fields_params"] ?? null)], 193, $context, $this->getSourceContext());
                    yield "
                ";
                } else {
                    // line 195
                    yield "                    ";
                    $context["field_value"] = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
                        // line 196
                        yield "                        ";
                        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "displaySpecificTypeField", [(($__internal_compile_110 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, false, false, 196)) && is_array($__internal_compile_110) || $__internal_compile_110 instanceof ArrayAccess ? ($__internal_compile_110["id"] ?? null) : null), $context["field"], ($context["fields_params"] ?? null)], "method", false, false, false, 196), "html", null, true);
                        yield "
                    ";
                        return; yield '';
                    })())) ? '' : new Markup($tmp, $this->env->getCharset());
                    // line 198
                    yield "                    ";
                    yield CoreExtension::callMacro($macros["fields"], "macro_field", [(($__internal_compile_111 = $context["field"]) && is_array($__internal_compile_111) || $__internal_compile_111 instanceof ArrayAccess ? ($__internal_compile_111["name"] ?? null) : null), ($context["field_value"] ?? null), (($__internal_compile_112 = $context["field"]) && is_array($__internal_compile_112) || $__internal_compile_112 instanceof ArrayAccess ? ($__internal_compile_112["label"] ?? null) : null), ($context["fields_params"] ?? null)], 198, $context, $this->getSourceContext());
                    yield "
                ";
                }
                // line 200
                yield "            ";
            } elseif (CoreExtension::inFilter((($__internal_compile_113 = $context["field"]) && is_array($__internal_compile_113) || $__internal_compile_113 instanceof ArrayAccess ? ($__internal_compile_113["name"] ?? null) : null), ($context["picture_fields"] ?? null))) {
                // line 201
                yield "                ";
                $context["has_picture_field"] = true;
                // line 202
                yield "            ";
            }
            // line 203
            yield "        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['field'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 204
        yield "
        ";
        // line 205
        if (($context["has_picture_field"] ?? null)) {
            // line 206
            yield "            ";
            yield CoreExtension::callMacro($macros["fields"], "macro_largeTitle", [_n("Picture", "Pictures", Session::getPluralNumber()), "fas fa-image"], 206, $context, $this->getSourceContext());
            yield "
        ";
        }
        // line 208
        yield "        ";
        // line 209
        yield "        ";
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(($context["additional_fields"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["field"]) {
            // line 210
            yield "            ";
            $context["type"] = ((CoreExtension::getAttribute($this->env, $this->source, $context["field"], "type", [], "array", true, true, false, 210)) ? (Twig\Extension\CoreExtension::default((($__internal_compile_114 = $context["field"]) && is_array($__internal_compile_114) || $__internal_compile_114 instanceof ArrayAccess ? ($__internal_compile_114["type"] ?? null) : null), "")) : (""));
            // line 211
            yield "            ";
            if (CoreExtension::inFilter((($__internal_compile_115 = $context["field"]) && is_array($__internal_compile_115) || $__internal_compile_115 instanceof ArrayAccess ? ($__internal_compile_115["name"] ?? null) : null), ($context["picture_fields"] ?? null))) {
                // line 212
                yield "                ";
                if ((($context["type"] ?? null) == "picture")) {
                    // line 213
                    yield "                    ";
                    if (((($__internal_compile_116 = $context["field"]) && is_array($__internal_compile_116) || $__internal_compile_116 instanceof ArrayAccess ? ($__internal_compile_116["name"] ?? null) : null) == "picture_front")) {
                        // line 214
                        yield "                        ";
                        yield CoreExtension::callMacro($macros["fields"], "macro_smallTitle", [__("Rack pictures"), $this->extensions['Glpi\Application\View\Extension\ItemtypeExtension']->getItemtypeIcon("Rack")], 214, $context, $this->getSourceContext());
                        yield "
                    ";
                    }
                    // line 216
                    yield "                    ";
                    if ( !Twig\Extension\CoreExtension::testEmpty((($__internal_compile_117 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, false, false, 216)) && is_array($__internal_compile_117) || $__internal_compile_117 instanceof ArrayAccess ? ($__internal_compile_117[(($__internal_compile_118 = $context["field"]) && is_array($__internal_compile_118) || $__internal_compile_118 instanceof ArrayAccess ? ($__internal_compile_118["name"] ?? null) : null)] ?? null) : null))) {
                        // line 217
                        yield "                        ";
                        yield CoreExtension::callMacro($macros["fields"], "macro_imageField", [(($__internal_compile_119 = $context["field"]) && is_array($__internal_compile_119) || $__internal_compile_119 instanceof ArrayAccess ? ($__internal_compile_119["name"] ?? null) : null), $this->extensions['Glpi\Application\View\Extension\DataHelpersExtension']->getPictureUrl((($__internal_compile_120 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, false, false, 217)) && is_array($__internal_compile_120) || $__internal_compile_120 instanceof ArrayAccess ? ($__internal_compile_120[(($__internal_compile_121 = $context["field"]) && is_array($__internal_compile_121) || $__internal_compile_121 instanceof ArrayAccess ? ($__internal_compile_121["name"] ?? null) : null)] ?? null) : null)), (($__internal_compile_122 = $context["field"]) && is_array($__internal_compile_122) || $__internal_compile_122 instanceof ArrayAccess ? ($__internal_compile_122["label"] ?? null) : null), Twig\Extension\CoreExtension::merge(($context["base_fields_params"] ?? null), ["clearable" => CoreExtension::getAttribute($this->env, $this->source,                         // line 218
($context["item"] ?? null), "canUpdateItem", [], "method", false, false, false, 218)])], 217, $context, $this->getSourceContext());
                        // line 219
                        yield "
                    ";
                    } else {
                        // line 221
                        yield "                        ";
                        yield CoreExtension::callMacro($macros["fields"], "macro_fileField", [(($__internal_compile_123 = $context["field"]) && is_array($__internal_compile_123) || $__internal_compile_123 instanceof ArrayAccess ? ($__internal_compile_123["name"] ?? null) : null), null, (($__internal_compile_124 = $context["field"]) && is_array($__internal_compile_124) || $__internal_compile_124 instanceof ArrayAccess ? ($__internal_compile_124["label"] ?? null) : null), ["onlyimages" => true]], 221, $context, $this->getSourceContext());
                        // line 223
                        yield "
                    ";
                    }
                    // line 225
                    yield "                ";
                } elseif ((($context["type"] ?? null) == "picture_gallery")) {
                    // line 226
                    yield "                    ";
                    yield CoreExtension::callMacro($macros["fields"], "macro_smallTitle", [__("Other pictures"), "fas fa-images"], 226, $context, $this->getSourceContext());
                    yield "
                    ";
                    // line 227
                    $context["pictures"] = $this->extensions['Glpi\Application\View\Extension\PhpExtension']->call("importArrayFromDB", [(($__internal_compile_125 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, false, false, 227)) && is_array($__internal_compile_125) || $__internal_compile_125 instanceof ArrayAccess ? ($__internal_compile_125[(($__internal_compile_126 = $context["field"]) && is_array($__internal_compile_126) || $__internal_compile_126 instanceof ArrayAccess ? ($__internal_compile_126["name"] ?? null) : null)] ?? null) : null)]);
                    // line 228
                    yield "                    ";
                    $context["picture_urls"] = [];
                    // line 229
                    yield "                    ";
                    $context['_parent'] = $context;
                    $context['_seq'] = CoreExtension::ensureTraversable(($context["pictures"] ?? null));
                    foreach ($context['_seq'] as $context["_key"] => $context["picture"]) {
                        // line 230
                        yield "                        ";
                        $context["picture_urls"] = Twig\Extension\CoreExtension::merge(($context["picture_urls"] ?? null), [$this->extensions['Glpi\Application\View\Extension\DataHelpersExtension']->getPictureUrl($context["picture"])]);
                        // line 231
                        yield "                    ";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['_key'], $context['picture'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 232
                    yield "                    ";
                    yield CoreExtension::callMacro($macros["fields"], "macro_imageGalleryField", [(($__internal_compile_127 = $context["field"]) && is_array($__internal_compile_127) || $__internal_compile_127 instanceof ArrayAccess ? ($__internal_compile_127["name"] ?? null) : null), ($context["picture_urls"] ?? null), "", Twig\Extension\CoreExtension::merge(($context["base_fields_params"] ?? null), ["clearable" => CoreExtension::getAttribute($this->env, $this->source,                     // line 233
($context["item"] ?? null), "canUpdateItem", [], "method", false, false, false, 233), "no_label" => true])], 232, $context, $this->getSourceContext());
                    // line 235
                    yield "
                ";
                }
                // line 237
                yield "            ";
            }
            // line 238
            yield "        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['field'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 239
        yield "    </div>
    ";
        // line 240
        yield Twig\Extension\CoreExtension::include($this->env, $context, "components/form/buttons.html.twig");
        yield "
</div>
";
        return; yield '';
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "dropdown_form.html.twig";
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
        return array (  580 => 240,  577 => 239,  571 => 238,  568 => 237,  564 => 235,  562 => 233,  560 => 232,  554 => 231,  551 => 230,  546 => 229,  543 => 228,  541 => 227,  536 => 226,  533 => 225,  529 => 223,  526 => 221,  522 => 219,  520 => 218,  518 => 217,  515 => 216,  509 => 214,  506 => 213,  503 => 212,  500 => 211,  497 => 210,  492 => 209,  490 => 208,  484 => 206,  482 => 205,  479 => 204,  473 => 203,  470 => 202,  467 => 201,  464 => 200,  458 => 198,  451 => 196,  448 => 195,  442 => 193,  439 => 192,  436 => 191,  433 => 190,  431 => 189,  426 => 188,  420 => 187,  417 => 186,  412 => 185,  409 => 184,  407 => 183,  402 => 182,  399 => 181,  397 => 180,  392 => 179,  390 => 178,  387 => 177,  385 => 176,  383 => 175,  377 => 174,  374 => 173,  369 => 172,  366 => 171,  363 => 170,  360 => 169,  356 => 167,  353 => 165,  349 => 163,  347 => 162,  345 => 161,  342 => 160,  340 => 159,  335 => 158,  333 => 157,  328 => 156,  326 => 155,  321 => 154,  319 => 153,  314 => 152,  312 => 151,  307 => 150,  305 => 149,  300 => 148,  297 => 147,  294 => 146,  291 => 145,  289 => 144,  284 => 143,  281 => 142,  278 => 141,  275 => 140,  272 => 139,  269 => 138,  266 => 137,  263 => 136,  260 => 135,  257 => 134,  254 => 133,  252 => 132,  247 => 131,  245 => 130,  242 => 129,  239 => 128,  236 => 127,  233 => 126,  230 => 125,  227 => 124,  224 => 123,  221 => 122,  218 => 121,  215 => 120,  213 => 118,  211 => 117,  209 => 116,  204 => 115,  202 => 114,  197 => 113,  195 => 112,  190 => 111,  187 => 110,  184 => 109,  181 => 108,  178 => 107,  176 => 105,  174 => 104,  172 => 103,  169 => 102,  167 => 100,  166 => 99,  165 => 98,  164 => 96,  163 => 95,  162 => 94,  160 => 92,  158 => 91,  155 => 90,  153 => 88,  152 => 83,  151 => 82,  149 => 80,  147 => 79,  142 => 78,  140 => 77,  137 => 76,  134 => 75,  131 => 74,  128 => 73,  125 => 72,  122 => 71,  119 => 70,  117 => 69,  114 => 68,  111 => 67,  108 => 66,  105 => 65,  100 => 64,  97 => 62,  93 => 60,  91 => 58,  89 => 56,  87 => 55,  83 => 53,  81 => 52,  80 => 50,  79 => 49,  77 => 48,  74 => 46,  71 => 45,  69 => 44,  65 => 42,  62 => 41,  59 => 40,  56 => 39,  53 => 38,  51 => 37,  47 => 36,  43 => 34,  41 => 33,  38 => 32,);
    }

    public function getSourceContext()
    {
        return new Source("", "dropdown_form.html.twig", "D:\\laragon\\www\\glpi\\templates\\dropdown_form.html.twig");
    }
}
