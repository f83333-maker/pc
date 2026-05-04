<?php
declare(strict_types=1);

namespace App\Entity\Query;

class Save
{
    
    public string $model;

    public ?int $id = null;

    public array $map = [];

    public array $forceMap = [];

    public array $middle = [];

    public bool $isModifiable = true;

    public bool $isAddable = true;

    public bool $isAddCreateTime = false;

    public array $addWhitelist = [];

    public array $modifiableWhitelist = [];

    public function __construct(string $model)
    {
        $this->model = $model;
    }

    public function disableModifiable(): void
    {
        $this->isModifiable = false;
    }

    public function disableAddable(): void
    {
        $this->isAddable = false;
    }

    public function enableCreateTime(): void
    {
        $this->isAddCreateTime = true;
    }

    public function setMap(array $map, array $bypass = [], array $forbidden = []): void
    {
        if ($this->id === null) {
            $this->id = (isset($map['id']) && is_numeric($map['id'])) ? (int)$map['id'] : null;
        }

        foreach ($map as $key => $value) {
            $key = strtolower(trim((string)$key));
            if ($value === '' || $key == "id" || (!in_array($key, $bypass) && !empty($bypass))) { 
                continue;
            }

            if (in_array($key, $forbidden) && !empty($forbidden)) {
                continue;
            }

            if (is_scalar($value)) {
                $this->addMap($key, trim((string)$value));
                continue;
            }

            $this->addMap($key, $value);
        }
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function addMap(string $name, mixed $value): void
    {
        if (isset($this->map[$name])) {
            return;
        }
        $this->map[$name] = $value;
    }

    public function addForceMap(string $name, mixed $value): void
    {
        if (isset($this->forceMap[$name])) {
            return;
        }
        $this->forceMap[$name] = $value;
    }

    public function getMiddle(string $key): ?array
    {
        if (!array_key_exists($key, $this->middle)) {
            return null;
        }
        return $this->middle[$key];
    }

    public function setMiddle(string $key, string $middle, string $foreignKey, string $localKey): void
    {
        $this->middle[$key] = [
            'middle' => $middle,
            'foreignKey' => $foreignKey,
            'localKey' => $localKey
        ];
    }

    public function setAddWhitelist(string ...$column): void
    {
        $this->addWhitelist = $column;
    }

    public function setModifiableWhitelist(string ...$column): void
    {
        $this->modifiableWhitelist = $column;
    }
}