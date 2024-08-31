<?php

namespace App\Core;

abstract class Model {
    private mixed $data;
    protected static $relations;

    public function __construct() {
        $refl = new \ReflectionClass($this::class);
        $relations = [];

        foreach ($refl->getMethods() as $method) {
            if ($method->class != $this::class) { break; }
            $relations[] = $method;
        }
    
        foreach ($relations as $relation) {
            $relation->invoke($this);
        }
    }

    public function tableName() {
        $class = new \ReflectionClass($this::class);

        return $this->tableName ?? $class->getShortName();
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

    public function getProperties() {
        $refl = new \ReflectionClass($this::class);
        $properties = [];

        foreach ( ( $refl->getProperties(\ReflectionProperty::IS_PROTECTED) ) as $prop) {
            array_push($properties, $prop->getName());
        }

        return $properties;
    }

    public function hasMany(string $class, $foreignKey = null, $ownerKey = null) {
        if (!$ownerKey) { $ownerKey = 'id'; }
        if (!$foreignKey) { $foreignKey = strtolower( $this::class . '_id' ); }

        static::$relations[] = new Relation(RelationType::OneToMany, $this->class, $class, $ownerKey, $foreignKey);

        print_r($this::$relations);
    }

    public function belongsToMany(string $class, $foreignKey = null, $ownerKey = null) {
        self::$relations[] = new Relation(RelationType::OneToMany, $class, $this->class, $foreignKey, $ownerKey);
    }

    public function hasOne(string $class, $foreignKey = null, $ownerKey = null) {
        self::$relations[] = new Relation(RelationType::OneToOne, $this->class, $class, $ownerKey, $foreignKey);
    }

    public function belongsTo(string $class, $foreignKey = null, $ownerKey = null) {
        self::$relations[] = new Relation(RelationType::OneToOne, $class, $this->class, $foreignKey, $ownerKey);
    }
}

enum RelationType: int {
    case OneToMany = 1;
    case ManyToMany = 2;
    case OneToOne = 3;   
}

class Relation {
    public RelationType $type;
    private array $parent;
    private array $child;

    public function __construct(RelationType $type, string $parent, string $child,  string $ownerKey = null, string $foreignKey = null) {
        $this->setType($type);
        $this->setParent($parent, $ownerKey);
        $this->setChild($child, $foreignKey);
    }

    public function setType(RelationType $type) {
        $this->type = $type;
    }

    public function setParent(string $parent, string $parentKey) {
        $this->parent = [$parent, $parentKey];
    }

    public function setChild(string $child, string $childKey) {
        $this->child = [$child, $childKey];
    }
}
