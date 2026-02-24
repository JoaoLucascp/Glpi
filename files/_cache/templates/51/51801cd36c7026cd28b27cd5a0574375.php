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

/* components/form/fields_macros.html.twig */
class __TwigTemplate_472c61aa9eedb1259a355543cc991bfe extends Template
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
        $macros["_self"] = $this->macros["_self"] = $this;
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 32
        yield "
";
        // line 52
        yield "
";
        // line 69
        yield "
";
        // line 85
        yield "

";
        // line 106
        yield "

";
        // line 128
        yield "

";
        // line 157
        yield "

";
        // line 182
        yield "

";
        // line 197
        yield "

";
        // line 279
        yield "
";
        // line 313
        yield "
";
        // line 347
        yield "
";
        // line 382
        yield "
";
        // line 412
        yield "
";
        // line 451
        yield "
";
        // line 456
        yield "
";
        // line 478
        yield "
";
        // line 503
        yield "
";
        // line 531
        yield "
";
        // line 548
        yield "
";
        // line 571
        yield "
";
        // line 595
        yield "
";
        // line 618
        yield "
";
        // line 640
        yield "
";
        // line 665
        yield "
";
        // line 676
        yield "
";
        // line 698
        yield "
";
        // line 721
        yield "
";
        // line 755
        yield "
";
        // line 766
        yield "
";
        // line 801
        yield "
";
        // line 812
        yield "
";
        // line 822
        yield "

";
        // line 840
        yield "

";
        // line 902
        yield "

";
        // line 933
        yield "

";
        return; yield '';
    }

    // line 33
    public function macro_largeTitle($__label__ = null, $__icon__ = "", $__first__ = false, ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "label" => $__label__,
            "icon" => $__icon__,
            "first" => $__first__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        return ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
            // line 34
            yield "   ";
            $context["margins"] = "mt-3";
            // line 35
            yield "   ";
            if (($context["first"] ?? null)) {
                // line 36
                yield "      ";
                $context["margins"] = "mt-n2";
                // line 37
                yield "   ";
            }
            // line 38
            yield "
   <div class=\"card border-0 shadow-none p-0 m-0 ";
            // line 39
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["margins"] ?? null), "html", null, true);
            yield "\">
      <div class=\"card-header mb-3\">
         <h4 class=\"card-title ";
            // line 41
            yield ((Twig\Extension\CoreExtension::length($this->env->getCharset(), ($context["icon"] ?? null))) ? ("ms-4") : (""));
            yield "\">
            ";
            // line 42
            if (Twig\Extension\CoreExtension::length($this->env->getCharset(), ($context["icon"] ?? null))) {
                // line 43
                yield "               <div class=\"ribbon ribbon-bookmark ribbon-top ribbon-start bg-blue s-1\">
                  <i class=\"fa-2x ";
                // line 44
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["icon"] ?? null), "html", null, true);
                yield "\"></i>
               </div>
            ";
            }
            // line 47
            yield "            ";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["label"] ?? null), "html", null, true);
            yield "
         </h4>
      </div>
   </div>
";
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
    }

    // line 53
    public function macro_smallTitle($__label__ = null, $__icon__ = "", ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "label" => $__label__,
            "icon" => $__icon__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        return ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
            // line 54
            yield "   ";
            $context["margins"] = "mt-2 mb-2";
            // line 55
            yield "
   <div class=\"card border-0 shadow-none p-0 m-0 ";
            // line 56
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["margins"] ?? null), "html", null, true);
            yield "\">
      <div class=\"card-header mb-1 p-0 ps-3\">
         <h4 class=\"card-subtitle ";
            // line 58
            yield ((Twig\Extension\CoreExtension::length($this->env->getCharset(), ($context["icon"] ?? null))) ? ("ms-4") : (""));
            yield "\">
            ";
            // line 59
            if (Twig\Extension\CoreExtension::length($this->env->getCharset(), ($context["icon"] ?? null))) {
                // line 60
                yield "               <div class=\"ribbon ribbon-bookmark ribbon-top ribbon-start bg-blue s-1\">
                  <i class=\"fa-2x ";
                // line 61
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["icon"] ?? null), "html", null, true);
                yield "\"></i>
               </div>
            ";
            }
            // line 64
            yield "            ";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["label"] ?? null), "html", null, true);
            yield "
         </h4>
      </div>
   </div>
";
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
    }

    // line 70
    public function macro_autoNameField($__name__ = null, $__item__ = null, $__label__ = "", $__withtemplate__ = null, $__options__ = [], ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "name" => $__name__,
            "item" => $__item__,
            "label" => $__label__,
            "withtemplate" => $__withtemplate__,
            "options" => $__options__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        return ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
            // line 71
            yield "   ";
            $context["tpl_value"] = (((Twig\Extension\CoreExtension::length($this->env->getCharset(), CoreExtension::getAttribute($this->env, $this->source, ($context["option"] ?? null), "value", [], "any", false, false, false, 71)) > 0)) ? (CoreExtension::getAttribute($this->env, $this->source, ($context["option"] ?? null), "value", [], "any", false, false, false, 71)) : ($this->extensions['Glpi\Application\View\Extension\DataHelpersExtension']->getVerbatimValue((($__internal_compile_0 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, false, false, 71)) && is_array($__internal_compile_0) || $__internal_compile_0 instanceof ArrayAccess ? ($__internal_compile_0[($context["name"] ?? null)] ?? null) : null))));
            // line 72
            yield "   ";
            $context["tplmark"] = "";
            // line 73
            yield "   ";
            if (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isTemplate", [], "method", false, false, false, 73)) {
                yield " ";
                // line 74
                yield "      ";
                $context["tplmark"] = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "getAutofillMark", [($context["name"] ?? null), ["withtemplate" => ($context["withtemplate"] ?? null)], ($context["tpl_value"] ?? null)], "method", false, false, false, 74);
                // line 75
                yield "   ";
            }
            // line 76
            yield "   ";
            if ((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, true, false, 76), ($context["name"] ?? null), [], "array", true, true, false, 76) &&  !(null === (($__internal_compile_1 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, false, false, 76)) && is_array($__internal_compile_1) || $__internal_compile_1 instanceof ArrayAccess ? ($__internal_compile_1[($context["name"] ?? null)] ?? null) : null)))) {
                // line 77
                yield "      ";
                $context["value"] = $this->extensions['Glpi\Application\View\Extension\PhpExtension']->call("autoName", [$this->extensions['Glpi\Application\View\Extension\DataHelpersExtension']->getVerbatimValue((($__internal_compile_2 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, false, false, 77)) && is_array($__internal_compile_2) || $__internal_compile_2 instanceof ArrayAccess ? ($__internal_compile_2[($context["name"] ?? null)] ?? null) : null)), ($context["name"] ?? null), (($context["withtemplate"] ?? null) == 2), CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "getType", [], "method", false, false, false, 77), (($__internal_compile_3 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, false, false, 77)) && is_array($__internal_compile_3) || $__internal_compile_3 instanceof ArrayAccess ? ($__internal_compile_3["entities_id"] ?? null) : null)]);
                // line 78
                yield "   ";
            } else {
                // line 79
                yield "      ";
                $context["value"] = null;
                // line 80
                yield "   ";
            }
            // line 81
            yield "   ";
            $context["label"] = Twig\Extension\CoreExtension::sprintf(__("%1\$s%2\$s"), ($context["label"] ?? null), ($context["tplmark"] ?? null));
            // line 82
            yield "
   ";
            // line 83
            yield CoreExtension::callMacro($macros["_self"], "macro_textField", [($context["name"] ?? null), ($context["value"] ?? null), ($context["label"] ?? null), ($context["options"] ?? null)], 83, $context, $this->getSourceContext());
            yield "
";
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
    }

    // line 87
    public function macro_textField($__name__ = null, $__value__ = null, $__label__ = "", $__options__ = [], ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "name" => $__name__,
            "value" => $__value__,
            "label" => $__label__,
            "options" => $__options__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        return ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
            // line 88
            yield "   ";
            $context["options"] = Twig\Extension\CoreExtension::merge(["type" => "text"], ($context["options"] ?? null));
            // line 89
            yield "   ";
            if (CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "fields_template", [], "any", false, false, false, 89), "isMandatoryField", [($context["name"] ?? null)], "method", false, false, false, 89)) {
                // line 90
                yield "      ";
                $context["options"] = Twig\Extension\CoreExtension::merge(["required" => true], ($context["options"] ?? null));
                // line 91
                yield "   ";
            }
            // line 92
            yield "
   ";
            // line 93
            $context["field"] = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
                // line 94
                yield "      <input type=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "type", [], "any", false, false, false, 94), "html", null, true);
                yield "\" id=\"%id%\"
             class=\"form-control ";
                // line 95
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "input_addclass", [], "any", false, false, false, 95), "html", null, true);
                yield "\"
             name=\"";
                // line 96
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["name"] ?? null), "html", null, true);
                yield "\" value=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Glpi\Application\View\Extension\DataHelpersExtension']->getVerbatimValue(($context["value"] ?? null)), "html", null, true);
                yield "\"
             ";
                // line 97
                ((CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "maxlength", [], "any", false, false, false, 97)) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(("maxlength=" . CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "maxlength", [], "any", false, false, false, 97)), "html", null, true)) : (yield ""));
                yield "
             ";
                // line 98
                yield ((CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "readonly", [], "any", false, false, false, 98)) ? ("readonly") : (""));
                yield "
             ";
                // line 99
                yield ((CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "disabled", [], "any", false, false, false, 99)) ? ("disabled") : (""));
                yield "
             ";
                // line 100
                yield ((CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "multiple", [], "any", false, false, false, 100)) ? ("multiple") : (""));
                yield " ";
                // line 101
                yield "             ";
                yield ((CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "required", [], "any", false, false, false, 101)) ? ("required") : (""));
                yield " />
   ";
                return; yield '';
            })())) ? '' : new Markup($tmp, $this->env->getCharset());
            // line 103
            yield "
   ";
            // line 104
            yield CoreExtension::callMacro($macros["_self"], "macro_field", [($context["name"] ?? null), ($context["field"] ?? null), ($context["label"] ?? null), ($context["options"] ?? null)], 104, $context, $this->getSourceContext());
            yield "
";
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
    }

    // line 108
    public function macro_checkboxField($__name__ = null, $__value__ = null, $__label__ = "", $__options__ = [], ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "name" => $__name__,
            "value" => $__value__,
            "label" => $__label__,
            "options" => $__options__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        return ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
            // line 109
            yield "   ";
            if (CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "fields_template", [], "any", false, false, false, 109), "isMandatoryField", [($context["name"] ?? null)], "method", false, false, false, 109)) {
                // line 110
                yield "      ";
                $context["options"] = Twig\Extension\CoreExtension::merge(["required" => true], ($context["options"] ?? null));
                // line 111
                yield "   ";
            }
            // line 112
            yield "
   ";
            // line 113
            $context["options"] = Twig\Extension\CoreExtension::merge(["center" => true],             // line 115
($context["options"] ?? null));
            // line 116
            yield "
   ";
            // line 117
            $context["field"] = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
                // line 118
                yield "      <input type=\"hidden\"   name=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["name"] ?? null), "html", null, true);
                yield "\" value=\"0\" />
      <input type=\"checkbox\" name=\"";
                // line 119
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["name"] ?? null), "html", null, true);
                yield "\" value=\"1\" class=\"form-check-input\" id=\"%id%\"
             ";
                // line 120
                yield (((($context["value"] ?? null) == 1)) ? ("checked") : (""));
                yield "
             ";
                // line 121
                yield ((CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "readonly", [], "any", false, false, false, 121)) ? ("readonly") : (""));
                yield "
             ";
                // line 122
                yield ((CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "required", [], "any", false, false, false, 122)) ? ("required") : (""));
                yield "
             ";
                // line 123
                yield ((CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "disabled", [], "any", false, false, false, 123)) ? ("disabled") : (""));
                yield " />
   ";
                return; yield '';
            })())) ? '' : new Markup($tmp, $this->env->getCharset());
            // line 125
            yield "
   ";
            // line 126
            yield CoreExtension::callMacro($macros["_self"], "macro_field", [($context["name"] ?? null), ($context["field"] ?? null), ($context["label"] ?? null), ($context["options"] ?? null)], 126, $context, $this->getSourceContext());
            yield "
";
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
    }

    // line 130
    public function macro_sliderField($__name__ = null, $__value__ = null, $__label__ = "", $__options__ = [], ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "name" => $__name__,
            "value" => $__value__,
            "label" => $__label__,
            "options" => $__options__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        return ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
            // line 131
            yield "   ";
            if (CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "fields_template", [], "any", false, false, false, 131), "isMandatoryField", [($context["name"] ?? null)], "method", false, false, false, 131)) {
                // line 132
                yield "      ";
                $context["options"] = Twig\Extension\CoreExtension::merge(["required" => true],                 // line 134
($context["options"] ?? null));
                // line 135
                yield "   ";
            }
            // line 136
            yield "   ";
            $context["options"] = Twig\Extension\CoreExtension::merge(["no_value" => 0, "yes_value" => 1],             // line 139
($context["options"] ?? null));
            // line 140
            yield "
   ";
            // line 141
            $context["field"] = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
                // line 142
                yield "      <label class=\"form-check form-switch mt-2\">
         <input type=\"hidden\"   name=\"";
                // line 143
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["name"] ?? null), "html", null, true);
                yield "\" value=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "no_value", [], "any", false, false, false, 143), "html", null, true);
                yield "\" />
         <input type=\"checkbox\" name=\"";
                // line 144
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["name"] ?? null), "html", null, true);
                yield "\" value=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "yes_value", [], "any", false, false, false, 144), "html", null, true);
                yield "\" class=\"form-check-input\" id=\"%id%\"
                ";
                // line 145
                yield (((($context["value"] ?? null) == 1)) ? ("checked") : (""));
                yield "
                ";
                // line 146
                yield ((CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "readonly", [], "any", false, false, false, 146)) ? ("readonly") : (""));
                yield "
                ";
                // line 147
                yield ((CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "required", [], "any", false, false, false, 147)) ? ("required") : (""));
                yield "
                ";
                // line 148
                yield ((CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "disabled", [], "any", false, false, false, 148)) ? ("disabled") : (""));
                yield " />
         ";
                // line 149
                if (CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "label2", [], "any", false, false, false, 149)) {
                    // line 150
                    yield "            <span class=\"form-check-label\">";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "label2", [], "any", false, false, false, 150), "html", null, true);
                    yield "</span>
         ";
                }
                // line 152
                yield "      </label>
   ";
                return; yield '';
            })())) ? '' : new Markup($tmp, $this->env->getCharset());
            // line 154
            yield "
   ";
            // line 155
            yield CoreExtension::callMacro($macros["_self"], "macro_field", [($context["name"] ?? null), ($context["field"] ?? null), ($context["label"] ?? null), ($context["options"] ?? null)], 155, $context, $this->getSourceContext());
            yield "
