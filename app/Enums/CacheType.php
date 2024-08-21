<?php

namespace App\Enums;

enum CacheType: int {
    case RAW = 0;
    case ENCODED = 1;

    public static function fromName(string $name): CacheType {
        foreach (self::cases() as $type) {
            if ($name === $type->name) {
                return $type;
            }
        }

        throw new \ValueError("$name is not a valid value for caching " . self::class);
    }
}