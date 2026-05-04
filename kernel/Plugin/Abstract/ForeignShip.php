<?php
declare (strict_types=1);

namespace Kernel\Plugin\Abstract;

use Kernel\Container\Di;
use Kernel\Plugin\Entity\Plugin;

abstract class ForeignShip implements \Kernel\Plugin\Handle\ForeignShip
{

    protected Plugin $plugin;

    protected array $config;

    public function __construct(Plugin $plugin, array $config)
    {
        Di::inst()->inject($this);
        $this->plugin = $plugin;
        $this->config = $config;
    }
}