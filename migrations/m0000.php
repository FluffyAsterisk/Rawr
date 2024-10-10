<?php

namespace App\Migrations;

use App\Enums\MySQLDefault;
use App\Migrations\Blueprint;
use App\Migrations\Migration;
use App\Migrations\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('migrations', function(Blueprint $table) {
            $table->string('name')->length(75)->primary();
            $table->int('batch');
            $table->timestamp('migrated_at')->default(MySQLDefault::CURRENT_TIMESTAMP);
        });
    }

    public function down() {
        Schema::dropIfExists('migrations');
    }
};