<?php
declare(strict_types=1);
error_reporting(0);
const BASE_PATH = __DIR__ . "/../";
require(BASE_PATH . '/vendor/autoload.php');
require("Helper.php");

\Kernel\Util\Context::set(\Kernel\Consts\Base::LOCK, (string)file_get_contents(BASE_PATH . "/kernel/Install/Lock"));

$capsule = new \Illuminate\Database\Capsule\Manager();

$capsule->addConnection(config('database'));

$capsule->setAsGlobal();

$capsule->bootEloquent();

