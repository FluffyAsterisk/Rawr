<?php

namespace App\Middlewares;
use App\Core\Middleware;

class FunMiddleware extends Middleware {
    public function handle() {
        if (true) {
            return $this->pass();
        }

        $this->fail("Middleware failed, lol", 99999);
    }
}