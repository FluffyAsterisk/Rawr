<?php

namespace App\Migrations;
use App\Enums\MySQLAttributes;
use App\Enums\MySQLDefault;

class ColumnBlueprint 
{
    private $name;
    private $type;
    private $length;
    private $onDefault = false;
    private $comparison;
    private $attributes = [];
    private $isNullable = false;
    private $isIndex = false;
    private $isAutoIncrement = false;
    private $isPrimary = false;
    private $isUnique = false;
    private $comment;
    // Virtual/Stored/Null
    private $virtuality = null;

    public function __construct($name, $type, $len = null) {
        $this->name = $name;
        $this->type = strtoupper($type);

        if ($len === null) 
        {
            switch ($type) 
            {
                case 'VARCHAR':
                    $this->length = 100;
                case 'INT':
                    $this->length = 11;
                default:
                    $this->length = null;
            }
        } 
        else 
        {
            $this->length = $len; 
        }
    }

    public function length(int $len) {
        $this->type == 'TEXT' ? $this->type = 'VARCHAR' : 1;
        $this->length = $len;
        return $this;
    }

    public function default(MySQLDefault $def, $val = null) {
        $this->onDefault = $def == MySQLDefault::DEFINED ? $val : $def;
        return $this;
    }

    public function comment($text) {
        $this->comment = $text;
        return $this;
    }

    public function attributes(MySQLAttributes $attr) {
        $this->attributes[] = $attr;
        return $this;
    }

    public function null() {
        $this->isNullable = true;
        return $this;
    }

    public function index() {
        $this->isIndex = true;
        return $this;
    }

    public function unique()
    {
        $this->isUnique = true;
        return $this;
    }

    public function primary()
    {
        $this->isPrimary = true;
        return $this;
    }

    public function ai() {
        $this->isAutoIncrement = true;
        return $this;
    }

    public function virtual($virt) {
        $this->virtuality = $virt;
        return $this;
    }

    public function __get($name) {
        return $this->$name ?? false;
    }

    public function __isset($name) {
        return isset($this->$name);
    }
}