<?php
declare (strict_types=1);

namespace Kernel\Plugin\Entity;

use Kernel\Component\ToArray;
use Kernel\Waf\Firewall;

class Sku
{
    use ToArray;

    public string $name;
    public string $pictureUrl;
    public string $price;
    public ?string $cost = null;
    public array $options = [];
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

    public string $uniqueId;

    public array $versions = [];

    public function __construct(string|int|float $uniqueId, string $name, string $pictureUrl, string|int|float $price)
    {
        $this->name = strip_tags($name);
        $this->pictureUrl = strip_tags($pictureUrl);
        $this->price = strip_tags((string)$price);
        $this->uniqueId = md5((string)$uniqueId);

        $this->versions["name"] = md5((string)$this->name);
        $this->versions["price"] = md5((string)$this->price);
        $this->versions["picture_url"] = md5($this->pictureUrl);
    }

    public function setOptions(array $options): void
    {
        $this->options = Firewall::inst()->xssKiller($options);
    }

    public function setPrice(string $price): void
    {
        $this->price = $price;
        $this->versions["price"] = md5($price);
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

    public function setMessage(?string $message): void
    {
        $this->message = $message;
    }

    public function setCost(string|int|float|null $cost): void
    {
        if ($cost === null) {
            return;
        }
        $this->cost = (string)$cost;
    }
}