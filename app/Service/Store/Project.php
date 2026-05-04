<?php
declare (strict_types=1);

namespace App\Service\Store;

use App\Entity\Store\UpdateLog;
use Kernel\Annotation\Bind;

#[Bind(class: \App\Service\Store\Bind\Project::class)]
interface Project
{

    public function getNotice(): array;

    public function getVersionLatest(): array;

    public function getVersionList(): array;

    public function update(): void;

    public function getUpdateLog(string $hash): UpdateLog;
}