<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* web/view/index.html */
class __TwigTemplate_e53f976df6654a157c6f7542adf1a0a83e02ed3664d68e44c4a0cc93f9896d6b extends \Twig\Template
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
        // line 1
        echo "
<!doctype html>
<html lang=\"zh-CN\">
  <head>
    <meta charset=\"utf-8\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1, shrink-to-fit=no\">
    <meta name=\"description\" content=\"\">
    <meta name=\"author\" content=\"mahaixing(mahaixing@gmail.com)\">
    <title>Dashboard Template · Bootstrap</title>

    <!-- Bootstrap core CSS -->
    <link href=\"";
        // line 12
        echo twig_escape_filter($this->env, \context_path(), "html", null, true);
        echo "/static/css/bootstrap/bootstrap.min.css\" rel=\"stylesheet\" integrity=\"sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T\" crossorigin=\"anonymous\">


    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>
  </head>
  <body>
    <nav class=\"navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow\">
        <a class=\"navbar-brand col-sm-3 col-md-2 mr-0\" href=\"#\">Hymie PHPMVC</a>
    <!-- <input class=\"form-control form-control-dark w-100\" type=\"text\" placeholder=\"Search\" aria-label=\"Search\"> -->
    </nav>

    <div class=\"container-fluid\">
    <div class=\"row\">
        <nav class=\"col-md-2 d-none d-md-block bg-light sidebar\">
        <div class=\"sidebar-sticky\">
            <ul class=\"nav flex-column\">
            <li class=\"nav-item\">
                <a class=\"nav-link active\" href=\"#\">
                <span data-feather=\"home\"></span>
                主页 <span class=\"sr-only\">(current)</span>
                </a>
            </li>
            <li class=\"nav-item\">
                <a class=\"nav-link\" href=\"#\">
                <span data-feather=\"file\"></span>
                Orders
                </a>
            </li>
            <li class=\"nav-item\">
                <a class=\"nav-link\" href=\"#\">
                <span data-feather=\"shopping-cart\"></span>
                Products
                </a>
            </li>
            <li class=\"nav-item\">
                <a class=\"nav-link\" href=\"#\">
                <span data-feather=\"users\"></span>
                Customers
                </a>
            </li>
            <li class=\"nav-item\">
                <a class=\"nav-link\" href=\"#\">
                <span data-feather=\"bar-chart-2\"></span>
                Reports
                </a>
            </li>
            <li class=\"nav-item\">
                <a class=\"nav-link\" href=\"#\">
                <span data-feather=\"layers\"></span>
                Integrations
                </a>
            </li>
            </ul>

            <h6 class=\"sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted\">
            <span>Saved reports</span>
            <a class=\"d-flex align-items-center text-muted\" href=\"#\">
                <span data-feather=\"plus-circle\"></span>
            </a>
            </h6>
            <ul class=\"nav flex-column mb-2\">
            <li class=\"nav-item\">
                <a class=\"nav-link\" href=\"#\">
                <span data-feather=\"file-text\"></span>
                Current month
                </a>
            </li>
            <li class=\"nav-item\">
                <a class=\"nav-link\" href=\"#\">
                <span data-feather=\"file-text\"></span>
                Last quarter
                </a>
            </li>
            <li class=\"nav-item\">
                <a class=\"nav-link\" href=\"#\">
                <span data-feather=\"file-text\"></span>
                Social engagement
                </a>
            </li>
            <li class=\"nav-item\">
                <a class=\"nav-link\" href=\"#\">
                <span data-feather=\"file-text\"></span>
                Year-end sale
                </a>
            </li>
            </ul>
        </div>
        </nav>

        <main role=\"main\" class=\"col-md-9 ml-sm-auto col-lg-10 px-4\">
        <div class=\"d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom\">
            <h1 class=\"h2\">Dashboard</h1>
            <div class=\"btn-toolbar mb-2 mb-md-0\">
            <div class=\"btn-group mr-2\">
                <button type=\"button\" class=\"btn btn-sm btn-outline-secondary\">Share</button>
                <button type=\"button\" class=\"btn btn-sm btn-outline-secondary\">Export</button>
            </div>
            <button type=\"button\" class=\"btn btn-sm btn-outline-secondary dropdown-toggle\">
                <span data-feather=\"calendar\"></span>
                This week
            </button>
            </div>
        </div>

        <canvas class=\"my-4 w-100\" id=\"myChart\" width=\"900\" height=\"380\"></canvas>

        <h2>Section title</h2>
        <div class=\"table-responsive\">
            <table class=\"table table-striped table-sm\">
            <thead>
                <tr>
                <th>#</th>
                <th>Header</th>
                <th>Header</th>
                <th>Header</th>
                <th>Header</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                <td>1,001</td>
                <td>Lorem</td>
                <td>ipsum</td>
                <td>dolor</td>
                <td>sit</td>
                </tr>
                <tr>
                <td>1,002</td>
                <td>amet</td>
                <td>consectetur</td>
                <td>adipiscing</td>
                <td>elit</td>
                </tr>
                <tr>
                <td>1,003</td>
                <td>Integer</td>
                <td>nec</td>
                <td>odio</td>
                <td>Praesent</td>
                </tr>
                <tr>
                <td>1,003</td>
                <td>libero</td>
                <td>Sed</td>
                <td>cursus</td>
                <td>ante</td>
                </tr>
                <tr>
                <td>1,004</td>
                <td>dapibus</td>
                <td>diam</td>
                <td>Sed</td>
                <td>nisi</td>
                </tr>
                <tr>
                <td>1,005</td>
                <td>Nulla</td>
                <td>quis</td>
                <td>sem</td>
                <td>at</td>
                </tr>
                <tr>
                <td>1,006</td>
                <td>nibh</td>
                <td>elementum</td>
                <td>imperdiet</td>
                <td>Duis</td>
                </tr>
                <tr>
                <td>1,007</td>
                <td>sagittis</td>
                <td>ipsum</td>
                <td>Praesent</td>
                <td>mauris</td>
                </tr>
                <tr>
                <td>1,008</td>
                <td>Fusce</td>
                <td>nec</td>
                <td>tellus</td>
                <td>sed</td>
                </tr>
                <tr>
                <td>1,009</td>
                <td>augue</td>
                <td>semper</td>
                <td>porta</td>
                <td>Mauris</td>
                </tr>
                <tr>
                <td>1,010</td>
                <td>massa</td>
                <td>Vestibulum</td>
                <td>lacinia</td>
                <td>arcu</td>
                </tr>
                <tr>
                <td>1,011</td>
                <td>eget</td>
                <td>nulla</td>
                <td>Class</td>
                <td>aptent</td>
                </tr>
                <tr>
                <td>1,012</td>
                <td>taciti</td>
                <td>sociosqu</td>
                <td>ad</td>
                <td>litora</td>
                </tr>
                <tr>
                <td>1,013</td>
                <td>torquent</td>
                <td>per</td>
                <td>conubia</td>
                <td>nostra</td>
                </tr>
                <tr>
                <td>1,014</td>
                <td>per</td>
                <td>inceptos</td>
                <td>himenaeos</td>
                <td>Curabitur</td>
                </tr>
                <tr>
                <td>1,015</td>
                <td>sodales</td>
                <td>ligula</td>
                <td>in</td>
                <td>libero</td>
                </tr>
            </tbody>
            </table>
        </div>
        </main>
    </div>
    </div>
    <script src=\"";
        // line 264
        echo twig_escape_filter($this->env, \context_path(), "html", null, true);
        echo "/static/js/jquery/jquery.min.js\"></script>
    <script>window.jQuery || document.write('<script src=\"/docs/4.3/assets/js/vendor/jquery-slim.min.js\"><\\/script>')</script>
    <script src=\"";
        // line 266
        echo twig_escape_filter($this->env, \context_path(), "html", null, true);
        echo "/static/js/bootstrap/bootstrap.bundle.min.js\"></script>
</html>
";
    }

    public function getTemplateName()
    {
        return "web/view/index.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  310 => 266,  305 => 264,  50 => 12,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "web/view/index.html", "/www/hymie-website/app/web/view/index.html");
    }
}
