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

/* components/form/buttons.html.twig */
class __TwigTemplate_87294ad7438f908757f5b17db18df2ea extends Template
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
        $context["candel"] = (((CoreExtension::getAttribute($this->env, $this->source, ($context["params"] ?? null), "candel", [], "array", true, true, false, 34) &&  !(null === (($__internal_compile_2 = ($context["params"] ?? null)) && is_array($__internal_compile_2) || $__internal_compile_2 instanceof ArrayAccess ? ($__internal_compile_2["candel"] ?? null) : null)))) ? ((($__internal_compile_3 = ($context["params"] ?? null)) && is_array($__internal_compile_3) || $__internal_compile_3 instanceof ArrayAccess ? ($__internal_compile_3["candel"] ?? null) : null)) : (true));
        // line 35
        $context["canedit"] = (((CoreExtension::getAttribute($this->env, $this->source, ($context["params"] ?? null), "canedit", [], "array", true, true, false, 35) &&  !(null === (($__internal_compile_4 = ($context["params"] ?? null)) && is_array($__internal_compile_4) || $__internal_compile_4 instanceof ArrayAccess ? ($__internal_compile_4["canedit"] ?? null) : null)))) ? ((($__internal_compile_5 = ($context["params"] ?? null)) && is_array($__internal_compile_5) || $__internal_compile_5 instanceof ArrayAccess ? ($__internal_compile_5["canedit"] ?? null) : null)) : (true));
        // line 36
        $context["id"] = (((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, true, false, 36), "id", [], "array", true, true, false, 36) &&  !(null === (($__internal_compile_6 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, true, false, 36)) && is_array($__internal_compile_6) || $__internal_compile_6 instanceof ArrayAccess ? ($__internal_compile_6["id"] ?? null) : null)))) ? ((($__internal_compile_7 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, true, false, 36)) && is_array($__internal_compile_7) || $__internal_compile_7 instanceof ArrayAccess ? ($__internal_compile_7["id"] ?? null) : null)) : ( -1));
        // line 37
        yield "
         <div class=\"row\">
         ";
        // line 39
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Glpi\Application\View\Extension\PluginExtension']->callPluginHook(Twig\Extension\CoreExtension::constant("Glpi\\Plugin\\Hooks::POST_ITEM_FORM"), ["item" => ($context["item"] ?? null), "options" => ($context["params"] ?? null)]), "html", null, true);
        yield "
         </div>

