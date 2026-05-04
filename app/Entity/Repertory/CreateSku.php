<?php
declare (strict_types=1);

namespace App\Entity\Repertory;

class CreateSku
{
    public string $name;
    public string $pictureUrl;
    public string $pictureThumbUrl;

    public string $price;

    public ?string $cost = null;

    public ?string $message = null;

    public bool $marketControl = false;

    
    public string $marketControlMinPrice = "0";

    public string $marketControlMaxPrice = "0";

    public string $marketControlLevelMinPrice = "0";

    public string $marketControlLevelMaxPrice = "0";

    public string $marketControlUserMinPrice = "0";

    public string $marketControlUserMaxPrice = "0";

    public int $marketControlMinNum = 0;

    public int $marketControlMaxNum = 0;

    public int $marketControlOnlyNum = 0;

    public array $pluginData = [];

    public ?string $uniqueId = null;

    public array $versions = [];

    public function __construct(array $versions, string $name, string $pictureUrl, string $pictureThumbUrl, string $price)
    {
        $this->versions = $versions;
        $this->name = $name;
        $this->pictureUrl = $pictureUrl;
        $this->pictureThumbUrl = $pictureThumbUrl;
        $this->price = $price;
    }

    public function setUniqueId(null|string|int|float $uniqueId): void
    {
        $this->uniqueId = (string)$uniqueId;
    }

    public function setMarketControl(bool $marketControl): void
    {
        $this->marketControl = $marketControl;
    }

    public function setMarketControlMinPrice(string $marketControlMinPrice): void
    {
        $this->marketControlMinPrice = $marketControlMinPrice;
    }

    
    public function setMarketControlMaxPrice(string $marketControlMaxPrice): void
    {
        $this->marketControlMaxPrice = $marketControlMaxPrice;
    }

    public function setMarketControlLevelMaxPrice(string $marketControlLevelMaxPrice): void
    {
        $this->marketControlLevelMaxPrice = $marketControlLevelMaxPrice;
    }

    public function setMarketControlLevelMinPrice(string $marketControlLevelMinPrice): void
    {
        $this->marketControlLevelMinPrice = $marketControlLevelMinPrice;
    }

    public function setMarketControlUserMaxPrice(string $marketControlUserMaxPrice): void
    {
        $this->marketControlUserMaxPrice = $marketControlUserMaxPrice;
    }

    public function setMarketControlUserMinPrice(string $marketControlUserMinPrice): void
    {
        $this->marketControlUserMinPrice = $marketControlUserMinPrice;
    }

    public function setMarketControlMinNum(int $marketControlMinNum): void
    {
        $this->marketControlMinNum = $marketControlMinNum;
    }

    public function setMarketControlMaxNum(int $marketControlMaxNum): void
    {
        $this->marketControlMaxNum = $marketControlMaxNum;
    }

    public function setMarketControlOnlyNum(int $marketControlOnlyNum): void
    {
        $this->marketControlOnlyNum = $marketControlOnlyNum;
    }

    public function setPluginData(array $pluginData): void
    {
        $this->pluginData = $pluginData;
    }

    public function setMessage(?string $message): void
    {
        $this->message = $message;
    }

    
    public function setCost(?string $cost): void
    {
        $this->cost = $cost;
    }
}