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

/* generic_show_form.html.twig */
class __TwigTemplate_360927b0768d825b09c88aeaf42d8a9a extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
            'form_fields' => [$this, 'block_form_fields'],
            'more_fields' => [$this, 'block_more_fields'],
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 32
        yield "
";
        // line 33
        $macros["fields"] = $this->macros["fields"] = $this->loadTemplate("components/form/fields_macros.html.twig", "generic_show_form.html.twig", 33)->unwrap();
        // line 34
        yield "
";
        // line 35
        $context["no_header"] = ((array_key_exists("no_header", $context)) ? (Twig\Extension\CoreExtension::default(($context["no_header"] ?? null), ( !CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isNewItem", [], "method", false, false, false, 35) &&  !((CoreExtension::getAttribute($this->env, $this->source, ($context["_get"] ?? null), "_in_modal", [], "any", true, true, false, 35)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, ($context["_get"] ?? null), "_in_modal", [], "any", false, false, false, 35), false)) : (false))))) : (( !CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isNewItem", [], "method", false, false, false, 35) &&  !((CoreExtension::getAttribute($this->env, $this->source, ($context["_get"] ?? null), "_in_modal", [], "any", true, true, false, 35)) ? (Twig\Extension\CoreExtension::default(CoreExtension::getAttribute($this->env, $this->source, ($context["_get"] ?? null), "_in_modal", [], "any", false, false, false, 35), false)) : (false)))));
        // line 36
        $context["bg"] = "";
        // line 37
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isDeleted", [], "method", false, false, false, 37)) {
            // line 38
            yield "   ";
            $context["bg"] = "asset-deleted";
        }
        // line 40
        yield "
<div class=\"asset ";
        // line 41
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["bg"] ?? null), "html", null, true);
        yield "\">
   ";
        // line 42
        yield Twig\Extension\CoreExtension::include($this->env, $context, "components/form/header.html.twig", ["in_twig" => true]);
        yield "

   ";
        // line 44
        $context["rand"] = Twig\Extension\CoreExtension::random($this->env->getCharset());
        // line 45
        yield "   ";
        $context["params"] = (($context["params"]) ?? ([]));
        // line 46
        yield "   ";
        $context["target"] = (((CoreExtension::getAttribute($this->env, $this->source, ($context["params"] ?? null), "target", [], "array", true, true, false, 46) &&  !(null === (($__internal_compile_0 = ($context["params"] ?? null)) && is_array($__internal_compile_0) || $__internal_compile_0 instanceof ArrayAccess ? ($__internal_compile_0["target"] ?? null) : null)))) ? ((($__internal_compile_1 = ($context["params"] ?? null)) && is_array($__internal_compile_1) || $__internal_compile_1 instanceof ArrayAccess ? ($__internal_compile_1["target"] ?? null) : null)) : (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "getFormURL", [], "method", false, false, false, 46)));
        // line 47
        yield "   ";
        $context["withtemplate"] = (((CoreExtension::getAttribute($this->env, $this->source, ($context["params"] ?? null), "withtemplate", [], "array", true, true, false, 47) &&  !(null === (($__internal_compile_2 = ($context["params"] ?? null)) && is_array($__internal_compile_2) || $__internal_compile_2 instanceof ArrayAccess ? ($__internal_compile_2["withtemplate"] ?? null) : null)))) ? ((($__internal_compile_3 = ($context["params"] ?? null)) && is_array($__internal_compile_3) || $__internal_compile_3 instanceof ArrayAccess ? ($__internal_compile_3["withtemplate"] ?? null) : null)) : (""));
        // line 48
        yield "   ";
        $context["item_type"] = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "getType", [], "method", false, false, false, 48);
        // line 49
        yield "   ";
        $context["item_has_pictures"] = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "hasItemtypeOrModelPictures", [], "method", false, false, false, 49);
        // line 50
        yield "   ";
        $context["field_options"] = ["locked_fields" => CoreExtension::getAttribute($this->env, $this->source,         // line 51
($context["item"] ?? null), "getLockedFields", [], "method", false, false, false, 51)];
        // line 53
        yield "
   <div class=\"card-body d-flex flex-wrap\">
      <div class=\"col-12 col-xxl-";
        // line 55
        yield ((($context["item_has_pictures"] ?? null)) ? ("9") : ("12"));
        yield " flex-column\">
         <div class=\"d-flex flex-row flex-wrap flex-xl-nowrap\">
            <div class=\"row flex-row align-items-start flex-grow-1\" style=\"min-width: 0;\">
               <div class=\"row flex-row\">
                  ";
        // line 59
        yield from $this->unwrap()->yieldBlock('form_fields', $context, $blocks);
        // line 665
        yield "               </div> ";
        // line 666
        yield "            </div> ";
        // line 667
        yield "         </div> ";
        // line 668
        yield "      </div>
      ";
        // line 669
        if (($context["item_has_pictures"] ?? null)) {
            // line 670
            yield "         <div class=\"col-12 col-xxl-3 flex-column\">
            <div class=\"flex-row asset-pictures\">
               ";
            // line 672
            yield Twig\Extension\CoreExtension::include($this->env, $context, "components/form/pictures.html.twig", ["gallery_type" => ""]);
            yield "
            </div>
         </div>
      ";
        }
        // line 676
        yield "   </div> ";
        // line 677
        yield "
   ";
        // line 678
        if ((($context["item_type"] ?? null) == "Contract")) {
            // line 679
            yield "      ";
            yield Twig\Extension\CoreExtension::include($this->env, $context, "components/form/support_hours.html.twig");
            yield "
   ";
        }
        // line 681
        yield "   ";
        yield Twig\Extension\CoreExtension::include($this->env, $context, "components/form/buttons.html.twig");
        yield "
   ";
        // line 682
        if (( !array_key_exists("no_inventory_footer", $context) || (($context["no_inventory_footer"] ?? null) == false))) {
            // line 683
            yield "      ";
            yield Twig\Extension\CoreExtension::include($this->env, $context, "components/form/inventory_info.html.twig");
            yield "
   ";
        }
        // line 685
        yield "
   ";
        // line 686
        if (((null === (($__internal_compile_4 = ($context["params"] ?? null)) && is_array($__internal_compile_4) || $__internal_compile_4 instanceof ArrayAccess ? ($__internal_compile_4["formfooter"] ?? null) : null)) || ((($__internal_compile_5 = ($context["params"] ?? null)) && is_array($__internal_compile_5) || $__internal_compile_5 instanceof ArrayAccess ? ($__internal_compile_5["formfooter"] ?? null) : null) == true))) {
            // line 687
            yield "      <div class=\"card-footer mx-n2 mb-n2 mt-4\">
         ";
            // line 688
            yield Twig\Extension\CoreExtension::include($this->env, $context, "components/form/dates.html.twig");
            yield "
      </div>
   ";
        }
        // line 691
        yield "</div>
