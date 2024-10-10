<?php

namespace App\Migrations;

use App\Enums\MySQLAttributes;
use App\Enums\MySQLDefault;

class Blueprint {
    public function __construct($name) 
    {
        $this->tableName = $name;
    }

    public function id() {
        $this->id = new ColumnBlueprint('id', 'int', 11);
        $this->id->primary();
        $this->id->ai();
    }

    public function string($name, $len = null) {
        if ($len) {
            $this->$name = new ColumnBlueprint($name, 'VARCHAR', 75);
        } else {
            $this->$name = new ColumnBlueprint($name, 'TEXT');
        }
        return $this->$name;
    }

    public function int($name, $len = null) {
        $this->$name = new ColumnBlueprint($name, 'int', $len);
        return $this->$name;
    }

    public function date($name) {
        $this->$name = new ColumnBlueprint($name, 'date');
        return $this->$name;
    }

    public function dateTime($name) {
        $this->$name = new ColumnBlueprint($name, 'datetime');
        return $this->$name;
    }

    public function timestamp($name) {
        $this->$name = new ColumnBlueprint($name, 'timestamp');
        return $this->$name;
    }

    public function timestamps() {
        $this->created_at = new ColumnBlueprint('created_at', 'timestamp');
        $this->updated_at = new ColumnBlueprint('updated_at', 'timestamp');

        $this->created_at->default(MySQLDefault::CURRENT_TIMESTAMP);
        $this->updated_at->default(MySQLDefault::CURRENT_TIMESTAMP);
        $this->updated_at->attributes(MySQLAttributes::ON_UPDATE_CURRENT_TIMESTAMP);
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function build() {
        $properties = get_object_vars($this);
    
        $sql = "CREATE TABLE IF NOT EXISTS `{$this->tableName}` (\n";

        // String for index definition
        $indexes = [];
        $columns = [];
    
        array_shift($properties);

        foreach ($properties as $prop) {
            $columns[] = sprintf(
                "`%s` %s%s %s%s%s%s%s",
                $prop->name,
                $prop->type,
                isset($prop->length) ? "({$prop->length})" : "",
                $prop->isNullable ? "NULL" : "NOT NULL",
                $prop->onDefault ? 
                    (is_object($prop->onDefault) ? " DEFAULT {$prop->onDefault->name}" : " DEFAULT {$prop->onDefault}") : "",
                $prop->isPrimary ? " PRIMARY KEY" : "",
                $prop->isAutoIncrement ? " AUTO_INCREMENT" : "",
                $prop->attributes ? " " . implode(' ', array_map(function($attr) { return $attr->value; }, $prop->attributes)) : "",
                $prop->comment ? " COMMENT '{$prop->comment}'" : "",
            );

            if ($prop->isIndex) 
            {
                $indexes[] = "INDEX `{$prop->name}_index` (`$prop->name`)";
            }

            if ($prop->isUnique)
            {
                $indexes[] = "UNIQUE (`$prop->name`)";
            }
        }

        $sql .= implode(",\n", $columns);
        $sql .= $indexes ? ",\n" . implode(",\n", $indexes) . "\n)" : "\n";

        return $sql;
    }
}