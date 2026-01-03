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

/* install/blocks/requirements_table.html.twig */
class __TwigTemplate_069367027202ed4f5cbbcd6de1304789 extends Template
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
<table class=\"table tab_check\">
   <thead>
      <tr>
         <th>";
        // line 36
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(__("Test done"), "html", null, true);
        yield "</th>
         <th>";
        // line 37
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(__("Results"), "html", null, true);
        yield "</th>
      </tr>
   </thead>
   <tbody>
      ";
        // line 41
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(($context["requirements"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["requirement"]) {
            // line 42
            yield "         ";
            if ( !CoreExtension::getAttribute($this->env, $this->source, $context["requirement"], "isOutOfContext", [], "method", false, false, false, 42)) {
                // line 43
                yield "            ";
                $context["is_important"] = ( !CoreExtension::getAttribute($this->env, $this->source, $context["requirement"], "isOptional", [], "method", false, false, false, 43) || CoreExtension::getAttribute($this->env, $this->source, $context["requirement"], "isRecommendedForSecurity", [], "method", false, false, false, 43));
                // line 44
                yield "            <tr class=\"tab_bg_1\">
               <td class=\"text-start\">
                  ";
                // line 46
                if ( !CoreExtension::getAttribute($this->env, $this->source, $context["requirement"], "isOptional", [], "method", false, false, false, 46)) {
                    // line 47
                    yield "                     <span class=\"badge bg-warning\">";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(__("Required"), "html", null, true);
                    yield "</span>
                  ";
                } elseif (CoreExtension::getAttribute($this->env, $this->source,                 // line 48
$context["requirement"], "isRecommendedForSecurity", [], "method", false, false, false, 48)) {
                    // line 49
                    yield "                     <span class=\"badge bg-danger\">";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(__("Security"), "html", null, true);
                    yield "</span>
                  ";
                } else {
                    // line 51
                    yield "                     <span class=\"badge bg-secondary\">";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(__("Suggested"), "html", null, true);
                    yield "</span>
                  ";
                }
                // line 53
                yield "                  <strong>";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["requirement"], "getTitle", [], "method", false, false, false, 53), "html", null, true);
                yield "</strong>
                  ";
                // line 54
                $context["description"] = CoreExtension::getAttribute($this->env, $this->source, $context["requirement"], "getDescription", [], "method", false, false, false, 54);
                // line 55
                yield "                  ";
                if ( !Twig\Extension\CoreExtension::testEmpty(($context["description"] ?? null))) {
                    // line 56
                    yield "                      <br />
                      <em>";
                    // line 57
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["requirement"], "getDescription", [], "method", false, false, false, 57), "html", null, true);
                    yield "</em>
                  ";
                }
                // line 59
                yield "                  ";
                if ( !CoreExtension::getAttribute($this->env, $this->source, $context["requirement"], "isValidated", [], "method", false, false, false, 59)) {
                    // line 60
                    yield "                      ";
                    $context['_parent'] = $context;
                    $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, $context["requirement"], "getValidationMessages", [], "method", false, false, false, 60));
                    foreach ($context['_seq'] as $context["_key"] => $context["message"]) {
                        // line 61
                        yield "                          <br />
                          <strong>
                             <em class=\"";
                        // line 63
                        yield ((($context["is_important"] ?? null)) ? ("red") : ("missing"));
                        yield "\">
                                ";
                        // line 64
                        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($context["message"], "html", null, true);
                        yield "
                             </em>
                          </strong>
                      ";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['_key'], $context['message'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 68
                    yield "                  ";
                }
                // line 69
                yield "               </td>
               <td class=\"";
                // line 70
                yield ((CoreExtension::getAttribute($this->env, $this->source, $context["requirement"], "isMissing", [], "method", false, false, false, 70)) ? (((($context["is_important"] ?? null)) ? ("red") : ("missing"))) : ("green"));
                yield "\">
                  <span data-bs-toggle=\"popover\"
                        data-bs-placement=\"right\"
                        data-bs-html=\"true\"
                        data-bs-custom-class=\"validation-messages\"
                        data-bs-content=\"";
                // line 75
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::join(CoreExtension::getAttribute($this->env, $this->source, $context["requirement"], "getValidationMessages", [], "method", false, false, false, 75), "<br />"), "html", null, true);
                yield "\">
                     <i class=\"";
                // line 76
                yield ((CoreExtension::getAttribute($this->env, $this->source, $context["requirement"], "isValidated", [], "method", false, false, false, 76)) ? ("fas fa-check") : (((CoreExtension::getAttribute($this->env, $this->source, $context["requirement"], "isOptional", [], "method", false, false, false, 76)) ? ("fas fa-exclamation-triangle") : ("ti ti-x"))));
                yield "\"></i>
                  </span>
               </td>
            </tr>
         ";
            }
            // line 81
            yield "      ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['requirement'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 82
        yield "   </tbody>
</table>
";
        return; yield '';
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "install/blocks/requirements_table.html.twig";
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
        return array (  166 => 82,  160 => 81,  152 => 76,  148 => 75,  140 => 70,  137 => 69,  134 => 68,  124 => 64,  120 => 63,  116 => 61,  111 => 60,  108 => 59,  103 => 57,  100 => 56,  97 => 55,  95 => 54,  90 => 53,  84 => 51,  78 => 49,  76 => 48,  71 => 47,  69 => 46,  65 => 44,  62 => 43,  59 => 42,  55 => 41,  48 => 37,  44 => 36,  38 => 32,);
    }

    public function getSourceContext()
    {
        return new Source("", "install/blocks/requirements_table.html.twig", "D:\\laragon\\www\\glpi\\templates\\install\\blocks\\requirements_table.html.twig");
    }
}
