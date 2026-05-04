<?php
declare (strict_types=1);

namespace App\Command;

use App\Model\Manage;
use App\Service\Store\Project;
use Kernel\Annotation\Inject;
use Kernel\Console\Command;
use Kernel\Container\Di;
use Kernel\Util\Str;

class Kit extends Command
{

    #[Inject]
    private Project $project;

    public function update(): void
    {
        $this->project->update();
        
        Di::inst()->make(Service::class)->restart();
    }

    public function reset(string $password): void
    {
        
        $manage = Manage::query()->find(1);
        if (!$manage) {
            $this->error("超级管理员不存在");
            return;
        }
        $manage->password = Str::generatePassword($password, $manage->salt);
        $manage->save();
        $this->success("超级管理员密码重置成功!\n账号：{$manage->email}\n密码：{$password}");
    }
}