";
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
    }

    // line 159
    public function macro_numberField($__name__ = null, $__value__ = null, $__label__ = "", $__options__ = [], ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "name" => $__name__,
            "value" => $__value__,
            "label" => $__label__,
            "options" => $__options__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        return ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
            // line 160
            yield "   ";
            if (((CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "step", [], "any", false, false, false, 160) != "any") && (Twig\Extension\CoreExtension::round(CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "step", [], "any", false, false, false, 160), 0, "floor") != CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "step", [], "any", false, false, false, 160)))) {
                // line 161
                yield "      ";
                // line 162
                yield "      ";
                $context["value"] = $this->extensions['Glpi\Application\View\Extension\PhpExtension']->call("Html::formatNumber", [($context["value"] ?? null), true]);
                // line 163
                yield "   ";
            }
            // line 164
            yield "
   ";
            // line 165
            if ((($context["value"] ?? null) == "")) {
                // line 166
                yield "      ";
                $context["value"] = ((CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "min", [], "any", true, true, false, 166)) ? (CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "min", [], "any", false, false, false, 166)) : (0));
                // line 167
                yield "   ";
            }
            // line 168
            yield "
   ";
            // line 169
            $context["field"] = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
                // line 170
                yield "      <input type=\"number\" id=\"%id%\"
             class=\"form-control ";
                // line 171
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "input_addclass", [], "any", false, false, false, 171), "html", null, true);
                yield "\"
             name=\"";
                // line 172
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["name"] ?? null), "html", null, true);
                yield "\" value=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["value"] ?? null), "html", null, true);
                yield "\"
         ";
                // line 173
                yield ((CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "readonly", [], "any", false, false, false, 173)) ? ("readonly") : (""));
                yield "
         ";
                // line 174
                yield ((CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "disabled", [], "any", false, false, false, 174)) ? ("disabled") : (""));
                yield "
         ";
                // line 175
                yield ((CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "required", [], "any", false, false, false, 175)) ? ("required") : (""));
                yield "
         ";
                // line 176
                if (CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "min", [], "any", true, true, false, 176)) {
                    yield "min=\"";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "min", [], "any", false, false, false, 176), "html", null, true);
                    yield "\"";
                }
                // line 177
                yield "         ";
                if (CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "max", [], "any", true, true, false, 177)) {
                    yield "max=\"";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "max", [], "any", false, false, false, 177), "html", null, true);
                    yield "\"";
                }
                // line 178
                yield "         ";
                if (CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "step", [], "any", true, true, false, 178)) {
                    yield "step=\"";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "step", [], "any", false, false, false, 178), "html", null, true);
                    yield "\"";
                }
                yield " />
   ";
                return; yield '';
            })())) ? '' : new Markup($tmp, $this->env->getCharset());
            // line 180
            yield "   ";
            yield CoreExtension::callMacro($macros["_self"], "macro_field", [($context["name"] ?? null), ($context["field"] ?? null), ($context["label"] ?? null), ($context["options"] ?? null)], 180, $context, $this->getSourceContext());
            yield "
";
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
    }

    // line 184
    public function macro_readOnlyField($__name__ = null, $__value__ = null, $__label__ = "", $__options__ = [], ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "name" => $__name__,
            "value" => $__value__,
            "label" => $__label__,
            "options" => $__options__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        return ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
            // line 185
            yield "   ";
            $context["options"] = Twig\Extension\CoreExtension::merge(($context["options"] ?? null), ["readonly" => true]);
            // line 186
            yield "   ";
            $context["value"] = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
                // line 187
                yield "      <span class=\"form-control ";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "input_addclass", [], "any", false, false, false, 187), "html", null, true);
                yield "\" readonly>
         ";
                // line 188
                if ((Twig\Extension\CoreExtension::length($this->env->getCharset(), ($context["value"] ?? null)) == 0)) {
                    // line 189
                    yield "            &nbsp;
         ";
                } else {
                    // line 191
                    yield "            ";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Glpi\Application\View\Extension\DataHelpersExtension']->getVerbatimValue(($context["value"] ?? null)), "html", null, true);
                    yield "
         ";
                }
                // line 193
                yield "      </span>
   ";
                return; yield '';
            })())) ? '' : new Markup($tmp, $this->env->getCharset());
            // line 195
            yield "   ";
            yield CoreExtension::callMacro($macros["_self"], "macro_field", [($context["name"] ?? null), ($context["value"] ?? null), ($context["label"] ?? null), ($context["options"] ?? null)], 195, $context, $this->getSourceContext());
            yield "
";
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
    }

    // line 199
    public function macro_textareaField($__name__ = null, $__value__ = null, $__label__ = "", $__options__ = [], ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "name" => $__name__,
            "value" => $__value__,
            "label" => $__label__,
            "options" => $__options__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        return ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
            // line 200
            yield "   ";
            $context["options"] = Twig\Extension\CoreExtension::merge(["rand" => Twig\Extension\CoreExtension::random($this->env->getCharset()), "enable_richtext" => false, "enable_images" => true, "enable_fileupload" => false, "enable_mentions" => false, "entities_id" => $this->extensions['Glpi\Application\View\Extension\SessionExtension']->session("glpiactive_entity"), "uploads" => []],             // line 208
($context["options"] ?? null));
            // line 209
            yield "
   ";
            // line 210
            if (CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "fields_template", [], "any", false, false, false, 210), "isMandatoryField", [($context["name"] ?? null)], "method", false, false, false, 210)) {
                // line 211
                yield "      ";
                $context["options"] = Twig\Extension\CoreExtension::merge(["required" => true], ($context["options"] ?? null));
                // line 212
                yield "   ";
            }
            // line 213
            yield "   ";
            $context["id"] = (((Twig\Extension\CoreExtension::length($this->env->getCharset(), CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "id", [], "any", false, false, false, 213)) > 0)) ? (CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "id", [], "any", false, false, false, 213)) : (((($context["name"] ?? null) . "_") . CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "rand", [], "any", false, false, false, 213))));
            // line 214
            yield "
   ";
            // line 215
            $context["field"] = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
                // line 216
                yield "      ";
                // line 217
                yield "      <textarea class=\"form-control ";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "input_addclass", [], "any", false, false, false, 217), "html", null, true);
                yield "\" id=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["id"] ?? null), "html", null, true);
                yield "\" name=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["name"] ?? null), "html", null, true);
                yield "\" rows=\"3\"
                style=\"width: 100%;\"
                ";
                // line 219
                yield ((CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "disabled", [], "any", false, false, false, 219)) ? ("disabled") : (""));
                yield "
                ";
                // line 220
                yield ((CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "readonly", [], "any", false, false, false, 220)) ? ("readonly") : (""));
                yield "
                ";
                // line 221
                yield ((CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "required", [], "any", false, false, false, 221)) ? ("required") : (""));
                yield ">";
                ((CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "enable_richtext", [], "any", false, false, false, 221)) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Glpi\Application\View\Extension\DataHelpersExtension']->getSafeHtml(($context["value"] ?? null)))) : (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Glpi\Application\View\Extension\DataHelpersExtension']->getVerbatimValue(($context["value"] ?? null)), "html", null, true)));
                yield "</textarea>
   ";
                return; yield '';
            })())) ? '' : new Markup($tmp, $this->env->getCharset());
            // line 223
            yield "
   ";
            // line 224
            if (CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "enable_richtext", [], "any", false, false, false, 224)) {
                // line 225
                yield "      ";
                $this->extensions['Glpi\Application\View\Extension\PhpExtension']->call("Html::initEditorSystem", [                // line 226
($context["id"] ?? null), CoreExtension::getAttribute($this->env, $this->source,                 // line 227
($context["options"] ?? null), "rand", [], "any", false, false, false, 227), true, ((CoreExtension::getAttribute($this->env, $this->source,                 // line 229
($context["options"] ?? null), "disabled", [], "any", true, true, false, 229)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "disabled", [], "any", false, false, false, 229), false)) : (false)), CoreExtension::getAttribute($this->env, $this->source,                 // line 230
($context["options"] ?? null), "enable_images", [], "any", false, false, false, 230)]);
                // line 232
                yield "   ";
            }
            // line 233
            yield "
   ";
            // line 234
            $context["add_html"] = "";
            // line 235
            yield "   ";
            if (( !CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "readonly", [], "any", false, false, false, 235) && CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "enable_fileupload", [], "any", false, false, false, 235))) {
                // line 236
                yield "      ";
                $context["add_html"] = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
                    // line 237
                    yield "         ";
                    $this->extensions['Glpi\Application\View\Extension\PhpExtension']->call("Html::file", [["editor_id" =>                     // line 238
($context["id"] ?? null), "multiple" => true, "uploads" => CoreExtension::getAttribute($this->env, $this->source,                     // line 240
($context["options"] ?? null), "uploads", [], "any", false, false, false, 240), "required" => CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source,                     // line 241
($context["options"] ?? null), "fields_template", [], "any", false, false, false, 241), "isMandatoryField", ["_documents_id"], "method", false, false, false, 241)]]);
                    // line 243
                    yield "      ";
                    return; yield '';
                })())) ? '' : new Markup($tmp, $this->env->getCharset());
                // line 244
                yield "   ";
            } elseif (((( !CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "readonly", [], "any", false, false, false, 244) &&  !CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "enable_fileupload", [], "any", false, false, false, 244)) && CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "enable_richtext", [], "any", false, false, false, 244)) && CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "enable_images", [], "any", false, false, false, 244))) {
                // line 245
                yield "      ";
                $context["add_html"] = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
                    // line 246
                    yield "         ";
                    $this->extensions['Glpi\Application\View\Extension\PhpExtension']->call("Html::file", [["editor_id" =>                     // line 247
($context["id"] ?? null), "name" =>                     // line 248
($context["name"] ?? null), "only_uploaded_files" => true, "uploads" => CoreExtension::getAttribute($this->env, $this->source,                     // line 250
($context["options"] ?? null), "uploads", [], "any", false, false, false, 250), "required" => CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source,                     // line 251
($context["options"] ?? null), "fields_template", [], "any", false, false, false, 251), "isMandatoryField", ["_documents_id"], "method", false, false, false, 251)]]);
                    // line 253
                    yield "      ";
                    return; yield '';
                })())) ? '' : new Markup($tmp, $this->env->getCharset());
                // line 254
                yield "   ";
            }
            // line 255
            yield "
   ";
            // line 256
            if ((($context["add_html"] ?? null) != "")) {
                // line 257
                yield "      ";
                if (CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "add_field_html", [], "any", true, true, false, 257)) {
                    // line 258
                    yield "         ";
                    $context["add_html"] = (($context["add_html"] ?? null) . CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "add_field_html", [], "any", false, false, false, 258));
                    // line 259
                    yield "      ";
                }
                // line 260
                yield "      ";
                $context["options"] = Twig\Extension\CoreExtension::merge(($context["options"] ?? null), ["add_field_html" => ($context["add_html"] ?? null)]);
                // line 261
                yield "   ";
            }
            // line 262
            yield "
   ";
            // line 263
            yield CoreExtension::callMacro($macros["_self"], "macro_field", [($context["name"] ?? null), ($context["field"] ?? null), ($context["label"] ?? null), ($context["options"] ?? null)], 263, $context, $this->getSourceContext());
            yield "
   ";
            // line 264
            if ((CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "enable_mentions", [], "any", false, false, false, 264) && $this->extensions['Glpi\Application\View\Extension\ConfigExtension']->config("use_notifications"))) {
                // line 265
                yield "      <script>
         \$(
            function() {
               const user_mention = new GLPI.RichText.UserMention(
                  tinymce.get('";
                // line 269
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["id"] ?? null), "html", null, true);
                yield "'),
                  ";
                // line 270
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "entities_id", [], "any", false, false, false, 270), "html", null, true);
                yield ",
                  '";
                // line 271
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Session::getNewIDORToken("User", ["right" => "all", "entity_restrict" => CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "entities_id", [], "any", false, false, false, 271)]), "html", null, true);
                yield "'
               );
               user_mention.register();
            }
         )
      </script>
   ";
            }
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
    }

    // line 280
    public function macro_flatpickrHtmlInput($__name__ = null, $__value__ = null, $__label__ = "", $__options__ = [], ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "name" => $__name__,
            "value" => $__value__,
            "label" => $__label__,
            "options" => $__options__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        return ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
            // line 281
            yield "   ";
            if (CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "fields_template", [], "any", false, false, false, 281), "isMandatoryField", [($context["name"] ?? null)], "method", false, false, false, 281)) {
                // line 282
                yield "      ";
                $context["options"] = Twig\Extension\CoreExtension::merge(["required" => true], ($context["options"] ?? null));
                // line 283
                yield "   ";
            }
            // line 284
            yield "
   ";
            // line 285
            if ((($context["value"] ?? null) == "NULL")) {
                // line 286
                yield "      ";
                $context["value"] = null;
                // line 287
                yield "   ";
            }
            // line 288
            yield "   ";
            if (CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "checkIsExpired", [], "any", false, false, false, 288)) {
                // line 289
                yield "      ";
                if (($this->extensions['Twig\Extension\CoreExtension']->formatDate(($context["value"] ?? null), "Y-m-d H:i:s") < $this->extensions['Twig\Extension\CoreExtension']->formatDate("now", "Y-m-d H:i:s"))) {
                    // line 290
                    yield "         ";
                    $context["class"] = "warn";
                    // line 291
                    yield "      ";
                }
                // line 292
                yield "   ";
            } else {
                // line 293
                yield "      ";
                if (CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "expiration_class", [], "any", true, true, false, 293)) {
                    // line 294
                    yield "         ";
                    $context["class"] = CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "expiration_class", [], "any", false, false, false, 294);
                    // line 295
                    yield "      ";
                } else {
                    // line 296
                    yield "         ";
                    $context["class"] = "";
                    // line 297
                    yield "      ";
                }
                // line 298
                yield "   ";
            }
            // line 299
            yield "
   <div class=\"input-group flex-grow-1 flatpickr\" id=\"";
            // line 300
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "id", [], "any", false, false, false, 300), "html", null, true);
            yield "\">
      ";
            // line 302
            yield "      ";
            // line 303
            yield "      <input type=\"text\" class=\"form-control rounded-start ps-2 ";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "input_addclass", [], "any", false, false, false, 303), "html", null, true);
            yield " ";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["class"] ?? null), "html", null, true);
            yield "\" data-input
             name=\"";
            // line 304
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["name"] ?? null), "html", null, true);
            yield "\" value=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Glpi\Application\View\Extension\DataHelpersExtension']->getVerbatimValue(($context["value"] ?? null)), "html", null, true);
            yield "\"
             ";
            // line 305
            yield ((CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "required", [], "any", false, false, false, 305)) ? ("required") : (""));
            yield "
             ";
            // line 306
            yield ((CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "readonly", [], "any", false, false, false, 306)) ? ("readonly") : (""));
            yield "
             ";
            // line 307
            yield ((CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "disabled", [], "any", false, false, false, 307)) ? ("disabled") : (""));
            yield " />
      ";
            // line 308
            if ( !CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "readonly", [], "any", false, false, false, 308)) {
                // line 309
                yield "         <i class=\"input-group-text far fa-calendar-alt\" data-toggle role=\"button\"></i>
      ";
            }
            // line 311
            yield "   </div>
