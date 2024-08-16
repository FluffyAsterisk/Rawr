<?php

namespace App\Core;

class View {
    public function __construct(private $template) {}
	
    public function render($file, $data=[]) {
        try {
	        $this->template->prepare($file);
        } catch (\App\Exceptions\MissingTemplateException $e) {
            echo($e->getMessage());
            die();
        }

		extract($data);
		require $this->template->filePath();
    }

}
