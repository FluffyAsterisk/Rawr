<?php

namespace App\Migrations;

use App\Enums\MySQLDefault;
use App\Migrations\Blueprint;
use App\Migrations\Migration;
use App\Migrations\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('migrations', function(Blueprint $table) {
            $table->id();
            $table->string('name')->length(75);
            $table->timestamp('created_at')->default(MySQLDefault::CURRENT_TIMESTAMP);
        });
    }

    public function down() {
        Schema::dropIfExists('migrations');
    }
};

// CREATE TABLE Persons (
//     PersonID int,
//     LastName varchar(255),
//     FirstName varchar(255),
//     Address varchar(255),
//     City varchar(255)
// );
