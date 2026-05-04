<?php
declare(strict_types=1);

namespace App\Consts;

interface Pay
{
    const IS_SIGN = 0x1;    
    const IS_STATUS = 0x4;  
    const FIELD_STATUS_KEY = 0x2; 
    const FIELD_STATUS_VALUE = 0x3; 
    const FIELD_ORDER_KEY = 0x5; 
    const FIELD_AMOUNT_KEY = 0x6; 
    const FIELD_RESPONSE = 0x7; 

    const DAFA = "FROM_PAY_DATA"; 
}