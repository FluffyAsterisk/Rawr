<?php

namespace App\Caching;

use App\Interfaces\CacheFrontInterface;
use App\Enums\CacheType;


class BaseCacheFront implements CacheFrontInterface {
    private static CacheType $cacheType = CacheType::ENCODED;

    public function encode(string $data): string|bool {
        return self::$cacheType == CacheType::ENCODED ? base64_encode($data) : $data;
    }

    public function decode(string $data): string|bool {
        return self::$cacheType == CacheType::ENCODED ? base64_decode($data) : $data;
    }

    public function setCacheType(CacheType $type): void {
        self::$cacheType = $type;
    }
}