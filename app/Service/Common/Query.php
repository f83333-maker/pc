<?php
declare(strict_types=1);

namespace App\Service\Common;

use App\Entity\Query\Delete;
use App\Entity\Query\Get;
use App\Entity\Query\Save;
use Kernel\Annotation\Bind;

#[Bind(class: \App\Service\Common\Bind\Query::class)]
interface Query
{
    public const RESULT_TYPE_ARRAY = 0;
    public const RESULT_TYPE_RAW = 4;

    public function get(Get $get, ?callable $append = null, int $resultType = self::RESULT_TYPE_ARRAY): mixed;

    public function save(Save $save): mixed;

    public function delete(Delete $delete): int;

    
    public function getOrderBy(array $map, string $field, string $rule = 'desc'): array;
}