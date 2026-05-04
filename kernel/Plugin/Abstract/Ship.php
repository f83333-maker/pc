<?php
declare (strict_types=1);

namespace Kernel\Plugin\Abstract;

use App\Model\PluginConfig;
use App\Model\RepertoryItem;
use App\Model\RepertoryItemSku;
use App\Model\RepertoryOrder;
use Kernel\Plugin\Entity\Plugin;

abstract class Ship implements \Kernel\Plugin\Handle\Ship
{

    protected RepertoryItem $item;
    protected RepertoryItemSku $sku;
    protected ?RepertoryOrder $order;
    protected Plugin $plugin;
    protected array $config = []; 
    protected array $options = [];

    protected bool $isCustomRender = false;

    public function __construct(Plugin $plugin, RepertoryItem $item, RepertoryItemSku $sku, ?RepertoryOrder $order = null)
    {
        $this->plugin = $plugin;
        $this->item = $item;
        $this->sku = $sku;
        $this->order = $order;

        if ($item->ship_config_id > 0 && $config = PluginConfig::find($item->ship_config_id)) {
            $this->config = is_array($config->config) ? $config->config : [];
        }

        if ($sku->plugin_data) {
            $this->options = (array)json_decode((string)$sku->plugin_data, true) ?: [];
        }
    }

    public function inspection(array $map): bool
    {
        return true;
    }

    public function isCustomRender(): bool
    {
        return $this->isCustomRender;
    }

    public function render(): string
    {
        return "write your custom HTML code here";
    }
}