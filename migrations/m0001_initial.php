<?php

namespace App\Migrations;

use App\Migrations\Migration;
use App\Migrations\Schema;
use App\Migrations\Blueprint;
use App\Enums\MySQLDefault;

return new class extends Migration {
    public function up() {
        Schema::create('asdf', function(Blueprint $table) {
            $table->id();
            $table->string('username')->length(50);
            $table->string('password')->length(75)->default(MySQLDefault::DEFINED, 12345);
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('asdf');
    }
};