";
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
    }

    // line 314
    public function macro_dateField($__name__ = null, $__value__ = null, $__label__ = "", $__options__ = [], ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "name" => $__name__,
            "value" => $__value__,
            "label" => $__label__,
            "options" => $__options__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        return ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
            // line 315
            yield "   ";
            $context["options"] = Twig\Extension\CoreExtension::merge(["rand" => Twig\Extension\CoreExtension::random($this->env->getCharset())], ($context["options"] ?? null));
            // line 316
            yield "   ";
            $context["options"] = Twig\Extension\CoreExtension::merge(["id" => (($this->extensions['Twig\Extra\String\StringExtension']->createSlug(($context["name"] ?? null)) . "_") . CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "rand", [], "any", false, false, false, 316))], ($context["options"] ?? null));
            // line 317
            yield "   ";
            $context["locale"] = $this->extensions['Glpi\Application\View\Extension\I18nExtension']->getCurrentLocale();
            // line 318
            yield "
   ";
            // line 319
            $context["field"] = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
                // line 320
                yield "      ";
                yield CoreExtension::callMacro($macros["_self"], "macro_flatpickrHtmlInput", [($context["name"] ?? null), ($context["value"] ?? null), ($context["label"] ?? null), ($context["options"] ?? null)], 320, $context, $this->getSourceContext());
                yield "
      <script>
      \$(function() {
         \$(\"#";
                // line 323
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "id", [], "any", false, false, false, 323), "html", null, true);
                yield "\").flatpickr({
            wrap: true,
            altInput: true,
            altFormat: '";
                // line 326
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Glpi\Application\View\Extension\PhpExtension']->call("Toolbox::getDateFormat", ["js"]), "html", null, true);
                yield "',
            dateFormat: 'Y-m-d',
            enableTime: false,
            weekNumbers: true,
            time_24hr: true,
            allowInput: ";
                // line 331
                yield ((CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "readonly", [], "any", false, false, false, 331)) ? ("false") : ("true"));
                yield ",
            clickOpens: ";
                // line 332
                yield ((CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "readonly", [], "any", false, false, false, 332)) ? ("false") : ("true"));
                yield ",
            locale: getFlatPickerLocale(\"";
                // line 333
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((($__internal_compile_4 = ($context["locale"] ?? null)) && is_array($__internal_compile_4) || $__internal_compile_4 instanceof ArrayAccess ? ($__internal_compile_4["language"] ?? null) : null), "html", null, true);
                yield "\", \"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((($__internal_compile_5 = ($context["locale"] ?? null)) && is_array($__internal_compile_5) || $__internal_compile_5 instanceof ArrayAccess ? ($__internal_compile_5["region"] ?? null) : null), "html", null, true);
                yield "\"),
            onClose(dates, currentdatestring, picker) {
               picker.setDate(picker.altInput.value, true, picker.config.altFormat)
            },
            plugins: [
               CustomFlatpickrButtons()
            ]
         });
      });
      </script>
   ";
                return; yield '';
            })())) ? '' : new Markup($tmp, $this->env->getCharset());
            // line 344
            yield "
   ";
            // line 345
            yield CoreExtension::callMacro($macros["_self"], "macro_field", [($context["name"] ?? null), ($context["field"] ?? null), ($context["label"] ?? null), ($context["options"] ?? null)], 345, $context, $this->getSourceContext());
            yield "
";
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
    }

    // line 348
    public function macro_datetimeField($__name__ = null, $__value__ = null, $__label__ = "", $__options__ = [], ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "name" => $__name__,
            "value" => $__value__,
            "label" => $__label__,
            "options" => $__options__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        return ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
            // line 349
            yield "   ";
            $context["options"] = Twig\Extension\CoreExtension::merge(["rand" => Twig\Extension\CoreExtension::random($this->env->getCharset())], ($context["options"] ?? null));
            // line 350
            yield "   ";
            $context["options"] = Twig\Extension\CoreExtension::merge(["id" => (($this->extensions['Twig\Extra\String\StringExtension']->createSlug(($context["name"] ?? null)) . "_") . CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "rand", [], "any", false, false, false, 350))], ($context["options"] ?? null));
            // line 351
            yield "   ";
            $context["locale"] = $this->extensions['Glpi\Application\View\Extension\I18nExtension']->getCurrentLocale();
            // line 352
            yield "
   ";
            // line 353
            $context["field"] = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
                // line 354
                yield "      ";
                yield CoreExtension::callMacro($macros["_self"], "macro_flatpickrHtmlInput", [($context["name"] ?? null), ($context["value"] ?? null), ($context["label"] ?? null), ($context["options"] ?? null)], 354, $context, $this->getSourceContext());
                yield "
      <script>
      \$(function() {
         \$('#";
                // line 357
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "id", [], "any", false, false, false, 357), "html", null, true);
                yield "').flatpickr({
            altInput: true,
            dateFormat: 'Y-m-d H:i:S',
            altFormat: '";
                // line 360
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Glpi\Application\View\Extension\PhpExtension']->call("Toolbox::getDateFormat", ["js"]), "html", null, true);
                yield " H:i:S',
            enableTime: true,
            wrap: true,
            enableSeconds: true,
            weekNumbers: true,
            time_24hr: true,
            allowInput: ";
                // line 366
                yield ((CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "readonly", [], "any", false, false, false, 366)) ? ("false") : ("true"));
                yield ",
            clickOpens: ";
                // line 367
                yield ((CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "readonly", [], "any", false, false, false, 367)) ? ("false") : ("true"));
                yield ",
            locale: getFlatPickerLocale('";
                // line 368
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((($__internal_compile_6 = ($context["locale"] ?? null)) && is_array($__internal_compile_6) || $__internal_compile_6 instanceof ArrayAccess ? ($__internal_compile_6["language"] ?? null) : null), "html", null, true);
                yield "', '";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((($__internal_compile_7 = ($context["locale"] ?? null)) && is_array($__internal_compile_7) || $__internal_compile_7 instanceof ArrayAccess ? ($__internal_compile_7["region"] ?? null) : null), "html", null, true);
                yield "'),
            onClose(dates, currentdatestring, picker) {
               picker.setDate(picker.altInput.value, true, picker.config.altFormat)
            },
            plugins: [
               CustomFlatpickrButtons()
            ]
         });
      });
      </script>
   ";
                return; yield '';
            })())) ? '' : new Markup($tmp, $this->env->getCharset());
            // line 379
            yield "
   ";
            // line 380
            yield CoreExtension::callMacro($macros["_self"], "macro_field", [($context["name"] ?? null), ($context["field"] ?? null), ($context["label"] ?? null), ($context["options"] ?? null)], 380, $context, $this->getSourceContext());
            yield "
";
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
    }

    // line 383
    public function macro_colorField($__name__ = null, $__value__ = null, $__label__ = "", $__options__ = [], ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "name" => $__name__,
            "value" => $__value__,
            "label" => $__label__,
            "options" => $__options__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        return ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
            // line 384
            yield "   ";
            $context["field"] = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
                // line 385
                yield "      <input id=\"%id%\"
             class=\"form-control ";
                // line 386
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "input_addclass", [], "any", false, false, false, 386), "html", null, true);
                yield "\"
             name=\"";
                // line 387
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["name"] ?? null), "html", null, true);
                yield "\" value=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Glpi\Application\View\Extension\DataHelpersExtension']->getVerbatimValue(($context["value"] ?? null)), "html", null, true);
                yield "\"
         ";
                // line 388
                yield ((CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "readonly", [], "any", false, false, false, 388)) ? ("readonly") : (""));
                yield "
         ";
                // line 389
                yield ((CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "disabled", [], "any", false, false, false, 389)) ? ("disabled") : (""));
                yield "
         ";
                // line 390
                yield ((CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "required", [], "any", false, false, false, 390)) ? ("required") : (""));
                yield " />
      <script>
      \$(function() {
        \$(\"#%id%\").spectrum({
            showInput: true,
            preferredFormat: \"hex\",
            type: \"text\",
            cancelText: \"";
                // line 397
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(__("Cancel"), "html", null, true);
                yield "\",
            chooseText: \"";
                // line 398
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(__("Validate"), "html", null, true);
                yield "\",
            change: function(color) {
                if (color !== null && color.getAlpha() !== 1) {
                    let hex = color.toHexString();
                    hex += (\"0\" + Math.round(parseFloat(color.getAlpha()) * 255).toString(16)).slice(-2);
                    this.value = hex;
                }
            }
        });
      });
      </script>
   ";
                return; yield '';
            })())) ? '' : new Markup($tmp, $this->env->getCharset());
            // line 410
            yield "   ";
            yield CoreExtension::callMacro($macros["_self"], "macro_field", [($context["name"] ?? null), ($context["field"] ?? null), ($context["label"] ?? null), ($context["options"] ?? null)], 410, $context, $this->getSourceContext());
            yield "
