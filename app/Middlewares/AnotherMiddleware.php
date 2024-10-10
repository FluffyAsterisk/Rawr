<?php

namespace App\Middlewares;
use App\Core\Middleware;

class AnotherMiddleware extends Middleware {
    public function handle() {
        if (true) {
            return $this->pass();
        }

        $this->fail("Middlewares is COOOOOOL", 124534324132);
    }
}