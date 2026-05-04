<?php
declare(strict_types=1);

namespace App\Controller\Shared;

use App\Controller\Base\API\Shared;
use App\Entity\Query\Get;
use App\Interceptor\SharedValidation;
use App\Interceptor\Waf;
use App\Model\Card;
use App\Model\Category;
use App\Service\Order;
use App\Service\Query;
use App\Service\Shop;
use App\Util\Ini;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Kernel\Annotation\Inject;
use Kernel\Annotation\Interceptor;
use Kernel\Context\Interface\Request;
use Kernel\Exception\JSONException;
use Kernel\Waf\Filter;

#[Interceptor([Waf::class, SharedValidation::class], Interceptor::TYPE_API)]
class Commodity extends Shared
{
    #[Inject]
    private Order $order;

    #[Inject]
    private Query $query;

    #[Inject]
    private \App\Service\Shared $shared;

    #[Inject]
    private Shop $shop;

    private function getItems(): array
    {
        $items = Category::query()->with(['children' => function (Relation $relation) {
            $relation->where("api_status", 1)->where("status", 1);
        }])->where("status", 1)->get();

        $list = $items->toArray();
        $userGroup = $this->getUserGroup();

        foreach ($list as $key => $item) {
            if (count($item['children']) == 0) {
                unset($list[$key]);
                continue;
            }
            foreach ($item['children'] as $index => $child) {
                $commodity = $items[$key]['children'][$index]; 
                if (!$commodity || $commodity->id != $child['id']) {
                    unset($list[$key]['children'][$index]);
                    continue;
                }

                $parseGroupConfig = \App\Model\Commodity::parseGroupConfig($child['level_price'], $userGroup);
                if ($child['hide'] == 1 && (!$parseGroupConfig || !isset($parseGroupConfig['show']) || $parseGroupConfig['show'] != 1)) {
                    unset($list[$key]['children'][$index]);
                    continue;
                }

                if ($child['delivery_way'] == 0) { 
                    $list[$key]['children'][$index]['stock'] = Card::query()->where("status", 0)->where("commodity_id", $child['id'])->count();
                }

                unset($list[$key]['children'][$index]['leave_message'], $list[$key]['children'][$index]['delivery_message']);
            }
            
            $list[$key]['children'] = array_values($list[$key]['children']);
        }

        return array_values($list);
    }

    public function items(): array
    {
        return $this->json(data: $this->getItems());
    }

    public function item(): array
    {
        $code = $_POST['code'] ?? null;
        if (!$code) {
            throw new JSONException("对接CODE不能为空");
        }
        return $this->json(data: $this->shop->getItem($code));
    }

    public function inventoryState(): array
    {
        $sharedCode = (string)$_POST['shared_code'];
        $cardId = (int)$_POST['card_id'];
        $num = (int)$_POST['num']; 
        $race = (string)$_POST['race']; 

        if ($sharedCode == "") {
            throw new JSONException("商品代码不能为空");
        }
        $commodity = \App\Model\Commodity::query()->where("code", $sharedCode)->first();

        if (!$commodity) {
            throw new JSONException("商品不存在");
        }
        if ($commodity->status != 1) {
            throw new JSONException("当前商品已停售");
        }

        $shared = $commodity->shared;
        
        if ($shared) {
            if (!$this->shared->inventoryState($shared, $commodity, $cardId, $num, $race)) {
                throw new JSONException("库存不足");
            }
            return $this->json(200, "success");
        }

        if ($commodity->draft_status == 1 && $cardId != 0) {
            $card = Card::query()->find($cardId);
            if (!$card || $card->status != 0) {
                throw new JSONException("该卡已被他人抢走啦");
            }

            if ($card->commodity_id != $commodity->id) {
                throw new JSONException("该卡密不属于这个商品，无法预选");
            }
        } else {
            
            if ($commodity->delivery_way == 0) {
                $count = Card::query()->where("commodity_id", $commodity->id)->where("status", 0);

                if ($race) {
                    $count = $count->where("race", $race);
                }

                $count = $count->count();

                if ($count == 0 || $num > $count) {
                    throw new JSONException("库存不足");
                }
            }
        }
        return $this->json(200, "success");
    }

