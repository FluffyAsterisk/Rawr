<?php

namespace App\Migrations;

abstract class Migration {
    public function __construct() {}
    abstract public function up();
    abstract public function down();
}