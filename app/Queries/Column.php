<?php

namespace App\Queries;

class Column {
    public $name;
    public $alias;
    
    public function __construct(string $name, string|null $alias = null) {
        $this->name = $name;
        $this->alias = $alias;
    }
}