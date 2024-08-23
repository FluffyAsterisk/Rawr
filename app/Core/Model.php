<?php

namespace App\Core;

abstract class Model {
    private mixed $data;

    public function tableName() {
        return $this->tableName;
    }

    public function __get(string $key): mixed {
        if (property_exists($this, $key)) { return $this->$key; }
        $class = $this::class;
        throw new \Exception("Cannot get property $key. It does not exist in $class object");
    }

    public function __set(string $key, mixed $value): void {
        if (property_exists($this, $key)) { $this->data[$key] = $value; }
        $class = $this::class;
        throw new \Exception("Cannot set property $key. It does not exist in $class object");
    }

    public static function mapFromArray(array $data) {
        $o = new self();

        foreach ($data as $key => $value) {
            $o->{$key} = $value;
        }

        return $o;
    }
}