";
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
    }

    // line 413
    public function macro_passwordField($__name__ = null, $__value__ = null, $__label__ = "", $__options__ = [], ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "name" => $__name__,
            "value" => $__value__,
            "label" => $__label__,
            "options" => $__options__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        return ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
            // line 414
            yield "   ";
            $context["options"] = Twig\Extension\CoreExtension::merge(["autocomplete" => "new-password", "is_disclosable" => false, "rand" => (((CoreExtension::getAttribute($this->env, $this->source,             // line 417
($context["options"] ?? null), "rand", [], "any", true, true, false, 417) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "rand", [], "any", false, false, false, 417)))) ? (CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "rand", [], "any", false, false, false, 417)) : (Twig\Extension\CoreExtension::random($this->env->getCharset())))],             // line 418
($context["options"] ?? null));
            // line 419
            yield "   ";
            $context["options"] = Twig\Extension\CoreExtension::merge(["id" => ((CoreExtension::getAttribute($this->env, $this->source,             // line 420
($context["options"] ?? null), "id", [], "any", true, true, false, 420)) ? (CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "id", [], "any", false, false, false, 420)) : ((($context["name"] ?? null) . Twig\Extension\CoreExtension::random($this->env->getCharset())))), "clearable" => ((CoreExtension::getAttribute($this->env, $this->source,             // line 421
($context["options"] ?? null), "clearable", [], "any", true, true, false, 421)) ? (CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "clearable", [], "any", false, false, false, 421)) : ( !CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "is_disclosable", [], "any", false, false, false, 421)))],             // line 422
($context["options"] ?? null));
            // line 423
            yield "
   ";
            // line 424
            $context["field"] = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
                // line 425
                yield "      <input type=\"password\" id=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "id", [], "any", false, false, false, 425), "html", null, true);
                yield "\"
             class=\"form-control ";
                // line 426
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "input_addclass", [], "any", false, false, false, 426), "html", null, true);
                yield "\"
             name=\"";
                // line 427
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["name"] ?? null), "html", null, true);
                yield "\"
             value=\"";
                // line 428
                ((CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "is_disclosable", [], "any", false, false, false, 428)) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["value"] ?? null), "html", null, true)) : (yield ""));
                yield "\"
         ";
                // line 429
                yield ((CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "readonly", [], "any", false, false, false, 429)) ? ("readonly") : (""));
                yield "
         ";
                // line 430
                yield ((CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "disabled", [], "any", false, false, false, 430)) ? ("disabled") : (""));
                yield "
         ";
                // line 431
                yield ((CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "required", [], "any", false, false, false, 431)) ? ("required") : (""));
                yield " />
   ";
                return; yield '';
            })())) ? '' : new Markup($tmp, $this->env->getCharset());
            // line 433
            yield "   ";
            $context["btn_group"] = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
                // line 434
                yield "      &nbsp;<i class=\"far fa-eye pointer disclose\" onmousedown=\"showDisclosablePasswordField('";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "id", [], "any", false, false, false, 434), "html", null, true);
                yield "')\"
               onmouseup=\"hideDisclosablePasswordField('";
                // line 435
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "id", [], "any", false, false, false, 435), "html", null, true);
                yield "')\"
               onmouseout=\"hideDisclosablePasswordField('";
                // line 436
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "id", [], "any", false, false, false, 436), "html", null, true);
                yield "')\"></i>
      &nbsp;<i class=\"fa fa-paste pointer disclose\" onclick=\"copyDisclosablePasswordFieldToClipboard('";
                // line 437
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "id", [], "any", false, false, false, 437), "html", null, true);
                yield "')\"></i>
   ";
                return; yield '';
            })())) ? '' : new Markup($tmp, $this->env->getCharset());
            // line 439
            yield "   ";
            if (CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "is_disclosable", [], "any", false, false, false, 439)) {
                // line 440
                yield "      ";
                $context["label"] = (($context["label"] ?? null) . ($context["btn_group"] ?? null));
                // line 441
                yield "   ";
            }
            // line 442
            yield "   ";
            if (CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "clearable", [], "any", false, false, false, 442)) {
                // line 443
                yield "      ";
                $context["clear_chk"] = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
                    // line 444
                    yield "         <input type=\"checkbox\" name=\"_blank_";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["name"] ?? null), "html", null, true);
                    yield "\" id=\"_blank_";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["name"] ?? null), "html", null, true);
                    yield "\">&nbsp;<label for=\"_blank_";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["name"] ?? null), "html", null, true);
                    yield "\">";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(__("Clear"), "html", null, true);
                    yield "</label>
      ";
                    return; yield '';
                })())) ? '' : new Markup($tmp, $this->env->getCharset());
                // line 446
                yield "      ";
                $context["field"] = (($context["field"] ?? null) . ($context["clear_chk"] ?? null));
                // line 447
                yield "   ";
            }
            // line 448
            yield "
   ";
            // line 449
            yield CoreExtension::callMacro($macros["_self"], "macro_field", [($context["name"] ?? null), ($context["field"] ?? null), ($context["label"] ?? null), ($context["options"] ?? null)], 449, $context, $this->getSourceContext());
            yield "
";
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
    }

    // line 452
    public function macro_emailField($__name__ = null, $__value__ = null, $__label__ = "", $__options__ = [], ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "name" => $__name__,
            "value" => $__value__,
            "label" => $__label__,
            "options" => $__options__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        return ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
            // line 453
            yield "   ";
            $context["options"] = Twig\Extension\CoreExtension::merge(["type" => "email"], ($context["options"] ?? null));
            // line 454
            yield "   ";
            yield CoreExtension::callMacro($macros["_self"], "macro_textField", [($context["name"] ?? null), ($context["value"] ?? null), ($context["label"] ?? null), ($context["options"] ?? null)], 454, $context, $this->getSourceContext());
            yield "
";
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
    }

    // line 457
    public function macro_fileField($__name__ = null, $__value__ = null, $__label__ = "", $__options__ = [], ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "name" => $__name__,
            "value" => $__value__,
            "label" => $__label__,
            "options" => $__options__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        return ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
            // line 458
            yield "   ";
            $context["options"] = Twig\Extension\CoreExtension::merge(["rand" => Twig\Extension\CoreExtension::random($this->env->getCharset()), "name" =>             // line 460
($context["name"] ?? null), "simple" => false],             // line 462
($context["options"] ?? null));
            // line 463
            yield "   ";
            $context["field"] = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
                // line 464
                yield "      ";
                if (CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "simple", [], "any", false, false, false, 464)) {
                    // line 465
                    yield "        <input type=\"file\" id=\"%id%\"
               class=\"form-control ";
                    // line 466
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "input_addclass", [], "any", false, false, false, 466), "html", null, true);
                    yield "\"
               name=\"";
                    // line 467
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["name"] ?? null), "html", null, true);
                    yield "\"
               ";
                    // line 468
                    yield ((CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "multiple", [], "any", false, false, false, 468)) ? ("multiple") : (""));
                    yield "
               ";
                    // line 469
                    yield ((CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "readonly", [], "any", false, false, false, 469)) ? ("readonly") : (""));
                    yield "
               ";
                    // line 470
                    yield ((CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "disabled", [], "any", false, false, false, 470)) ? ("disabled") : (""));
                    yield "
               ";
                    // line 471
                    yield ((CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "required", [], "any", false, false, false, 471)) ? ("required") : (""));
                    yield " />
      ";
                } else {
                    // line 473
                    yield "        ";
                    $this->extensions['Glpi\Application\View\Extension\PhpExtension']->call("Html::file", [($context["options"] ?? null)]);
                    // line 474
                    yield "      ";
                }
                // line 475
                yield "   ";
                return; yield '';
            })())) ? '' : new Markup($tmp, $this->env->getCharset());
            // line 476
            yield "   ";
            yield CoreExtension::callMacro($macros["_self"], "macro_field", [($context["name"] ?? null), ($context["field"] ?? null), ($context["label"] ?? null), ($context["options"] ?? null)], 476, $context, $this->getSourceContext());
            yield "
";
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
    }

    // line 479
    public function macro_imageField($__name__ = null, $__value__ = null, $__label__ = "", $__options__ = [], $__link_options__ = [], ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "name" => $__name__,
            "value" => $__value__,
            "label" => $__label__,
            "options" => $__options__,
            "link_options" => $__link_options__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        return ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
            // line 480
            yield "   ";
            $context["field"] = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
                // line 481
                yield "      <div class=\"img-overlay-wrapper position-relative\">
         ";
                // line 482
                $context["clearable"] = (($__internal_compile_8 = ($context["options"] ?? null)) && is_array($__internal_compile_8) || $__internal_compile_8 instanceof ArrayAccess ? ($__internal_compile_8["clearable"] ?? null) : null);
                // line 483
                yield "         ";
                $context["url"] = (((CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "url", [], "array", true, true, false, 483) &&  !(null === (($__internal_compile_9 = ($context["options"] ?? null)) && is_array($__internal_compile_9) || $__internal_compile_9 instanceof ArrayAccess ? ($__internal_compile_9["url"] ?? null) : null)))) ? ((($__internal_compile_10 = ($context["options"] ?? null)) && is_array($__internal_compile_10) || $__internal_compile_10 instanceof ArrayAccess ? ($__internal_compile_10["url"] ?? null) : null)) : (null));
                // line 484
                yield "         ";
                $context["options"] = Twig\Extension\CoreExtension::filter($this->env, ($context["options"] ?? null), function ($__v__, $__k__) use ($context, $macros) { $context["v"] = $__v__; $context["k"] = $__k__; return ((($context["k"] ?? null) != "url") && (($context["k"] ?? null) != "clearable")); });
                // line 485
                yield "         ";
                if ( !Twig\Extension\CoreExtension::testEmpty(($context["url"] ?? null))) {
                    // line 486
                    yield "            <a href=\"";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["url"] ?? null), "html", null, true);
                    yield "\" ";
                    yield $this->extensions['Glpi\Application\View\Extension\PhpExtension']->call("Html::parseAttributes", [($context["link_options"] ?? null)]);
                    yield ">
         ";
                }
                // line 488
                yield "               <img src=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Glpi\Application\View\Extension\DataHelpersExtension']->getVerbatimValue(($context["value"] ?? null)), "html", null, true);
                yield "\" ";
                yield $this->extensions['Glpi\Application\View\Extension\PhpExtension']->call("Html::parseAttributes", [($context["options"] ?? null)]);
                yield " />
         ";
                // line 489
                if ( !Twig\Extension\CoreExtension::testEmpty(($context["url"] ?? null))) {
                    // line 490
                    yield "            </a>
         ";
                }
                // line 492
                yield "         ";
                if (($context["clearable"] ?? null)) {
                    // line 493
                    yield "            <input type=\"hidden\" name=\"_blank_";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["name"] ?? null), "html", null, true);
                    yield "\" />
            <button type=\"button\" class=\"btn p-2 position-absolute top-0 start-0\" title=\"";
                    // line 494
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(__("Delete"), "html", null, true);
                    yield "\"
                    onclick=\"const blank_input = \$('input[name=\\'_blank_";
                    // line 495
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["name"] ?? null), "html", null, true);
                    yield "\\']'); blank_input.val(blank_input.val() ? '' : true); \$(this).toggleClass('btn-danger')\">
               <i class=\"ti ti-x\"></i>
            </button>
         ";
                }
                // line 499
                yield "      </div>
   ";
                return; yield '';
            })())) ? '' : new Markup($tmp, $this->env->getCharset());
            // line 501
            yield "   ";
            yield CoreExtension::callMacro($macros["_self"], "macro_field", [($context["name"] ?? null), ($context["field"] ?? null), ($context["label"] ?? null), ($context["options"] ?? null)], 501, $context, $this->getSourceContext());
            yield "
";
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
    }

    // line 504
    public function macro_imageGalleryField($__name__ = null, $__value__ = null, $__label__ = "", $__options__ = [], ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "name" => $__name__,
            "value" => $__value__,
            "label" => $__label__,
            "options" => $__options__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        return ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
            // line 505
            yield "   ";
            $context["field"] = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
                // line 506
                yield "      <div class=\"picture_gallery d-flex flex-wrap overflow-auto p-3\">
         ";
                // line 507
                $context['_parent'] = $context;
                $context['_seq'] = CoreExtension::ensureTraversable(($context["value"] ?? null));
                foreach ($context['_seq'] as $context["i"] => $context["picture"]) {
                    // line 508
                    yield "            <div style=\"position: relative; width: fit-content\">
               ";
                    // line 509
                    yield CoreExtension::callMacro($macros["_self"], "macro_imageField", [((($context["name"] ?? null) . "_") . $context["i"]), $context["picture"], "", ["style" => "max-width: 300px; max-height: 150px", "class" => "picture_square", "clearable" => (($__internal_compile_11 =                     // line 512
($context["options"] ?? null)) && is_array($__internal_compile_11) || $__internal_compile_11 instanceof ArrayAccess ? ($__internal_compile_11["clearable"] ?? null) : null), "no_label" => true]], 509, $context, $this->getSourceContext());
                    // line 514
                    yield "
            </div>
         ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['i'], $context['picture'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 517
                yield "      </div>
      ";
                // line 518
                yield CoreExtension::callMacro($macros["_self"], "macro_fileField", [($context["name"] ?? null), null, "", ["onlyimages" => true, "multiple" => true]], 518, $context, $this->getSourceContext());
                // line 521
                yield "
   ";
                return; yield '';
            })())) ? '' : new Markup($tmp, $this->env->getCharset());
            // line 523
            yield "
   ";
            // line 524
            $context["id"] = (((Twig\Extension\CoreExtension::length($this->env->getCharset(), CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "id", [], "any", false, false, false, 524)) > 0)) ? (CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "id", [], "any", false, false, false, 524)) : (((($context["name"] ?? null) . "_") . CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "rand", [], "any", false, false, false, 524))));
            // line 525
            yield "   ";
            yield CoreExtension::callMacro($macros["_self"], "macro_label", [($context["label"] ?? null), ($context["id"] ?? null), ($context["options"] ?? null)], 525, $context, $this->getSourceContext());
            yield "
   ";
            // line 526
            yield CoreExtension::callMacro($macros["_self"], "macro_field", [($context["name"] ?? null), ($context["field"] ?? null), ($context["label"] ?? null), Twig\Extension\CoreExtension::merge(($context["options"] ?? null), ["full_width" => true, "no_label" => true])], 526, $context, $this->getSourceContext());
            // line 529
            yield "
";
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
    }

    // line 532
    public function macro_hiddenField($__name__ = null, $__value__ = null, $__label__ = "", $__options__ = [], ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "name" => $__name__,
            "value" => $__value__,
            "label" => $__label__,
            "options" => $__options__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        return ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
            // line 533
            yield "   ";
            if (CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "no_label", [], "any", false, false, false, 533)) {
                // line 534
                yield "      ";
                $context["options"] = Twig\Extension\CoreExtension::merge(["mb" => "mb-0"],                 // line 536
($context["options"] ?? null));
                // line 537
                yield "   ";
            }
            // line 538
            yield "   ";
            $context["field"] = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
                // line 539
                yield "      <input type=\"hidden\" id=\"%id%\"
             class=\"form-control ";
                // line 540
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "input_addclass", [], "any", false, false, false, 540), "html", null, true);
                yield "\"
             name=\"";
                // line 541
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["name"] ?? null), "html", null, true);
                yield "\" value=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Glpi\Application\View\Extension\DataHelpersExtension']->getVerbatimValue(($context["value"] ?? null)), "html", null, true);
                yield "\"
         ";
                // line 542
                yield ((CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "readonly", [], "any", false, false, false, 542)) ? ("readonly") : (""));
                yield "
         ";
                // line 543
                yield ((CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "disabled", [], "any", false, false, false, 543)) ? ("disabled") : (""));
                yield "
         ";
                // line 544
                yield ((CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "required", [], "any", false, false, false, 544)) ? ("required") : (""));
                yield " />
   ";
                return; yield '';
            })())) ? '' : new Markup($tmp, $this->env->getCharset());
            // line 546
            yield "   ";
            yield CoreExtension::callMacro($macros["_self"], "macro_field", [($context["name"] ?? null), ($context["field"] ?? null), ($context["label"] ?? null), ($context["options"] ?? null)], 546, $context, $this->getSourceContext());
            yield "
