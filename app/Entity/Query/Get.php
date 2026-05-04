<?php
declare (strict_types=1);

namespace App\Entity\Query;

class Get
{
    
    public string $model;

    
    public ?array $paginate = null;

    public array $where = [];

    public array $orderBy = ['id', 'desc'];

    public array $columns = ['*'];

    
    public array $leftJoinWhere = [];

    public function __construct(string $class)
    {
        $this->model = $class;
    }

    public function setPaginate(int $page, int $limit = 15): void
    {
        $this->paginate = [$page, $limit];
    }

    public function setWhere(array $where): void
    {
        $map = [];
        foreach ($where as $key => $value) {
            if ($value !== '' && is_scalar($value)) {
                $keys = explode('·', urldecode($key));
                $map[$keys[0]] = $value;
            } else if (!is_scalar($value)) {
                $map[$key] = $value;
            }
        }
        $this->where = $map;
    }

    public function setOrderBy(string $column, string $rule = 'desc'): void
    {
        $this->orderBy = [$column, $rule];
    }

    
    public function setColumn(string ...$columns): void
    {
        $this->columns = $columns;
    }

    public function setWhereLeftJoin(string $related, string $foreignKey, string $localKey, array $whereColumns): void
    {
        $this->leftJoinWhere[] = [
            'columns' => $whereColumns,
            'related' => $related,
            'foreignKey' => $foreignKey,
            'localKey' => $localKey
        ];
    }
}