<?php
declare (strict_types=1);

namespace App\Entity\Report;

class Reply
{
    
    public int $reportId;

    public string $message;

    public ?string $imageUrl = null;

    public int $userId;

    
    public function __construct(int $userId, int $reportId, string $message)
    {
        $this->userId = $userId;
        $this->reportId = $reportId;
        $this->message = $message;
    }

    
    public function setImageUrl(string $imageUrl): void
    {
        $this->imageUrl = $imageUrl;
    }
}