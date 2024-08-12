<?php

namespace App\Helpers;

class Request {
    public static function capture(): array {
		$meth = $_SERVER['REQUEST_METHOD'];
		if ($meth == 'GET') {
			return array(
				'METHOD' => $_SERVER['REQUEST_METHOD'],
				'URI' => $_SERVER['REQUEST_URI'],
				'USER_AGENT' => $_SERVER['HTTP_USER_AGENT'],
			);
		} elseif ($meth == 'POST') {
			$r = [];
			foreach ($_POST as $key=>$value) { $r[$key] = $value; }
			return $r;
		}
    }
}
