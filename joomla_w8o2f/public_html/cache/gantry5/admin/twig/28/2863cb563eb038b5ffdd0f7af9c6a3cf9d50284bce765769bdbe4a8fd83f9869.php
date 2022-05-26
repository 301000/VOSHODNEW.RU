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

/* @gantry-admin/partials/layout.html.twig */
class __TwigTemplate_35647e6010ad82cab7e101a008fc3acc5e718b14ea12439fe4ccdd33cb8487da extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->blocks = [
            'stylesheets' => [$this, 'block_stylesheets'],
            'javascript' => [$this, 'block_javascript'],
            'content' => [$this, 'block_content'],
            'gantry_content_wrapper' => [$this, 'block_gantry_content_wrapper'],
            'gantry' => [$this, 'block_gantry'],
            'footer_section' => [$this, 'block_footer_section'],
        ];
    }

    protected function doGetParent(array $context)
    {
        // line 1
        return "@gantry-admin/partials/page.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $this->parent = $this->loadTemplate("@gantry-admin/partials/page.html.twig", "@gantry-admin/partials/layout.html.twig", 1);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_stylesheets($context, array $blocks = [])
    {
        // line 4
        echo "    <link rel=\"stylesheet\" href=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('Gantry\Component\Twig\TwigExtension')->urlFunc("gantry-admin://assets/css-compiled/g-admin.css"), "html", null, true);
        echo "\" type=\"text/css\" />
    ";
        // line 5
        if ( !$this->getAttribute($this->getAttribute(($context["gantry"] ?? null), "platform", []), "has", [0 => "fontawesome"], "method")) {
            // line 6
            echo "    <link rel=\"stylesheet\" href=\"";
            echo twig_escape_filter($this->env, $this->env->getExtension('Gantry\Component\Twig\TwigExtension')->urlFunc("gantry-admin://assets/css/font-awesome5-all.min.css"), "html", null, true);
            echo "\" type=\"text/css\" />
    ";
        }
        // line 8
        echo "    <link rel=\"stylesheet\" href=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('Gantry\Component\Twig\TwigExtension')->urlFunc("gantry-admin://assets/css/font-awesome5-shim.min.css"), "html", null, true);
        echo "\" type=\"text/css\" />
    ";
        // line 9
        $this->displayParentBlock("stylesheets", $context, $blocks);
        echo "
";
    }

    // line 12
    public function block_javascript($context, array $blocks = [])
    {
        // line 13
        echo "    <script type=\"text/javascript\" defer=\"defer\" src=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('Gantry\Component\Twig\TwigExtension')->urlFunc("gantry-admin://assets/js/main.js"), "html", null, true);
        echo "\"></script>
    ";
        // line 14
        $this->loadTemplate("@gantry-admin/partials/js-translations.html.twig", "@gantry-admin/partials/layout.html.twig", 14)->display($context);
        // line 15
        echo "    ";
        $this->displayParentBlock("javascript", $context, $blocks);
        echo "
";
    }

    // line 18
    public function block_content($context, array $blocks = [])
    {
        // line 19
        echo "<div id=\"g5-container\">
    <div class=\"inner-container\">
        ";
        // line 21
        $this->displayBlock('gantry_content_wrapper', $context, $blocks);
        // line 36
        echo "    </div>
</div>
";
    }

    // line 21
    public function block_gantry_content_wrapper($context, array $blocks = [])
    {
        // line 22
        echo "            <div data-g5-content-wrapper=\"\">
                <div class=\"g-grid\">
                    <div class=\"g-block main-block\">
                        <div id=\"g-main\">
                            <div class=\"g-content\" data-g5-content=\"\">
                                ";
        // line 27
        $this->displayBlock('gantry', $context, $blocks);
        // line 30
        echo "                            </div>
                        </div>
                    </div>
                </div>
            </div>
        ";
    }

    // line 27
    public function block_gantry($context, array $blocks = [])
    {
        // line 28
        echo "                                    ";
        echo ($context["content"] ?? null);
        echo "
                                ";
    }

    // line 40
    public function block_footer_section($context, array $blocks = [])
    {
    }

    public function getTemplateName()
    {
        return "@gantry-admin/partials/layout.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  134 => 40,  127 => 28,  124 => 27,  115 => 30,  113 => 27,  106 => 22,  103 => 21,  97 => 36,  95 => 21,  91 => 19,  88 => 18,  81 => 15,  79 => 14,  74 => 13,  71 => 12,  65 => 9,  60 => 8,  54 => 6,  52 => 5,  47 => 4,  44 => 3,  34 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "@gantry-admin/partials/layout.html.twig", "/home/c/cp42004/joomla_w8o2f/public_html/administrator/components/com_gantry5/templates/partials/layout.html.twig");
    }
}
