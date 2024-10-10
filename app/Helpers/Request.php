<?php

namespace App\Helpers;

class Request {
	public function __construct() {}

    public function capture(): array {
		  return $_SERVER;
    }

    public function getPath(): string {
        return parse_url($_SERVER["REQUEST_URI"])['path'];
    }
}
