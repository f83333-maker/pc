<?php
declare (strict_types=1);

namespace Kernel\Plugin\Abstract;

use Kernel\Exception\NotFoundException;
use Kernel\Plugin\Entity\Plugin as PGI;
use Kernel\Template\Template;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

abstract class Plugin
{

    protected PGI $plugin;

    public function __construct(PGI $plugin)
    {
        $this->plugin = $plugin;
    }

    public function view(string $template, array $data = []): string
    {
        return Template::instance()->load($template, $data, BASE_PATH . $this->plugin->env . "/" . $this->plugin->name . "/View/");
    }
}