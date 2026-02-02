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

/* components/form/dates.html.twig */
class __TwigTemplate_212a1e8cbe0830cc6c9c845285165e17 extends Template
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
        $context["withtemplate"] = (((CoreExtension::getAttribute($this->env, $this->source, ($context["params"] ?? null), "withtemplate", [], "array", true, true, false, 33) &&  !(null === (($__internal_compile_0 = ($context["params"] ?? null)) && is_array($__internal_compile_0) || $__internal_compile_0 instanceof ArrayAccess ? ($__internal_compile_0["withtemplate"] ?? null) : null)))) ? ((($__internal_compile_1 = ($context["params"] ?? null)) && is_array($__internal_compile_1) || $__internal_compile_1 instanceof ArrayAccess ? ($__internal_compile_1["withtemplate"] ?? null) : null)) : (""));
        // line 34
        yield "
";
        // line 35
        if ( !CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isNewItem", [], "method", false, false, false, 35)) {
            // line 36
            yield "<div class=\"row\">
   ";
            // line 37
            if (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isField", ["date_creation"], "method", false, false, false, 37)) {
                // line 38
                yield "   <div class=\"col-sm-6 col-12\">
      ";
                // line 39
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::sprintf(__("Created on %s"), $this->extensions['Glpi\Application\View\Extension\DataHelpersExtension']->getFormattedDatetime((($__internal_compile_2 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, false, false, 39)) && is_array($__internal_compile_2) || $__internal_compile_2 instanceof ArrayAccess ? ($__internal_compile_2["date_creation"] ?? null) : null))), "html", null, true);
                yield "
   </div>
   ";
            }
            // line 42
            yield "
   ";
            // line 43
            if (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isField", ["date_mod"], "method", false, false, false, 43)) {
                // line 44
                yield "   <div class=\"col-sm-6 col-12\">
      ";
                // line 45
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::sprintf(__("Last update on %s"), $this->extensions['Glpi\Application\View\Extension\DataHelpersExtension']->getFormattedDatetime((($__internal_compile_3 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, false, false, 45)) && is_array($__internal_compile_3) || $__internal_compile_3 instanceof ArrayAccess ? ($__internal_compile_3["date_mod"] ?? null) : null))), "html", null, true);
                yield "
   </div>
   ";
            }
            // line 48
            yield "</div>
";
        }
        // line 50
        yield "
";
        // line 51
        if (((array_key_exists("withtemplate", $context) && (($context["withtemplate"] ?? null) > 0)) && (Twig\Extension\CoreExtension::length($this->env->getCharset(), (($__internal_compile_4 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, false, false, 51)) && is_array($__internal_compile_4) || $__internal_compile_4 instanceof ArrayAccess ? ($__internal_compile_4["template_name"] ?? null) : null)) > 0))) {
            // line 52
            yield "<div class=\"row\">
   <div class=\"col-12\">
      ";
            // line 54
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::sprintf(__("Created from the template %s"), $this->extensions['Glpi\Application\View\Extension\DataHelpersExtension']->getVerbatimValue((($__internal_compile_5 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, false, false, 54)) && is_array($__internal_compile_5) || $__internal_compile_5 instanceof ArrayAccess ? ($__internal_compile_5["template_name"] ?? null) : null))), "html", null, true);
            yield "
   </div>
</div>
";
        }
        return; yield '';
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "components/form/dates.html.twig";
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
        return array (  89 => 54,  85 => 52,  83 => 51,  80 => 50,  76 => 48,  70 => 45,  67 => 44,  65 => 43,  62 => 42,  56 => 39,  53 => 38,  51 => 37,  48 => 36,  46 => 35,  43 => 34,  41 => 33,  38 => 32,);
    }

    public function getSourceContext()
    {
        return new Source("", "components/form/dates.html.twig", "D:\\laragon\\www\\glpi\\templates\\components\\form\\dates.html.twig");
    }
}
