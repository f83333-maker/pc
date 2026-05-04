<?php
declare (strict_types=1);

namespace Kernel\Plugin;

use Kernel\Component\Singleton;

class Theme
{
    use Singleton;

    
    public function getTheme(string $name, string $env = "/app/Plugin"): ?\Kernel\Plugin\Entity\Theme
    {
        $plugin = Plugin::inst()->getPlugin($name, $env);
        if ($plugin->state['run'] != 1) {
            return null;
        }

        return new \Kernel\Plugin\Entity\Theme($plugin->name, $plugin->theme);
    }

}