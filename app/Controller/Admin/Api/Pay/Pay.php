<?php
declare (strict_types=1);

namespace App\Controller\Admin\API\Pay;

use App\Controller\Admin\Base;
use App\Entity\Query\Delete;
use App\Entity\Query\Get;
use App\Entity\Query\Save;
use App\Interceptor\Admin;
use App\Interceptor\PostDecrypt;
use App\Model\Pay as Model;
use App\Model\PayGroup;
use App\Model\PayUser;
use App\Model\PluginConfig;
use App\Service\Common\Query;
use App\Validator\Common;
use Hyperf\Database\Model\Builder;
use Kernel\Annotation\Inject;
use Kernel\Annotation\Interceptor;
use Kernel\Annotation\Validator;
use Kernel\Context\App;
use Kernel\Context\Interface\Response;
use Kernel\Database\Exception\Resolver;
use Kernel\Exception\JSONException;
use Kernel\Exception\RuntimeException;
use Kernel\Plugin\Plugin;
use Kernel\Plugin\Usr;
use Kernel\Util\Date;

#[Interceptor(class: [PostDecrypt::class, Admin::class], type: Interceptor::API)]
class Pay extends Base
{

    #[Inject]
    private Query $query;

    public function code(string $plugin): Response
    {
        $plg = Plugin::instance()->getPlugin($plugin, App::$mEnv);
        if (!$plg) {
            throw new JSONException("插件不存在");
        }
        return $this->json(data: $plg->payCode);
    }

    #[Validator([
        [Common::class, ["page", "limit"]]
    ])]
    public function get(): Response
    {
        $map = $this->request->post();
        $get = new Get(Model::class);
        $get->setWhere($map);
        $get->setPaginate((int)$this->request->post("page"), (int)$this->request->post("limit"));
        $get->setOrderBy(...$this->query->getOrderBy($map, "sort", "asc"));
        $get->setWhereLeftJoin(PluginConfig::class, "id", "pay_config_id", ["plugin" => "plugin"]);
        $data = $this->query->get($get, function (Builder $builder) use ($map) {
            if (isset($map['display_scope'])) {
                if ($map['display_scope'] == 1) {
                    $builder = $builder->whereNull("pay.user_id");
                } elseif ($map['display_scope'] == 2) {
                    if (isset($map['user_id']) && $map['user_id'] > 0) {
                        $builder = $builder->where("pay.user_id", $map['user_id']);
                    } else {
                        $builder = $builder->whereNotNull("pay.user_id");
                    }
                }
            }
            return $builder
                ->with(['config', 'user', 'parent'])
                ->withSum("paidOrder as order_amount", "trade_amount")
                ->withSum("todayOrder as today_amount", "trade_amount")
                ->withSum("yesterdayOrder as yesterday_amount", "trade_amount")
                ->withSum("weekdayOrder as weekday_amount", "trade_amount")
                ->withSum("monthOrder as month_amount", "trade_amount")
                ->withSum("lastMonthOrder as last_month_amount", "trade_amount");
        });

        $envCache = [];
        $pluginCache = [];
        foreach ($data['list'] as &$item) {
            if (!$item['pid'] && isset($item['config'])) {
                $uid = (int)($item['user_id'] ?? 0);
                if (!isset($envCache[$uid])) {
                    $envCache[$uid] = Usr::inst()->userToEnv($uid);
                }
                $pname = $item['config']['plugin'] ?? '';
                $ck = $pname . "\0" . (string)$uid;
                if (!array_key_exists($ck, $pluginCache)) {
                    $pluginCache[$ck] = Plugin::instance()->getPlugin($pname, $envCache[$uid]);
                }
                $item['plugin'] = $pluginCache[$ck];
            }
            $item['scope'] = is_array($item['scope']) ? $item['scope'] : (array)json_decode((string)$item['scope'], true);
        }
        unset($item);

        return $this->json(data: $data);
    }

    #[Validator([
        [\App\Validator\Admin\Pay::class, ["name", "payConfigId", "code"]]
    ])]
    public function save(): Response
    {
        $map = $this->request->post();
        $tempId = $map['temp_id'] ?? "";
        unset($map['temp_id']);

        $save = new Save(Model::class);
        $save->enableCreateTime();
        $save->setMap(map: $map,forbidden:  ["api_fee_status"]);
        try {
            $saved = $this->query->save($save);

            if (!isset($map['id'])) {
                PayGroup::query()->where("temp_id", $tempId)->update([
                    "pay_id" => $saved->id
                ]);
                PayUser::query()->where("temp_id", $tempId)->update([
                    "pay_id" => $saved->id
                ]);
            }

            PayGroup::query()->where("create_time", "<=", Date::calcDay(-1))->whereNull("pay_id")->delete();
            
            PayUser::query()->where("create_time", "<=", Date::calcDay(-1))->whereNull("pay_id")->delete();
        } catch (\Exception $exception) {
            throw new JSONException(Resolver::make($exception)->getMessage());
        }
        return $this->response->json(message: "保存成功");
    }

    public function del(): Response
    {
        $delete = new Delete(Model::class, (array)$this->request->post("list"));
        $this->query->delete($delete);
        return $this->response->json(message: "删除成功");
    }
}