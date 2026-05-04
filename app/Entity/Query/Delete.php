<?php
declare(strict_types=1);

namespace App\Entity\Query;

class Delete
{

    public string $model;

    public array $list = [];

    public array $where = [];

    public function __construct(string $model, array|int $list)
    {
        $this->model = $model;
        $this->list = is_array($list) ? $list : [$list];
    }

    public function setWhere(string $column, mixed $operator = null, mixed $value = null, mixed $boolean = 'and'): void
    {
        $this->where[] = [$column, (string)$operator, $value, $boolean];
    }
}