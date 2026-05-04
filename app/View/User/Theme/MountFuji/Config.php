<?php
declare(strict_types=1);

namespace App\View\User\Theme\MountFuji;

use App\Consts\Render;

interface Config
{

    const INFO = [
        "NAME" => "富士山",
        "AUTHOR" => "荔枝",
        "VERSION" => "1.0.0",
        "WEB_SITE" => "#",
        "DESCRIPTION" => "富士山模版，会员中心专用，极致的简约优化",
        "RENDER" => Render::ENGINE_SMARTY
    ];

    const SUBMIT = [
        [
            "title" => "色彩模式",
            "name" => "theme_mode",
            "type" => "radio",
            "dict" => [
                ["id" => "auto", "name" => "跟随系统"],
                ["id" => "light", "name" => "固定白天"],
                ["id" => "dark", "name" => "固定黑夜"]
            ],
            "default" => "auto"
        ]
    ];

    const THEME = [
        "DASHBOARD" => "Dashboard/Index.html", 
        "RECHARGE" => "User/Recharge.html", 
        "BILL" => "User/Bill.html", 
        "BUSINESS" => "User/Business.html", 
        "CATEGORY" => "User/Category.html", 
        "COMMODITY" => "User/Commodity.html", 
        "CARD" => "User/Card.html", 
        "COUPON" => "User/Coupon.html", 
        "CASH" => "User/Cash.html", 
        "CASH_RECORD" => "User/CashRecord.html", 
        "PERSONAL" => "User/Personal.html", 
        "EMAIL" => "User/Email.html", 
        "PHONE" => "User/Phone.html", 
        "PASSWORD" => "User/Password.html", 
        "ORDER" => "User/Order.html", 
        "PURCHASE_RECORD" => "User/PurchaseRecord.html", 
        "AGENT_MEMBER" => "Agent/Member.html", 
    ];
}
