<?php
declare (strict_types=1);

namespace Kernel\Plugin\Entity;

use Kernel\Component\ToArray;
use Kernel\Waf\Firewall;

class Item
{

    use ToArray;

    public string $name;
    public string $introduce;
    public string $pictureUrl;
    
    public array $widgets = [];
    
    public array $attr = [];
    public string $category;

    public string $uniqueId;

    public array $skus;

    public array $versions = [];

    public array $options = [];

    public function __construct(string|int|float $uniqueId, string $category, string $name, string $introduce, string $pictureUrl, array $skus)
    {
        $this->name = Firewall::inst()->xssKiller($name);
        $this->introduce = Firewall::inst()->xssKiller($introduce);
        $this->pictureUrl = strip_tags($pictureUrl);
        $this->skus = $skus;
        $this->category = Firewall::inst()->xssKiller($category);
        $this->uniqueId = md5((string)$uniqueId);

        $this->versions["name"] = md5((string)$this->name);
        $this->versions["introduce"] = md5((string)$this->introduce);
        $this->versions["picture_url"] = md5($this->pictureUrl);
    }

    public function setWidgets(array $widgets): void
    {
        $this->widgets = $widgets;
    }

    public function setAttr(array $attr): void
    {
        $this->attr = $attr;
    }

    public function setOptions(array $options): void
    {
        $this->options = Firewall::inst()->xssKiller($options);
    }
}