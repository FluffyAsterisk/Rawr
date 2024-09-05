<?php

namespace App\Core;

abstract class Model {
    public function __get(string $key): mixed {
        return $this->$key;
    }

    public function __set(string $key, mixed $value): void {
        $this->$key = $value;
    }

    public function __isset(string $key): bool {
        return isset($this->$key);
    }
}