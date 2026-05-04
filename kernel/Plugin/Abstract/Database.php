<?php
declare (strict_types=1);

namespace Kernel\Plugin\Abstract;

use Kernel\Database\Schema;
use Kernel\Plugin\Entity\Plugin;

abstract class Database implements \Kernel\Plugin\Handle\Database
{
    
    private string $usr;

    protected Plugin $plugin;

    public function __construct(Plugin $plugin, string $usr = "*")
    {
        if ($usr == "*") {
            $usr = "";
        } else {
            $usr = strtolower($usr) . "_";
        }
        $this->plugin = $plugin;
        $this->usr = $usr;
    }

    protected function getTable(string $table): string
    {
        return $this->usr . $table;
    }

    protected function hasColumn(string $table, string $column): bool
    {
        return Schema::hasColumn($this->getTable($table), $column);
    }

    protected function hasColumns(string $table, array $columns): bool
    {
        return Schema::hasColumns($this->getTable($table), $columns);
    }

    protected function hasTable(string $table): bool
    {
        return Schema::hasTable($this->getTable($table));
    }

    protected function table(string $table, \Closure $callback): void
    {

        Schema::table($this->getTable($table), $callback);
    }

    protected function create(string $table, \Closure $callback): void
    {
        Schema::create($this->getTable($table), $callback);
    }

    protected function drop(string $table): void
    {
        Schema::drop($this->getTable($table));
    }

    protected function dropIfExists(string $table): void
    {
        Schema::dropIfExists($this->getTable($table));
    }
}