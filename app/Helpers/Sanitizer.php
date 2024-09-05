<?php

namespace App\Helpers;

class Sanitizer {
    private const wrapSmbl = '@';

    public function getWrapSmbl():string {
        return self::wrapSmbl;
    }

    public function sanitizeString($string): string {
        return htmlspecialchars( strip_tags( trim( $string ) ) );
    }

    public function wrapString(int|string $string): string {
        return self::wrapSmbl . $string . self::wrapSmbl;
    }

    public function unwrapString(int|string $string): string {
        $s = preg_replace('~^'.self::wrapSmbl.'~m', '', $string);
        return preg_replace('~'.self::wrapSmbl.'$~m', '', $s);
    }

    public function customWrap(string $string, string $smbl) {
        return $smbl . $string . $smbl;
    }

    public function customUnwrap(string $string, string $smbl) {
        $s = preg_replace('~^'.$smbl.'~m', '', $string);
        return preg_replace('~'.$smbl.'$~m', '', $s);
    }
}