    public function inventory(): array
    {
        $sharedCode = (string)$_POST['sharedCode'];
        $race = (string)$_POST['race'];

        if ($sharedCode == "") {
            throw new JSONException("商品代码不能为空");
        }

        $commodity = \App\Model\Commodity::query()->where("code", $sharedCode)->first();

        if (!$commodity) {
            throw new JSONException("商品不存在");
        }

        if ($commodity->status != 1) {
            throw new JSONException("当前商品已停售");
        }

        $count = 0;

        $shared = $commodity->shared;

        if ($shared) {
            $inventory = $this->shared->inventory($shared, $commodity, $race);
            return $this->json(200, "success", $inventory);
        }

        if ($commodity->delivery_way == 0) {
            $count = Card::query()->where("commodity_id", $commodity->id)->where("status", 0);
            $parseConfig = Ini::toArray((string)$commodity->config);
            if (key_exists("category", $parseConfig)) {
                $commodity->race = $parseConfig['category'];
                if ($race) {
                    $count = $count->where("race", $race);
                } else {
                    foreach ($commodity->race as $key => $race) {
                        $count = $count->where("race", $key);
                        break;
                    }
                }
            }

            $count = $count->count();
        }

        $userId = $this->getUser()->id;
        $userGroup = $this->getUserGroup();

        $factoryPrice = 0;
        $isCategory = false;

        $configs = Ini::toArray((string)$commodity->config);
        if (array_key_exists("category_factory", $configs)) {
            unset($configs['category_factory']);
        }

        if (array_key_exists("category", $configs)) {
            
            $categorys = $configs['category'];
            $factorys = [];
            
            foreach ($categorys as $ck => $cv) {
                $isCategory = true;
                
                try {
                    $factorys[$ck] = $this->order->calcAmount(owner: $userId, num: 1, disableSubstation: true, group: $userGroup, commodity: $commodity, race: $ck);
                } catch (\Error|\Exception $e) {
                    unset($configs['category'][$ck]);
                    continue;
                }
            }
            if (count($factorys) != 0) {
                
                $configs['category_factory'] = $factorys;
            }
        } else {
            
            $factoryPrice = $this->order->calcAmount(owner: $userId, num: 1, disableSubstation: true, group: $userGroup, commodity: $commodity);
        }

        $cfg = Ini::toConfig($configs);

        return $this->json(200, "success", [
            'count' => $count,
            'delivery_way' => $commodity->delivery_way,
            "draft_status" => $commodity->draft_status,
            'price' => $commodity->price,
            'user_price' => $commodity->user_price,
            "config" => $cfg,
            "factory_price" => $factoryPrice,
            "is_category" => $isCategory
        ]);
    }

    public function trade(Request $request): array
    {
        $map = $request->post(flags: Filter::NORMAL);
        $map['pay_id'] = 1; 

        $commodity = \App\Model\Commodity::query()->where("code", (string)$map['shared_code'])->first();

        if (!$commodity) {
            throw new JSONException("商品不存在");
        }
        $map['item_id'] = $commodity->id;
        return $this->json(200, 'success', $this->order->trade($this->getUser(), $this->getUserGroup(), $map));
    }

    public function draftCard(): array
    {
        $map = $this->request->post();
        
        $commodity = \App\Model\Commodity::query()->where("code", $map['code'])->first();
        $limit = $map['limit'] ?? 10;

        if (!$commodity) {
            throw new JSONException("商品不存在");
        }

        if ($commodity->status != 1) {
            throw new JSONException("该商品暂未上架");
        }

        if ($commodity->draft_status != 1) {
            throw new JSONException("该商品不支持预选");
        }

        if ($commodity->shared) {
            $data = $this->shared->draftCard($commodity->shared, $commodity->shared_code, $map);
        } else {
            $get = new Get(Card::class);
            $get->setPaginate((int)$this->request->post("page"), (int)$limit);
            $get->setWhere($map);
            $get->setColumn('id', 'draft', 'draft_premium');

            $data = $this->query->get($get, function (Builder $builder) use ($map, $commodity) {
                $builder = $builder->where("commodity_id", $commodity->id)->where("status", 0);

                if (!empty($map['race'])) {
                    $builder = $builder->where("race", $map['race']);
                }

                if (!empty($map['sku']) && is_array($map['sku'])) {
                    foreach ($map['sku'] as $k => $v) {
                        $builder = $builder->where("sku->{$k}", $v);
                    }
                }

                return $builder;
            });
        }

        return $this->json(data: $data);
    }

    public function query(string $tradeNo): array
    {
        
        $order = \App\Model\Order::query()->where("trade_no", $tradeNo)->where("owner", $this->getUser()->id)->first();

        if (!$order) {
            throw new JSONException("订单不存在");
        }

        $widget = (array)json_decode((string)$order->widget, true);
        if (empty($widget)) {
            $widget = null;
        }

        return $this->json(200, 'success', ['secret' => $order->secret, 'widget' => $widget, "status" => $order->status]);
    }

    public function stock(): array
    {
        $map = $this->request->post(flags: Filter::NORMAL);
        $stock = $this->shop->getItemStock($map['code'], $map['race'] ?? null, $map['sku'] ?? null);
        return $this->json(data: ["stock" => $stock]);
    }

    public function valuation(): array
    {
        $commodity = \App\Model\Commodity::query()->where("code", $this->request->post("code"))->first();

        if (!$commodity) {
            throw new JSONException("商品不存在#0");
        }

        $price = $this->order->valuation(
            commodity: $commodity,
            num: (int)$this->request->post("num"),
            race: (string)$this->request->post("race"),
            sku: (array)$this->request->post("sku"),
            cardId: (int)$this->request->post("card_id"),
            group: $this->getUserGroup()
        );
        $price = $this->shop->getSubstationPrice($commodity, $price);
        return $this->json(data: ["price" => $price]);
    }

    public function draft(): array
    {
        $map = $this->request->post(flags: Filter::NORMAL);
        $commodity = \App\Model\Commodity::query()->where("code", $map['code'])->first();

        if (!$commodity) {
            throw new JSONException("商品不存在");
        }

        return $this->json(data: $this->shop->getDraft($commodity, (int)$map['card_id']));
    }
}