";
        // line 42
        if ((($context["canedit"] ?? null) || CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "canEdit", [(($__internal_compile_8 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, false, false, 42)) && is_array($__internal_compile_8) || $__internal_compile_8 instanceof ArrayAccess ? ($__internal_compile_8["id"] ?? null) : null)], "method", false, false, false, 42))) {
            // line 43
            yield "      <div class=\"card-body mx-n2 mb-4 border-top d-flex flex-row-reverse align-items-start flex-wrap\">
         ";
            // line 44
            if (((($context["withtemplate"] ?? null) > 0) || CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isNewID", [($context["id"] ?? null)], "method", false, false, false, 44))) {
                // line 45
                yield "            ";
                if (((($context["id"] ?? null) <= 0) || (($context["withtemplate"] ?? null) == 2))) {
                    // line 46
                    yield "               <button class=\"btn btn-primary me-2\" type=\"submit\" name=\"add\" value=\"1\">
                  <i class=\"fas fa-plus\"></i>
                  <span>";
                    // line 48
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(_x("button", "Add"), "html", null, true);
                    yield "</span>
               </button>
            ";
                } else {
                    // line 51
                    yield "               <button class=\"btn btn-primary me-2\" type=\"submit\" name=\"update\" value=\"1\">
                  <i class=\"far fa-save\"></i>
                  <span>";
                    // line 53
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(_x("button", "Save"), "html", null, true);
                    yield "</span>
               </button>
            ";
                }
                // line 56
                yield "         ";
            } else {
                // line 57
                yield "            ";
                if (((($context["candel"] ?? null) &&  !CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "can", [($context["id"] ?? null), Twig\Extension\CoreExtension::constant("DELETE")], "method", false, false, false, 57)) &&  !CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "can", [($context["id"] ?? null), Twig\Extension\CoreExtension::constant("PURGE")], "method", false, false, false, 57))) {
                    // line 58
                    yield "               ";
                    $context["candel"] = false;
                    // line 59
                    yield "            ";
                }
                // line 60
                yield "
            ";
                // line 61
                if ((($context["canedit"] ?? null) && CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "can", [($context["id"] ?? null), Twig\Extension\CoreExtension::constant("UPDATE")], "method", false, false, false, 61))) {
                    // line 62
                    yield "               <button class=\"btn btn-primary me-2\" type=\"submit\" name=\"update\" value=\"1\">
                  <i class=\"far fa-save\"></i>
                  <span>";
                    // line 64
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(_x("button", "Save"), "html", null, true);
                    yield "</span>
               </button>
            ";
                }
                // line 67
                yield "
            ";
                // line 68
                if (($context["candel"] ?? null)) {
                    // line 69
                    yield "               ";
                    if (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isDeleted", [], "method", false, false, false, 69)) {
                        // line 70
                        yield "                  ";
                        if (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "can", [($context["id"] ?? null), Twig\Extension\CoreExtension::constant("DELETE")], "method", false, false, false, 70)) {
                            // line 71
                            yield "                     <button class=\"btn btn-outline-secondary me-2\" type=\"submit\"
                             name=\"restore\" value=\"1\">
                        <i class=\"ti ti-trash-off\"></i>
                        <span>";
                            // line 74
                            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(_x("button", "Restore"), "html", null, true);
                            yield "</span>
                     </button>
                  ";
                        }
                        // line 77
                        yield "
                  ";
                        // line 78
                        if (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "can", [($context["id"] ?? null), Twig\Extension\CoreExtension::constant("PURGE")], "method", false, false, false, 78)) {
                            // line 79
                            yield "                    ";
                            $context["item_devices"] = $this->extensions['Glpi\Application\View\Extension\PhpExtension']->call("Item_Devices::getConcernedItems");
                            // line 80
                            yield "                    ";
                            $context["show_keep_devices"] = CoreExtension::inFilter(CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "getType", [], "method", false, false, false, 80), ($context["item_devices"] ?? null));
                            // line 81
                            yield "
                    ";
                            // line 82
                            $context["span_cls"] = "";
                            // line 83
                            yield "                    ";
                            if (($context["show_keep_devices"] ?? null)) {
                                // line 84
                                yield "                        ";
                                $context["span_cls"] = "border border-danger d-flex align-items-start flex-wrap p-1 px-2 me-2";
                                // line 85
                                yield "                    ";
                            }
                            // line 86
                            yield "
                     <span class=\"";
                            // line 87
                            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["span_cls"] ?? null), "html", null, true);
                            yield "\">
                        <button class=\"btn btn-danger me-2\" type=\"submit\" name=\"purge\"
                              value=\"1\"
                              onclick=\"return confirm('";
                            // line 90
                            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(__("Confirm the final deletion?"), "html", null, true);
                            yield "');\">
                           <i class=\"ti ti-trash\"></i>
                           <span>";
                            // line 92
                            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(_x("button", "Delete permanently"), "html", null, true);
                            yield "</span>
                        </button>
                        ";
                            // line 94
                            if (($context["show_keep_devices"] ?? null)) {
                                // line 95
                                yield "                            <span class=\"mt-2\">
                                <label class=\"form-check form-switch\">
                                    <input class=\"form-check-input\" type=\"checkbox\" name=\"keep_devices\" value=\"1\" />
                                    <span class=\"form-check-label text-danger\">
                                        ";
                                // line 99
                                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(__("Keep the devices while deleting this item"), "html", null, true);
                                yield "
                                    </span>
                                </label>
                            </span>
                        ";
                            }
                            // line 104
                            yield "                     <span>
                  ";
                        }
                        // line 106
                        yield "               ";
                    } else {
                        // line 107
                        yield "                  ";
                        if (( !CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "maybeDeleted", [], "method", false, false, false, 107) || CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "useDeletedToLockIfDynamic", [], "method", false, false, false, 107))) {
                            // line 108
                            yield "                     ";
                            if (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "can", [($context["id"] ?? null), Twig\Extension\CoreExtension::constant("PURGE")], "method", false, false, false, 108)) {
                                // line 109
                                yield "                        <button class=\"btn btn-outline-danger me-2\" type=\"submit\" name=\"purge\"
                                onclick=\"return confirm('";
                                // line 110
                                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(__("Confirm the final deletion?"), "html", null, true);
                                yield "');\"
                                value=\"1\">
                           <i class=\"ti ti-trash\"></i>
                           <span>";
                                // line 113
                                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(_x("button", "Delete permanently"), "html", null, true);
                                yield "</span>
                        </button>
                     ";
                            }
                            // line 116
                            yield "                  ";
                        } elseif (( !CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isDeleted", [], "method", false, false, false, 116) && CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "can", [($context["id"] ?? null), Twig\Extension\CoreExtension::constant("DELETE")], "method", false, false, false, 116))) {
                            // line 117
                            yield "                     <button class=\"btn btn-outline-warning me-2\" type=\"submit\"
                             name=\"delete\" value=\"1\">
                        <i class=\"ti ti-trash\"></i>
                        <span>";
                            // line 120
                            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(_x("button", "Put in trashbin"), "html", null, true);
                            yield "</span>
                     </button>
                  ";
                        }
                        // line 123
                        yield "               ";
                    }
                    // line 124
                    yield "            ";
                }
                // line 125
                yield "
            ";
                // line 126
                if (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isField", ["date_mod"], "method", false, false, false, 126)) {
                    // line 127
                    yield "               <input type=\"hidden\" name=\"_read_date_mod\" value=\"";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((($__internal_compile_9 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, false, false, 127)) && is_array($__internal_compile_9) || $__internal_compile_9 instanceof ArrayAccess ? ($__internal_compile_9["date_mod"] ?? null) : null), "html", null, true);
                    yield "\" />
            ";
                }
                // line 129
                yield "         ";
            }
            // line 130
            yield "
         ";
            // line 131
            if ( !CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isNewID", [($context["id"] ?? null)], "method", false, false, false, 131)) {
                // line 132
                yield "            <input type=\"hidden\" name=\"id\" value=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["id"] ?? null), "html", null, true);
                yield "\" />
         ";
            }
            // line 134
            yield "
         ";
            // line 135
            if ((($context["canedit"] ?? null) && (Twig\Extension\CoreExtension::length($this->env->getCharset(), (($__internal_compile_10 = ($context["params"] ?? null)) && is_array($__internal_compile_10) || $__internal_compile_10 instanceof ArrayAccess ? ($__internal_compile_10["addbuttons"] ?? null) : null)) > 0))) {
                // line 136
                yield "            ";
                $context['_parent'] = $context;
                $context['_seq'] = CoreExtension::ensureTraversable((($__internal_compile_11 = ($context["params"] ?? null)) && is_array($__internal_compile_11) || $__internal_compile_11 instanceof ArrayAccess ? ($__internal_compile_11["addbuttons"] ?? null) : null));
                foreach ($context['_seq'] as $context["key"] => $context["val"]) {
                    // line 137
                    yield "               <button class=\"btn btn-outline-secondary me-2\" type=\"submit\" name=\"";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($context["key"], "html", null, true);
                    yield "\" value=\"1\">
                  ";
                    // line 138
                    if (is_iterable($context["val"])) {
                        // line 139
                        yield "                     ";
                        if (CoreExtension::getAttribute($this->env, $this->source, $context["val"], "icon", [], "any", true, true, false, 139)) {
                            // line 140
                            yield "                        <i class=\"";
                            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["val"], "icon", [], "any", false, false, false, 140), "html", null, true);
                            yield "\"></i>
                     ";
                        }
                        // line 142
                        yield "                     <span>";
                        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["val"], "text", [], "any", true, true, false, 142)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, $context["val"], "text", [], "any", false, false, false, 142), "")) : ("")), "html", null, true);
                        yield "</span>
                  ";
                    } else {
                        // line 144
                        yield "                     ";
                        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($context["val"], "html", null, true);
                        yield "
                  ";
                    }
                    // line 146
                    yield "               </button>
            ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['key'], $context['val'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 148
                yield "         ";
            }
            // line 149
            yield "
      </div>

      <input type=\"hidden\" name=\"_glpi_csrf_token\" value=\"";
            // line 152
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Session::getNewCSRFToken(), "html", null, true);
            yield "\" />
   </div> ";
            // line 154
            yield "</form> ";
        } else {
            // line 156
            yield "   </div> ";
        }
        return; yield '';
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "components/form/buttons.html.twig";
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
        return array (  331 => 156,  328 => 154,  324 => 152,  319 => 149,  316 => 148,  309 => 146,  303 => 144,  297 => 142,  291 => 140,  288 => 139,  286 => 138,  281 => 137,  276 => 136,  274 => 135,  271 => 134,  265 => 132,  263 => 131,  260 => 130,  257 => 129,  251 => 127,  249 => 126,  246 => 125,  243 => 124,  240 => 123,  234 => 120,  229 => 117,  226 => 116,  220 => 113,  214 => 110,  211 => 109,  208 => 108,  205 => 107,  202 => 106,  198 => 104,  190 => 99,  184 => 95,  182 => 94,  177 => 92,  172 => 90,  166 => 87,  163 => 86,  160 => 85,  157 => 84,  154 => 83,  152 => 82,  149 => 81,  146 => 80,  143 => 79,  141 => 78,  138 => 77,  132 => 74,  127 => 71,  124 => 70,  121 => 69,  119 => 68,  116 => 67,  110 => 64,  106 => 62,  104 => 61,  101 => 60,  98 => 59,  95 => 58,  92 => 57,  89 => 56,  83 => 53,  79 => 51,  73 => 48,  69 => 46,  66 => 45,  64 => 44,  61 => 43,  59 => 42,  53 => 39,  49 => 37,  47 => 36,  45 => 35,  43 => 34,  41 => 33,  38 => 32,);
    }

    public function getSourceContext()
    {
        return new Source("", "components/form/buttons.html.twig", "D:\\laragon\\www\\glpi\\templates\\components\\form\\buttons.html.twig");
    }
}
