<?php

namespace App\Queries;
use App\Queries\Column;

class Table {
    private string $name;
    private string|null $alias;
    private array $columns = [];
    private array $conditions = [];

    public function __construct(string $name, string|null $alias = null) {
        $this->name = $name;
        $this->alias = $alias;
    }
    
    public function getName(): string {
        return $this->name;
    }

    public function getAlias(): string|null {
        return $this->alias;
    }

    public function addCondition(string $condition) {
        $this->conditions[] = $condition;
    }

    public function setColumns(array $columns) {
        $this->columns = $columns;
    }

    public function getColumns(): array {
        $r = [];

        foreach ($this->columns as $column) {
            $r[] = $column->alias ? "`{$this->getName()}`.`{$column->name}` AS '{$column->alias}'" : "`{$this->getName()}`.`{$column->name}`";
        }

        return $r;
    }

    public function hasColumn($column) {
        return array_key_exists($column, $this->columns);
    }

    public function getConditions(): array {
        $r = [];

        foreach ($this->conditions as $condition) {
            
            $p = explode(' ', $condition);
            $p[0] = $this->hasColumn($p[0]) ? "`{$this->getName()}`.`{$p[0]}`" : "'{$p[0]}'";
            $r[] = implode(' ', $p);
        }

        return $r;
    }
}