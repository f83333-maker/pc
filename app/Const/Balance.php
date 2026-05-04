<?php
declare (strict_types=1);

namespace App\Const;

interface Balance
{

    public const TYPE_RESTOCK = 0;

    public const TYPE_SUPPLY_SETTLEMENT = 1;

    public const TYPE_SUB_DIVIDEND = 2;

    public const TYPE_ORDER_DIVIDEND = 3;

    public const TYPE_SHOPPING = 4;

    public const TYPE_ORDER_REFUND = 5;

    public const TYPE_PAY_ORDER_REFUND = 6;

    public const TYPE_MANUAL = 7;

    public const TYPE_TRANSFER = 8;

    public const TYPE_RECHARGE = 9;

    public const TYPE_INVITE_DIVIDEND = 10;

    public const TYPE_WITHDRAW = 11;

    public const TYPE_WITHDRAW_REJECT = 12;

    public const TYPE_DEPOSIT = 13;

    public const TYPE_GOODS_SALE = 14;

    public const TYPE_LOAN = 15;

    public const TYPE_REPAYMENT = 16;

    public const TYPE_APPROPRIATION = 17;

    public const STATUS_DIRECT = 0;

    public const STATUS_DELAYED = 1;

    public const STATUS_ROLLBACK = 2;

    public const ACTION_ADD = 1;

    public const ACTION_DEDUCT = 0;
}