<?php

namespace App\Interfaces;

interface iQuery {
    public function addTable(string $table): iQuery;
    public function write(): array|string;
}