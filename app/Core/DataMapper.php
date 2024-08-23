<?php

namespace App\Core;

use App\Core\Model;
use App\Helpers\Cache;

abstract class DataMapper {
    protected string $class;

    public function __construct(private \PDO $db) {}

    public function selectAll() {
        $r = [];
        $table = $this->tableName;
        $query = $this->db->query("SELECT * FROM $table");
        $query->setFetchMode(\PDO::FETCH_CLASS, $this->class);

        return $query->fetchAll();
    }

    public function save(array|object $objects) {
        $class = new \ReflectionClass($this->class);

        $tableName = isset($this->tableName) ? $this->tableName : strtolower( $class->getShortName() );
        $props = $this->getClassProperties($class);
        $toImplode = [];
        $onUpdate = [];
        
        foreach ($props as $prop) {
            array_push($onUpdate, "$prop = VALUES($prop)");
        }
        
        if ( is_array($objects) ) 
        {
            foreach ( $objects as $object ) { array_push($toImplode, $this->stringifyProps($props, $object)); }
        } 
        else { array_push($toImplode, $this->stringifyProps($props, $objects)); }
        
        $sql = sprintf( "INSERT INTO `%s` (%s) VALUES %s ON DUPLICATE KEY UPDATE %s", $this->tableName, implode(', ', $props), implode(', ', $toImplode), implode(', ', $onUpdate));

        print_r($toImplode);

        die();
        
        print_r('<pre>');
        print_r($values);
        print_r('</pre>');
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
            array_push($p, $object->{$prop});
        }

        return "(" . implode(', ', $p) . ")";
    }
}

// INSERT INTO table_name (column1, column2, column3, ...) VALUES (value1, value2, value3, ...);