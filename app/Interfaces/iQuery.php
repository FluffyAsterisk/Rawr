<?php

namespace App\Interfaces;

interface iQuery {
    public function setTable(string $table): iQuery;
    public function write(): array|string;
}