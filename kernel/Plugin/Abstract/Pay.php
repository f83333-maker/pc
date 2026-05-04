<?php
declare (strict_types=1);

namespace Kernel\Plugin\Abstract;

use App\Model\Order;
use App\Model\PayOrder;
use Kernel\Annotation\Inject;
use Kernel\Container\Di;
use Kernel\Context\Interface\Request;
use Kernel\Context\Interface\Response;
use Kernel\Plugin\Entity\Plugin;
use Kernel\Util\Date;

abstract class Pay implements \Kernel\Plugin\Handle\Pay
{
    #[Inject]
    protected Request $request;

    #[Inject]
    protected Response $response;

    #[Inject]
    protected \App\Service\User\Order $orderService;

    protected Order $order;
    
    protected PayOrder $payOrder;
    
    protected Plugin $plugin;
    
    protected array $config;
    
    protected string $code;
    
    protected string $clientIp;
    
    protected ?string $amount;
    
    protected ?string $asyncUrl;
    
    protected ?string $syncUrl;

    public function __construct(Plugin $plugin, Order $order, PayOrder $payOrder, array $config, string $code, string $clientIp, ?string $amount = null, ?string $asyncUrl = null, ?string $syncUrl = null)
    {
        Di::inst()->inject($this);
        $this->order = $order;
        $this->plugin = $plugin;
        $this->config = $config;
        $this->clientIp = $clientIp;
        $this->amount = $amount;
        $this->asyncUrl = $asyncUrl;
        $this->syncUrl = $syncUrl;
        $this->code = $code;
        $this->payOrder = $payOrder;
    }

    public function successful(): void
    {
        $this->orderService->deliver($this->order, $this->request->clientIp());
        $this->payOrder->status = 2;
        $this->payOrder->pay_time = Date::current();
        $this->payOrder->save();
    }

    public function sync(): Response
    {
        return $this->response;
    }
}