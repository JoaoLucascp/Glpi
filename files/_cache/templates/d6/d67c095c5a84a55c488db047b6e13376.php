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

/* pages/admin/rules_list.html.twig */
class __TwigTemplate_5dfb21a74de09933887cf8c39d8fdca9 extends Template
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
<div class=\"center mb-3\">
   <a class=\"btn btn-primary me-2\" href=\"";
        // line 34
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Glpi\Application\View\Extension\RoutingExtension']->path("front/rule.backup.php?action=import"), "html", null, true);
        yield "\">
      <i class=\"fas fa-upload\"></i>
      <span>";
        // line 36
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(_x("button", "Import"), "html", null, true);
        yield "</span>
   </a>
   <a class=\"btn btn-primary me-2\" href=\"";
        // line 38
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Glpi\Application\View\Extension\RoutingExtension']->path("front/rule.backup.php?action=export"), "html", null, true);
        yield "\">
      <i class=\"fas fa-download\"></i>
      <span>";
        // line 40
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(_x("button", "Export"), "html", null, true);
        yield "</span>
   </a>
</div>

<div class=\"container px-4\">
   <div class=\"row g-3 justify-content-evenly\">
      ";
        // line 46
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(($context["rules_group"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["rules_block"]) {
            // line 47
            yield "         <div class=\"col-12 ";
            yield (((Twig\Extension\CoreExtension::length($this->env->getCharset(), ($context["rules_group"] ?? null)) > 2)) ? ("col-xxl-3") : ("col-xxl-5"));
            yield "\">
            <div class=\"card\">
               <div class=\"card-header\">
                  <h3>";
            // line 50
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((($__internal_compile_0 = $context["rules_block"]) && is_array($__internal_compile_0) || $__internal_compile_0 instanceof ArrayAccess ? ($__internal_compile_0["type"] ?? null) : null), "html", null, true);
            yield "</h3>
               </div>
               <div class=\"list-group list-group-flush\">
                  ";
            // line 53
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable((($__internal_compile_1 = $context["rules_block"]) && is_array($__internal_compile_1) || $__internal_compile_1 instanceof ArrayAccess ? ($__internal_compile_1["entries"] ?? null) : null));
            foreach ($context['_seq'] as $context["_key"] => $context["rule"]) {
                // line 54
                yield "                     <a class=\"list-group-item list-group-item-action\" href=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((($__internal_compile_2 = $context["rule"]) && is_array($__internal_compile_2) || $__internal_compile_2 instanceof ArrayAccess ? ($__internal_compile_2["link"] ?? null) : null), "html", null, true);
                yield "\">
                        <i class=\"fa-fw ";
                // line 55
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((($__internal_compile_3 = $context["rule"]) && is_array($__internal_compile_3) || $__internal_compile_3 instanceof ArrayAccess ? ($__internal_compile_3["icon"] ?? null) : null), "html", null, true);
                yield " me-1\"></i>
                        <span>";
                // line 56
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((($__internal_compile_4 = $context["rule"]) && is_array($__internal_compile_4) || $__internal_compile_4 instanceof ArrayAccess ? ($__internal_compile_4["label"] ?? null) : null), "html", null, true);
                yield "</span>
                     </a>
                  ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['rule'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 59
            yield "               </div>
            </div>
         </div>
      ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['rules_block'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 63
        yield "   </div>
</div>
";
        return; yield '';
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "pages/admin/rules_list.html.twig";
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
        return array (  114 => 63,  105 => 59,  96 => 56,  92 => 55,  87 => 54,  83 => 53,  77 => 50,  70 => 47,  66 => 46,  57 => 40,  52 => 38,  47 => 36,  42 => 34,  38 => 32,);
    }

    public function getSourceContext()
    {
        return new Source("", "pages/admin/rules_list.html.twig", "D:\\laragon\\www\\glpi\\templates\\pages\\admin\\rules_list.html.twig");
    }
}
