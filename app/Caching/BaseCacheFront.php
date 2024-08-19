<?php

namespace App\Caching;

use App\Interfaces\CacheFrontInterface;

class BaseCacheFront implements CacheFrontInterface {
    public function encode($data): string {
        if ( !is_string($data) ) { $data = serialize($data); }

        return base64_encode($data);
    }

    public function decode($data): string {
        return base64_decode($data);
    }
}