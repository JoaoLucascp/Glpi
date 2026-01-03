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

/* install/step8.html.twig */
class __TwigTemplate_5cd5f14f62c67c47c63937da6d36166b extends Template
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
<h2>";
        // line 33
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(__("The installation is finished"), "html", null, true);
        yield "</h2>

<p>
   ";
        // line 36
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(__("Default logins / passwords are:"), "html", null, true);
        yield "
</p>

<p>
   <ul>
      <li>";
        // line 41
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(__("glpi/glpi for the administrator account"), "html", null, true);
        yield "</li>
      <li>";
        // line 42
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(__("tech/tech for the technician account"), "html", null, true);
        yield "</li>
      <li>";
        // line 43
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(__("normal/normal for the normal account"), "html", null, true);
        yield "</li>
      <li>";
        // line 44
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(__("post-only/postonly for the postonly account"), "html", null, true);
        yield "</li>
   </ul>
</p>

<p>
   ";
        // line 49
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(__("You can delete or modify these accounts as well as the initial data."), "html", null, true);
        yield "
</p>

<p class=\"center\">
   <a class=\"btn btn-primary\" href=\"../index.php\">
      <i class=\"fas fa-thumbs-up me-1\"></i>
      ";
        // line 55
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(__("Use GLPI"), "html", null, true);
        yield "
   </a>
</p>
";
        return; yield '';
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "install/step8.html.twig";
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
        return array (  84 => 55,  75 => 49,  67 => 44,  63 => 43,  59 => 42,  55 => 41,  47 => 36,  41 => 33,  38 => 32,);
    }

    public function getSourceContext()
    {
        return new Source("", "install/step8.html.twig", "D:\\laragon\\www\\glpi\\templates\\install\\step8.html.twig");
    }
}
