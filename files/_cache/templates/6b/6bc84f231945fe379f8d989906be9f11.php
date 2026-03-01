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

/* pages/setup/general/general_setup.html.twig */
class __TwigTemplate_7aa8feb522e6f2747c2694b1e790428e extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->blocks = [
            'config_fields' => [$this, 'block_config_fields'],
        ];
    }

    protected function doGetParent(array $context)
    {
        // line 33
        return "pages/setup/general/base_form.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 34
        $macros["fields"] = $this->macros["fields"] = $this->loadTemplate("components/form/fields_macros.html.twig", "pages/setup/general/general_setup.html.twig", 34)->unwrap();
        // line 38
        $context["field_options"] = ["field_class" => "col-12 col-xxl-6 col-sm-6", "label_class" => "col-xxl-8", "input_class" => "col-xxl-4"];
        // line 44
        $context["field_options"] = ["field_class" => "col-12 col-xxl-6 col-sm-6", "full_width" => true];
        // line 49
        $context["inline_field_options"] = Twig\Extension\CoreExtension::merge(($context["field_options"] ?? null), ["input_addclass" => "w-auto"]);
        // line 33
        $this->parent = $this->loadTemplate("pages/setup/general/base_form.html.twig", "pages/setup/general/general_setup.html.twig", 33);
        yield from $this->parent->unwrap()->yield($context, array_merge($this->blocks, $blocks));
    }

    // line 53
    public function block_config_fields($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 54
        yield "
<div class=\"row ps-4\">

    ";
        // line 57
        yield CoreExtension::callMacro($macros["fields"], "macro_textField", ["url_base", (($__internal_compile_0 =         // line 59
($context["config"] ?? null)) && is_array($__internal_compile_0) || $__internal_compile_0 instanceof ArrayAccess ? ($__internal_compile_0["url_base"] ?? null) : null), __("URL of the application"),         // line 61
($context["field_options"] ?? null)], 57, $context, $this->getSourceContext());
        // line 62
        yield "

    ";
        // line 64
        yield CoreExtension::callMacro($macros["fields"], "macro_textareaField", ["text_login", (($__internal_compile_1 =         // line 66
($context["config"] ?? null)) && is_array($__internal_compile_1) || $__internal_compile_1 instanceof ArrayAccess ? ($__internal_compile_1["text_login"] ?? null) : null), __("Text in the login box"), Twig\Extension\CoreExtension::merge(        // line 68
($context["field_options"] ?? null), ["enable_richtext" => true, "enable_images" => false])], 64, $context, $this->getSourceContext());
        // line 72
        yield "

    ";
        // line 74
        yield CoreExtension::callMacro($macros["fields"], "macro_textField", ["helpdesk_doc_url", (($__internal_compile_2 =         // line 76
($context["config"] ?? null)) && is_array($__internal_compile_2) || $__internal_compile_2 instanceof ArrayAccess ? ($__internal_compile_2["helpdesk_doc_url"] ?? null) : null), __("Simplified interface help link"),         // line 78
($context["field_options"] ?? null)], 74, $context, $this->getSourceContext());
        // line 79
        yield "

    ";
        // line 81
        yield CoreExtension::callMacro($macros["fields"], "macro_textField", ["central_doc_url", (($__internal_compile_3 =         // line 83
($context["config"] ?? null)) && is_array($__internal_compile_3) || $__internal_compile_3 instanceof ArrayAccess ? ($__internal_compile_3["central_doc_url"] ?? null) : null), __("Standard interface help link"),         // line 85
($context["field_options"] ?? null)], 81, $context, $this->getSourceContext());
        // line 86
        yield "

    ";
        // line 88
        yield CoreExtension::callMacro($macros["fields"], "macro_numberField", ["decimal_number", (($__internal_compile_4 =         // line 90
($context["config"] ?? null)) && is_array($__internal_compile_4) || $__internal_compile_4 instanceof ArrayAccess ? ($__internal_compile_4["decimal_number"] ?? null) : null), __("Default decimals limit"), Twig\Extension\CoreExtension::merge(        // line 92
($context["inline_field_options"] ?? null), ["min" => 1, "max" => 4])], 88, $context, $this->getSourceContext());
        // line 96
        yield "

    ";
        // line 98
        yield CoreExtension::callMacro($macros["fields"], "macro_checkboxField", ["use_public_faq", (($__internal_compile_5 =         // line 100
($context["config"] ?? null)) && is_array($__internal_compile_5) || $__internal_compile_5 instanceof ArrayAccess ? ($__internal_compile_5["use_public_faq"] ?? null) : null), __("Allow FAQ anonymous access"),         // line 102
($context["field_options"] ?? null)], 98, $context, $this->getSourceContext());
        // line 103
        yield "

</div>

<div class=\"hr-text\">
    <i class=\"ti ti-language\"></i>
    <span>";
        // line 109
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(__("Translations"), "html", null, true);
        yield "</span>
</div>

<div class=\"row ps-4\">

    ";
        // line 114
        yield CoreExtension::callMacro($macros["fields"], "macro_checkboxField", ["translate_dropdowns", (($__internal_compile_6 =         // line 116
($context["config"] ?? null)) && is_array($__internal_compile_6) || $__internal_compile_6 instanceof ArrayAccess ? ($__internal_compile_6["translate_dropdowns"] ?? null) : null), __("Translation of dropdowns"),         // line 118
($context["field_options"] ?? null)], 114, $context, $this->getSourceContext());
        // line 119
        yield "

    ";
        // line 121
        yield CoreExtension::callMacro($macros["fields"], "macro_checkboxField", ["translate_kb", (($__internal_compile_7 =         // line 123
($context["config"] ?? null)) && is_array($__internal_compile_7) || $__internal_compile_7 instanceof ArrayAccess ? ($__internal_compile_7["translate_kb"] ?? null) : null), __("Knowledge base translation"),         // line 125
($context["field_options"] ?? null)], 121, $context, $this->getSourceContext());
        // line 126
        yield "

    ";
        // line 128
        yield CoreExtension::callMacro($macros["fields"], "macro_checkboxField", ["translate_reminders", (($__internal_compile_8 =         // line 130
($context["config"] ?? null)) && is_array($__internal_compile_8) || $__internal_compile_8 instanceof ArrayAccess ? ($__internal_compile_8["translate_reminders"] ?? null) : null), __("Translation of reminders"),         // line 132
($context["field_options"] ?? null)], 128, $context, $this->getSourceContext());
        // line 133
        yield "

</div>

<div class=\"hr-text\">
    <i class=\"ti ti-list\"></i>
    <span>";
        // line 139
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(__("Dynamic display"), "html", null, true);
        yield "</span>
</div>

<div class=\"row ps-4\">

    ";
        // line 144
        yield CoreExtension::callMacro($macros["fields"], "macro_numberField", ["dropdown_max", (($__internal_compile_9 =         // line 146
($context["config"] ?? null)) && is_array($__internal_compile_9) || $__internal_compile_9 instanceof ArrayAccess ? ($__internal_compile_9["dropdown_max"] ?? null) : null), __("Page size for dropdown (paging using scroll)"), Twig\Extension\CoreExtension::merge(        // line 148
($context["inline_field_options"] ?? null), ["min" => 1, "max" => 200])], 144, $context, $this->getSourceContext());
        // line 152
        yield "

    ";
        // line 154
        yield CoreExtension::callMacro($macros["fields"], "macro_dropdownNumberField", ["ajax_limit_count", (($__internal_compile_10 =         // line 156
($context["config"] ?? null)) && is_array($__internal_compile_10) || $__internal_compile_10 instanceof ArrayAccess ? ($__internal_compile_10["ajax_limit_count"] ?? null) : null), __("Don't show search engine in dropdowns if the number of items is less than"), Twig\Extension\CoreExtension::merge(        // line 158
($context["inline_field_options"] ?? null), ["min" => 1, "max" => 200, "toadd" => ["0" => __("Never")], "width" => "auto"])], 154, $context, $this->getSourceContext());
        // line 164
        yield "
</div>

<div class=\"hr-text\">
    <i class=\"ti ti-search\"></i>
    <span>";
        // line 169
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(__("Search engine"), "html", null, true);
        yield "</span>
</div>

<div class=\"row ps-4\">

    ";
        // line 174
        yield CoreExtension::callMacro($macros["fields"], "macro_dropdownArrayField", ["allow_search_view", (($__internal_compile_11 =         // line 176
($context["config"] ?? null)) && is_array($__internal_compile_11) || $__internal_compile_11 instanceof ArrayAccess ? ($__internal_compile_11["allow_search_view"] ?? null) : null), [__("No"), Twig\Extension\CoreExtension::sprintf(__("%1\$s (%2\$s)"), __("Yes"), __("last criterion")), Twig\Extension\CoreExtension::sprintf(__("%1\$s (%2\$s)"), __("Yes"), __("default criterion"))], __("Items seen"), Twig\Extension\CoreExtension::merge(        // line 183
($context["inline_field_options"] ?? null), ["width" => "auto"])], 174, $context, $this->getSourceContext());
        // line 186
        yield "

    ";
        // line 188
        yield CoreExtension::callMacro($macros["fields"], "macro_dropdownYesNo", ["allow_search_global", (($__internal_compile_12 =         // line 190
($context["config"] ?? null)) && is_array($__internal_compile_12) || $__internal_compile_12 instanceof ArrayAccess ? ($__internal_compile_12["allow_search_global"] ?? null) : null), __("Global search"), Twig\Extension\CoreExtension::merge(        // line 192
($context["inline_field_options"] ?? null), ["width" => "auto"])], 188, $context, $this->getSourceContext());
        // line 195
        yield "

    ";
        // line 197
        yield CoreExtension::callMacro($macros["fields"], "macro_dropdownArrayField", ["allow_search_all", (($__internal_compile_13 =         // line 199
($context["config"] ?? null)) && is_array($__internal_compile_13) || $__internal_compile_13 instanceof ArrayAccess ? ($__internal_compile_13["allow_search_all"] ?? null) : null), [__("No"), Twig\Extension\CoreExtension::sprintf(__("%1\$s (%2\$s)"), __("Yes"), __("last criterion"))], __("All"), Twig\Extension\CoreExtension::merge(        // line 205
($context["inline_field_options"] ?? null), ["width" => "auto"])], 197, $context, $this->getSourceContext());
        // line 208
        yield "

    ";
        // line 210
        yield CoreExtension::callMacro($macros["fields"], "macro_numberField", ["list_limit_max", (($__internal_compile_14 =         // line 212
($context["config"] ?? null)) && is_array($__internal_compile_14) || $__internal_compile_14 instanceof ArrayAccess ? ($__internal_compile_14["list_limit_max"] ?? null) : null), __("Default search results limit (page)"), Twig\Extension\CoreExtension::merge(        // line 214
($context["inline_field_options"] ?? null), ["min" => 5, "max" => 200, "step" => 5])], 210, $context, $this->getSourceContext());
        // line 219
        yield "

    ";
        // line 221
        yield CoreExtension::callMacro($macros["fields"], "macro_numberField", ["cut", (($__internal_compile_15 =         // line 223
($context["config"] ?? null)) && is_array($__internal_compile_15) || $__internal_compile_15 instanceof ArrayAccess ? ($__internal_compile_15["cut"] ?? null) : null), __("Default characters limit (summary text boxes)"), Twig\Extension\CoreExtension::merge(        // line 225
($context["inline_field_options"] ?? null), ["step" => 50])], 221, $context, $this->getSourceContext());
        // line 228
        yield "

    ";
        // line 230
        yield CoreExtension::callMacro($macros["fields"], "macro_numberField", ["url_maxlength", (($__internal_compile_16 =         // line 232
($context["config"] ?? null)) && is_array($__internal_compile_16) || $__internal_compile_16 instanceof ArrayAccess ? ($__internal_compile_16["url_maxlength"] ?? null) : null), __("Default url length limit"), Twig\Extension\CoreExtension::merge(        // line 234
($context["inline_field_options"] ?? null), ["min" => 20, "max" => 80, "step" => 5])], 230, $context, $this->getSourceContext());
        // line 239
        yield "

</div>

<div class=\"hr-text\">
    <i class=\"ti ti-lock\"></i>
    <span>";
        // line 245
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(__("Item locks"), "html", null, true);
        yield "</span>
</div>

<div class=\"row ps-4\">

    ";
        // line 250
        $context["locks_rand"] = Twig\Extension\CoreExtension::random($this->env->getCharset());
        // line 251
        yield "    ";
        $context["id_lock_use_lock_item"] = ("lock_use_lock_item_" . ($context["locks_rand"] ?? null));
        // line 252
        yield "    ";
        $context["id_lock_lockprofile_id"] = ("dropdown_lock_lockprofile_id" . ($context["locks_rand"] ?? null));
        // line 253
        yield "    ";
        $context["id_lock_item_list_id"] = ("dropdown_lock_item_list" . ($context["locks_rand"] ?? null));
        // line 254
        yield "    ";
        yield CoreExtension::callMacro($macros["fields"], "macro_checkboxField", ["lock_use_lock_item", (($__internal_compile_17 =         // line 256
($context["config"] ?? null)) && is_array($__internal_compile_17) || $__internal_compile_17 instanceof ArrayAccess ? ($__internal_compile_17["lock_use_lock_item"] ?? null) : null), __("Use locks"), Twig\Extension\CoreExtension::merge(        // line 258
($context["field_options"] ?? null), ["id" =>         // line 259
($context["id_lock_use_lock_item"] ?? null)])], 254, $context, $this->getSourceContext());
        // line 261
        yield "

    ";
        // line 263
        $context["lock_options"] = ($context["field_options"] ?? null);
        // line 264
        yield "    ";
        if ( !(($__internal_compile_18 = ($context["config"] ?? null)) && is_array($__internal_compile_18) || $__internal_compile_18 instanceof ArrayAccess ? ($__internal_compile_18["lock_use_lock_item"] ?? null) : null)) {
            // line 265
            yield "        ";
            $context["lock_options"] = Twig\Extension\CoreExtension::merge(($context["lock_options"] ?? null), ["disabled" => true]);
            // line 268
            yield "    ";
        }
        // line 269
        yield "
    ";
        // line 270
        yield CoreExtension::callMacro($macros["fields"], "macro_dropdownField", ["Profile", "lock_lockprofile_id", (($__internal_compile_19 =         // line 273
($context["config"] ?? null)) && is_array($__internal_compile_19) || $__internal_compile_19 instanceof ArrayAccess ? ($__internal_compile_19["lock_lockprofile_id"] ?? null) : null), __("Profile to be used when locking items"), Twig\Extension\CoreExtension::merge(Twig\Extension\CoreExtension::merge(        // line 275
($context["inline_field_options"] ?? null), ($context["lock_options"] ?? null)), ["width" => "auto", "rand" =>         // line 277
($context["locks_rand"] ?? null), "condition" => ["interface" => "central"]])], 270, $context, $this->getSourceContext());
        // line 282
        yield "

    ";
        // line 284
        yield CoreExtension::callMacro($macros["fields"], "macro_dropdownArrayField", ["lock_item_list", "", $this->extensions['Glpi\Application\View\Extension\PhpExtension']->call("ObjectLock::getLockableObjects"), __("List of items to lock"), Twig\Extension\CoreExtension::merge(        // line 289
($context["lock_options"] ?? null), ["multiple" => true, "values" => (($__internal_compile_20 =         // line 291
($context["config"] ?? null)) && is_array($__internal_compile_20) || $__internal_compile_20 instanceof ArrayAccess ? ($__internal_compile_20["lock_item_list"] ?? null) : null), "rand" =>         // line 292
($context["locks_rand"] ?? null)])], 284, $context, $this->getSourceContext());
        // line 294
        yield "

    <script>
    \$(function(ready){
        \$('#";
        // line 298
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["id_lock_use_lock_item"] ?? null), "html", null, true);
        yield "').change(function(){
            var enabled = this.checked;
            \$('#";
        // line 300
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["id_lock_lockprofile_id"] ?? null), "html", null, true);
        yield "').prop('disabled', !enabled);
            \$('#";
        // line 301
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["id_lock_item_list_id"] ?? null), "html", null, true);
        yield "').prop('disabled', !enabled);
        });
    });
    </script>
