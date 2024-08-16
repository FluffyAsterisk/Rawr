<?php

namespace App\Helpers;

class Sanitizer {
    public function sanitizeString($string) {
        return htmlspecialchars( strip_tags( trim( $string ) ) );
    }
}