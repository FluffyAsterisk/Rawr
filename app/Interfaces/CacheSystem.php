<?php


namespace App\Interfaces;

interface CacheSystem {
    public function serialize();
    public function deserialize();
}