<?php

namespace App\Helpers;

class Template {
    public $data;
    private $cacheEnabled = False;
	// Path to rendered file
	private $filePath;

	public function __construct(private \App\Core\App $app) {}

	public function filePath(): string {
		return $this->filePath;
	}

    public function prepare($file) {
		$this->filePath = $this->cache($file);
    }

    private function cache($filename): string {
		$f = str_contains($filename, '.php') ? $filename : $filename . '.php';

		$file = $this->app->views_path().$f;
		$cache_path = $this->app->cache_path();

		file_exists($file) ?: throw new \App\Exceptions\MissingTemplateException(sprintf( 'File %s doesn\'t exist', $file));

		if (!file_exists($cache_path)) {
			mkdir($cache_path, 0774);
		}

		$filename = $filename.'.php';
		$cachedFile = $cache_path.str_replace(array('/','.html'), array('_', '.php'), $filename);

		if (true || !$this->$cacheEnabled || !file_exists($cachedFile) || filemtime($cachedFile) < filemtime($file))
		{
			$code = $this->includeFiles($filename);
			$code = $this->compileTemplate($code);
			file_put_contents($cachedFile, $code);
		}
    
		return $cachedFile;
    }

    private function compileTemplate($code):string {
		$code = $this->compilePhp($code);
		$code = $this->compileEcho($code);
	    $code = $this->compilePrettyPrint($code);
		return $code;
    }

    private function compilePhp($code):string {
		return preg_replace('~^\s+{\s*([^{\s].+?)\s*}\s+?$~ism', '<?php $1 ?>', $code);
    }

    private function compileEcho($code):string {
		return preg_replace('~{{\s+?(?!pretty)(.+)\s+?}}~im', '<?php echo $1 ?>', $code);
    }

    private function compilePrettyPrint($code):string {
		return preg_replace('~^\s+?{{\s*pretty ([^{\s].+?)\s*}}\s+?$~ism', '<pre style="text-align:left;"><?php print_r($1); ?></pre>', $code);
    }

    private function includeFiles($filename): string {
		$code = file_get_contents($this->app->views_path().$filename);
		$matches = array();
		preg_match_all('~^{{{\s*(extends|include)? ?\'?([^{\s].+?)\'?\s*}}}(\s+)?$~ism', $code, $matches, PREG_SET_ORDER);

		foreach ($matches as $value) {
			$code = str_replace($value[0], $this->includeFiles($value[2]), $code);
		}

		return $code;
    }

}