";
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
    }

    // line 549
    public function macro_dropdownNumberField($__name__ = null, $__value__ = null, $__label__ = "", $__options__ = [], ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "name" => $__name__,
            "value" => $__value__,
            "label" => $__label__,
            "options" => $__options__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        return ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
            // line 550
            yield "    ";
            $context["options"] = Twig\Extension\CoreExtension::merge(["rand" => Twig\Extension\CoreExtension::random($this->env->getCharset()), "width" => "100%"],             // line 553
($context["options"] ?? null));
            // line 554
            yield "
   ";
            // line 555
            if (CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "disabled", [], "any", false, false, false, 555)) {
                // line 556
                yield "      ";
                $context["options"] = Twig\Extension\CoreExtension::merge(($context["options"] ?? null), ["specific_tags" => ["disabled" => "disabled"]]);
                // line 557
                yield "   ";
            }
            // line 558
            yield "   ";
            if (CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "fields_template", [], "any", false, false, false, 558), "isMandatoryField", [($context["name"] ?? null)], "method", false, false, false, 558)) {
                // line 559
                yield "      ";
                $context["options"] = Twig\Extension\CoreExtension::merge(["specific_tags" => ["required" => true]], ($context["options"] ?? null));
                // line 560
                yield "   ";
            }
            // line 561
            yield "
   ";
            // line 562
            $context["field"] = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
                // line 563
                yield "      ";
                $this->extensions['Glpi\Application\View\Extension\PhpExtension']->call("Dropdown::showNumber", [($context["name"] ?? null), Twig\Extension\CoreExtension::merge(["value" =>                 // line 564
($context["value"] ?? null), "rand" =>                 // line 565
($context["rand"] ?? null)],                 // line 566
($context["options"] ?? null))]);
                // line 567
                yield "   ";
                return; yield '';
            })())) ? '' : new Markup($tmp, $this->env->getCharset());
            // line 568
            yield "
   ";
            // line 569
            yield CoreExtension::callMacro($macros["_self"], "macro_field", [($context["name"] ?? null), ($context["field"] ?? null), ($context["label"] ?? null), Twig\Extension\CoreExtension::merge(($context["options"] ?? null), ["id" => ((("dropdown_" . ($context["name"] ?? null)) . "_") . CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "rand", [], "any", false, false, false, 569))])], 569, $context, $this->getSourceContext());
            yield "
";
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
    }

    // line 572
    public function macro_dropdownArrayField($__name__ = null, $__value__ = null, $__elements__ = null, $__label__ = "", $__options__ = [], ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "name" => $__name__,
            "value" => $__value__,
            "elements" => $__elements__,
            "label" => $__label__,
            "options" => $__options__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        return ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
            // line 573
            yield "    ";
            $context["options"] = Twig\Extension\CoreExtension::merge(["rand" => Twig\Extension\CoreExtension::random($this->env->getCharset()), "width" => "100%"],             // line 576
($context["options"] ?? null));
            // line 577
            yield "
   ";
            // line 578
            if (CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "disabled", [], "any", false, false, false, 578)) {
                // line 579
                yield "      ";
                $context["options"] = Twig\Extension\CoreExtension::merge(($context["options"] ?? null), ["specific_tags" => ["disabled" => "disabled"]]);
                // line 580
                yield "   ";
            }
            // line 581
            yield "
   ";
            // line 582
            if (CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "fields_template", [], "any", false, false, false, 582), "isMandatoryField", [($context["name"] ?? null)], "method", false, false, false, 582)) {
                // line 583
                yield "      ";
                $context["options"] = Twig\Extension\CoreExtension::merge(["required" => true], ($context["options"] ?? null));
                // line 584
                yield "   ";
            }
            // line 585
            yield "
   ";
            // line 586
            $context["field"] = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
                // line 587
                yield "      ";
                $this->extensions['Glpi\Application\View\Extension\PhpExtension']->call("Dropdown::showFromArray", [($context["name"] ?? null), ($context["elements"] ?? null), Twig\Extension\CoreExtension::merge(["value" => $this->extensions['Glpi\Application\View\Extension\DataHelpersExtension']->getVerbatimValue(                // line 588
($context["value"] ?? null)), "rand" =>                 // line 589
($context["rand"] ?? null)],                 // line 590
($context["options"] ?? null))]);
                // line 591
                yield "   ";
                return; yield '';
            })())) ? '' : new Markup($tmp, $this->env->getCharset());
            // line 592
            yield "
   ";
            // line 593
            yield CoreExtension::callMacro($macros["_self"], "macro_field", [($context["name"] ?? null), ($context["field"] ?? null), ($context["label"] ?? null), Twig\Extension\CoreExtension::merge(($context["options"] ?? null), ["id" => ((("dropdown_" . ($context["name"] ?? null)) . "_") . CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "rand", [], "any", false, false, false, 593))])], 593, $context, $this->getSourceContext());
            yield "
";
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
    }

    // line 596
    public function macro_dropdownTimestampField($__name__ = null, $__value__ = null, $__label__ = "", $__options__ = [], ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "name" => $__name__,
            "value" => $__value__,
            "label" => $__label__,
            "options" => $__options__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        return ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
            // line 597
            yield "    ";
            $context["options"] = Twig\Extension\CoreExtension::merge(["rand" => Twig\Extension\CoreExtension::random($this->env->getCharset()), "width" => "100%"],             // line 600
($context["options"] ?? null));
            // line 601
            yield "   ";
            if (CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "fields_template", [], "any", false, false, false, 601), "isMandatoryField", [($context["name"] ?? null)], "method", false, false, false, 601)) {
                // line 602
                yield "      ";
                $context["options"] = Twig\Extension\CoreExtension::merge(["required" => true], ($context["options"] ?? null));
                // line 603
                yield "   ";
            }
            // line 604
            yield "
   ";
            // line 605
            if (CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "disabled", [], "any", false, false, false, 605)) {
                // line 606
                yield "      ";
                $context["options"] = Twig\Extension\CoreExtension::merge(($context["options"] ?? null), ["specific_tags" => ["disabled" => "disabled"]]);
                // line 607
                yield "   ";
            }
            // line 608
            yield "
   ";
            // line 609
            $context["field"] = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
                // line 610
                yield "      ";
                $this->extensions['Glpi\Application\View\Extension\PhpExtension']->call("Dropdown::showTimestamp", [($context["name"] ?? null), Twig\Extension\CoreExtension::merge(["value" =>                 // line 611
($context["value"] ?? null), "rand" =>                 // line 612
($context["rand"] ?? null)],                 // line 613
($context["options"] ?? null))]);
                // line 614
                yield "   ";
                return; yield '';
            })())) ? '' : new Markup($tmp, $this->env->getCharset());
            // line 615
            yield "
   ";
            // line 616
            yield CoreExtension::callMacro($macros["_self"], "macro_field", [($context["name"] ?? null), ($context["field"] ?? null), ($context["label"] ?? null), Twig\Extension\CoreExtension::merge(($context["options"] ?? null), ["id" => ((("dropdown_" . ($context["name"] ?? null)) . "_") . CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "rand", [], "any", false, false, false, 616))])], 616, $context, $this->getSourceContext());
            yield "
";
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
    }

    // line 619
    public function macro_dropdownYesNo($__name__ = null, $__value__ = null, $__label__ = "", $__options__ = [], ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "name" => $__name__,
            "value" => $__value__,
            "label" => $__label__,
            "options" => $__options__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        return ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
            // line 620
            yield "    ";
            $context["options"] = Twig\Extension\CoreExtension::merge(["rand" => Twig\Extension\CoreExtension::random($this->env->getCharset()), "width" => "100%"],             // line 623
($context["options"] ?? null));
            // line 624
            yield "   ";
            if (CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "fields_template", [], "any", false, false, false, 624), "isMandatoryField", [($context["name"] ?? null)], "method", false, false, false, 624)) {
                // line 625
                yield "      ";
                $context["options"] = Twig\Extension\CoreExtension::merge(["required" => true], ($context["options"] ?? null));
                // line 626
                yield "   ";
            }
            // line 627
            yield "
   ";
            // line 628
            if (CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "disabled", [], "any", false, false, false, 628)) {
                // line 629
                yield "      ";
                $context["options"] = Twig\Extension\CoreExtension::merge(($context["options"] ?? null), ["specific_tags" => ["disabled" => "disabled"]]);
                // line 630
                yield "   ";
            }
            // line 631
            yield "
   ";
            // line 632
            $context["field"] = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
                // line 633
                yield "      ";
                $this->extensions['Glpi\Application\View\Extension\PhpExtension']->call("Dropdown::showYesNo", [($context["name"] ?? null), ($context["value"] ?? null),  -1, Twig\Extension\CoreExtension::merge(["rand" =>                 // line 634
($context["rand"] ?? null)],                 // line 635
($context["options"] ?? null))]);
                // line 636
                yield "   ";
                return; yield '';
            })())) ? '' : new Markup($tmp, $this->env->getCharset());
            // line 637
            yield "
   ";
            // line 638
            yield CoreExtension::callMacro($macros["_self"], "macro_field", [($context["name"] ?? null), ($context["field"] ?? null), ($context["label"] ?? null), Twig\Extension\CoreExtension::merge(($context["options"] ?? null), ["id" => ((("dropdown_" . ($context["name"] ?? null)) . "_") . CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "rand", [], "any", false, false, false, 638))])], 638, $context, $this->getSourceContext());
            yield "
";
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
    }

    // line 641
    public function macro_dropdownItemTypes($__name__ = null, $__value__ = null, $__label__ = "", $__options__ = [], ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "name" => $__name__,
            "value" => $__value__,
            "label" => $__label__,
            "options" => $__options__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        return ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
            // line 642
            yield "    ";
            $context["options"] = Twig\Extension\CoreExtension::merge(["rand" => Twig\Extension\CoreExtension::random($this->env->getCharset()), "width" => "100%"],             // line 645
($context["options"] ?? null));
            // line 646
            yield "   ";
            if (CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "fields_template", [], "any", false, false, false, 646), "isMandatoryField", [($context["name"] ?? null)], "method", false, false, false, 646)) {
                // line 647
                yield "      ";
                $context["options"] = Twig\Extension\CoreExtension::merge(["required" => true], ($context["options"] ?? null));
                // line 648
                yield "   ";
            }
            // line 649
            yield "
   ";
            // line 650
            if (CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "disabled", [], "any", false, false, false, 650)) {
                // line 651
                yield "      ";
                $context["options"] = Twig\Extension\CoreExtension::merge(($context["options"] ?? null), ["specific_tags" => ["disabled" => "disabled"]]);
                // line 652
                yield "   ";
            }
            // line 653
            yield "
   ";
            // line 654
            $context["types"] = ((CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "types", [], "array", true, true, false, 654)) ? (Twig\Extension\CoreExtension::default((($__internal_compile_12 = ($context["options"] ?? null)) && is_array($__internal_compile_12) || $__internal_compile_12 instanceof ArrayAccess ? ($__internal_compile_12["types"] ?? null) : null), [])) : ([]));
            // line 655
            yield "
   ";
            // line 656
            $context["field"] = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
                // line 657
                yield "      ";
                $this->extensions['Glpi\Application\View\Extension\PhpExtension']->call("Dropdown::showItemTypes", [($context["name"] ?? null), ($context["types"] ?? null), Twig\Extension\CoreExtension::merge(["rand" =>                 // line 658
($context["rand"] ?? null), "value" =>                 // line 659
($context["value"] ?? null)],                 // line 660
($context["options"] ?? null))]);
                // line 661
                yield "   ";
                return; yield '';
            })())) ? '' : new Markup($tmp, $this->env->getCharset());
            // line 662
            yield "
   ";
            // line 663
            yield CoreExtension::callMacro($macros["_self"], "macro_field", [($context["name"] ?? null), ($context["field"] ?? null), ($context["label"] ?? null), Twig\Extension\CoreExtension::merge(($context["options"] ?? null), ["id" => ((("dropdown_" . ($context["name"] ?? null)) . "_") . CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "rand", [], "any", false, false, false, 663))])], 663, $context, $this->getSourceContext());
            yield "
";
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
    }

    // line 666
    public function macro_dropdownItemsFromItemtypes($__name__ = null, $__label__ = "", $__options__ = [], ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "name" => $__name__,
            "label" => $__label__,
            "options" => $__options__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        return ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
            // line 667
            yield "   ";
            $context["options"] = Twig\Extension\CoreExtension::merge(["rand" => Twig\Extension\CoreExtension::random($this->env->getCharset())],             // line 669
($context["options"] ?? null));
            // line 670
            yield "
   ";
            // line 671
            $context["field"] = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
                // line 672
                yield "      ";
                $this->extensions['Glpi\Application\View\Extension\PhpExtension']->call("Dropdown::showSelectItemFromItemtypes", [($context["options"] ?? null)]);
                // line 673
                yield "   ";
                return; yield '';
            })())) ? '' : new Markup($tmp, $this->env->getCharset());
            // line 674
            yield "   ";
            yield CoreExtension::callMacro($macros["_self"], "macro_field", [($context["name"] ?? null), ($context["field"] ?? null), ($context["label"] ?? null), Twig\Extension\CoreExtension::merge(($context["options"] ?? null), ["id" => ((("dropdown_" . ($context["name"] ?? null)) . "_") . CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "rand", [], "any", false, false, false, 674))])], 674, $context, $this->getSourceContext());
            yield "
