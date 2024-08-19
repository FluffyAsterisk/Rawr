<?php

namespace App\Interfaces;

interface CacheFrontInterface {
    public function encode($data);
    public function decode($data);
}