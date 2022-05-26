<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* @gantry-admin/pages/configurations/settings/settings.html.twig */
class __TwigTemplate_786da7cdf04b270a93063e09d56e60df77726e1a03d9b956081fd5798c9733e8 extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->blocks = [
            'gantry' => [$this, 'block_gantry'],
        ];
    }

    protected function doGetParent(array $context)
    {
        // line 1
        return $this->loadTemplate((((($context["ajax"] ?? null) - ($context["suffix"] ?? null))) ? ("@gantry-admin/partials/ajax.html.twig") : ("@gantry-admin/partials/base.html.twig")), "@gantry-admin/pages/configurations/settings/settings.html.twig", 1);
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $this->getParent($context)->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_gantry($context, array $blocks = [])
    {
        // line 4
        echo "    ";
        if (($this->getAttribute($this->getAttribute(($context["gantry"] ?? null), "particles", []), "overrides", [0 => ($context["configuration"] ?? null)], "method") || $this->getAttribute($this->getAttribute($this->getAttribute(($context["gantry"] ?? null), "request", []), "get", []), "get", [0 => "enable"], "method"))) {
            // line 5
            echo "    ";
            $context["stored_data"] = $this->env->getExtension('Gantry\Component\Twig\TwigExtension')->jsonDecodeFilter(_twig_default_filter($this->env->getExtension('Gantry\Component\Twig\TwigExtension')->getCookie("g5-collapsed"), "{}"));
            // line 6
            echo "    <div id=\"settings\">
        <form method=\"post\">
            <div data-set-page=\"";
            // line 8
            echo twig_escape_filter($this->env, ($context["page_id"] ?? null), "html", null, true);
            echo "\" data-set-root=\"\">
                <span class=\"float-right\">
                    <button type=\"submit\" class=\"button button-primary button-save\" data-save=\"Particle Defaults\">
                    <i class=\"fa fa-fw fa-check\" aria-hidden=\"true\"></i> <span>";
            // line 11
            echo twig_escape_filter($this->env, $this->env->getExtension('Gantry\Component\Twig\TwigExtension')->transFilter("GANTRY5_PLATFORM_SAVE_PARTICLE_DEFAULTS"), "html", null, true);
            echo "</span></button>
                </span>
                ";
            // line 13
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["particles"] ?? null));
            $context['loop'] = [
              'parent' => $context['_parent'],
              'index0' => 0,
              'index'  => 1,
              'first'  => true,
            ];
            foreach ($context['_seq'] as $context["group"] => $context["list"]) {
                if (($context["list"] && ($context["group"] != "hidden"))) {
                    // line 14
                    echo "                    <h2 class=\"page-title\">
                        <span class=\"title\">";
                    // line 15
                    echo twig_escape_filter($this->env, twig_capitalize_string_filter($this->env, $context["group"]), "html", null, true);
                    echo " ";
                    echo twig_escape_filter($this->env, $this->env->getExtension('Gantry\Component\Twig\TwigExtension')->transFilter("GANTRY5_PLATFORM_DEFAULTS"), "html", null, true);
                    echo "</span>
                    </h2>

                    <div class=\"g-filter-actions\">
                        <div class=\"g-panel-filters\" data-g-global-filter=\"\">
                            <div class=\"search settings-block\">
                                <input type=\"text\" data-g-collapse-filter placeholder=\"";
                    // line 21
                    echo twig_escape_filter($this->env, $this->env->getExtension('Gantry\Component\Twig\TwigExtension')->transFilter("GANTRY5_PLATFORM_FILTER"), "html", null, true);
                    echo " ";
                    echo twig_escape_filter($this->env, twig_capitalize_string_filter($this->env, $context["group"]), "html", null, true);
                    echo "...\" aria-label=\"";
                    echo twig_escape_filter($this->env, $this->env->getExtension('Gantry\Component\Twig\TwigExtension')->transFilter("GANTRY5_PLATFORM_FILTER"), "html", null, true);
                    echo " ";
                    echo twig_escape_filter($this->env, twig_capitalize_string_filter($this->env, $context["group"]), "html", null, true);
                    echo "...\" role=\"search\" />
                                <i class=\"fa fa-fw fa-search\" aria-hidden=\"true\"></i>
                            </div>
                            <button class=\"button\" type=\"button\" data-g-collapse-all=\"true\"><i class=\"far fa-fw fa-caret-square-up\" aria-hidden=\"true\"></i> ";
                    // line 24
                    echo twig_escape_filter($this->env, $this->env->getExtension('Gantry\Component\Twig\TwigExtension')->transFilter("GANTRY5_PLATFORM_COLLAPSE_ALL"), "html", null, true);
                    echo "</button>
                            <button class=\"button\" type=\"button\" data-g-collapse-all=\"false\"><i class=\"far fa-fw fa-caret-square-down\" aria-hidden=\"true\"></i> ";
                    // line 25
                    echo twig_escape_filter($this->env, $this->env->getExtension('Gantry\Component\Twig\TwigExtension')->transFilter("GANTRY5_PLATFORM_EXPAND_ALL"), "html", null, true);
                    echo "</button>
                        </div>
                    </div>

                    <div class=\"cards-wrapper g-grid\">
                        ";
                    // line 30
                    $context['_parent'] = $context;
                    $context['_seq'] = twig_ensure_traversable($context["list"]);
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
                    foreach ($context['_seq'] as $context["id"] => $context["particle"]) {
                        // line 31
                        echo "                            ";
                        if ( !$this->getAttribute($context["particle"], "hidden", [])) {
                            // line 32
                            echo "                                ";
                            $context["particle"] = $this->getAttribute($this->getAttribute(($context["gantry"] ?? null), "particles", []), "getBlueprintForm", [0 => $context["id"]], "method");
                            // line 33
                            echo "                                ";
                            $context["prefix"] = (("particles." . $context["id"]) . ".");
                            // line 34
                            echo "                                ";
                            $context["collapsed"] = ($this->getAttribute($this->getAttribute($context["particle"], "form", []), "collapsed", []) || $this->getAttribute(($context["stored_data"] ?? null), ($context["prefix"] ?? null)));
                            // line 35
                            echo "                                ";
                            $context["labels"] = ["collapse" => $this->env->getExtension('Gantry\Component\Twig\TwigExtension')->transFilter("GANTRY5_PLATFORM_COLLAPSE"), "expand" => $this->env->getExtension('Gantry\Component\Twig\TwigExtension')->transFilter("GANTRY5_PLATFORM_EXPAND")];
                            // line 36
                            echo "                                <div class=\"card settings-block";
                            echo ((($context["collapsed"] ?? null)) ? (" g-collapsed") : (""));
                            echo "\">
                                    <input type=\"hidden\" name=\"particles[";
                            // line 37
                            echo twig_escape_filter($this->env, $context["id"], "html", null, true);
                            echo "]\"/>
                                    <h4 data-g-collapse=\"";
                            // line 38
                            echo twig_escape_filter($this->env, twig_jsonencode_filter(twig_array_merge(($context["labels"] ?? null), ["collapsed" => ((($context["collapsed"] ?? null)) ? (true) : (false)), "id" => ($context["prefix"] ?? null), "target" => "~ .inner-params"])), "html_attr");
                            echo "\"
                                        data-g-collapse-id=\"";
                            // line 39
                            echo twig_escape_filter($this->env, ($context["prefix"] ?? null), "html", null, true);
                            echo "\"
                                        ";
                            // line 40
                            echo ((($context["overrideable"] ?? null)) ? (" class=\"card-overrideable\"") : (""));
                            echo "
                                    >
                                        <span class=\"g-collapse\" data-title=\"";
                            // line 42
                            echo twig_escape_filter($this->env, ((($context["collapsed"] ?? null)) ? ($this->getAttribute(($context["labels"] ?? null), "expand", [])) : ($this->getAttribute(($context["labels"] ?? null), "collapse", []))), "html", null, true);
                            echo "\" data-tip=\"";
                            echo twig_escape_filter($this->env, ((($context["collapsed"] ?? null)) ? ($this->getAttribute(($context["labels"] ?? null), "expand", [])) : ($this->getAttribute(($context["labels"] ?? null), "collapse", []))), "html", null, true);
                            echo "\" data-tip-place=\"top-right\"><i class=\"fa fa-fw fa-caret-up\" aria-hidden=\"true\"></i></span>
                                        <span class=\"g-title\">";
                            // line 43
                            echo twig_escape_filter($this->env, $this->getAttribute($context["particle"], "name", []), "html", null, true);
                            echo "</span>
                                        ";
                            // line 44
                            if ($this->getAttribute($this->getAttribute($this->getAttribute($context["particle"], "form", []), "fields", []), "enabled", [])) {
                                // line 45
                                echo "                                            ";
                                $this->loadTemplate("forms/fields/enable/enable.html.twig", "@gantry-admin/pages/configurations/settings/settings.html.twig", 45)->display(twig_array_merge($context, ["default" => true, "scope" => ($context["prefix"] ?? null), "name" => "enabled", "field" => $this->getAttribute($this->getAttribute($this->getAttribute($context["particle"], "form", []), "fields", []), "enabled", []), "value" => $this->getAttribute(($context["data"] ?? null), "get", [0 => (($context["prefix"] ?? null) . "enabled")], "method")]));
                                // line 46
                                echo "
                                            ";
                                // line 47
                                if (($context["overrideable"] ?? null)) {
                                    // line 48
                                    echo "                                                ";
                                    $this->loadTemplate("forms/override.html.twig", "@gantry-admin/pages/configurations/settings/settings.html.twig", 48)->display(twig_array_merge($context, ["scope" => ($context["prefix"] ?? null), "name" => "enabled", "has_value" =>  !(null === $this->getAttribute(($context["data"] ?? null), "get", [0 => (($context["prefix"] ?? null) . "enabled")], "method")), "field" => ["label" => (("Enabled of the " . $this->getAttribute($context["particle"], "name", [])) . " Particle")]]));
                                    // line 49
                                    echo "                                            ";
                                }
                                // line 50
                                echo "                                        ";
                            }
                            // line 51
                            echo "                                    </h4>

                                    <div class=\"inner-params\">
                                        ";
                            // line 54
                            $this->loadTemplate("forms/fields.html.twig", "@gantry-admin/pages/configurations/settings/settings.html.twig", 54)->display(twig_array_merge($context, ["ignore_not_overrideable" => true, "overrideable" => ($context["overrideable"] ?? null), "not_global_overrideable" => ($this->getAttribute($this->getAttribute($context["particle"], "form", []), "overrideable", []) === false), "blueprints" => $this->getAttribute($context["particle"], "form", []), "skip" => [0 => "enabled"], "data" => ($context["data"] ?? null), "prefix" => ($context["prefix"] ?? null)]));
                            // line 55
                            echo "                                    </div>
                                </div>
                            ";
                        }
                        // line 58
                        echo "                        ";
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
                    unset($context['_seq'], $context['_iterated'], $context['id'], $context['particle'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 59
                    echo "                    </div>
                ";
                    ++$context['loop']['index0'];
                    ++$context['loop']['index'];
                    $context['loop']['first'] = false;
                }
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['group'], $context['list'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 61
            echo "
                <div class=\"g-footer-actions\">
                    <span class=\"float-right\">
                        <button type=\"submit\" class=\"button button-primary button-save\" data-save=\"Particle Defaults\">
                            <i class=\"fa fa-fw fa-check\" aria-hidden=\"true\"></i> <span>";
            // line 65
            echo twig_escape_filter($this->env, $this->env->getExtension('Gantry\Component\Twig\TwigExtension')->transFilter("GANTRY5_PLATFORM_SAVE_PARTICLE_DEFAULTS"), "html", null, true);
            echo "</span></button>
                    </span>
                </div>
            </div>
            <input type=\"hidden\" name=\"_end\" value=\"1\" />
        </form>
        ";
        } else {
            // line 72
            echo "            ";
            ob_start(function () { return ''; });
            echo "data-g5-ajaxify=\"\" data-g5-nav=\"settings\" data-g5-ajaxify-target=\"[data-g5-content-wrapper]\" data-g5-ajaxify-params=\"";
            echo twig_escape_filter($this->env, twig_jsonencode_filter(["navbar" => true]), "html_attr");
            echo "\"";
            $context["ajaxify"] = ('' === $tmp = ob_get_clean()) ? '' : new Markup($tmp, $this->env->getCharset());
            // line 73
            echo "            <h2 class=\"page-title\">";
            echo twig_escape_filter($this->env, $this->env->getExtension('Gantry\Component\Twig\TwigExtension')->transFilter("GANTRY5_PLATFORM_PARTICLE_DEFAULTS"), "html", null, true);
            echo "</h2>
            <p>";
            // line 74
            echo $this->env->getExtension('Gantry\Component\Twig\TwigExtension')->transFilter("GANTRY5_PLATFORM_PARTICLE_DEFAULTS_FROM_BASE", ($context["ajaxify"] ?? null), $this->getAttribute(($context["gantry"] ?? null), "route", [0 => "configurations/default/settings"], "method"), $this->env->getExtension('Gantry\Component\Twig\TwigExtension')->transFilter("GANTRY5_PLATFORM_BASE_OUTLINE"));
            echo "</p>
            <p>";
            // line 75
            echo $this->env->getExtension('Gantry\Component\Twig\TwigExtension')->transFilter("GANTRY5_PLATFORM_PARTICLE_DEFAULTS_ENTER", ($context["ajaxify"] ?? null), ($this->getAttribute(($context["gantry"] ?? null), "route", [0 => "configurations", 1 => ($context["configuration"] ?? null), 2 => "settings"], "method") . "&enable=1"));
            echo "</p>
            <p class=\"alert alert-notice\">";
            // line 76
            echo $this->env->getExtension('Gantry\Component\Twig\TwigExtension')->transFilter("GANTRY5_PLATFORM_PARTICLE_DEFAULTS_WARNING", $this->env->getExtension('Gantry\Component\Twig\TwigExtension')->transFilter("GANTRY5_PLATFORM_PARTICLE_DEFAULTS"));
            echo "</p>
        ";
        }
        // line 78
        echo "    </div>
";
    }

    public function getTemplateName()
    {
        return "@gantry-admin/pages/configurations/settings/settings.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  272 => 78,  267 => 76,  263 => 75,  259 => 74,  254 => 73,  247 => 72,  237 => 65,  231 => 61,  220 => 59,  206 => 58,  201 => 55,  199 => 54,  194 => 51,  191 => 50,  188 => 49,  185 => 48,  183 => 47,  180 => 46,  177 => 45,  175 => 44,  171 => 43,  165 => 42,  160 => 40,  156 => 39,  152 => 38,  148 => 37,  143 => 36,  140 => 35,  137 => 34,  134 => 33,  131 => 32,  128 => 31,  111 => 30,  103 => 25,  99 => 24,  87 => 21,  76 => 15,  73 => 14,  62 => 13,  57 => 11,  51 => 8,  47 => 6,  44 => 5,  41 => 4,  38 => 3,  29 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "@gantry-admin/pages/configurations/settings/settings.html.twig", "/home/c/cp42004/joomla_w8o2f/public_html/administrator/components/com_gantry5/templates/pages/configurations/settings/settings.html.twig");
    }
}
