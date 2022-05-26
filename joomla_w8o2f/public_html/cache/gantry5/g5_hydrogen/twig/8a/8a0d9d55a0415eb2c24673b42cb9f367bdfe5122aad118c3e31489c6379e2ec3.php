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

/* @particles/copyright.html.twig */
class __TwigTemplate_8e33d78126181ff454ee1debb85a7944369a940f4c411cf7f7e8403aa5469d7e extends \Twig\Template
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
        // line 3
        $context["start_date"] = ((twig_in_filter(twig_trim_filter($this->getAttribute($this->getAttribute(($context["particle"] ?? null), "date", []), "start", [])), [0 => "now", 1 => ""])) ? (call_user_func_array($this->env->getFilter('date')->getCallable(), [$this->env, "now", "Y"])) : (twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["particle"] ?? null), "date", []), "start", []))));
        // line 4
        $context["end_date"] = ((twig_in_filter(twig_trim_filter($this->getAttribute($this->getAttribute(($context["particle"] ?? null), "date", []), "end", [])), [0 => "now", 1 => ""])) ? (call_user_func_array($this->env->getFilter('date')->getCallable(), [$this->env, "now", "Y"])) : (twig_escape_filter($this->env, $this->getAttribute($this->getAttribute(($context["particle"] ?? null), "date", []), "end", []))));
        // line 1
        $this->parent = $this->loadTemplate("@nucleus/partials/particle.html.twig", "@particles/copyright.html.twig", 1);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 6
    public function block_particle($context, array $blocks = [])
    {
        // line 7
        echo "    &copy;
    ";
        // line 8
        if ((($context["start_date"] ?? null) != ($context["end_date"] ?? null))) {
            echo twig_escape_filter($this->env, ($context["start_date"] ?? null));
            echo " - ";
        }
        // line 9
        echo "    ";
        echo twig_escape_filter($this->env, ($context["end_date"] ?? null));
        echo "
    ";
        // line 10
        echo $this->getAttribute(($context["particle"] ?? null), "owner", []);
        echo "
";
    }

    public function getTemplateName()
    {
        return "@particles/copyright.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  60 => 10,  55 => 9,  50 => 8,  47 => 7,  44 => 6,  39 => 1,  37 => 4,  35 => 3,  29 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "@particles/copyright.html.twig", "/home/c/cp42004/joomla_w8o2f/public_html/media/gantry5/engines/nucleus/particles/copyright.html.twig");
    }
}