</div>

<div class=\"hr-text\">
    <i class=\"ti ti-login\"></i>
    <span>";
        // line 309
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(__("Auto Login"), "html", null, true);
        yield "</span>
</div>

<div class=\"row ps-4\">

    ";
        // line 314
        yield CoreExtension::callMacro($macros["fields"], "macro_dropdownTimestampField", ["login_remember_time", (($__internal_compile_21 =         // line 316
($context["config"] ?? null)) && is_array($__internal_compile_21) || $__internal_compile_21 instanceof ArrayAccess ? ($__internal_compile_21["login_remember_time"] ?? null) : null), __("Time to allow \"Remember Me\""), Twig\Extension\CoreExtension::merge(        // line 318
($context["inline_field_options"] ?? null), ["emptylabel" => __("Disabled"), "min" => 0, "max" => (Twig\Extension\CoreExtension::constant("MONTH_TIMESTAMP") * 2), "step" => Twig\Extension\CoreExtension::constant("DAY_TIMESTAMP"), "toadd" => [Twig\Extension\CoreExtension::constant("HOUR_TIMESTAMP"), (Twig\Extension\CoreExtension::constant("HOUR_TIMESTAMP") * 2), (Twig\Extension\CoreExtension::constant("HOUR_TIMESTAMP") * 6), (Twig\Extension\CoreExtension::constant("HOUR_TIMESTAMP") * 12)], "width" => "auto"])], 314, $context, $this->getSourceContext());
        // line 331
        yield "

    ";
        // line 333
        yield CoreExtension::callMacro($macros["fields"], "macro_checkboxField", ["login_remember_default", (($__internal_compile_22 =         // line 335
($context["config"] ?? null)) && is_array($__internal_compile_22) || $__internal_compile_22 instanceof ArrayAccess ? ($__internal_compile_22["login_remember_default"] ?? null) : null), __("Default state of checkbox"),         // line 337
($context["field_options"] ?? null)], 333, $context, $this->getSourceContext());
        // line 338
        yield "

    ";
        // line 340
        yield CoreExtension::callMacro($macros["fields"], "macro_checkboxField", ["display_login_source", (($__internal_compile_23 =         // line 342
($context["config"] ?? null)) && is_array($__internal_compile_23) || $__internal_compile_23 instanceof ArrayAccess ? ($__internal_compile_23["display_login_source"] ?? null) : null), __("Display source dropdown on login page"),         // line 344
($context["field_options"] ?? null)], 340, $context, $this->getSourceContext());
        // line 345
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
        return "pages/setup/general/general_setup.html.twig";
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
        return array (  351 => 345,  349 => 344,  348 => 342,  347 => 340,  343 => 338,  341 => 337,  340 => 335,  339 => 333,  335 => 331,  333 => 318,  332 => 316,  331 => 314,  323 => 309,  312 => 301,  308 => 300,  303 => 298,  297 => 294,  295 => 292,  294 => 291,  293 => 289,  292 => 284,  288 => 282,  286 => 277,  285 => 275,  284 => 273,  283 => 270,  280 => 269,  277 => 268,  274 => 265,  271 => 264,  269 => 263,  265 => 261,  263 => 259,  262 => 258,  261 => 256,  259 => 254,  256 => 253,  253 => 252,  250 => 251,  248 => 250,  240 => 245,  232 => 239,  230 => 234,  229 => 232,  228 => 230,  224 => 228,  222 => 225,  221 => 223,  220 => 221,  216 => 219,  214 => 214,  213 => 212,  212 => 210,  208 => 208,  206 => 205,  205 => 199,  204 => 197,  200 => 195,  198 => 192,  197 => 190,  196 => 188,  192 => 186,  190 => 183,  189 => 176,  188 => 174,  180 => 169,  173 => 164,  171 => 158,  170 => 156,  169 => 154,  165 => 152,  163 => 148,  162 => 146,  161 => 144,  153 => 139,  145 => 133,  143 => 132,  142 => 130,  141 => 128,  137 => 126,  135 => 125,  134 => 123,  133 => 121,  129 => 119,  127 => 118,  126 => 116,  125 => 114,  117 => 109,  109 => 103,  107 => 102,  106 => 100,  105 => 98,  101 => 96,  99 => 92,  98 => 90,  97 => 88,  93 => 86,  91 => 85,  90 => 83,  89 => 81,  85 => 79,  83 => 78,  82 => 76,  81 => 74,  77 => 72,  75 => 68,  74 => 66,  73 => 64,  69 => 62,  67 => 61,  66 => 59,  65 => 57,  60 => 54,  56 => 53,  51 => 33,  49 => 49,  47 => 44,  45 => 38,  43 => 34,  36 => 33,);
    }

    public function getSourceContext()
    {
        return new Source("", "pages/setup/general/general_setup.html.twig", "D:\\laragon\\www\\glpi\\templates\\pages\\setup\\general\\general_setup.html.twig");
    }
}
