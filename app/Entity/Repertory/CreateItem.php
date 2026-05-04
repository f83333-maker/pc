<?php
declare (strict_types=1);

namespace App\Entity\Repertory;

class CreateItem
{
    public ?int $userId = null;

    public int $categoryId;
    public ?int $shipConfigId = null;
    public string $name;
    public string $introduce = "";
    public string $pictureUrl;
    public string $pictureThumbUrl;

    public string $plugin;

    public array $widget = [];

    public array $attr = [];

    public array $skus = [];

    public int $refundMode = 0; 
    public int $autoReceiptTime = 4320; 

    public ?string $uniqueId = null;

    public array $versions = [];

    
    public array $pluginData = [];

    public int $markupTemplateId;

    public function __construct(int $markupTemplateId, array $versions, int $categoryId, string $name, string $introduce, string $pictureUrl, string $pictureThumbUrl, string $plugin, array $skus)
    {
        $this->versions = $versions;
        $this->categoryId = $categoryId;
        $this->name = $name;
        $this->introduce = $introduce;
        $this->pictureUrl = $pictureUrl;
        $this->pictureThumbUrl = $pictureThumbUrl;
        $this->plugin = $plugin;
        $this->skus = $skus;
        $this->markupTemplateId = $markupTemplateId;
    }

    public function setUniqueId(null|string|int|float $uniqueId): void
    {
        $this->uniqueId = (string)$uniqueId;
    }

    public function setShipConfigId(int $shipConfigId): void
    {
        $this->shipConfigId = $shipConfigId;
    }

    public function setAttr(array $attr): void
    {
        $this->attr = $attr;
    }

    public function setWidget(array $widget): void
    {
        $this->widget = $widget;
    }

    public function setRefundMode(int $refundMode): void
    {
        $this->refundMode = $refundMode;
    }

    public function setAutoReceiptTime(int $autoReceiptTime): void
    {
        $this->autoReceiptTime = $autoReceiptTime;
    }

    public function setUserId(?int $userId): void
    {
        $this->userId = $userId;
    }

    public function setPluginData(array $pluginData): void
    {
        $this->pluginData = $pluginData;
    }
}