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

/* components/alerts_macros.html.twig */
class __TwigTemplate_38c2bbb675d5f5c43de4fe90b67b799b extends Template
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
        // line 61
        yield "
";
        // line 71
        yield "
";
        // line 81
        yield "
";
        // line 91
        yield "
";
        return; yield '';
    }

    // line 33
    public function macro_alert($__alert_type__ = "", $__title__ = "", $__messages__ = [], $__icon__ = "", $__important__ = false, ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "alert_type" => $__alert_type__,
            "title" => $__title__,
            "messages" => $__messages__,
            "icon" => $__icon__,
            "important" => $__important__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        return ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
            // line 34
            yield "   <div class=\"alert alert-";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["alert_type"] ?? null), "html", null, true);
            yield " ";
            yield ((($context["important"] ?? null)) ? ("alert-important") : (""));
            yield "\" role=\"alert\">
      <div class=\"d-flex\">
         <div>
            <i class=\"";
            // line 37
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["icon"] ?? null), "html", null, true);
            yield " fa-2x alert-icon\"></i>
         </div>
         <div>
            ";
            // line 40
            if (Twig\Extension\CoreExtension::length($this->env->getCharset(), ($context["title"] ?? null))) {
                // line 41
                yield "               <h4 class=\"alert-title\">
                  ";
                // line 42
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["title"] ?? null), "html", null, true);
                yield "
               </h4>
            ";
            }
            // line 45
            yield "            ";
            if ((Twig\Extension\CoreExtension::length($this->env->getCharset(), ($context["messages"] ?? null)) > 0)) {
                // line 46
                yield "               <div class=\"text-muted\">
                  ";
                // line 47
                if (is_iterable(($context["messages"] ?? null))) {
                    // line 48
                    yield "                     ";
                    $context['_parent'] = $context;
                    $context['_seq'] = CoreExtension::ensureTraversable(($context["messages"] ?? null));
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
                    foreach ($context['_seq'] as $context["_key"] => $context["message"]) {
                        // line 49
                        yield "                        ";
                        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($context["message"], "html", null, true);
                        yield "
                        ";
                        // line 50
                        if (CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "last", [], "any", false, false, false, 50)) {
                            yield "<br />";
                        }
                        // line 51
                        yield "                     ";
                        ++$context['loop']['index0'];
                        ++$context['loop']['index'];
                        $context['loop']['first'] = false;
                        if (isset($context['loop']['length'])) {
                            --$context['loop']['revindex0'];
                            --$context['loop']['revindex'];
                            $context['loop']['last'] = 0 === $context['loop']['revindex0'];
                        }
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['_key'], $context['message'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 52
                    yield "                  ";
                } else {
                    // line 53
                    yield "                     ";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["messages"] ?? null), "html", null, true);
                    yield "
                  ";
                }
                // line 55
                yield "               </div>
            ";
            }
            // line 57
            yield "         </div>
      </div>
   </div>
";
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
    }

    // line 62
    public function macro_alert_success($__title__ = "", $__messages__ = [], $__important__ = false, ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "title" => $__title__,
            "messages" => $__messages__,
            "important" => $__important__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        return ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
            // line 63
            yield "   ";
            yield CoreExtension::callMacro($macros["_self"], "macro_alert", ["success",             // line 65
($context["title"] ?? null),             // line 66
($context["messages"] ?? null), "fas fa-check",             // line 68
($context["important"] ?? null)], 63, $context, $this->getSourceContext());
            // line 69
            yield "
";
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
    }

    // line 72
    public function macro_alert_info($__title__ = "", $__messages__ = [], $__important__ = false, ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "title" => $__title__,
            "messages" => $__messages__,
            "important" => $__important__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        return ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
            // line 73
            yield "   ";
            yield CoreExtension::callMacro($macros["_self"], "macro_alert", ["info",             // line 75
($context["title"] ?? null),             // line 76
($context["messages"] ?? null), "fas fa-info-circle",             // line 78
($context["important"] ?? null)], 73, $context, $this->getSourceContext());
            // line 79
            yield "
";
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
    }

    // line 82
    public function macro_alert_warning($__title__ = "", $__messages__ = [], $__important__ = false, ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "title" => $__title__,
            "messages" => $__messages__,
            "important" => $__important__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        return ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
            // line 83
            yield "   ";
            yield CoreExtension::callMacro($macros["_self"], "macro_alert", ["warning",             // line 85
($context["title"] ?? null),             // line 86
($context["messages"] ?? null), "fas fa-exclamation-triangle",             // line 88
($context["important"] ?? null)], 83, $context, $this->getSourceContext());
            // line 89
            yield "
";
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
    }

    // line 92
    public function macro_alert_danger($__title__ = "", $__messages__ = [], $__important__ = false, ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "title" => $__title__,
            "messages" => $__messages__,
            "important" => $__important__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        return ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
            // line 93
            yield "   ";
            yield CoreExtension::callMacro($macros["_self"], "macro_alert", ["danger",             // line 95
($context["title"] ?? null),             // line 96
($context["messages"] ?? null), "fas fa-exclamation-circle",             // line 98
($context["important"] ?? null)], 93, $context, $this->getSourceContext());
            // line 99
            yield "
";
            return; yield '';
        })())) ? '' : new Markup($tmp, $this->env->getCharset());
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "components/alerts_macros.html.twig";
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
        return array (  271 => 99,  269 => 98,  268 => 96,  267 => 95,  265 => 93,  251 => 92,  244 => 89,  242 => 88,  241 => 86,  240 => 85,  238 => 83,  224 => 82,  217 => 79,  215 => 78,  214 => 76,  213 => 75,  211 => 73,  197 => 72,  190 => 69,  188 => 68,  187 => 66,  186 => 65,  184 => 63,  170 => 62,  161 => 57,  157 => 55,  151 => 53,  148 => 52,  134 => 51,  130 => 50,  125 => 49,  107 => 48,  105 => 47,  102 => 46,  99 => 45,  93 => 42,  90 => 41,  88 => 40,  82 => 37,  73 => 34,  57 => 33,  51 => 91,  48 => 81,  45 => 71,  42 => 61,  39 => 32,);
    }

    public function getSourceContext()
    {
        return new Source("", "components/alerts_macros.html.twig", "D:\\laragon\\www\\glpi\\templates\\components\\alerts_macros.html.twig");
    }
}
