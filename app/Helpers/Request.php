<?php

namespace App\Helpers;

class Request {
	public function __construct() {}

    public static function capture(): array {
		$meth = $_SERVER['REQUEST_METHOD'];

		if ($meth == 'GET') 
		{
			return [
				'METHOD' => $_SERVER['REQUEST_METHOD'],
				'URI' => $_SERVER['REQUEST_URI'],
				'USER_AGENT' => $_SERVER['HTTP_USER_AGENT'],
			];
		} 
		elseif ($meth == 'POST') 
		{
			return $_POST;
		}

		return [];
    }
}
