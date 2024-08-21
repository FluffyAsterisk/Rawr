<?php

namespace App\Interfaces;

interface CacheFrontInterface {
    public function encode(string $data): string|bool;
    public function decode(string $data): string|bool;
    public function setCacheType(\App\Enums\CacheType $type): void;
}