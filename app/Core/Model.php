<?php

namespace App\Core;

abstract class Model {
    private mixed $data;

    public function __construct(array $values = []) {
        $class = $this::class;

        if ($values && is_int( $values[0] )) { throw new \Exception("Params should be passed as associative array in $class constructor"); }

        foreach ($values as $key => $value ) {
            $this->{$key} = $value;
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
        $this->relations[] = new Relation(RelationType::OneToMany, $this->class, $class);
    }

    public function belongsToMany(string $class, $foreignKey = null, $ownerKey = null) {
        $this->relations[] = new Relation(RelationType::OneToMany, $class, $this->class);
    }

    public function hasOne(string $class, $foreignKey = null, $ownerKey = null) {
        $this->relations[] = new Relation(RelationType::OneToOne, $this->class, $class);
    }

    public function belongsTo(string $class, $foreignKey = null, $ownerKey = null) {
        $this->relations[] = new Relation(RelationType::OneToOne, $class, $this->class);
    }
}

enum RelationType: int {
    case OneToMany = 1;
    case ManyToMany = 2;
    case OneToOne = 3;   
}

class Relation {
    public RelationType $type;
    private string $parent;
    private string $child;

    public function __construct(RelationType $type, string $parent, string $child) {
        $this->setType($type);
        $this->setParent($parent);
        $this->setChild($child);
    }

    public function setType(RelationType $type) {
        $this->type = $type;
    }

    public function setParent(string $parent) {
        $this->parent = $parent;
    }

    public function setChild(string $child) {
        $this->child = $child;
    }
}