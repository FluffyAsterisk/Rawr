<?php

namespace App\Queries;

class Column {
    private $name;
    private $alias;
    
    public function __construct(string $name, string|null $alias = null) {
        $this->name = $name;
        $this->alias = $alias;
    }

    public function __get($name) {
        return $this->$name;
    }
}