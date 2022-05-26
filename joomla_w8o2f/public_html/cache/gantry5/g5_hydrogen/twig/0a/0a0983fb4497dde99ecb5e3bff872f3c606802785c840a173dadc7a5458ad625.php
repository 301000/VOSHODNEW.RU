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

/* @particles/sample.html.twig */
class __TwigTemplate_14c5b54d757192f8d944adce1cfbd28b8f92d3b02a4cfbc611599e7a2d93c0c0 extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->blocks = [
            'particle' => [$this, 'block_particle'],
        ];
    }

    protected function doGetParent(array $context)
    {
        // line 1
        return "@nucleus/partials/particle.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $this->parent = $this->loadTemplate("@nucleus/partials/particle.html.twig", "@particles/sample.html.twig", 1);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_particle($context, array $blocks = [])
    {
        // line 4
        echo "\t<div class=\"sample-content\">
\t\t<div class=\"g-grid\">
\t\t\t<div class=\"g-block\">
\t\t\t\t<div class=\"g-content\">
\t\t\t\t\t";
        // line 8
        if ($this->getAttribute(($context["particle"] ?? null), "image", [])) {
            echo "<img src=\"";
            echo twig_escape_filter($this->env, $this->env->getExtension('Gantry\Component\Twig\TwigExtension')->urlFunc($this->getAttribute(($context["particle"] ?? null), "image", [])), "html", null, true);
            echo "\" class=\"logo-large\" alt=\"";
            echo twig_escape_filter($this->env, $this->getAttribute(($context["particle"] ?? null), "headline", []));
            echo "\" />";
        }
        // line 9
        echo "\t\t\t\t\t";
        if ($this->getAttribute(($context["particle"] ?? null), "headline", [])) {
            echo "<h1>";
            echo $this->getAttribute(($context["particle"] ?? null), "headline", []);
            echo "</h1>";
        }
        // line 10
        echo "\t\t\t\t\t";
        if ($this->getAttribute(($context["particle"] ?? null), "description", [])) {
            echo "<div class=\"sample-description\">";
            echo $this->getAttribute(($context["particle"] ?? null), "description", []);
            echo "</div>";
        }
        // line 11
        echo "\t\t\t\t\t";
        if ($this->getAttribute(($context["particle"] ?? null), "linktext", [])) {
            echo "<p><a href=\"";
            echo twig_escape_filter($this->env, $this->getAttribute(($context["particle"] ?? null), "link", []));
            echo "\" class=\"button\">";
            echo twig_escape_filter($this->env, $this->getAttribute(($context["particle"] ?? null), "linktext", []));
            echo "</a></p>";
        }
        // line 12
        echo "\t\t\t\t</div>
\t\t\t</div>
\t\t</div>
\t\t<div class=\"g-grid\">
\t\t\t";
        // line 16
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["particle"] ?? null), "samples", []));
        foreach ($context['_seq'] as $context["_key"] => $context["sample"]) {
            // line 17
            echo "\t\t\t\t<div ";
            if ($this->getAttribute($context["sample"], "id", [])) {
                echo "id=\"";
                echo twig_escape_filter($this->env, $this->getAttribute($context["sample"], "id", []));
                echo "\"";
            }
            // line 18
            echo "\t\t\t\t\t class=\"g-block ";
            echo twig_escape_filter($this->env, $this->getAttribute($context["sample"], "class", []), "html", null, true);
            echo " ";
            echo twig_escape_filter($this->env, $this->getAttribute($context["sample"], "variations", []), "html", null, true);
            echo "\">
\t\t\t\t\t<div class=\"g-content\">
\t\t\t\t\t\t<i class=\"";
            // line 20
            echo twig_escape_filter($this->env, $this->getAttribute($context["sample"], "icon", []), "html", null, true);
            echo " sample-icons\"></i>
\t\t\t\t\t\t<h4>";
            // line 21
            echo $this->getAttribute($context["sample"], "title", []);
            echo "</h4>
\t\t\t\t\t\t";
            // line 22
            echo $this->getAttribute($context["sample"], "subtitle", []);
            echo "
\t\t\t\t\t\t";
            // line 23
            echo $this->getAttribute($context["sample"], "description", []);
            echo "
\t\t\t\t\t</div>
\t\t\t\t</div>
\t\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['sample'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 27
        echo "\t\t</div>
\t</div>
";
    }

    public function getTemplateName()
    {
        return "@particles/sample.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  126 => 27,  116 => 23,  112 => 22,  108 => 21,  104 => 20,  96 => 18,  89 => 17,  85 => 16,  79 => 12,  70 => 11,  63 => 10,  56 => 9,  48 => 8,  42 => 4,  39 => 3,  29 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "@particles/sample.html.twig", "/home/c/cp42004/joomla_w8o2f/public_html/templates/g5_hydrogen/particles/sample.html.twig");
    }
}