";
        return; yield '';
    }

    // line 59
    public function block_form_fields($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 60
        yield "                     ";
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isField", ["name"], "method", false, false, false, 60)) {
            // line 61
            yield "                        ";
            yield CoreExtension::callMacro($macros["fields"], "macro_autoNameField", ["name",             // line 63
($context["item"] ?? null), (((            // line 64
($context["item_type"] ?? null) == "Contact")) ? (__("Surname")) : (__("Name"))),             // line 65
($context["withtemplate"] ?? null),             // line 66
($context["field_options"] ?? null)], 61, $context, $this->getSourceContext());
            // line 67
            yield "
                     ";
        }
        // line 69
        yield "
                     ";
        // line 70
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isField", ["firstname"], "method", false, false, false, 70)) {
            // line 71
            yield "                        ";
            yield CoreExtension::callMacro($macros["fields"], "macro_autoNameField", ["firstname",             // line 73
($context["item"] ?? null), __("First name"),             // line 75
($context["withtemplate"] ?? null),             // line 76
($context["field_options"] ?? null)], 71, $context, $this->getSourceContext());
            // line 77
            yield "
                     ";
        }
        // line 79
        yield "
                     ";
        // line 80
        if (((CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isField", ["template_name"], "method", false, false, false, 80) && (($context["withtemplate"] ?? null) == 1)) && ($context["no_header"] ?? null))) {
            // line 81
            yield "                        ";
            yield CoreExtension::callMacro($macros["fields"], "macro_autoNameField", ["template_name",             // line 83
($context["item"] ?? null), __("Template name"), 0,             // line 86
($context["field_options"] ?? null)], 81, $context, $this->getSourceContext());
            // line 87
            yield "
                     ";
        }
        // line 89
        yield "
                     ";
        // line 90
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isField", ["is_active"], "method", false, false, false, 90)) {
            // line 91
            yield "                        ";
            if ((($context["withtemplate"] ?? null) >= 1)) {
                // line 92
                yield "                           ";
                yield CoreExtension::callMacro($macros["fields"], "macro_hiddenField", ["is_active", (($__internal_compile_6 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, false, false, 92)) && is_array($__internal_compile_6) || $__internal_compile_6 instanceof ArrayAccess ? ($__internal_compile_6["is_active"] ?? null) : null), __("Is active"), ["add_field_html" => __("No")]], 92, $context, $this->getSourceContext());
                // line 94
                yield "
                        ";
            } else {
                // line 96
                yield "                        ";
            }
            // line 97
            yield "                     ";
        }
        // line 98
        yield "
                     ";
        // line 99
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isField", ["states_id"], "method", false, false, false, 99)) {
            // line 100
            yield "                        ";
            $context["condition"] = ((CoreExtension::inFilter(CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "getType", [], "method", false, false, false, 100), $this->extensions['Glpi\Application\View\Extension\ConfigExtension']->config("state_types"))) ? ([("is_visible_" . Twig\Extension\CoreExtension::lower($this->env->getCharset(), CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "getType", [], "method", false, false, false, 100))) => 1]) : ([]));
            // line 101
            yield "                        ";
            yield CoreExtension::callMacro($macros["fields"], "macro_dropdownField", ["State", "states_id", (($__internal_compile_7 = CoreExtension::getAttribute($this->env, $this->source,             // line 104
($context["item"] ?? null), "fields", [], "any", false, false, false, 104)) && is_array($__internal_compile_7) || $__internal_compile_7 instanceof ArrayAccess ? ($__internal_compile_7["states_id"] ?? null) : null), __("Status"), Twig\Extension\CoreExtension::merge(            // line 106
($context["field_options"] ?? null), ["entity" => (($__internal_compile_8 = CoreExtension::getAttribute($this->env, $this->source,             // line 107
($context["item"] ?? null), "fields", [], "any", false, false, false, 107)) && is_array($__internal_compile_8) || $__internal_compile_8 instanceof ArrayAccess ? ($__internal_compile_8["entities_id"] ?? null) : null), "condition" =>             // line 108
($context["condition"] ?? null)])], 101, $context, $this->getSourceContext());
            // line 110
            yield "
                     ";
        }
        // line 112
        yield "
                     ";
        // line 113
        $context["fk"] = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "getForeignKeyField", [], "method", false, false, false, 113);
        // line 114
        yield "                     ";
        if ((CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isField", [($context["fk"] ?? null)], "method", false, false, false, 114) && (($context["item_type"] ?? null) != "Software"))) {
            // line 115
            yield "                        ";
            yield CoreExtension::callMacro($macros["fields"], "macro_dropdownField", [            // line 116
($context["item_type"] ?? null),             // line 117
($context["fk"] ?? null), (($__internal_compile_9 = CoreExtension::getAttribute($this->env, $this->source,             // line 118
($context["item"] ?? null), "fields", [], "any", false, false, false, 118)) && is_array($__internal_compile_9) || $__internal_compile_9 instanceof ArrayAccess ? ($__internal_compile_9[($context["fk"] ?? null)] ?? null) : null), __("As child of"), Twig\Extension\CoreExtension::merge(            // line 120
($context["field_options"] ?? null), ["entity" => (($__internal_compile_10 = CoreExtension::getAttribute($this->env, $this->source,             // line 121
($context["item"] ?? null), "fields", [], "any", false, false, false, 121)) && is_array($__internal_compile_10) || $__internal_compile_10 instanceof ArrayAccess ? ($__internal_compile_10["entities_id"] ?? null) : null)])], 115, $context, $this->getSourceContext());
            // line 123
            yield "
                     ";
        }
        // line 125
        yield "
                     ";
        // line 126
        if (((($context["item_type"] ?? null) != "SoftwareLicense") && CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isField", ["is_helpdesk_visible"], "method", false, false, false, 126))) {
            // line 127
            yield "                        ";
            // line 128
            yield "                        ";
            yield CoreExtension::callMacro($macros["fields"], "macro_checkboxField", ["is_helpdesk_visible", (($__internal_compile_11 = CoreExtension::getAttribute($this->env, $this->source,             // line 130
($context["item"] ?? null), "fields", [], "any", false, false, false, 130)) && is_array($__internal_compile_11) || $__internal_compile_11 instanceof ArrayAccess ? ($__internal_compile_11["is_helpdesk_visible"] ?? null) : null), __("Associable to a ticket"),             // line 132
($context["field_options"] ?? null)], 128, $context, $this->getSourceContext());
            // line 133
            yield "
                     ";
        }
        // line 135
        yield "
                     ";
        // line 136
        $context["dc_breadcrumbs"] = (($this->extensions['Glpi\Application\View\Extension\PhpExtension']->isUsingTrait(($context["item"] ?? null), "Glpi\\Features\\DCBreadcrumb")) ? (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "getDcBreadcrumb", [], "method", false, false, false, 136)) : ([]));
        // line 137
        yield "                     ";
        if ((Twig\Extension\CoreExtension::length($this->env->getCharset(), ($context["dc_breadcrumbs"] ?? null)) > 0)) {
            // line 138
            yield "                        ";
            $context["dc_breadcrumbs"] = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
                // line 139
                yield "                           <ol class=\"breadcrumb breadcrumb-arrows\" aria-label=\"breadcrumbs\">
                              ";
                // line 140
                $context['_parent'] = $context;
                $context['_seq'] = CoreExtension::ensureTraversable(Twig\Extension\CoreExtension::reverse($this->env->getCharset(), ($context["dc_breadcrumbs"] ?? null)));
                foreach ($context['_seq'] as $context["_key"] => $context["dc_item"]) {
                    // line 141
                    yield "                                 <li class=\"breadcrumb-item text-nowrap\">";
                    yield $context["dc_item"];
                    yield "</li>
                              ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['dc_item'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 143
                yield "                           </ol>
                        ";
                return; yield '';
            })())) ? '' : new Markup($tmp, $this->env->getCharset());
            // line 145
            yield "
                        ";
            // line 146
            yield CoreExtension::callMacro($macros["fields"], "macro_htmlField", ["",             // line 148
($context["dc_breadcrumbs"] ?? null), __("Data center position")], 146, $context, $this->getSourceContext());
            // line 150
            yield "

                        ";
            // line 152
            yield CoreExtension::callMacro($macros["fields"], "macro_nullField", [], 152, $context, $this->getSourceContext());
            yield " ";
            // line 153
            yield "                     ";
        }
        // line 154
        yield "
                     ";
        // line 155
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isField", ["locations_id"], "method", false, false, false, 155)) {
            // line 156
            yield "                        ";
            yield CoreExtension::callMacro($macros["fields"], "macro_dropdownField", ["Location", "locations_id", (($__internal_compile_12 = CoreExtension::getAttribute($this->env, $this->source,             // line 159
($context["item"] ?? null), "fields", [], "any", false, false, false, 159)) && is_array($__internal_compile_12) || $__internal_compile_12 instanceof ArrayAccess ? ($__internal_compile_12["locations_id"] ?? null) : null), $this->extensions['Glpi\Application\View\Extension\ItemtypeExtension']->getItemtypeName("Location"), Twig\Extension\CoreExtension::merge(            // line 161
($context["field_options"] ?? null), ["entity" => (($__internal_compile_13 = CoreExtension::getAttribute($this->env, $this->source,             // line 162
($context["item"] ?? null), "fields", [], "any", false, false, false, 162)) && is_array($__internal_compile_13) || $__internal_compile_13 instanceof ArrayAccess ? ($__internal_compile_13["entities_id"] ?? null) : null)])], 156, $context, $this->getSourceContext());
            // line 164
            yield "
                     ";
        }
        // line 166
        yield "
                     ";
        // line 167
        if (((($context["item_type"] ?? null) == "Unmanaged") && CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isField", ["item_type"], "method", false, false, false, 167))) {
            // line 168
            yield "                        ";
            yield CoreExtension::callMacro($macros["fields"], "macro_dropdownArrayField", ["item_type", (($__internal_compile_14 = CoreExtension::getAttribute($this->env, $this->source,             // line 170
($context["item"] ?? null), "fields", [], "any", false, false, false, 170)) && is_array($__internal_compile_14) || $__internal_compile_14 instanceof ArrayAccess ? ($__internal_compile_14["itemtype"] ?? null) : null), _n("Type", "Types", 1), ["Computer", "NetworkEquipment", "Printer", "Peripheral", "Phone"],             // line 175
($context["field_options"] ?? null)], 168, $context, $this->getSourceContext());
            // line 176
            yield "
                     ";
        }
        // line 178
        yield "
                     ";
        // line 179
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isField", ["date_domaincreation"], "method", false, false, false, 179)) {
            // line 180
            yield "                        ";
            yield CoreExtension::callMacro($macros["fields"], "macro_datetimeField", ["date_domaincreation", (($__internal_compile_15 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, false, false, 180)) && is_array($__internal_compile_15) || $__internal_compile_15 instanceof ArrayAccess ? ($__internal_compile_15["date_domaincreation"] ?? null) : null), __("Registration date")], 180, $context, $this->getSourceContext());
            yield "
                     ";
        }
        // line 182
        yield "
                     ";
        // line 183
        $context["type_itemtype"] = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "getTypeClass", [], "method", false, false, false, 183);
        // line 184
        yield "                     ";
        $context["type_fk"] = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "getTypeForeignKeyField", [], "method", false, false, false, 184);
        // line 185
        yield "                     ";
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isField", [($context["type_fk"] ?? null)], "method", false, false, false, 185)) {
            // line 186
            yield "                        ";
            yield CoreExtension::callMacro($macros["fields"], "macro_dropdownField", [            // line 187
($context["type_itemtype"] ?? null),             // line 188
($context["type_fk"] ?? null), (($__internal_compile_16 = CoreExtension::getAttribute($this->env, $this->source,             // line 189
($context["item"] ?? null), "fields", [], "any", false, false, false, 189)) && is_array($__internal_compile_16) || $__internal_compile_16 instanceof ArrayAccess ? ($__internal_compile_16[($context["type_fk"] ?? null)] ?? null) : null), $this->extensions['Glpi\Application\View\Extension\ItemtypeExtension']->getItemtypeName(            // line 190
($context["type_itemtype"] ?? null)),             // line 191
($context["field_options"] ?? null)], 186, $context, $this->getSourceContext());
            // line 192
            yield "
                     ";
        }
        // line 194
        yield "
                     ";
        // line 195
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isField", ["usertitles_id"], "method", false, false, false, 195)) {
            // line 196
            yield "                        ";
            yield CoreExtension::callMacro($macros["fields"], "macro_dropdownField", ["UserTitle", "usertitles_id", (($__internal_compile_17 = CoreExtension::getAttribute($this->env, $this->source,             // line 199
($context["item"] ?? null), "fields", [], "any", false, false, false, 199)) && is_array($__internal_compile_17) || $__internal_compile_17 instanceof ArrayAccess ? ($__internal_compile_17["usertitles_id"] ?? null) : null), $this->extensions['Glpi\Application\View\Extension\ItemtypeExtension']->getItemtypeName("UserTitle"), Twig\Extension\CoreExtension::merge(            // line 201
($context["field_options"] ?? null), ["entity" => (($__internal_compile_18 = CoreExtension::getAttribute($this->env, $this->source,             // line 202
($context["item"] ?? null), "fields", [], "any", false, false, false, 202)) && is_array($__internal_compile_18) || $__internal_compile_18 instanceof ArrayAccess ? ($__internal_compile_18["entities_id"] ?? null) : null)])], 196, $context, $this->getSourceContext());
            // line 204
            yield "
                     ";
        }
        // line 206
        yield "
                     ";
        // line 207
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isField", ["registration_number"], "method", false, false, false, 207)) {
            // line 208
            yield "                        ";
            yield CoreExtension::callMacro($macros["fields"], "macro_autoNameField", ["registration_number",             // line 210
($context["item"] ?? null), (((is_string($__internal_compile_19 =             // line 211
($context["item_type"] ?? null)) && is_string($__internal_compile_20 = "User") && str_starts_with($__internal_compile_19, $__internal_compile_20))) ? (_x("user", "Administrative number")) : (_x("infocom", "Administrative number"))),             // line 212
($context["withtemplate"] ?? null),             // line 213
($context["field_options"] ?? null)], 208, $context, $this->getSourceContext());
            // line 214
            yield "
                     ";
        }
        // line 216
        yield "
                     ";
        // line 217
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isField", ["phone"], "method", false, false, false, 217)) {
            // line 218
            yield "                        ";
            yield CoreExtension::callMacro($macros["fields"], "macro_autoNameField", ["phone",             // line 220
($context["item"] ?? null), $this->extensions['Glpi\Application\View\Extension\ItemtypeExtension']->getItemtypeName("Phone"),             // line 222
($context["withtemplate"] ?? null),             // line 223
($context["field_options"] ?? null)], 218, $context, $this->getSourceContext());
            // line 224
            yield "
                     ";
        }
        // line 226
        yield "
                     ";
        // line 227
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isField", ["phone2"], "method", false, false, false, 227)) {
            // line 228
            yield "                        ";
            yield CoreExtension::callMacro($macros["fields"], "macro_autoNameField", ["phone2",             // line 230
($context["item"] ?? null), __("Phone 2"),             // line 232
($context["withtemplate"] ?? null),             // line 233
($context["field_options"] ?? null)], 228, $context, $this->getSourceContext());
            // line 234
            yield "
                     ";
        }
        // line 236
        yield "
                     ";
        // line 237
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isField", ["phonenumber"], "method", false, false, false, 237)) {
            // line 238
            yield "                        ";
            yield CoreExtension::callMacro($macros["fields"], "macro_autoNameField", ["phonenumber",             // line 240
($context["item"] ?? null), $this->extensions['Glpi\Application\View\Extension\ItemtypeExtension']->getItemtypeName("Phone"),             // line 242
($context["withtemplate"] ?? null),             // line 243
($context["field_options"] ?? null)], 238, $context, $this->getSourceContext());
            // line 244
            yield "
                     ";
        }
        // line 246
        yield "
                     ";
        // line 247
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isField", ["mobile"], "method", false, false, false, 247)) {
            // line 248
            yield "                        ";
            yield CoreExtension::callMacro($macros["fields"], "macro_autoNameField", ["mobile",             // line 250
($context["item"] ?? null), __("Mobile phone"),             // line 252
($context["withtemplate"] ?? null),             // line 253
($context["field_options"] ?? null)], 248, $context, $this->getSourceContext());
            // line 254
            yield "
                     ";
        }
        // line 256
        yield "
                     ";
        // line 257
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isField", ["fax"], "method", false, false, false, 257)) {
            // line 258
            yield "                        ";
            yield CoreExtension::callMacro($macros["fields"], "macro_autoNameField", ["fax",             // line 260
($context["item"] ?? null), __("Fax"),             // line 262
($context["withtemplate"] ?? null),             // line 263
($context["field_options"] ?? null)], 258, $context, $this->getSourceContext());
            // line 264
            yield "
                     ";
        }
        // line 266
        yield "
                     ";
        // line 267
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isField", ["website"], "method", false, false, false, 267)) {
            // line 268
            yield "                        ";
            yield CoreExtension::callMacro($macros["fields"], "macro_autoNameField", ["website",             // line 270
($context["item"] ?? null), __("Website"),             // line 272
($context["withtemplate"] ?? null),             // line 273
($context["field_options"] ?? null)], 268, $context, $this->getSourceContext());
            // line 274
            yield "
                     ";
        }
        // line 276
        yield "
                     ";
        // line 277
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isField", ["email"], "method", false, false, false, 277)) {
            // line 278
            yield "                        ";
            yield CoreExtension::callMacro($macros["fields"], "macro_autoNameField", ["email",             // line 280
($context["item"] ?? null), _n("Email", "Emails", 1),             // line 282
($context["withtemplate"] ?? null),             // line 283
($context["field_options"] ?? null)], 278, $context, $this->getSourceContext());
            // line 284
            yield "
                     ";
        }
        // line 286
        yield "
                     ";
        // line 287
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isField", ["address"], "method", false, false, false, 287)) {
            // line 288
            yield "                        ";
            yield CoreExtension::callMacro($macros["fields"], "macro_textareaField", ["address", (($__internal_compile_21 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, false, false, 288)) && is_array($__internal_compile_21) || $__internal_compile_21 instanceof ArrayAccess ? ($__internal_compile_21["address"] ?? null) : null), __("Address")], 288, $context, $this->getSourceContext());
            yield "
                     ";
        }
        // line 290
        yield "
                     ";
        // line 291
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isField", ["postalcode"], "method", false, false, false, 291)) {
            // line 292
            yield "                        ";
            yield CoreExtension::callMacro($macros["fields"], "macro_autoNameField", ["postalcode",             // line 294
($context["item"] ?? null), __("Postal code"),             // line 296
($context["withtemplate"] ?? null),             // line 297
($context["field_options"] ?? null)], 292, $context, $this->getSourceContext());
            // line 298
            yield "
                     ";
        }
        // line 300
        yield "
                     ";
        // line 301
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isField", ["town"], "method", false, false, false, 301)) {
            // line 302
            yield "                        ";
            yield CoreExtension::callMacro($macros["fields"], "macro_autoNameField", ["town",             // line 304
($context["item"] ?? null), __("City"),             // line 306
($context["withtemplate"] ?? null),             // line 307
($context["field_options"] ?? null)], 302, $context, $this->getSourceContext());
            // line 308
            yield "
                     ";
        }
        // line 310
        yield "
                     ";
        // line 312
        yield "                     ";
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isField", ["postcode"], "method", false, false, false, 312)) {
            // line 313
            yield "                        ";
            yield CoreExtension::callMacro($macros["fields"], "macro_autoNameField", ["postcode",             // line 315
($context["item"] ?? null), __("Postal code"),             // line 317
($context["withtemplate"] ?? null),             // line 318
($context["field_options"] ?? null)], 313, $context, $this->getSourceContext());
            // line 319
            yield "
                     ";
        }
        // line 321
        yield "
                     ";
        // line 322
        if (((($context["item_type"] ?? null) == "Supplier") || (($context["item_type"] ?? null) == "Contact"))) {
            // line 323
            yield "                        ";
            yield CoreExtension::callMacro($macros["fields"], "macro_autoNameField", ["state",             // line 325
($context["item"] ?? null), _x("location", "State"),             // line 327
($context["withtemplate"] ?? null),             // line 328
($context["field_options"] ?? null)], 323, $context, $this->getSourceContext());
            // line 329
            yield "
                     ";
        }
        // line 331
        yield "
                     ";
        // line 332
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isField", ["country"], "method", false, false, false, 332)) {
            // line 333
            yield "                        ";
            yield CoreExtension::callMacro($macros["fields"], "macro_autoNameField", ["country",             // line 335
($context["item"] ?? null), __("Country"),             // line 337
($context["withtemplate"] ?? null),             // line 338
($context["field_options"] ?? null)], 333, $context, $this->getSourceContext());
            // line 339
            yield "
                     ";
        }
        // line 341
        yield "
                     ";
        // line 342
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isField", ["date_expiration"], "method", false, false, false, 342)) {
            // line 343
            yield "                        ";
            if ((($context["item_type"] ?? null) == "Certificate")) {
                // line 344
                yield "                           ";
                yield CoreExtension::callMacro($macros["fields"], "macro_dateField", ["date_expiration", (($__internal_compile_22 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, false, false, 344)) && is_array($__internal_compile_22) || $__internal_compile_22 instanceof ArrayAccess ? ($__internal_compile_22["date_expiration"] ?? null) : null), __("Expiration date"), Twig\Extension\CoreExtension::merge(["helper" => __("Empty for infinite"), "checkIsExpired" => false, "expiration_class" => CoreExtension::getAttribute($this->env, $this->source,                 // line 347
($context["params"] ?? null), "expiration_class", [], "any", false, false, false, 347)],                 // line 348
($context["field_options"] ?? null))], 344, $context, $this->getSourceContext());
                yield "
                        ";
            } elseif ((            // line 349
($context["item_type"] ?? null) == "ComputerAntivirus")) {
                // line 350
                yield "                           ";
                yield CoreExtension::callMacro($macros["fields"], "macro_dateField", ["date_expiration", (($__internal_compile_23 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, false, false, 350)) && is_array($__internal_compile_23) || $__internal_compile_23 instanceof ArrayAccess ? ($__internal_compile_23["date_expiration"] ?? null) : null), __("Expiration date"), Twig\Extension\CoreExtension::merge(["helper" => __("Empty for infinite"), "checkIsExpired" => true],                 // line 353
($context["field_options"] ?? null))], 350, $context, $this->getSourceContext());
                yield "
                        ";
            } else {
                // line 355
                yield "                           ";
                yield CoreExtension::callMacro($macros["fields"], "macro_datetimeField", ["date_expiration", (($__internal_compile_24 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, false, false, 355)) && is_array($__internal_compile_24) || $__internal_compile_24 instanceof ArrayAccess ? ($__internal_compile_24["date_expiration"] ?? null) : null), __("Expiration date"), Twig\Extension\CoreExtension::merge(["helper" => __("Empty for infinite"), "checkIsExpired" => true],                 // line 358
($context["field_options"] ?? null))], 355, $context, $this->getSourceContext());
                yield "
                        ";
            }
            // line 360
            yield "                     ";
        }
        // line 361
        yield "
                     ";
        // line 362
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isField", ["ref"], "method", false, false, false, 362)) {
            // line 363
            yield "                        ";
            yield CoreExtension::callMacro($macros["fields"], "macro_textField", ["ref", (($__internal_compile_25 = CoreExtension::getAttribute($this->env, $this->source,             // line 365
($context["item"] ?? null), "fields", [], "any", false, false, false, 365)) && is_array($__internal_compile_25) || $__internal_compile_25 instanceof ArrayAccess ? ($__internal_compile_25["ref"] ?? null) : null), __("Reference"),             // line 367
($context["field_options"] ?? null)], 363, $context, $this->getSourceContext());
            // line 368
            yield "
                     ";
        }
        // line 370
        yield "
                     ";
        // line 371
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isField", ["users_id_tech"], "method", false, false, false, 371)) {
            // line 372
            yield "                        ";
            yield CoreExtension::callMacro($macros["fields"], "macro_dropdownField", ["User", "users_id_tech", (($__internal_compile_26 = CoreExtension::getAttribute($this->env, $this->source,             // line 375
($context["item"] ?? null), "fields", [], "any", false, false, false, 375)) && is_array($__internal_compile_26) || $__internal_compile_26 instanceof ArrayAccess ? ($__internal_compile_26["users_id_tech"] ?? null) : null), __("Technician in charge"), Twig\Extension\CoreExtension::merge(            // line 377
($context["field_options"] ?? null), ["entity" => (($__internal_compile_27 = CoreExtension::getAttribute($this->env, $this->source,             // line 378
($context["item"] ?? null), "fields", [], "any", false, false, false, 378)) && is_array($__internal_compile_27) || $__internal_compile_27 instanceof ArrayAccess ? ($__internal_compile_27["entities_id"] ?? null) : null), "right" => "own_ticket"])], 372, $context, $this->getSourceContext());
            // line 381
            yield "
                     ";
        }
        // line 383
        yield "
                     ";
        // line 384
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isField", ["manufacturers_id"], "method", false, false, false, 384)) {
            // line 385
            yield "                        ";
            yield CoreExtension::callMacro($macros["fields"], "macro_dropdownField", ["Manufacturer", "manufacturers_id", (($__internal_compile_28 = CoreExtension::getAttribute($this->env, $this->source,             // line 388
($context["item"] ?? null), "fields", [], "any", false, false, false, 388)) && is_array($__internal_compile_28) || $__internal_compile_28 instanceof ArrayAccess ? ($__internal_compile_28["manufacturers_id"] ?? null) : null), (((is_string($__internal_compile_29 =             // line 389
($context["item_type"] ?? null)) && is_string($__internal_compile_30 = "Software") && str_starts_with($__internal_compile_29, $__internal_compile_30))) ? (__("Publisher")) : ($this->extensions['Glpi\Application\View\Extension\ItemtypeExtension']->getItemtypeName("Manufacturer"))),             // line 390
($context["field_options"] ?? null)], 385, $context, $this->getSourceContext());
            // line 391
            yield "
                     ";
        }
        // line 393
        yield "
                     ";
        // line 394
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isField", ["groups_id_tech"], "method", false, false, false, 394)) {
            // line 395
            yield "                        ";
            yield CoreExtension::callMacro($macros["fields"], "macro_dropdownField", ["Group", "groups_id_tech", (($__internal_compile_31 = CoreExtension::getAttribute($this->env, $this->source,             // line 398
($context["item"] ?? null), "fields", [], "any", false, false, false, 398)) && is_array($__internal_compile_31) || $__internal_compile_31 instanceof ArrayAccess ? ($__internal_compile_31["groups_id_tech"] ?? null) : null), __("Group in charge"), Twig\Extension\CoreExtension::merge(            // line 400
($context["field_options"] ?? null), ["entity" => (($__internal_compile_32 = CoreExtension::getAttribute($this->env, $this->source,             // line 401
($context["item"] ?? null), "fields", [], "any", false, false, false, 401)) && is_array($__internal_compile_32) || $__internal_compile_32 instanceof ArrayAccess ? ($__internal_compile_32["entities_id"] ?? null) : null), "condition" => ["is_assign" => 1]])], 395, $context, $this->getSourceContext());
            // line 404
            yield "
                     ";
        }
        // line 406
        yield "
                     ";
        // line 407
        $context["model_itemtype"] = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "getModelClass", [], "method", false, false, false, 407);
        // line 408
        yield "                     ";
        $context["model_fk"] = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "getModelForeignKeyField", [], "method", false, false, false, 408);
        // line 409
        yield "                     ";
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isField", [($context["model_fk"] ?? null)], "method", false, false, false, 409)) {
            // line 410
            yield "                        ";
            yield CoreExtension::callMacro($macros["fields"], "macro_dropdownField", [            // line 411
($context["model_itemtype"] ?? null),             // line 412
($context["model_fk"] ?? null), (($__internal_compile_33 = CoreExtension::getAttribute($this->env, $this->source,             // line 413
($context["item"] ?? null), "fields", [], "any", false, false, false, 413)) && is_array($__internal_compile_33) || $__internal_compile_33 instanceof ArrayAccess ? ($__internal_compile_33[($context["model_fk"] ?? null)] ?? null) : null), _n("Model", "Models", 1),             // line 415
($context["field_options"] ?? null)], 410, $context, $this->getSourceContext());
            // line 416
            yield "
                     ";
        }
        // line 418
        yield "
                     ";
        // line 419
        if (((($context["item_type"] ?? null) != "SoftwareLicense") && CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isField", ["contact_num"], "method", false, false, false, 419))) {
            // line 420
            yield "                        ";
            // line 421
            yield "                        ";
            yield CoreExtension::callMacro($macros["fields"], "macro_textField", ["contact_num", (($__internal_compile_34 = CoreExtension::getAttribute($this->env, $this->source,             // line 423
($context["item"] ?? null), "fields", [], "any", false, false, false, 423)) && is_array($__internal_compile_34) || $__internal_compile_34 instanceof ArrayAccess ? ($__internal_compile_34["contact_num"] ?? null) : null), __("Alternate username number"),             // line 425
($context["field_options"] ?? null)], 421, $context, $this->getSourceContext());
            // line 426
            yield "
                     ";
        }
        // line 428
        yield "
                     ";
        // line 429
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isField", ["serial"], "method", false, false, false, 429)) {
            // line 430
            yield "                        ";
            yield CoreExtension::callMacro($macros["fields"], "macro_textField", ["serial", (($__internal_compile_35 = CoreExtension::getAttribute($this->env, $this->source,             // line 432
($context["item"] ?? null), "fields", [], "any", false, false, false, 432)) && is_array($__internal_compile_35) || $__internal_compile_35 instanceof ArrayAccess ? ($__internal_compile_35["serial"] ?? null) : null), __("Serial number"),             // line 434
($context["field_options"] ?? null)], 430, $context, $this->getSourceContext());
            // line 435
            yield "
                     ";
        }
        // line 437
        yield "
                     ";
        // line 438
        if (((($context["item_type"] ?? null) != "SoftwareLicense") && CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isField", ["contact"], "method", false, false, false, 438))) {
            // line 439
            yield "                        ";
            // line 440
            yield "                        ";
            yield CoreExtension::callMacro($macros["fields"], "macro_textField", ["contact", (($__internal_compile_36 = CoreExtension::getAttribute($this->env, $this->source,             // line 442
($context["item"] ?? null), "fields", [], "any", false, false, false, 442)) && is_array($__internal_compile_36) || $__internal_compile_36 instanceof ArrayAccess ? ($__internal_compile_36["contact"] ?? null) : null), __("Alternate username"),             // line 444
($context["field_options"] ?? null)], 440, $context, $this->getSourceContext());
            // line 445
            yield "
                     ";
        }
        // line 447
        yield "
                     ";
        // line 448
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isField", ["otherserial"], "method", false, false, false, 448)) {
            // line 449
            yield "                        ";
            yield CoreExtension::callMacro($macros["fields"], "macro_autoNameField", ["otherserial",             // line 451
($context["item"] ?? null), __("Inventory number"),             // line 453
($context["withtemplate"] ?? null),             // line 454
($context["field_options"] ?? null)], 449, $context, $this->getSourceContext());
            // line 455
            yield "
                     ";
        }
        // line 457
        yield "
                     ";
        // line 458
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isField", ["sysdescr"], "method", false, false, false, 458)) {
            // line 459
            yield "                        ";
            yield CoreExtension::callMacro($macros["fields"], "macro_textareaField", ["sysdescr", (($__internal_compile_37 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, false, false, 459)) && is_array($__internal_compile_37) || $__internal_compile_37 instanceof ArrayAccess ? ($__internal_compile_37["sysdescr"] ?? null) : null), __("Sysdescr"), ($context["field_options"] ?? null)], 459, $context, $this->getSourceContext());
            yield "
                     ";
        }
        // line 461
        yield "
                     ";
        // line 462
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isField", ["snmpcredentials_id"], "method", false, false, false, 462)) {
            // line 463
            yield "                        ";
            yield CoreExtension::callMacro($macros["fields"], "macro_dropdownField", ["SNMPCredential", "snmpcredentials_id", (($__internal_compile_38 = CoreExtension::getAttribute($this->env, $this->source,             // line 466
($context["item"] ?? null), "fields", [], "any", false, false, false, 466)) && is_array($__internal_compile_38) || $__internal_compile_38 instanceof ArrayAccess ? ($__internal_compile_38["snmpcredentials_id"] ?? null) : null), $this->extensions['Glpi\Application\View\Extension\ItemtypeExtension']->getItemtypeName("SNMPCredential"),             // line 468
($context["field_options"] ?? null)], 463, $context, $this->getSourceContext());
            // line 469
            yield "
                     ";
        }
        // line 471
        yield "
                     ";
        // line 472
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isField", ["users_id"], "method", false, false, false, 472)) {
            // line 473
            yield "                        ";
            yield CoreExtension::callMacro($macros["fields"], "macro_dropdownField", ["User", "users_id", (($__internal_compile_39 = CoreExtension::getAttribute($this->env, $this->source,             // line 476
($context["item"] ?? null), "fields", [], "any", false, false, false, 476)) && is_array($__internal_compile_39) || $__internal_compile_39 instanceof ArrayAccess ? ($__internal_compile_39["users_id"] ?? null) : null), $this->extensions['Glpi\Application\View\Extension\ItemtypeExtension']->getItemtypeName("User"), Twig\Extension\CoreExtension::merge(            // line 478
($context["field_options"] ?? null), ["entity" => (($__internal_compile_40 = CoreExtension::getAttribute($this->env, $this->source,             // line 479
($context["item"] ?? null), "fields", [], "any", false, false, false, 479)) && is_array($__internal_compile_40) || $__internal_compile_40 instanceof ArrayAccess ? ($__internal_compile_40["entities_id"] ?? null) : null), "right" => "all"])], 473, $context, $this->getSourceContext());
            // line 482
            yield "
                     ";
        }
        // line 484
        yield "
                     ";
        // line 485
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isField", ["is_global"], "method", false, false, false, 485)) {
            // line 486
            yield "                        ";
            $context["management_restrict"] = 0;
            // line 487
            yield "                        ";
            if ((($context["item_type"] ?? null) == "Monitor")) {
                // line 488
                yield "                           ";
                $context["management_restrict"] = $this->extensions['Glpi\Application\View\Extension\ConfigExtension']->config("monitors_management_restrict");
                // line 489
                yield "                        ";
            } elseif ((($context["item_type"] ?? null) == "Peripheral")) {
                // line 490
                yield "                           ";
                $context["management_restrict"] = $this->extensions['Glpi\Application\View\Extension\ConfigExtension']->config("peripherals_management_restrict");
                // line 491
                yield "                        ";
            } elseif ((($context["item_type"] ?? null) == "Phone")) {
                // line 492
                yield "                           ";
                $context["management_restrict"] = $this->extensions['Glpi\Application\View\Extension\ConfigExtension']->config("phones_management_restrict");
                // line 493
                yield "                        ";
            } elseif ((($context["item_type"] ?? null) == "Printer")) {
                // line 494
                yield "                           ";
                $context["management_restrict"] = $this->extensions['Glpi\Application\View\Extension\ConfigExtension']->config("printers_management_restrict");
                // line 495
                yield "                        ";
            } else {
                // line 496
                yield "                           ";
                $context["management_restrict"] = 0;
                // line 497
                yield "                        ";
            }
            // line 498
            yield "                        ";
            $context["dd_globalswitch"] = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
                // line 499
                yield "                           ";
                $this->extensions['Glpi\Application\View\Extension\PhpExtension']->call("Dropdown::showGlobalSwitch", [(($__internal_compile_41 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, false, false, 499)) && is_array($__internal_compile_41) || $__internal_compile_41 instanceof ArrayAccess ? ($__internal_compile_41["id"] ?? null) : null), ["withtemplate" =>                 // line 500
($context["withtemplate"] ?? null), "value" => (($__internal_compile_42 = CoreExtension::getAttribute($this->env, $this->source,                 // line 501
($context["item"] ?? null), "fields", [], "any", false, false, false, 501)) && is_array($__internal_compile_42) || $__internal_compile_42 instanceof ArrayAccess ? ($__internal_compile_42["is_global"] ?? null) : null), "management_restrict" =>                 // line 502
($context["management_restrict"] ?? null), "target" =>                 // line 503
($context["target"] ?? null), "width" => "100%"]]);
                // line 506
                yield "                        ";
                return; yield '';
            })())) ? '' : new Markup($tmp, $this->env->getCharset());
            // line 507
            yield "                        ";
            yield CoreExtension::callMacro($macros["fields"], "macro_htmlField", ["is_global",             // line 509
($context["dd_globalswitch"] ?? null), __("Management type")], 507, $context, $this->getSourceContext());
            // line 511
            yield "
                     ";
        }
        // line 513
        yield "
                     ";
        // line 514
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isField", ["size"], "method", false, false, false, 514)) {
            // line 515
            yield "                        ";
            yield CoreExtension::callMacro($macros["fields"], "macro_numberField", ["size", (($__internal_compile_43 = CoreExtension::getAttribute($this->env, $this->source,             // line 517
($context["item"] ?? null), "fields", [], "any", false, false, false, 517)) && is_array($__internal_compile_43) || $__internal_compile_43 instanceof ArrayAccess ? ($__internal_compile_43["size"] ?? null) : null), __("Size"), Twig\Extension\CoreExtension::merge(            // line 519
($context["field_options"] ?? null), ["min" => 0, "step" => 0.01])], 515, $context, $this->getSourceContext());
            // line 523
            yield "
                     ";
        }
        // line 525
        yield "
                     ";
        // line 526
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isField", ["networks_id"], "method", false, false, false, 526)) {
            // line 527
            yield "                        ";
            yield CoreExtension::callMacro($macros["fields"], "macro_dropdownField", ["Network", "networks_id", (($__internal_compile_44 = CoreExtension::getAttribute($this->env, $this->source,             // line 530
($context["item"] ?? null), "fields", [], "any", false, false, false, 530)) && is_array($__internal_compile_44) || $__internal_compile_44 instanceof ArrayAccess ? ($__internal_compile_44["networks_id"] ?? null) : null), _n("Network", "Networks", 1),             // line 532
($context["field_options"] ?? null)], 527, $context, $this->getSourceContext());
            // line 533
            yield "
                     ";
        }
        // line 535
        yield "
                     ";
        // line 536
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isField", ["groups_id"], "method", false, false, false, 536)) {
            // line 537
            yield "                        ";
            yield CoreExtension::callMacro($macros["fields"], "macro_dropdownField", ["Group", "groups_id", (($__internal_compile_45 = CoreExtension::getAttribute($this->env, $this->source,             // line 540
($context["item"] ?? null), "fields", [], "any", false, false, false, 540)) && is_array($__internal_compile_45) || $__internal_compile_45 instanceof ArrayAccess ? ($__internal_compile_45["groups_id"] ?? null) : null), $this->extensions['Glpi\Application\View\Extension\ItemtypeExtension']->getItemtypeName("Group"), Twig\Extension\CoreExtension::merge(            // line 542
($context["field_options"] ?? null), ["entity" => (($__internal_compile_46 = CoreExtension::getAttribute($this->env, $this->source,             // line 543
($context["item"] ?? null), "fields", [], "any", false, false, false, 543)) && is_array($__internal_compile_46) || $__internal_compile_46 instanceof ArrayAccess ? ($__internal_compile_46["entities_id"] ?? null) : null), "condition" => ["is_itemgroup" => 1]])], 537, $context, $this->getSourceContext());
            // line 546
            yield "
                     ";
        }
        // line 548
        yield "
                     ";
        // line 549
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isField", ["uuid"], "method", false, false, false, 549)) {
            // line 550
            yield "                        ";
            yield CoreExtension::callMacro($macros["fields"], "macro_textField", ["uuid", (($__internal_compile_47 = CoreExtension::getAttribute($this->env, $this->source,             // line 552
($context["item"] ?? null), "fields", [], "any", false, false, false, 552)) && is_array($__internal_compile_47) || $__internal_compile_47 instanceof ArrayAccess ? ($__internal_compile_47["uuid"] ?? null) : null), __("UUID"),             // line 554
($context["field_options"] ?? null)], 550, $context, $this->getSourceContext());
            // line 555
            yield "
                     ";
        }
        // line 557
        yield "
                     ";
        // line 558
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isField", ["version"], "method", false, false, false, 558)) {
            // line 559
            yield "                        ";
            yield CoreExtension::callMacro($macros["fields"], "macro_autoNameField", ["version",             // line 561
($context["item"] ?? null), _n("Version", "Versions", 1),             // line 563
($context["withtemplate"] ?? null),             // line 564
($context["field_options"] ?? null)], 559, $context, $this->getSourceContext());
            // line 565
            yield "
                     ";
        }
        // line 567
        yield "
                     ";
        // line 568
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isField", ["comment"], "method", false, false, false, 568)) {
            // line 569
            yield "                        ";
            yield CoreExtension::callMacro($macros["fields"], "macro_textareaField", ["comment", (($__internal_compile_48 = CoreExtension::getAttribute($this->env, $this->source,             // line 571
($context["item"] ?? null), "fields", [], "any", false, false, false, 571)) && is_array($__internal_compile_48) || $__internal_compile_48 instanceof ArrayAccess ? ($__internal_compile_48["comment"] ?? null) : null), _n("Comment", "Comments", Session::getPluralNumber()),             // line 573
($context["field_options"] ?? null)], 569, $context, $this->getSourceContext());
            // line 574
            yield "
                     ";
        }
        // line 576
        yield "
                     ";
        // line 577
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isField", ["ram"], "method", false, false, false, 577)) {
            // line 578
            yield "                        ";
            yield CoreExtension::callMacro($macros["fields"], "macro_numberField", ["ram", (($__internal_compile_49 = CoreExtension::getAttribute($this->env, $this->source,             // line 580
($context["item"] ?? null), "fields", [], "any", false, false, false, 580)) && is_array($__internal_compile_49) || $__internal_compile_49 instanceof ArrayAccess ? ($__internal_compile_49["ram"] ?? null) : null), Twig\Extension\CoreExtension::sprintf(__("%1\$s (%2\$s)"), _n("Memory", "Memories", 1), __("Mio")),             // line 582
($context["field_options"] ?? null)], 578, $context, $this->getSourceContext());
            // line 583
            yield "
                     ";
        }
        // line 585
        yield "
                     ";
        // line 586
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isField", ["alarm_threshold"], "method", false, false, false, 586)) {
            // line 587
            yield "                        ";
            $context["dd_alarm_treshold"] = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
                // line 588
                yield "                           ";
                $this->extensions['Glpi\Application\View\Extension\PhpExtension']->call("Dropdown::showNumber", ["alarm_threshold", Twig\Extension\CoreExtension::merge(["value" => (($__internal_compile_50 = CoreExtension::getAttribute($this->env, $this->source,                 // line 589
($context["item"] ?? null), "fields", [], "any", false, false, false, 589)) && is_array($__internal_compile_50) || $__internal_compile_50 instanceof ArrayAccess ? ($__internal_compile_50["alarm_threshold"] ?? null) : null), "rand" =>                 // line 590
($context["rand"] ?? null), "width" => "100%", "min" => 0, "max" => 100, "step" => 1, "toadd" => ["-1" => __("Never")]],                 // line 596
($context["params"] ?? null))]);
                // line 597
                yield "                        ";
                return; yield '';
            })())) ? '' : new Markup($tmp, $this->env->getCharset());
            // line 598
            yield "                        ";
            $context["last_alert_html"] = ('' === $tmp = \Twig\Extension\CoreExtension::captureOutput((function () use (&$context, $macros, $blocks) {
                // line 599
                yield "                           <span class=\"text-muted\">
                              ";
                // line 600
                $this->extensions['Glpi\Application\View\Extension\PhpExtension']->call("Alert::displayLastAlert", [($context["item_type"] ?? null), (($__internal_compile_51 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, false, false, 600)) && is_array($__internal_compile_51) || $__internal_compile_51 instanceof ArrayAccess ? ($__internal_compile_51["id"] ?? null) : null)]);
                // line 601
                yield "                           </span>
                        ";
                return; yield '';
            })())) ? '' : new Markup($tmp, $this->env->getCharset());
            // line 603
            yield "                        ";
            yield CoreExtension::callMacro($macros["fields"], "macro_htmlField", ["alarm_threshold",             // line 605
($context["dd_alarm_treshold"] ?? null), __("Alert threshold"), Twig\Extension\CoreExtension::merge(            // line 607
($context["field_options"] ?? null), ["add_field_html" =>             // line 608
($context["last_alert_html"] ?? null)])], 603, $context, $this->getSourceContext());
            // line 610
            yield "
                     ";
        }
        // line 612
        yield "
                     ";
        // line 613
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isField", ["brand"], "method", false, false, false, 613)) {
            // line 614
            yield "                        ";
            yield CoreExtension::callMacro($macros["fields"], "macro_textField", ["brand", (($__internal_compile_52 = CoreExtension::getAttribute($this->env, $this->source,             // line 616
($context["item"] ?? null), "fields", [], "any", false, false, false, 616)) && is_array($__internal_compile_52) || $__internal_compile_52 instanceof ArrayAccess ? ($__internal_compile_52["brand"] ?? null) : null), __("Brand"),             // line 618
($context["field_options"] ?? null)], 614, $context, $this->getSourceContext());
            // line 619
            yield "
                     ";
        }
        // line 621
        yield "
                     ";
        // line 622
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isField", ["begin_date"], "method", false, false, false, 622)) {
            // line 623
            yield "                        ";
            yield CoreExtension::callMacro($macros["fields"], "macro_dateField", ["begin_date", (($__internal_compile_53 = CoreExtension::getAttribute($this->env, $this->source,             // line 625
($context["item"] ?? null), "fields", [], "any", false, false, false, 625)) && is_array($__internal_compile_53) || $__internal_compile_53 instanceof ArrayAccess ? ($__internal_compile_53["begin_date"] ?? null) : null), __("Start date"),             // line 627
($context["field_options"] ?? null)], 623, $context, $this->getSourceContext());
            // line 628
            yield "
                     ";
        }
        // line 630
        yield "
                     ";
        // line 631
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isField", ["autoupdatesystems_id"], "method", false, false, false, 631)) {
            // line 632
            yield "                        ";
            yield CoreExtension::callMacro($macros["fields"], "macro_dropdownField", ["AutoUpdateSystem", "autoupdatesystems_id", (($__internal_compile_54 = CoreExtension::getAttribute($this->env, $this->source,             // line 635
($context["item"] ?? null), "fields", [], "any", false, false, false, 635)) && is_array($__internal_compile_54) || $__internal_compile_54 instanceof ArrayAccess ? ($__internal_compile_54["autoupdatesystems_id"] ?? null) : null), $this->extensions['Glpi\Application\View\Extension\ItemtypeExtension']->getItemtypeName("AutoUpdateSystem"),             // line 637
($context["field_options"] ?? null)], 632, $context, $this->getSourceContext());
            // line 638
            yield "
                     ";
        }
        // line 640
        yield "
                     ";
        // line 641
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isField", ["pictures"], "method", false, false, false, 641)) {
            // line 642
            yield "                        ";
            yield CoreExtension::callMacro($macros["fields"], "macro_fileField", ["pictures", null, _n("Picture", "Pictures", Session::getPluralNumber()), ["onlyimages" => true, "multiple" => true]], 642, $context, $this->getSourceContext());
            // line 645
            yield "
                     ";
        }
        // line 647
        yield "
                     ";
        // line 648
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isField", ["is_active"], "method", false, false, false, 648)) {
            // line 649
            yield "                        ";
            yield CoreExtension::callMacro($macros["fields"], "macro_dropdownYesNo", ["is_active", (($__internal_compile_55 = CoreExtension::getAttribute($this->env, $this->source,             // line 651
($context["item"] ?? null), "fields", [], "any", false, false, false, 651)) && is_array($__internal_compile_55) || $__internal_compile_55 instanceof ArrayAccess ? ($__internal_compile_55["is_active"] ?? null) : null), __("Active"),             // line 653
($context["field_options"] ?? null)], 649, $context, $this->getSourceContext());
            // line 654
            yield "
                     ";
        }
        // line 656
        yield "
                     ";
        // line 658
        yield "                     ";
        if (((CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isField", ["last_boot"], "method", false, false, false, 658) && CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "isField", ["is_dynamic"], "method", false, false, false, 658)) && (($__internal_compile_56 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, false, false, 658)) && is_array($__internal_compile_56) || $__internal_compile_56 instanceof ArrayAccess ? ($__internal_compile_56["is_dynamic"] ?? null) : null))) {
            // line 659
            yield "                        ";
            yield CoreExtension::callMacro($macros["fields"], "macro_htmlField", ["last_boot", $this->extensions['Glpi\Application\View\Extension\DataHelpersExtension']->getFormattedDatetime((($__internal_compile_57 = CoreExtension::getAttribute($this->env, $this->source, ($context["item"] ?? null), "fields", [], "any", false, false, false, 659)) && is_array($__internal_compile_57) || $__internal_compile_57 instanceof ArrayAccess ? ($__internal_compile_57["last_boot"] ?? null) : null), true), __("Last boot date")], 659, $context, $this->getSourceContext());
            yield "
                     ";
        }
        // line 661
        yield "
                     ";
        // line 662
        yield from $this->unwrap()->yieldBlock('more_fields', $context, $blocks);
        // line 664
        yield "                  ";
        return; yield '';
    }

    // line 662
    public function block_more_fields($context, array $blocks = [])
    {
        $macros = $this->macros;
        yield "                     ";
        return; yield '';
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "generic_show_form.html.twig";
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
        return array (  1139 => 662,  1134 => 664,  1132 => 662,  1129 => 661,  1123 => 659,  1120 => 658,  1117 => 656,  1113 => 654,  1111 => 653,  1110 => 651,  1108 => 649,  1106 => 648,  1103 => 647,  1099 => 645,  1096 => 642,  1094 => 641,  1091 => 640,  1087 => 638,  1085 => 637,  1084 => 635,  1082 => 632,  1080 => 631,  1077 => 630,  1073 => 628,  1071 => 627,  1070 => 625,  1068 => 623,  1066 => 622,  1063 => 621,  1059 => 619,  1057 => 618,  1056 => 616,  1054 => 614,  1052 => 613,  1049 => 612,  1045 => 610,  1043 => 608,  1042 => 607,  1041 => 605,  1039 => 603,  1034 => 601,  1032 => 600,  1029 => 599,  1026 => 598,  1022 => 597,  1020 => 596,  1019 => 590,  1018 => 589,  1016 => 588,  1013 => 587,  1011 => 586,  1008 => 585,  1004 => 583,  1002 => 582,  1001 => 580,  999 => 578,  997 => 577,  994 => 576,  990 => 574,  988 => 573,  987 => 571,  985 => 569,  983 => 568,  980 => 567,  976 => 565,  974 => 564,  973 => 563,  972 => 561,  970 => 559,  968 => 558,  965 => 557,  961 => 555,  959 => 554,  958 => 552,  956 => 550,  954 => 549,  951 => 548,  947 => 546,  945 => 543,  944 => 542,  943 => 540,  941 => 537,  939 => 536,  936 => 535,  932 => 533,  930 => 532,  929 => 530,  927 => 527,  925 => 526,  922 => 525,  918 => 523,  916 => 519,  915 => 517,  913 => 515,  911 => 514,  908 => 513,  904 => 511,  902 => 509,  900 => 507,  896 => 506,  894 => 503,  893 => 502,  892 => 501,  891 => 500,  889 => 499,  886 => 498,  883 => 497,  880 => 496,  877 => 495,  874 => 494,  871 => 493,  868 => 492,  865 => 491,  862 => 490,  859 => 489,  856 => 488,  853 => 487,  850 => 486,  848 => 485,  845 => 484,  841 => 482,  839 => 479,  838 => 478,  837 => 476,  835 => 473,  833 => 472,  830 => 471,  826 => 469,  824 => 468,  823 => 466,  821 => 463,  819 => 462,  816 => 461,  810 => 459,  808 => 458,  805 => 457,  801 => 455,  799 => 454,  798 => 453,  797 => 451,  795 => 449,  793 => 448,  790 => 447,  786 => 445,  784 => 444,  783 => 442,  781 => 440,  779 => 439,  777 => 438,  774 => 437,  770 => 435,  768 => 434,  767 => 432,  765 => 430,  763 => 429,  760 => 428,  756 => 426,  754 => 425,  753 => 423,  751 => 421,  749 => 420,  747 => 419,  744 => 418,  740 => 416,  738 => 415,  737 => 413,  736 => 412,  735 => 411,  733 => 410,  730 => 409,  727 => 408,  725 => 407,  722 => 406,  718 => 404,  716 => 401,  715 => 400,  714 => 398,  712 => 395,  710 => 394,  707 => 393,  703 => 391,  701 => 390,  700 => 389,  699 => 388,  697 => 385,  695 => 384,  692 => 383,  688 => 381,  686 => 378,  685 => 377,  684 => 375,  682 => 372,  680 => 371,  677 => 370,  673 => 368,  671 => 367,  670 => 365,  668 => 363,  666 => 362,  663 => 361,  660 => 360,  655 => 358,  653 => 355,  648 => 353,  646 => 350,  644 => 349,  640 => 348,  639 => 347,  637 => 344,  634 => 343,  632 => 342,  629 => 341,  625 => 339,  623 => 338,  622 => 337,  621 => 335,  619 => 333,  617 => 332,  614 => 331,  610 => 329,  608 => 328,  607 => 327,  606 => 325,  604 => 323,  602 => 322,  599 => 321,  595 => 319,  593 => 318,  592 => 317,  591 => 315,  589 => 313,  586 => 312,  583 => 310,  579 => 308,  577 => 307,  576 => 306,  575 => 304,  573 => 302,  571 => 301,  568 => 300,  564 => 298,  562 => 297,  561 => 296,  560 => 294,  558 => 292,  556 => 291,  553 => 290,  547 => 288,  545 => 287,  542 => 286,  538 => 284,  536 => 283,  535 => 282,  534 => 280,  532 => 278,  530 => 277,  527 => 276,  523 => 274,  521 => 273,  520 => 272,  519 => 270,  517 => 268,  515 => 267,  512 => 266,  508 => 264,  506 => 263,  505 => 262,  504 => 260,  502 => 258,  500 => 257,  497 => 256,  493 => 254,  491 => 253,  490 => 252,  489 => 250,  487 => 248,  485 => 247,  482 => 246,  478 => 244,  476 => 243,  475 => 242,  474 => 240,  472 => 238,  470 => 237,  467 => 236,  463 => 234,  461 => 233,  460 => 232,  459 => 230,  457 => 228,  455 => 227,  452 => 226,  448 => 224,  446 => 223,  445 => 222,  444 => 220,  442 => 218,  440 => 217,  437 => 216,  433 => 214,  431 => 213,  430 => 212,  429 => 211,  428 => 210,  426 => 208,  424 => 207,  421 => 206,  417 => 204,  415 => 202,  414 => 201,  413 => 199,  411 => 196,  409 => 195,  406 => 194,  402 => 192,  400 => 191,  399 => 190,  398 => 189,  397 => 188,  396 => 187,  394 => 186,  391 => 185,  388 => 184,  386 => 183,  383 => 182,  377 => 180,  375 => 179,  372 => 178,  368 => 176,  366 => 175,  365 => 170,  363 => 168,  361 => 167,  358 => 166,  354 => 164,  352 => 162,  351 => 161,  350 => 159,  348 => 156,  346 => 155,  343 => 154,  340 => 153,  337 => 152,  333 => 150,  331 => 148,  330 => 146,  327 => 145,  322 => 143,  313 => 141,  309 => 140,  306 => 139,  303 => 138,  300 => 137,  298 => 136,  295 => 135,  291 => 133,  289 => 132,  288 => 130,  286 => 128,  284 => 127,  282 => 126,  279 => 125,  275 => 123,  273 => 121,  272 => 120,  271 => 118,  270 => 117,  269 => 116,  267 => 115,  264 => 114,  262 => 113,  259 => 112,  255 => 110,  253 => 108,  252 => 107,  251 => 106,  250 => 104,  248 => 101,  245 => 100,  243 => 99,  240 => 98,  237 => 97,  234 => 96,  230 => 94,  227 => 92,  224 => 91,  222 => 90,  219 => 89,  215 => 87,  213 => 86,  212 => 83,  210 => 81,  208 => 80,  205 => 79,  201 => 77,  199 => 76,  198 => 75,  197 => 73,  195 => 71,  193 => 70,  190 => 69,  186 => 67,  184 => 66,  183 => 65,  182 => 64,  181 => 63,  179 => 61,  176 => 60,  172 => 59,  166 => 691,  160 => 688,  157 => 687,  155 => 686,  152 => 685,  146 => 683,  144 => 682,  139 => 681,  133 => 679,  131 => 678,  128 => 677,  126 => 676,  119 => 672,  115 => 670,  113 => 669,  110 => 668,  108 => 667,  106 => 666,  104 => 665,  102 => 59,  95 => 55,  91 => 53,  89 => 51,  87 => 50,  84 => 49,  81 => 48,  78 => 47,  75 => 46,  72 => 45,  70 => 44,  65 => 42,  61 => 41,  58 => 40,  54 => 38,  52 => 37,  50 => 36,  48 => 35,  45 => 34,  43 => 33,  40 => 32,);
    }

    public function getSourceContext()
    {
        return new Source("", "generic_show_form.html.twig", "D:\\laragon\\www\\glpi\\templates\\generic_show_form.html.twig");
    }
}
