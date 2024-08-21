<?php

namespace App\Caching;

use App\Interfaces\CacheFrontInterface;

class BaseCacheFront implements CacheFrontInterface {
    public function encode(string $data): string|bool {
        return base64_encode($data);
    }

    public function decode(string $data): string|bool {
        return base64_decode($data);
    }
}