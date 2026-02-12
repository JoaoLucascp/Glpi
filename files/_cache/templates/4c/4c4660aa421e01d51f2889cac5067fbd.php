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

/* pages/setup/general/base_form.html.twig */
class __TwigTemplate_4a426eba9eed0729ad40caf7d472bba3 extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
            'config_fields' => [$this, 'block_config_fields'],
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 32
        yield "
";
        // line 33
        if (($context["canedit"] ?? null)) {
            // line 34
            yield "    <form name=\"form\"
          action=\"";
            // line 35
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Glpi\Application\View\Extension\ItemtypeExtension']->getItemtypeFormPath("Config"), "html", null, true);
            yield "\"
          method=\"post\"
          data-track-changes=\"true\">
        <input type=\"hidden\" name=\"_glpi_csrf_token\" value=\"";
            // line 38
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Session::getNewCSRFToken(), "html", null, true);
            yield "\" />
";
        }
        // line 40
        yield "
<div class=\"row\">
    ";
        // line 42
        yield from $this->unwrap()->yieldBlock('config_fields', $context, $blocks);
        // line 44
        yield "</div>

";
        // line 46
        if (($context["canedit"] ?? null)) {
            // line 47
            yield "        <div class=\"card-footer mx-n2 d-flex\">
            <button type=\"submit\" name=\"update\" value=\"1\" class=\"ms-auto btn btn-primary\">
                <i class=\"ti ti-device-floppy\"></i>
                <span>";
            // line 50
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(_x("button", "Save"), "html", null, true);
            yield "</span>
            </button>
        </div>
    </form>
";
        }
        return; yield '';
    }

    // line 42
    public function block_config_fields($context, array $blocks = [])
    {
        $macros = $this->macros;
        yield "    ";
        return; yield '';
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "pages/setup/general/base_form.html.twig";
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
        return array (  86 => 42,  75 => 50,  70 => 47,  68 => 46,  64 => 44,  62 => 42,  58 => 40,  53 => 38,  47 => 35,  44 => 34,  42 => 33,  39 => 32,);
    }

    public function getSourceContext()
    {
        return new Source("", "pages/setup/general/base_form.html.twig", "D:\\laragon\\www\\glpi\\templates\\pages\\setup\\general\\base_form.html.twig");
    }
}
