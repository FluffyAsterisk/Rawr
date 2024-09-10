<?php

namespace App\Migrations;

use App\Migrations\Migration;
use App\Migrations\Schema;
use App\Migrations\Blueprint;

return new class extends Migration {
    public function up() {
        Schema::create('asdf', function(Blueprint $table) {
            $table->id();
            $table->string('username')->length(50);
            $table->string('password')->length(75);
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('users');
    }
};