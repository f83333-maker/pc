<?php
declare (strict_types=1);

namespace App\Const;

interface Order
{

    const ORDER_TYPE_PRODUCT = 0;

    const ORDER_TYPE_RECHARGE = 1;

    const ORDER_TYPE_UPGRADE_GROUP = 2;

    const ORDER_TYPE_UPGRADE_LEVEL = 3;

    const ORDER_TYPE_PLUGIN = 49;

    const AUTO_RECEIPT_ROLE_MAIN = 0;
    
    const AUTO_RECEIPT_ROLE_MERCHANT = 1;
    
    const AUTO_RECEIPT_ROLE_SUPPLIER = 2;
    
    const AUTO_RECEIPT_ROLE_CUSTOMER = 3;
}