";
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
    }

    // line 677
    public function macro_dropdownIcons($__name__ = null, $__value__ = null, $__label__ = "", $__options__ = [], ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "name" => $__name__,
            "value" => $__value__,
            "label" => $__label__,
            "options" => $__options__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        return ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
            // line 678
            yield "    ";
            $context["options"] = Twig\Extension\CoreExtension::merge(["rand" => Twig\Extension\CoreExtension::random($this->env->getCharset()), "width" => "100%"],             // line 681
($context["options"] ?? null));
            // line 682
            yield "   ";
            if (CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "fields_template", [], "any", false, false, false, 682), "isMandatoryField", [($context["name"] ?? null)], "method", false, false, false, 682)) {
                // line 683
                yield "      ";
                $context["options"] = Twig\Extension\CoreExtension::merge(["required" => true], ($context["options"] ?? null));
                // line 684
                yield "   ";
            }
            // line 685
            yield "
   ";
            // line 686
            if (CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "disabled", [], "any", false, false, false, 686)) {
                // line 687
                yield "      ";
                $context["options"] = Twig\Extension\CoreExtension::merge(($context["options"] ?? null), ["specific_tags" => ["disabled" => "disabled"]]);
                // line 688
                yield "   ";
            }
            // line 689
            yield "
   ";
            // line 690
            $context["field"] = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
                // line 691
                yield "      ";
                $this->extensions['Glpi\Application\View\Extension\PhpExtension']->call("Dropdown::dropdownIcons", [($context["name"] ?? null), ($context["value"] ?? null), (Twig\Extension\CoreExtension::constant("GLPI_ROOT") . "/pics/icones"), Twig\Extension\CoreExtension::merge(["rand" =>                 // line 692
($context["rand"] ?? null)],                 // line 693
($context["options"] ?? null))]);
                // line 694
                yield "   ";
                return; yield '';
            })())) ? '' : new Markup($tmp, $this->env->getCharset());
            // line 695
            yield "
   ";
            // line 696
            yield CoreExtension::callMacro($macros["_self"], "macro_field", [($context["name"] ?? null), ($context["field"] ?? null), ($context["label"] ?? null), Twig\Extension\CoreExtension::merge(($context["options"] ?? null), ["id" => ((("dropdown_" . ($context["name"] ?? null)) . "_") . CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "rand", [], "any", false, false, false, 696))])], 696, $context, $this->getSourceContext());
            yield "
";
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
    }

    // line 699
    public function macro_dropdownHoursField($__name__ = null, $__value__ = null, $__label__ = "", $__options__ = [], ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "name" => $__name__,
            "value" => $__value__,
            "label" => $__label__,
            "options" => $__options__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        return ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
            // line 700
            yield "    ";
            $context["options"] = Twig\Extension\CoreExtension::merge(["rand" => Twig\Extension\CoreExtension::random($this->env->getCharset()), "width" => "100%"],             // line 703
($context["options"] ?? null));
            // line 704
            yield "   ";
            if (CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "fields_template", [], "any", false, false, false, 704), "isMandatoryField", [($context["name"] ?? null)], "method", false, false, false, 704)) {
                // line 705
                yield "      ";
                $context["options"] = Twig\Extension\CoreExtension::merge(["required" => true], ($context["options"] ?? null));
                // line 706
                yield "   ";
            }
            // line 707
            yield "
   ";
            // line 708
            if (CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "disabled", [], "any", false, false, false, 708)) {
                // line 709
                yield "      ";
                $context["options"] = Twig\Extension\CoreExtension::merge(($context["options"] ?? null), ["specific_tags" => ["disabled" => "disabled"]]);
                // line 710
                yield "   ";
            }
            // line 711
            yield "
   ";
            // line 712
            $context["field"] = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
                // line 713
                yield "      ";
                $this->extensions['Glpi\Application\View\Extension\PhpExtension']->call("Dropdown::showHours", [($context["name"] ?? null), Twig\Extension\CoreExtension::merge(["rand" =>                 // line 714
($context["rand"] ?? null), "value" =>                 // line 715
($context["value"] ?? null)],                 // line 716
($context["options"] ?? null))]);
                // line 717
                yield "   ";
                return; yield '';
            })())) ? '' : new Markup($tmp, $this->env->getCharset());
            // line 718
            yield "
   ";
            // line 719
            yield CoreExtension::callMacro($macros["_self"], "macro_field", [($context["name"] ?? null), ($context["field"] ?? null), ($context["label"] ?? null), Twig\Extension\CoreExtension::merge(($context["options"] ?? null), ["id" => ((("dropdown_" . ($context["name"] ?? null)) . "_") . CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "rand", [], "any", false, false, false, 719))])], 719, $context, $this->getSourceContext());
            yield "
";
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
    }

    // line 722
    public function macro_dropdownField($__itemtype__ = null, $__name__ = null, $__value__ = null, $__label__ = "", $__options__ = [], ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "itemtype" => $__itemtype__,
            "name" => $__name__,
            "value" => $__value__,
            "label" => $__label__,
            "options" => $__options__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        return ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
            // line 723
            yield "   ";
            if (CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "multiple", [], "any", false, false, false, 723)) {
                // line 724
                yield "      ";
                // line 725
                yield "      ";
                $context["defined_input_name"] = (("_" . ($context["name"] ?? null)) . "_defined");
                // line 726
                yield "      <input type=\"hidden\" name=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["defined_input_name"] ?? null), "html", null, true);
                yield "\" value=\"1\"></input>

      ";
                // line 729
                yield "      ";
                $context["name"] = (($context["name"] ?? null) . "[]");
                // line 730
                yield "   ";
            }
            // line 731
            yield "    ";
            $context["options"] = Twig\Extension\CoreExtension::merge(["rand" => Twig\Extension\CoreExtension::random($this->env->getCharset()), "width" => "100%"],             // line 734
($context["options"] ?? null));
            // line 735
            yield "   ";
            if (CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "fields_template", [], "any", false, false, false, 735), "isMandatoryField", [($context["name"] ?? null)], "method", false, false, false, 735)) {
                // line 736
                yield "      ";
                $context["options"] = Twig\Extension\CoreExtension::merge(["specific_tags" => ["required" => true]], ($context["options"] ?? null));
                // line 737
                yield "   ";
            }
            // line 738
            yield "
   ";
            // line 739
            if (CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "disabled", [], "any", false, false, false, 739)) {
                // line 740
                yield "      ";
                $context["options"] = Twig\Extension\CoreExtension::merge(($context["options"] ?? null), ["specific_tags" => ["disabled" => "disabled"]]);
                // line 741
                yield "   ";
            }
            // line 742
            yield "
   ";
            // line 743
            $context["field"] = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
                // line 744
                yield "      ";
                yield $this->extensions['Glpi\Application\View\Extension\ItemtypeExtension']->getItemtypeDropdown(($context["itemtype"] ?? null), Twig\Extension\CoreExtension::merge(["name" =>                 // line 745
($context["name"] ?? null), "value" =>                 // line 746
($context["value"] ?? null), "rand" =>                 // line 747
($context["rand"] ?? null)],                 // line 748
($context["options"] ?? null)));
                yield "
   ";
                return; yield '';
            })())) ? '' : new Markup($tmp, $this->env->getCharset());
            // line 750
            yield "
   ";
            // line 751
            if ( !Twig\Extension\CoreExtension::testEmpty(Twig\Extension\CoreExtension::trim(($context["field"] ?? null)))) {
                // line 752
                yield "      ";
                yield CoreExtension::callMacro($macros["_self"], "macro_field", [($context["name"] ?? null), ($context["field"] ?? null), ($context["label"] ?? null), Twig\Extension\CoreExtension::merge(($context["options"] ?? null), ["id" => ((("dropdown_" . ($context["name"] ?? null)) . "_") . CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "rand", [], "any", false, false, false, 752))])], 752, $context, $this->getSourceContext());
                yield "
   ";
            }
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
    }

    // line 756
    public function macro_htmlField($__name__ = null, $__value__ = null, $__label__ = "", $__options__ = [], ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "name" => $__name__,
            "value" => $__value__,
            "label" => $__label__,
            "options" => $__options__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        return ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
            // line 757
            yield "   ";
            if ((Twig\Extension\CoreExtension::length($this->env->getCharset(), ($context["value"] ?? null)) == 0)) {
                // line 758
                yield "      ";
                $context["value"] = "&nbsp;";
                // line 759
                yield "   ";
            }
            // line 760
            yield "
   ";
            // line 761
            $context["value"] = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
                // line 762
                yield "      <span class=\"form-control-plaintext\">";
                yield ($context["value"] ?? null);
                yield "</span>
   ";
                return; yield '';
            })())) ? '' : new Markup($tmp, $this->env->getCharset());
            // line 764
            yield "   ";
            yield CoreExtension::callMacro($macros["_self"], "macro_field", [($context["name"] ?? null), ($context["value"] ?? null), ($context["label"] ?? null), ($context["options"] ?? null)], 764, $context, $this->getSourceContext());
            yield "
";
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
    }

    // line 767
    public function macro_field($__name__ = null, $__field__ = null, $__label__ = "", $__options__ = [], ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "name" => $__name__,
            "field" => $__field__,
            "label" => $__label__,
            "options" => $__options__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        return ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
            // line 768
            yield "   ";
            $context["options"] = Twig\Extension\CoreExtension::merge(["rand" => Twig\Extension\CoreExtension::random($this->env->getCharset()), "is_horizontal" => true, "include_field" => true, "add_field_html" => "", "locked" => false, "locked_fields" => []],             // line 775
($context["options"] ?? null));
            // line 776
            yield "
   ";
            // line 777
            if (CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "locked_fields", [], "any", false, true, false, 777), ($context["name"] ?? null), [], "array", true, true, false, 777)) {
                // line 778
                yield "      ";
                $context["options"] = Twig\Extension\CoreExtension::merge(($context["options"] ?? null), ["locked" => true, "locked_value" => (($__internal_compile_13 = CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "locked_fields", [], "any", false, false, false, 778)) && is_array($__internal_compile_13) || $__internal_compile_13 instanceof ArrayAccess ? ($__internal_compile_13[($context["name"] ?? null)] ?? null) : null)]);
                // line 779
                yield "   ";
            } elseif (CoreExtension::inFilter(($context["name"] ?? null), CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "locked_fields", [], "any", false, false, false, 779))) {
                // line 780
                yield "      ";
                $context["options"] = Twig\Extension\CoreExtension::merge(($context["options"] ?? null), ["locked" => true]);
                // line 781
                yield "   ";
            }
            // line 782
            yield "
   ";
            // line 783
            if ( !CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "include_field", [], "any", false, false, false, 783)) {
                // line 784
                yield "      ";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["field"] ?? null), "html", null, true);
                yield "
   ";
            } else {
                // line 786
                yield "      ";
                $context["id"] = (((Twig\Extension\CoreExtension::length($this->env->getCharset(), CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "id", [], "any", false, false, false, 786)) > 0)) ? (CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "id", [], "any", false, false, false, 786)) : (((($context["name"] ?? null) . "_") . CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "rand", [], "any", false, false, false, 786))));
                // line 787
                yield "      ";
                $context["field"] = Twig\Extension\CoreExtension::replace(($context["field"] ?? null), ["%id%" => ($context["id"] ?? null)]);
                // line 788
                yield "      ";
                $context["add_field_html"] = (((Twig\Extension\CoreExtension::length($this->env->getCharset(), CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "add_field_html", [], "any", false, false, false, 788)) > 0)) ? (CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "add_field_html", [], "any", false, false, false, 788)) : (""));
                // line 789
                yield "
      ";
                // line 790
                if (( !CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "fields_template", [], "any", true, true, false, 790) ||  !CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "fields_template", [], "any", false, false, false, 790), "isHiddenField", [($context["name"] ?? null)], "method", false, false, false, 790))) {
                    // line 791
                    yield "         ";
                    if (CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "no_label", [], "any", false, false, false, 791)) {
                        // line 792
                        yield "            ";
                        yield CoreExtension::callMacro($macros["_self"], "macro_noLabelField", [($context["field"] ?? null), ($context["id"] ?? null), ($context["add_field_html"] ?? null), ($context["options"] ?? null)], 792, $context, $this->getSourceContext());
                        yield "
         ";
                    } elseif (CoreExtension::getAttribute($this->env, $this->source,                     // line 793
($context["options"] ?? null), "is_horizontal", [], "any", false, false, false, 793)) {
                        // line 794
                        yield "            ";
                        yield CoreExtension::callMacro($macros["_self"], "macro_horizontalField", [($context["label"] ?? null), ($context["field"] ?? null), ($context["id"] ?? null), ($context["add_field_html"] ?? null), Twig\Extension\CoreExtension::merge(($context["options"] ?? null), ["name" => ($context["name"] ?? null)])], 794, $context, $this->getSourceContext());
                        yield "
         ";
                    } else {
                        // line 796
                        yield "            ";
                        yield CoreExtension::callMacro($macros["_self"], "macro_verticalField", [($context["label"] ?? null), ($context["field"] ?? null), ($context["id"] ?? null), ($context["add_field_html"] ?? null), Twig\Extension\CoreExtension::merge(($context["options"] ?? null), ["name" => ($context["name"] ?? null)])], 796, $context, $this->getSourceContext());
                        yield "
         ";
                    }
                    // line 798
                    yield "      ";
                }
                // line 799
                yield "   ";
            }
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
    }

    // line 802
    public function macro_ajaxField($__id__ = null, $__value__ = null, $__label__ = "", $__options__ = [], ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "id" => $__id__,
            "value" => $__value__,
            "label" => $__label__,
            "options" => $__options__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        return ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
            // line 803
            yield "   ";
            $context["field"] = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
                // line 804
                yield "      <div id=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["id"] ?? null), "html", null, true);
                yield "\" class=\"form-field-ajax\">
         ";
                // line 805
                if ( !(null === ($context["value"] ?? null))) {
                    // line 806
                    yield "            ";
                    yield ($context["value"] ?? null);
                    yield "
         ";
                }
                // line 808
                yield "      </div>
   ";
                return; yield '';
            })())) ? '' : new Markup($tmp, $this->env->getCharset());
            // line 810
            yield "   ";
            yield CoreExtension::callMacro($macros["_self"], "macro_field", [($context["id"] ?? null), ($context["field"] ?? null), ($context["label"] ?? null), Twig\Extension\CoreExtension::merge(($context["options"] ?? null), ["id" => ((($context["id"] ?? null) . "_") . CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "rand", [], "any", false, false, false, 810))])], 810, $context, $this->getSourceContext());
            yield "
