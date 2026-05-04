<?php
declare (strict_types=1);

namespace App\Entity\Repertory;

use Kernel\Component\ToArray;

class RepertoryItem
{

    use ToArray;

    public int $id;
    public string $name;
    public ?string $introduce = null;
    public string $pictureUrl;
    public string $pictureThumbUrl;
    public array $widget = [];
    public array $attr = [];

    
    public array $skus = [];

    public bool $haveWholesale = false;

    public function __construct(\App\Model\RepertoryItem $repertoryItem)
    {
        $this->id = $repertoryItem->id;
        $this->name = $repertoryItem->name;
        $this->pictureUrl = $repertoryItem->picture_url;
        $this->pictureThumbUrl = $repertoryItem->picture_thumb_url;
    }

    
    public function setWidget(array $widget): void
    {
        $this->widget = $widget;
    }

    public function setAttr(array $attr): void
    {
        $this->attr = $attr;
    }

    
    public function setIntroduce(?string $introduce): void
    {
        $this->introduce = (string)$introduce;
    }

    public function setSkus(array $skus): void
    {
        $this->skus = $skus;
    }

    
    public function setHaveWholesale(bool $haveWholesale): void
    {
        $this->haveWholesale = $haveWholesale;
    }
}