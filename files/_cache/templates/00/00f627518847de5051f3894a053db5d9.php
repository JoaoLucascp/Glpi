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

/* components/checkbox_matrix.html.twig */
class __TwigTemplate_c8f303a92eae14a622b2df4bafdf83cd extends Template
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
<div class=\"mx-n2 mb-4\">
   <table class=\"table table-hover card-table\">
      <thead>
         <tr class=\"border-top\">
            <th colspan=\"";
        // line 37
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["number_columns"] ?? null), "html", null, true);
        yield "\">
               <h4>";
        // line 38
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["title"] ?? null), "html", null, true);
        yield "</h4>
            </th>
         </tr>
         <tr>
            <th>";
        // line 42
        yield (($__internal_compile_0 = ($context["param"] ?? null)) && is_array($__internal_compile_0) || $__internal_compile_0 instanceof ArrayAccess ? ($__internal_compile_0["first_cell"] ?? null) : null);
        yield "</th>
            ";
        // line 43
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(($context["columns"] ?? null));
        foreach ($context['_seq'] as $context["col_name"] => $context["column"]) {
            // line 44
            yield "               ";
            $context["col_id"] = $this->extensions['Glpi\Application\View\Extension\PhpExtension']->call("Html::cleanId", [((("col_label_" . $context["col_name"]) . "_") . (($__internal_compile_1 = ($context["param"] ?? null)) && is_array($__internal_compile_1) || $__internal_compile_1 instanceof ArrayAccess ? ($__internal_compile_1["rand"] ?? null) : null))]);
            // line 45
            yield "               <th id=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["col_id"] ?? null), "html", null, true);
            yield "\">
                  ";
            // line 46
            if ( !is_iterable($context["column"])) {
                // line 47
                yield "                     ";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($context["column"], "html", null, true);
                yield "
                  ";
            } else {
                // line 49
                yield "                     ";
                if ((CoreExtension::getAttribute($this->env, $this->source, $context["column"], "short", [], "array", true, true, false, 49) && CoreExtension::getAttribute($this->env, $this->source, $context["column"], "long", [], "array", true, true, false, 49))) {
                    // line 50
                    yield "                        ";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((($__internal_compile_2 = $context["column"]) && is_array($__internal_compile_2) || $__internal_compile_2 instanceof ArrayAccess ? ($__internal_compile_2["short"] ?? null) : null), "html", null, true);
                    yield "
                        ";
                    // line 51
                    $this->extensions['Glpi\Application\View\Extension\PhpExtension']->call("Html::showToolTip", [(($__internal_compile_3 = $context["column"]) && is_array($__internal_compile_3) || $__internal_compile_3 instanceof ArrayAccess ? ($__internal_compile_3["long"] ?? null) : null), ["applyto" => ($context["col_id"] ?? null)]]);
                    // line 52
                    yield "                     ";
                } else {
                    // line 53
                    yield "                        ";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((($__internal_compile_4 = $context["column"]) && is_array($__internal_compile_4) || $__internal_compile_4 instanceof ArrayAccess ? ($__internal_compile_4["label"] ?? null) : null), "html", null, true);
                    yield "
                     ";
                }
                // line 55
                yield "                  ";
            }
            // line 56
            yield "               </th>
            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['col_name'], $context['column'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 58
        yield "
            ";
        // line 59
        if ((($__internal_compile_5 = ($context["param"] ?? null)) && is_array($__internal_compile_5) || $__internal_compile_5 instanceof ArrayAccess ? ($__internal_compile_5["row_check_all"] ?? null) : null)) {
            // line 60
            yield "               ";
            $context["col_id"] = $this->extensions['Glpi\Application\View\Extension\PhpExtension']->call("Html::cleanId", [("col_of_table_" . (($__internal_compile_6 = ($context["param"] ?? null)) && is_array($__internal_compile_6) || $__internal_compile_6 instanceof ArrayAccess ? ($__internal_compile_6["rand"] ?? null) : null))]);
            // line 61
            yield "               <th id=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["col_id"] ?? null), "html", null, true);
            yield "\">
                  ";
            // line 62
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(__("Select/unselect all"), "html", null, true);
            yield "
               </th>
            ";
        }
        // line 65
        yield "         </tr>
      </thead>
      <tbody>
         ";
        // line 68
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(($context["rows"] ?? null));
        foreach ($context['_seq'] as $context["row_name"] => $context["row"]) {
            // line 69
            yield "            <tr>
               ";
            // line 70
            if ( !is_iterable($context["row"])) {
                // line 71
                yield "                  <td colspan=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["number_columns"] ?? null), "html", null, true);
                yield "\">";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($context["row"], "html", null, true);
                yield "</td>
               ";
            } else {
                // line 73
                yield "                  ";
                $context["row_id"] = $this->extensions['Glpi\Application\View\Extension\PhpExtension']->call("Html::cleanId", [((("row_label_" . $context["row_name"]) . "_") . (($__internal_compile_7 = ($context["param"] ?? null)) && is_array($__internal_compile_7) || $__internal_compile_7 instanceof ArrayAccess ? ($__internal_compile_7["rand"] ?? null) : null))]);
                // line 74
                yield "                  <td class=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((($__internal_compile_8 = $context["row"]) && is_array($__internal_compile_8) || $__internal_compile_8 instanceof ArrayAccess ? ($__internal_compile_8["class"] ?? null) : null), "html", null, true);
                yield "\" id=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["row_id"] ?? null), "html", null, true);
                yield "\">
                     ";
                // line 75
                (((CoreExtension::getAttribute($this->env, $this->source, $context["row"], "label", [], "array", true, true, false, 75) &&  !(null === (($__internal_compile_9 = $context["row"]) && is_array($__internal_compile_9) || $__internal_compile_9 instanceof ArrayAccess ? ($__internal_compile_9["label"] ?? null) : null)))) ? (yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((($__internal_compile_10 = $context["row"]) && is_array($__internal_compile_10) || $__internal_compile_10 instanceof ArrayAccess ? ($__internal_compile_10["label"] ?? null) : null), "html", null, true)) : (yield "&nbsp;"));
                yield "
                  </td>

                  ";
                // line 78
                $context['_parent'] = $context;
                $context['_seq'] = CoreExtension::ensureTraversable(($context["columns"] ?? null));
                foreach ($context['_seq'] as $context["col_name"] => $context["column"]) {
                    // line 79
                    yield "                     ";
                    $context["content"] = (($__internal_compile_11 = (($__internal_compile_12 = $context["row"]) && is_array($__internal_compile_12) || $__internal_compile_12 instanceof ArrayAccess ? ($__internal_compile_12["columns"] ?? null) : null)) && is_array($__internal_compile_11) || $__internal_compile_11 instanceof ArrayAccess ? ($__internal_compile_11[$context["col_name"]] ?? null) : null);
                    // line 80
                    yield "                     <td>
                        ";
                    // line 81
                    if ((is_iterable(($context["content"] ?? null)) && CoreExtension::getAttribute($this->env, $this->source, ($context["content"] ?? null), "checked", [], "array", true, true, false, 81))) {
                        // line 82
                        yield "                           ";
                        if ( !CoreExtension::getAttribute($this->env, $this->source, ($context["content"] ?? null), "readonly", [], "array", true, true, false, 82)) {
                            // line 83
                            yield "                              ";
                            $context["content"] = Twig\Extension\CoreExtension::merge(($context["content"] ?? null), ["readonly" => false]);
                            // line 84
                            yield "                           ";
                        }
                        // line 85
                        yield "                           ";
                        $context["content"] = Twig\Extension\CoreExtension::merge(($context["content"] ?? null), ["name" => (((                        // line 86
$context["row_name"] . "[") . $context["col_name"]) . "]"), "id" => $this->extensions['Glpi\Application\View\Extension\PhpExtension']->call("Html::cleanId", [((((("cb_" .                         // line 87
$context["row_name"]) . "_") . $context["col_name"]) . "_") . (($__internal_compile_13 = ($context["param"] ?? null)) && is_array($__internal_compile_13) || $__internal_compile_13 instanceof ArrayAccess ? ($__internal_compile_13["rand"] ?? null) : null))])]);
                        // line 89
                        yield "                           ";
                        $context["massive_tags"] = [];
                        // line 90
                        yield "                           ";
                        if ((($__internal_compile_14 = ($context["param"] ?? null)) && is_array($__internal_compile_14) || $__internal_compile_14 instanceof ArrayAccess ? ($__internal_compile_14["row_check_all"] ?? null) : null)) {
                            // line 91
                            yield "                              ";
                            $context["massive_tags"] = Twig\Extension\CoreExtension::merge(($context["massive_tags"] ?? null), [((("row_" .                             // line 92
$context["row_name"]) . "_") . (($__internal_compile_15 = ($context["param"] ?? null)) && is_array($__internal_compile_15) || $__internal_compile_15 instanceof ArrayAccess ? ($__internal_compile_15["rand"] ?? null) : null))]);
                            // line 94
                            yield "                           ";
                        }
                        // line 95
                        yield "                           ";
                        if ((($__internal_compile_16 = ($context["param"] ?? null)) && is_array($__internal_compile_16) || $__internal_compile_16 instanceof ArrayAccess ? ($__internal_compile_16["col_check_all"] ?? null) : null)) {
                            // line 96
                            yield "                              ";
                            $context["massive_tags"] = Twig\Extension\CoreExtension::merge(($context["massive_tags"] ?? null), [((("col_" .                             // line 97
$context["col_name"]) . "_") . (($__internal_compile_17 = ($context["param"] ?? null)) && is_array($__internal_compile_17) || $__internal_compile_17 instanceof ArrayAccess ? ($__internal_compile_17["rand"] ?? null) : null))]);
                            // line 99
                            yield "                           ";
                        }
                        // line 100
                        yield "                           ";
                        if (((($__internal_compile_18 = ($context["param"] ?? null)) && is_array($__internal_compile_18) || $__internal_compile_18 instanceof ArrayAccess ? ($__internal_compile_18["row_check_all"] ?? null) : null) && (($__internal_compile_19 = ($context["param"] ?? null)) && is_array($__internal_compile_19) || $__internal_compile_19 instanceof ArrayAccess ? ($__internal_compile_19["col_check_all"] ?? null) : null))) {
                            // line 101
                            yield "                              ";
                            $context["massive_tags"] = Twig\Extension\CoreExtension::merge(($context["massive_tags"] ?? null), [("table_" . (($__internal_compile_20 =                             // line 102
($context["param"] ?? null)) && is_array($__internal_compile_20) || $__internal_compile_20 instanceof ArrayAccess ? ($__internal_compile_20["rand"] ?? null) : null))]);
                            // line 104
                            yield "                           ";
                        }
                        // line 105
                        yield "                           ";
                        $context["content"] = Twig\Extension\CoreExtension::merge(($context["content"] ?? null), ["massive_tags" =>                         // line 106
($context["massive_tags"] ?? null)]);
                        // line 108
                        yield "                           ";
                        $this->extensions['Glpi\Application\View\Extension\PhpExtension']->call("Html::showCheckbox", [($context["content"] ?? null)]);
                        // line 109
                        yield "
                        ";
                    } else {
                        // line 111
                        yield "                           ";
                        if ( !is_iterable(($context["content"] ?? null))) {
                            // line 112
                            yield "                              ";
                            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["content"] ?? null), "html", null, true);
                            yield "
                           ";
                        }
                        // line 114
                        yield "                        ";
                    }
                    // line 115
                    yield "                     </td>
                  ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['col_name'], $context['column'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 117
                yield "
                  ";
                // line 118
                if ((($__internal_compile_21 = ($context["param"] ?? null)) && is_array($__internal_compile_21) || $__internal_compile_21 instanceof ArrayAccess ? ($__internal_compile_21["row_check_all"] ?? null) : null)) {
                    // line 119
                    yield "                     <td>
                        ";
                    // line 120
                    $this->extensions['Glpi\Application\View\Extension\PhpExtension']->call("Html::showCheckbox", [["title" => __("Check/uncheck all"), "criterion" => ["tag_for_massive" => ((("row_" .                     // line 123
$context["row_name"]) . "_") . (($__internal_compile_22 = ($context["param"] ?? null)) && is_array($__internal_compile_22) || $__internal_compile_22 instanceof ArrayAccess ? ($__internal_compile_22["rand"] ?? null) : null))], "massive_tags" => ("table_" . (($__internal_compile_23 =                     // line 125
($context["param"] ?? null)) && is_array($__internal_compile_23) || $__internal_compile_23 instanceof ArrayAccess ? ($__internal_compile_23["rand"] ?? null) : null)), "id" => $this->extensions['Glpi\Application\View\Extension\PhpExtension']->call("Html::cleanId", [((("cb_checkall_row_" .                     // line 126
$context["row_name"]) . "_") . (($__internal_compile_24 = ($context["param"] ?? null)) && is_array($__internal_compile_24) || $__internal_compile_24 instanceof ArrayAccess ? ($__internal_compile_24["rand"] ?? null) : null))]), "checked" => ((($__internal_compile_25 = (($__internal_compile_26 =                     // line 127
($context["nb_cb_per_row"] ?? null)) && is_array($__internal_compile_26) || $__internal_compile_26 instanceof ArrayAccess ? ($__internal_compile_26[$context["row_name"]] ?? null) : null)) && is_array($__internal_compile_25) || $__internal_compile_25 instanceof ArrayAccess ? ($__internal_compile_25["checked"] ?? null) : null) >= (($__internal_compile_27 = (($__internal_compile_28 = ($context["nb_cb_per_row"] ?? null)) && is_array($__internal_compile_28) || $__internal_compile_28 instanceof ArrayAccess ? ($__internal_compile_28[$context["row_name"]] ?? null) : null)) && is_array($__internal_compile_27) || $__internal_compile_27 instanceof ArrayAccess ? ($__internal_compile_27["total"] ?? null) : null))]]);
                    // line 129
                    yield "                     </td>
                  ";
                }
                // line 131
                yield "               ";
            }
            // line 132
            yield "            </tr>
         ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['row_name'], $context['row'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 134
        yield "
         ";
        // line 135
        if ((($__internal_compile_29 = ($context["param"] ?? null)) && is_array($__internal_compile_29) || $__internal_compile_29 instanceof ArrayAccess ? ($__internal_compile_29["col_check_all"] ?? null) : null)) {
            // line 136
            yield "            <tr>
               <td>";
            // line 137
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(__("Select/unselect all"), "html", null, true);
            yield "</td>
               ";
            // line 138
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(($context["columns"] ?? null));
            foreach ($context['_seq'] as $context["col_name"] => $context["column"]) {
                // line 139
                yield "                  <td>
                     ";
                // line 140
                if (((($__internal_compile_30 = (($__internal_compile_31 = ($context["nb_cb_per_col"] ?? null)) && is_array($__internal_compile_31) || $__internal_compile_31 instanceof ArrayAccess ? ($__internal_compile_31[$context["col_name"]] ?? null) : null)) && is_array($__internal_compile_30) || $__internal_compile_30 instanceof ArrayAccess ? ($__internal_compile_30["total"] ?? null) : null) >= 2)) {
                    // line 141
                    yield "                        ";
                    $this->extensions['Glpi\Application\View\Extension\PhpExtension']->call("Html::showCheckbox", [["title" => __("Check/uncheck all"), "criterion" => ["tag_for_massive" => ((("col_" .                     // line 144
$context["col_name"]) . "_") . (($__internal_compile_32 = ($context["param"] ?? null)) && is_array($__internal_compile_32) || $__internal_compile_32 instanceof ArrayAccess ? ($__internal_compile_32["rand"] ?? null) : null))], "massive_tags" => ("table_" . (($__internal_compile_33 =                     // line 146
($context["param"] ?? null)) && is_array($__internal_compile_33) || $__internal_compile_33 instanceof ArrayAccess ? ($__internal_compile_33["rand"] ?? null) : null)), "id" => $this->extensions['Glpi\Application\View\Extension\PhpExtension']->call("Html::cleanId", [(("cb_checkall_col_" .                     // line 147
$context["col_name"]) . (($__internal_compile_34 = ($context["param"] ?? null)) && is_array($__internal_compile_34) || $__internal_compile_34 instanceof ArrayAccess ? ($__internal_compile_34["rand"] ?? null) : null))]), "checked" => ((($__internal_compile_35 = (($__internal_compile_36 =                     // line 148
($context["nb_cb_per_col"] ?? null)) && is_array($__internal_compile_36) || $__internal_compile_36 instanceof ArrayAccess ? ($__internal_compile_36[$context["col_name"]] ?? null) : null)) && is_array($__internal_compile_35) || $__internal_compile_35 instanceof ArrayAccess ? ($__internal_compile_35["checked"] ?? null) : null) >= (($__internal_compile_37 = (($__internal_compile_38 = ($context["nb_cb_per_col"] ?? null)) && is_array($__internal_compile_38) || $__internal_compile_38 instanceof ArrayAccess ? ($__internal_compile_38[$context["col_name"]] ?? null) : null)) && is_array($__internal_compile_37) || $__internal_compile_37 instanceof ArrayAccess ? ($__internal_compile_37["total"] ?? null) : null))]]);
                    // line 150
                    yield "                     ";
                }
                // line 151
                yield "                  </td>
               ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['col_name'], $context['column'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 153
            yield "
               ";
            // line 154
            if ((($__internal_compile_39 = ($context["param"] ?? null)) && is_array($__internal_compile_39) || $__internal_compile_39 instanceof ArrayAccess ? ($__internal_compile_39["row_check_all"] ?? null) : null)) {
                // line 155
                yield "                  <td>
                     ";
                // line 156
                $this->extensions['Glpi\Application\View\Extension\PhpExtension']->call("Html::showCheckbox", [["title" => __("Check/uncheck all"), "criterion" => ["tag_for_massive" => ("table_" . (($__internal_compile_40 =                 // line 159
($context["param"] ?? null)) && is_array($__internal_compile_40) || $__internal_compile_40 instanceof ArrayAccess ? ($__internal_compile_40["rand"] ?? null) : null))], "massive_tags" => "", "id" => $this->extensions['Glpi\Application\View\Extension\PhpExtension']->call("Html::cleanId", [("cb_checkall_table_" . (($__internal_compile_41 =                 // line 162
($context["param"] ?? null)) && is_array($__internal_compile_41) || $__internal_compile_41 instanceof ArrayAccess ? ($__internal_compile_41["rand"] ?? null) : null))])]]);
                // line 164
                yield "                  </td>
               ";
            }
            // line 166
            yield "            </tr>
         ";
        }
        // line 168
        yield "      </tbody>
   </table>
</div>
";
        return; yield '';
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "components/checkbox_matrix.html.twig";
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
        return array (  341 => 168,  337 => 166,  333 => 164,  331 => 162,  330 => 159,  329 => 156,  326 => 155,  324 => 154,  321 => 153,  314 => 151,  311 => 150,  309 => 148,  308 => 147,  307 => 146,  306 => 144,  304 => 141,  302 => 140,  299 => 139,  295 => 138,  291 => 137,  288 => 136,  286 => 135,  283 => 134,  276 => 132,  273 => 131,  269 => 129,  267 => 127,  266 => 126,  265 => 125,  264 => 123,  263 => 120,  260 => 119,  258 => 118,  255 => 117,  248 => 115,  245 => 114,  239 => 112,  236 => 111,  232 => 109,  229 => 108,  227 => 106,  225 => 105,  222 => 104,  220 => 102,  218 => 101,  215 => 100,  212 => 99,  210 => 97,  208 => 96,  205 => 95,  202 => 94,  200 => 92,  198 => 91,  195 => 90,  192 => 89,  190 => 87,  189 => 86,  187 => 85,  184 => 84,  181 => 83,  178 => 82,  176 => 81,  173 => 80,  170 => 79,  166 => 78,  160 => 75,  153 => 74,  150 => 73,  142 => 71,  140 => 70,  137 => 69,  133 => 68,  128 => 65,  122 => 62,  117 => 61,  114 => 60,  112 => 59,  109 => 58,  102 => 56,  99 => 55,  93 => 53,  90 => 52,  88 => 51,  83 => 50,  80 => 49,  74 => 47,  72 => 46,  67 => 45,  64 => 44,  60 => 43,  56 => 42,  49 => 38,  45 => 37,  38 => 32,);
    }

    public function getSourceContext()
    {
        return new Source("", "components/checkbox_matrix.html.twig", "D:\\laragon\\www\\glpi\\templates\\components\\checkbox_matrix.html.twig");
    }
}
