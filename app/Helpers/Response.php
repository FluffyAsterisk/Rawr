<?php

namespace App\Helpers;

class Response {
    const NOT_FOUND = 404;
    const FORBIDDEN = 403;

    public static function send($response_code, $message) {
        http_response_code($response_code);
    }
}