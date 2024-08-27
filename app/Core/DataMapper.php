<?php

namespace App\Core;

use App\Core\Model;
use App\Helpers\Cache;

abstract class DataMapper {
    protected string $class;

    public function __construct(private \PDO $pdo, private \App\Helpers\Sanitizer $sanitizer) {}

    public function selectAll() {
        $r = [];
        $table = $this->tableName ?? (new \ReflectionClass($this::class))->getShortName();
        $query = $this->pdo->query("SELECT * FROM $table");
        $query->setFetchMode(\PDO::FETCH_CLASS, $this->class);

        return $query->fetchAll();
    }

    public function save(array $objects) {
        $class = new \ReflectionClass($this::class);
        $propsValues = [];
        $onDuplicate = [];

        $tableName = $this->tableName ?? strtolower( $class->getShortName() );
        $props = $this->getClassProperties($class);
        $objCount = count($objects);
        
        foreach ($props as $prop) {
            array_push($onDuplicate, "$prop = VALUES($prop)");
        }
        
        foreach ( $objects as $object ) { $propsValues = array_merge( $propsValues, $this->stringifyProps($props, $object) ); }
        
        $placeholder = str_repeat('(' . 
                        rtrim( str_repeat('?, ', count($props)), ", ")
                         . '), ', $objCount);

        $placeholder = rtrim($placeholder, ', ');

        $sql = "INSERT INTO " . $tableName . " (" . implode(', ', $props) . ") VALUES " . $placeholder . " ON DUPLICATE KEY UPDATE " . implode(', ', $onDuplicate);
        
        $stmt = $this->pdo->prepare($sql);

        $stmt->execute($propsValues);
    }

    public function delete() {
        $tableName = $this->tableName ?? (new \ReflectionClass($this::class))->getShortName();

        $sql = "DELETE FROM " . $tableName . " WHERE ";
    }

    private function getClassProperties($reflection) {
        $properties = [];

        foreach ( ( $reflection->getProperties(\ReflectionProperty::IS_PROTECTED) ) as $prop) {
            array_push($properties, $prop->getName());
        }

        return $properties;
    }

    private function stringifyProps($props, $object) {
        $p = [];

        foreach ($props as $prop) {
            $val = $this->sanitizer->sanitizeString( $object->{$prop} );
            $p[] = $val == null ? null : $val;
        }

        return $p;
    }

}

// INSERT INTO table_name (column1, column2, column3, ...) VALUES (value1, value2, value3, ...);