";
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
    }

    // line 813
    public function macro_nullField($__options__ = [], ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "options" => $__options__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        return ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
            // line 814
            yield "   ";
            $context["options"] = Twig\Extension\CoreExtension::merge(["is_horizontal" => true], ($context["options"] ?? null));
            // line 815
            yield "
   ";
            // line 816
            if (CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "is_horizontal", [], "any", false, false, false, 816)) {
                // line 817
                yield "      ";
                yield CoreExtension::callMacro($macros["_self"], "macro_horizontalField", [($context["label"] ?? null), ($context["field"] ?? null), ($context["id"] ?? null), ($context["add_field_html"] ?? null), ($context["options"] ?? null)], 817, $context, $this->getSourceContext());
                yield "
   ";
            } else {
                // line 819
                yield "      ";
                yield CoreExtension::callMacro($macros["_self"], "macro_verticalField", [($context["label"] ?? null), ($context["field"] ?? null), ($context["id"] ?? null), ($context["add_field_html"] ?? null), ($context["options"] ?? null)], 819, $context, $this->getSourceContext());
                yield "
   ";
            }
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
    }

    // line 824
    public function macro_noLabelField($__field__ = null, $__id__ = "", $__add_field_html__ = "", $__options__ = [], ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "field" => $__field__,
            "id" => $__id__,
            "add_field_html" => $__add_field_html__,
            "options" => $__options__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        return ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
            // line 825
            yield "   ";
            $context["options"] = Twig\Extension\CoreExtension::merge(["full_width" => false, "mb" => "mb-3"],             // line 828
($context["options"] ?? null));
            // line 829
            yield "
   ";
            // line 830
            $context["class"] = (((CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "field_class", [], "any", true, true, false, 830) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "field_class", [], "any", false, false, false, 830)))) ? (CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "field_class", [], "any", false, false, false, 830)) : ("col-12 col-sm-6"));
            // line 831
            yield "   ";
            if (CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "full_width", [], "any", false, false, false, 831)) {
                // line 832
                yield "      ";
                $context["class"] = "col-12";
                // line 833
                yield "   ";
            }
            // line 834
            yield "
   <div class=\"";
            // line 835
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["class"] ?? null), "html", null, true);
            yield " ";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "mb", [], "any", false, false, false, 835), "html", null, true);
            yield "\">
      ";
            // line 836
            yield ($context["field"] ?? null);
            yield "
      ";
            // line 837
            yield ($context["add_field_html"] ?? null);
            yield "
   </div>
";
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
    }

    // line 842
    public function macro_horizontalField($__label__ = null, $__field__ = null, $__id__ = null, $__add_field_html__ = "", $__options__ = [], ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "label" => $__label__,
            "field" => $__field__,
            "id" => $__id__,
            "add_field_html" => $__add_field_html__,
            "options" => $__options__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        return ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
            // line 843
            yield "   ";
            $context["options"] = Twig\Extension\CoreExtension::merge(["full_width" => false, "full_width_adapt_column" => true, "align_label_right" => true, "mb" => "mb-2", "field_class" => "col-12 col-sm-6", "add_field_class" => "", "add_field_attribs" => [], "center" => false],             // line 852
($context["options"] ?? null));
            // line 853
            yield "
   ";
            // line 854
            if (CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "icon_label", [], "any", false, false, false, 854)) {
                // line 855
                yield "      ";
                $context["options"] = Twig\Extension\CoreExtension::merge(["label_class" => "col-2", "input_class" => "col-10"],                 // line 858
($context["options"] ?? null));
                // line 859
                yield "   ";
            }
            // line 860
            yield "
   ";
            // line 861
            if (CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "full_width", [], "any", false, false, false, 861)) {
                // line 862
                yield "      ";
                $context["options"] = Twig\Extension\CoreExtension::merge(($context["options"] ?? null), ["field_class" => "col-12"]);
                // line 865
                yield "
      ";
                // line 866
                if (CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "full_width_adapt_column", [], "any", false, false, false, 866)) {
                    // line 867
                    yield "         ";
                    $context["options"] = Twig\Extension\CoreExtension::merge(["label_class" => "col-xxl-4", "input_class" => "col-xxl-8"],                     // line 870
($context["options"] ?? null));
                    // line 871
                    yield "      ";
                }
                // line 872
                yield "   ";
            }
            // line 873
            yield "
   ";
            // line 874
            $context["options"] = Twig\Extension\CoreExtension::merge(["label_class" => "col-xxl-5", "input_class" => "col-xxl-7"],             // line 877
($context["options"] ?? null));
            // line 878
            yield "
   ";
            // line 879
            if (CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "align_label_right", [], "any", false, false, false, 879)) {
                // line 880
                yield "      ";
                $context["options"] = Twig\Extension\CoreExtension::merge(($context["options"] ?? null), ["label_class" => (CoreExtension::getAttribute($this->env, $this->source,                 // line 881
($context["options"] ?? null), "label_class", [], "any", false, false, false, 881) . " text-xxl-end")]);
                // line 883
                yield "   ";
            }
            // line 884
            yield "
   ";
            // line 885
            if ( !Twig\Extension\CoreExtension::testEmpty(CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "add_field_attribs", [], "any", false, false, false, 885))) {
                // line 886
                yield "      ";
                $context["extra_attribs"] = $this->extensions['Glpi\Application\View\Extension\PhpExtension']->call("Html::parseAttributes", ["options" => CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "add_field_attribs", [], "any", false, false, false, 886)]);
                // line 887
                yield "   ";
            } else {
                // line 888
                yield "      ";
                $context["extra_attribs"] = "";
                // line 889
                yield "   ";
            }
            // line 890
            yield "
   <div class=\"form-field row ";
            // line 891
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "field_class", [], "any", false, false, false, 891), "html", null, true);
            yield " ";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "add_field_class", [], "any", false, false, false, 891), "html", null, true);
            yield " ";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "mb", [], "any", false, false, false, 891), "html", null, true);
            yield "\" ";
            yield ($context["extra_attribs"] ?? null);
            yield ">
      ";
            // line 892
            yield CoreExtension::callMacro($macros["_self"], "macro_label", [($context["label"] ?? null), ($context["id"] ?? null), ($context["options"] ?? null), ("col-form-label " . CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "label_class", [], "any", false, false, false, 892))], 892, $context, $this->getSourceContext());
            yield "
      ";
            // line 893
            if (CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "center", [], "any", false, false, false, 893)) {
                // line 894
                yield "         ";
                $context["flex_class"] = "d-flex align-items-center";
                // line 895
                yield "      ";
            }
            // line 896
            yield "      <div class=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "input_class", [], "any", false, false, false, 896), "html", null, true);
            yield " ";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["flex_class"] ?? null), "html", null, true);
            yield " field-container\">
         ";
            // line 897
            yield ($context["field"] ?? null);
            yield "
         ";
            // line 898
            yield ($context["add_field_html"] ?? null);
            yield "
      </div>
   </div>
";
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
    }

    // line 904
    public function macro_verticalField($__label__ = null, $__field__ = null, $__id__ = null, $__add_field_html__ = "", $__options__ = [], ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "label" => $__label__,
            "field" => $__field__,
            "id" => $__id__,
            "add_field_html" => $__add_field_html__,
            "options" => $__options__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        return ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
            // line 905
            yield "   ";
            $context["options"] = Twig\Extension\CoreExtension::merge(["full_width" => false, "mb" => "mb-2", "field_class" => "col-12 col-sm-6", "add_field_class" => "", "add_field_attribs" => []],             // line 911
($context["options"] ?? null));
            // line 912
            yield "
   ";
            // line 913
            if (CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "full_width", [], "any", false, false, false, 913)) {
                // line 914
                yield "      ";
                $context["options"] = Twig\Extension\CoreExtension::merge(($context["options"] ?? null), ["field_class" => "col-12"]);
                // line 917
                yield "   ";
            }
            // line 918
            yield "
   ";
            // line 919
            if ( !Twig\Extension\CoreExtension::testEmpty(CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "add_field_attribs", [], "any", false, false, false, 919))) {
                // line 920
                yield "      ";
                $context["extra_attribs"] = $this->extensions['Glpi\Application\View\Extension\PhpExtension']->call("Html::parseAttributes", ["options" => CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "add_field_attribs", [], "any", false, false, false, 920)]);
                // line 921
                yield "   ";
            } else {
                // line 922
                yield "      ";
                $context["extra_attribs"] = "";
                // line 923
                yield "   ";
            }
            // line 924
            yield "
   <div class=\"form-field ";
            // line 925
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "field_class", [], "any", false, false, false, 925), "html", null, true);
            yield " ";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "add_field_class", [], "any", false, false, false, 925), "html", null, true);
            yield " ";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "mb", [], "any", false, false, false, 925), "html", null, true);
            yield "\" ";
            yield ($context["extra_attribs"] ?? null);
            yield ">
      ";
            // line 926
            yield CoreExtension::callMacro($macros["_self"], "macro_label", [($context["label"] ?? null), ($context["id"] ?? null), ($context["options"] ?? null), ("col-form-label " . CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "label_class", [], "any", false, false, false, 926))], 926, $context, $this->getSourceContext());
            yield "
      <div class=\"";
            // line 927
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "input_class", [], "any", false, false, false, 927), "html", null, true);
            yield " field-container\">
         ";
            // line 928
            yield ($context["field"] ?? null);
            yield "
      </div>
      ";
            // line 930
            yield ($context["add_field_html"] ?? null);
            yield "
   </div>
";
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
    }

    // line 935
    public function macro_label($__label__ = null, $__id__ = null, $__options__ = [], $__class__ = "form-label", ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "label" => $__label__,
            "id" => $__id__,
            "options" => $__options__,
            "class" => $__class__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        return ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
            // line 936
            yield "    ";
            $context["options"] = Twig\Extension\CoreExtension::merge(["locked" => false, "locked_value" => null],             // line 939
($context["options"] ?? null));
            // line 940
            yield "
   ";
            // line 941
            $context["required_mark"] = "";
            // line 942
            yield "   ";
            if ((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "fields_template", [], "any", false, false, false, 942), "isMandatoryField", [CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "name", [], "any", false, false, false, 942)], "method", false, false, false, 942) || CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "required", [], "any", false, false, false, 942))) {
                // line 943
                yield "      ";
                $context["required_mark"] = "<span class=\"required\">*</span>";
                // line 944
                yield "   ";
            }
            // line 945
            yield "
   ";
            // line 946
            $context["helper"] = "";
            // line 947
            yield "   ";
            if (CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "helper", [], "any", false, false, false, 947)) {
                // line 948
                yield "      ";
                $context["helper"] = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
                    // line 949
                    yield "         <span class=\"form-help\" data-bs-toggle=\"popover\" data-bs-placement=\"top\" data-bs-html=\"true\"
               data-bs-content=\"";
                    // line 950
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "helper", [], "any", false, false, false, 950), "html", null, true);
                    yield "\">
            ?
         </span>
      ";
                    return; yield '';
                })())) ? '' : new Markup($tmp, $this->env->getCharset());
                // line 954
                yield "   ";
            }
            // line 955
            yield "
   ";
            // line 956
            $context["locked_mark"] = "";
            // line 957
            yield "   ";
            if (CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "locked", [], "any", false, false, false, 957)) {
                // line 958
                yield "      ";
                $context["locked_mark"] = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
                    // line 959
                    yield "        ";
                    $context["locked_title"] = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
                        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(__("Field will not be updated from inventory"), "html", null, true);
                        return; yield '';
                    })())) ? '' : new Markup($tmp, $this->env->getCharset());
                    // line 960
                    yield "        ";
                    if ( !Twig\Extension\CoreExtension::testEmpty(CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "locked_value", [], "any", false, false, false, 960))) {
                        // line 961
                        yield "            ";
                        $context["locked_title"] = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
                            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["locked_title"] ?? null), "html", null, true);
                            yield " - ";
                            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((__("Last inventory value was:") . " ") . CoreExtension::getAttribute($this->env, $this->source, ($context["options"] ?? null), "locked_value", [], "any", false, false, false, 961)), "html", null, true);
                            return; yield '';
                        })())) ? '' : new Markup($tmp, $this->env->getCharset());
                        // line 962
                        yield "        ";
                    }
                    // line 963
                    yield "        <i class=\"ti ti-lock\"
           title=\"";
                    // line 964
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["locked_title"] ?? null), "html", null, true);
                    yield "\"
           data-bs-toggle=\"tooltip\"></i>
      ";
                    return; yield '';
                })())) ? '' : new Markup($tmp, $this->env->getCharset());
                // line 967
                yield "   ";
            }
            // line 968
            yield "
   <label class=\"";
            // line 969
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["class"] ?? null), "html", null, true);
            yield "\" for=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["id"] ?? null), "html", null, true);
            yield "\">
      ";
            // line 970
            yield ($context["label"] ?? null);
            yield "
      ";
            // line 971
            yield ($context["locked_mark"] ?? null);
            yield "
      ";
            // line 972
            yield ($context["required_mark"] ?? null);
            yield "
      ";
            // line 973
            yield ($context["helper"] ?? null);
            yield "
   </label>
