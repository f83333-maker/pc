<?php
declare (strict_types=1);

namespace App\Entity\Report;

class Handle
{
    
    public int $reportId;

    public int $type;

    public ?string $treasure = null;

    public ?string $refundAmount = null;

    public ?string $refundMerchantAmount = null;

    public string $message;

    public ?string $imageUrl = null;

    
    public int $role;

    
    public function __construct(int $reportId, int $type, string $message, int $role)
    {
        $this->reportId = $reportId;
        $this->type = $type;
        $this->message = $message;
        $this->role = $role;
    }

    
    public function setRefundAmount(string $refundAmount): void
    {
        $this->refundAmount = $refundAmount;
    }

    public function setRefundMerchantAmount(string $refundMerchantAmount): void
    {
        $this->refundMerchantAmount = $refundMerchantAmount;
    }

    public function setImageUrl(string $imageUrl): void
    {
        $this->imageUrl = $imageUrl;
    }

    
    public function setTreasure(string $treasure): void
    {
        $this->treasure = $treasure;
    }
}