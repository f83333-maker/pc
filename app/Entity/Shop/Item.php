<?php
declare (strict_types=1);

namespace App\Entity\Shop;

use Kernel\Component\ToArray;

class Item
{

    use ToArray;

    public int $id;
    public string $name;
    public string $pictureUrl;
    public string $thumbUrl;
    public int|string|null $stock = null;
    public ?int $sold = null;
    public ?Category $category = null;
    public array $widget = [];
    public array $attr = [];
    public array $source = [];
    
    public array $sku = [];

    public bool $haveWholesale = false;

    public ?int $supplierId = null;

    
    public function __construct(\App\Model\Item $item)
    {
        $this->id = $item->id;
        $this->name = $item->name;
        $this->pictureUrl = (string)$item->picture_url;
        $this->thumbUrl = (string)$item->picture_thumb_url;
    }

    public function setSupplierId(?int $supplierId): void
    {
        $this->supplierId = $supplierId;
    }

    
    public function setStock(int|string $stock): void
    {
        $this->stock = $stock;
    }

    public function setSold(int $sold): void
    {
        $this->sold = $sold;
    }

    public function setCategory(Category $category): void
    {
        $this->category = $category;
    }

    public function setSku(array $sku): void
    {
        $this->sku = $sku;
    }

    public function setWidget(array $widget): void
    {
        $this->widget = $widget;
    }

    public function setAttr(array $attr): void
    {
        $this->attr = $attr;
    }

    public function setSource(array $source): void
    {
        $this->source = $source;
    }

    public function setHaveWholesale(bool $haveWholesale): void
    {
        $this->haveWholesale = $haveWholesale;
    }
}