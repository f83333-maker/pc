<?php
declare (strict_types=1);

namespace App\Const;

interface RepertoryItem
{
    
    public const REFUND_MODE_NOT = 0;

    public const REFUND_MODE_CONDITION = 1;

    public const REFUND_MODE_UNCONDITIONALLY = 2;
}