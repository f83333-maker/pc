<?php
declare(strict_types=1);

namespace App\Entity\Repertory;

use Kernel\Component\ToArray;

class Sku
{

    use ToArray;

    public int $repertoryItemSkuId;

    
    public string $name;

    
    public string $stockPrice;

    
    public bool $marketControl;

    
    public string $marketControlMinPrice;

    public string $marketControlMaxPrice;

    public string $marketControlLevelMinPrice;

    public string $marketControlLevelMaxPrice;
    
    public string $marketControlUserMinPrice;

    public string $marketControlUserMaxPrice;

    public int $marketControlMinNum;

    public int $marketControlMaxNum;

    public int $marketControlOnlyNum;

    public function __construct(int $id, ?string $name, string|float|int $stockPrice, object $marketControl)
    {
        $this->repertoryItemSkuId = $id;
        $this->name = (string)$name;
        $this->stockPrice = (string)$stockPrice;
        $this->marketControl = $marketControl->market_control_status == 1;
        $this->marketControlMaxPrice = $marketControl->market_control_max_price;
        $this->marketControlMinPrice = $marketControl->market_control_min_price;
        $this->marketControlMinNum = $marketControl->market_control_min_num;
        $this->marketControlMaxNum = $marketControl->market_control_max_num;
        $this->marketControlOnlyNum = $marketControl->market_control_only_num;
        $this->marketControlUserMinPrice = $marketControl->market_control_user_min_price;
        $this->marketControlUserMaxPrice = $marketControl->market_control_user_max_price;
        $this->marketControlLevelMinPrice = $marketControl->market_control_level_min_price;
        $this->marketControlLevelMaxPrice = $marketControl->market_control_level_max_price;
    }
}