";
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "components/form/fields_macros.html.twig";
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
        return array (  2927 => 973,  2923 => 972,  2919 => 971,  2915 => 970,  2909 => 969,  2906 => 968,  2903 => 967,  2896 => 964,  2893 => 963,  2890 => 962,  2882 => 961,  2879 => 960,  2873 => 959,  2870 => 958,  2867 => 957,  2865 => 956,  2862 => 955,  2859 => 954,  2851 => 950,  2848 => 949,  2845 => 948,  2842 => 947,  2840 => 946,  2837 => 945,  2834 => 944,  2831 => 943,  2828 => 942,  2826 => 941,  2823 => 940,  2821 => 939,  2819 => 936,  2804 => 935,  2795 => 930,  2790 => 928,  2786 => 927,  2782 => 926,  2772 => 925,  2769 => 924,  2766 => 923,  2763 => 922,  2760 => 921,  2757 => 920,  2755 => 919,  2752 => 918,  2749 => 917,  2746 => 914,  2744 => 913,  2741 => 912,  2739 => 911,  2737 => 905,  2721 => 904,  2711 => 898,  2707 => 897,  2700 => 896,  2697 => 895,  2694 => 894,  2692 => 893,  2688 => 892,  2678 => 891,  2675 => 890,  2672 => 889,  2669 => 888,  2666 => 887,  2663 => 886,  2661 => 885,  2658 => 884,  2655 => 883,  2653 => 881,  2651 => 880,  2649 => 879,  2646 => 878,  2644 => 877,  2643 => 874,  2640 => 873,  2637 => 872,  2634 => 871,  2632 => 870,  2630 => 867,  2628 => 866,  2625 => 865,  2622 => 862,  2620 => 861,  2617 => 860,  2614 => 859,  2612 => 858,  2610 => 855,  2608 => 854,  2605 => 853,  2603 => 852,  2601 => 843,  2585 => 842,  2576 => 837,  2572 => 836,  2566 => 835,  2563 => 834,  2560 => 833,  2557 => 832,  2554 => 831,  2552 => 830,  2549 => 829,  2547 => 828,  2545 => 825,  2530 => 824,  2520 => 819,  2514 => 817,  2512 => 816,  2509 => 815,  2506 => 814,  2494 => 813,  2485 => 810,  2480 => 808,  2474 => 806,  2472 => 805,  2467 => 804,  2464 => 803,  2449 => 802,  2442 => 799,  2439 => 798,  2433 => 796,  2427 => 794,  2425 => 793,  2420 => 792,  2417 => 791,  2415 => 790,  2412 => 789,  2409 => 788,  2406 => 787,  2403 => 786,  2397 => 784,  2395 => 783,  2392 => 782,  2389 => 781,  2386 => 780,  2383 => 779,  2380 => 778,  2378 => 777,  2375 => 776,  2373 => 775,  2371 => 768,  2356 => 767,  2347 => 764,  2340 => 762,  2338 => 761,  2335 => 760,  2332 => 759,  2329 => 758,  2326 => 757,  2311 => 756,  2301 => 752,  2299 => 751,  2296 => 750,  2290 => 748,  2289 => 747,  2288 => 746,  2287 => 745,  2285 => 744,  2283 => 743,  2280 => 742,  2277 => 741,  2274 => 740,  2272 => 739,  2269 => 738,  2266 => 737,  2263 => 736,  2260 => 735,  2258 => 734,  2256 => 731,  2253 => 730,  2250 => 729,  2244 => 726,  2241 => 725,  2239 => 724,  2236 => 723,  2220 => 722,  2212 => 719,  2209 => 718,  2205 => 717,  2203 => 716,  2202 => 715,  2201 => 714,  2199 => 713,  2197 => 712,  2194 => 711,  2191 => 710,  2188 => 709,  2186 => 708,  2183 => 707,  2180 => 706,  2177 => 705,  2174 => 704,  2172 => 703,  2170 => 700,  2155 => 699,  2147 => 696,  2144 => 695,  2140 => 694,  2138 => 693,  2137 => 692,  2135 => 691,  2133 => 690,  2130 => 689,  2127 => 688,  2124 => 687,  2122 => 686,  2119 => 685,  2116 => 684,  2113 => 683,  2110 => 682,  2108 => 681,  2106 => 678,  2091 => 677,  2082 => 674,  2078 => 673,  2075 => 672,  2073 => 671,  2070 => 670,  2068 => 669,  2066 => 667,  2052 => 666,  2044 => 663,  2041 => 662,  2037 => 661,  2035 => 660,  2034 => 659,  2033 => 658,  2031 => 657,  2029 => 656,  2026 => 655,  2024 => 654,  2021 => 653,  2018 => 652,  2015 => 651,  2013 => 650,  2010 => 649,  2007 => 648,  2004 => 647,  2001 => 646,  1999 => 645,  1997 => 642,  1982 => 641,  1974 => 638,  1971 => 637,  1967 => 636,  1965 => 635,  1964 => 634,  1962 => 633,  1960 => 632,  1957 => 631,  1954 => 630,  1951 => 629,  1949 => 628,  1946 => 627,  1943 => 626,  1940 => 625,  1937 => 624,  1935 => 623,  1933 => 620,  1918 => 619,  1910 => 616,  1907 => 615,  1903 => 614,  1901 => 613,  1900 => 612,  1899 => 611,  1897 => 610,  1895 => 609,  1892 => 608,  1889 => 607,  1886 => 606,  1884 => 605,  1881 => 604,  1878 => 603,  1875 => 602,  1872 => 601,  1870 => 600,  1868 => 597,  1853 => 596,  1845 => 593,  1842 => 592,  1838 => 591,  1836 => 590,  1835 => 589,  1834 => 588,  1832 => 587,  1830 => 586,  1827 => 585,  1824 => 584,  1821 => 583,  1819 => 582,  1816 => 581,  1813 => 580,  1810 => 579,  1808 => 578,  1805 => 577,  1803 => 576,  1801 => 573,  1785 => 572,  1777 => 569,  1774 => 568,  1770 => 567,  1768 => 566,  1767 => 565,  1766 => 564,  1764 => 563,  1762 => 562,  1759 => 561,  1756 => 560,  1753 => 559,  1750 => 558,  1747 => 557,  1744 => 556,  1742 => 555,  1739 => 554,  1737 => 553,  1735 => 550,  1720 => 549,  1711 => 546,  1705 => 544,  1701 => 543,  1697 => 542,  1691 => 541,  1687 => 540,  1684 => 539,  1681 => 538,  1678 => 537,  1676 => 536,  1674 => 534,  1671 => 533,  1656 => 532,  1649 => 529,  1647 => 526,  1642 => 525,  1640 => 524,  1637 => 523,  1632 => 521,  1630 => 518,  1627 => 517,  1619 => 514,  1617 => 512,  1616 => 509,  1613 => 508,  1609 => 507,  1606 => 506,  1603 => 505,  1588 => 504,  1579 => 501,  1574 => 499,  1567 => 495,  1563 => 494,  1558 => 493,  1555 => 492,  1551 => 490,  1549 => 489,  1542 => 488,  1534 => 486,  1531 => 485,  1528 => 484,  1525 => 483,  1523 => 482,  1520 => 481,  1517 => 480,  1501 => 479,  1492 => 476,  1488 => 475,  1485 => 474,  1482 => 473,  1477 => 471,  1473 => 470,  1469 => 469,  1465 => 468,  1461 => 467,  1457 => 466,  1454 => 465,  1451 => 464,  1448 => 463,  1446 => 462,  1445 => 460,  1443 => 458,  1428 => 457,  1419 => 454,  1416 => 453,  1401 => 452,  1393 => 449,  1390 => 448,  1387 => 447,  1384 => 446,  1371 => 444,  1368 => 443,  1365 => 442,  1362 => 441,  1359 => 440,  1356 => 439,  1350 => 437,  1346 => 436,  1342 => 435,  1337 => 434,  1334 => 433,  1328 => 431,  1324 => 430,  1320 => 429,  1316 => 428,  1312 => 427,  1308 => 426,  1303 => 425,  1301 => 424,  1298 => 423,  1296 => 422,  1295 => 421,  1294 => 420,  1292 => 419,  1290 => 418,  1289 => 417,  1287 => 414,  1272 => 413,  1263 => 410,  1247 => 398,  1243 => 397,  1233 => 390,  1229 => 389,  1225 => 388,  1219 => 387,  1215 => 386,  1212 => 385,  1209 => 384,  1194 => 383,  1186 => 380,  1183 => 379,  1166 => 368,  1162 => 367,  1158 => 366,  1149 => 360,  1143 => 357,  1136 => 354,  1134 => 353,  1131 => 352,  1128 => 351,  1125 => 350,  1122 => 349,  1107 => 348,  1099 => 345,  1096 => 344,  1079 => 333,  1075 => 332,  1071 => 331,  1063 => 326,  1057 => 323,  1050 => 320,  1048 => 319,  1045 => 318,  1042 => 317,  1039 => 316,  1036 => 315,  1021 => 314,  1014 => 311,  1010 => 309,  1008 => 308,  1004 => 307,  1000 => 306,  996 => 305,  990 => 304,  983 => 303,  981 => 302,  977 => 300,  974 => 299,  971 => 298,  968 => 297,  965 => 296,  962 => 295,  959 => 294,  956 => 293,  953 => 292,  950 => 291,  947 => 290,  944 => 289,  941 => 288,  938 => 287,  935 => 286,  933 => 285,  930 => 284,  927 => 283,  924 => 282,  921 => 281,  906 => 280,  892 => 271,  888 => 270,  884 => 269,  878 => 265,  876 => 264,  872 => 263,  869 => 262,  866 => 261,  863 => 260,  860 => 259,  857 => 258,  854 => 257,  852 => 256,  849 => 255,  846 => 254,  842 => 253,  840 => 251,  839 => 250,  838 => 248,  837 => 247,  835 => 246,  832 => 245,  829 => 244,  825 => 243,  823 => 241,  822 => 240,  821 => 238,  819 => 237,  816 => 236,  813 => 235,  811 => 234,  808 => 233,  805 => 232,  803 => 230,  802 => 229,  801 => 227,  800 => 226,  798 => 225,  796 => 224,  793 => 223,  785 => 221,  781 => 220,  777 => 219,  767 => 217,  765 => 216,  763 => 215,  760 => 214,  757 => 213,  754 => 212,  751 => 211,  749 => 210,  746 => 209,  744 => 208,  742 => 200,  727 => 199,  718 => 195,  713 => 193,  707 => 191,  703 => 189,  701 => 188,  696 => 187,  693 => 186,  690 => 185,  675 => 184,  666 => 180,  655 => 178,  648 => 177,  642 => 176,  638 => 175,  634 => 174,  630 => 173,  624 => 172,  620 => 171,  617 => 170,  615 => 169,  612 => 168,  609 => 167,  606 => 166,  604 => 165,  601 => 164,  598 => 163,  595 => 162,  593 => 161,  590 => 160,  575 => 159,  567 => 155,  564 => 154,  559 => 152,  553 => 150,  551 => 149,  547 => 148,  543 => 147,  539 => 146,  535 => 145,  529 => 144,  523 => 143,  520 => 142,  518 => 141,  515 => 140,  513 => 139,  511 => 136,  508 => 135,  506 => 134,  504 => 132,  501 => 131,  486 => 130,  478 => 126,  475 => 125,  469 => 123,  465 => 122,  461 => 121,  457 => 120,  453 => 119,  448 => 118,  446 => 117,  443 => 116,  441 => 115,  440 => 113,  437 => 112,  434 => 111,  431 => 110,  428 => 109,  413 => 108,  405 => 104,  402 => 103,  395 => 101,  392 => 100,  388 => 99,  384 => 98,  380 => 97,  374 => 96,  370 => 95,  365 => 94,  363 => 93,  360 => 92,  357 => 91,  354 => 90,  351 => 89,  348 => 88,  333 => 87,  325 => 83,  322 => 82,  319 => 81,  316 => 80,  313 => 79,  310 => 78,  307 => 77,  304 => 76,  301 => 75,  298 => 74,  294 => 73,  291 => 72,  288 => 71,  272 => 70,  260 => 64,  254 => 61,  251 => 60,  249 => 59,  245 => 58,  240 => 56,  237 => 55,  234 => 54,  221 => 53,  209 => 47,  203 => 44,  200 => 43,  198 => 42,  194 => 41,  189 => 39,  186 => 38,  183 => 37,  180 => 36,  177 => 35,  174 => 34,  160 => 33,  153 => 933,  149 => 902,  145 => 840,  141 => 822,  138 => 812,  135 => 801,  132 => 766,  129 => 755,  126 => 721,  123 => 698,  120 => 676,  117 => 665,  114 => 640,  111 => 618,  108 => 595,  105 => 571,  102 => 548,  99 => 531,  96 => 503,  93 => 478,  90 => 456,  87 => 451,  84 => 412,  81 => 382,  78 => 347,  75 => 313,  72 => 279,  68 => 197,  64 => 182,  60 => 157,  56 => 128,  52 => 106,  48 => 85,  45 => 69,  42 => 52,  39 => 32,);
    }

    public function getSourceContext()
    {
        return new Source("", "components/form/fields_macros.html.twig", "D:\\laragon\\www\\glpi\\templates\\components\\form\\fields_macros.html.twig");
    }
}
