<?php

namespace App\Helpers;
use App\Exceptions\MissingTemplateException;

class Template {
	private $filename;
    private $cacheEnabled;
	private $ttl;
	private $fileContent;

	public function __construct(private \App\Core\App $app, private \App\Helpers\Cache $cache) {}

    public function init(string $filename, bool $enableCache = False, int $ttl = 500) {
		$this->filename = str_contains($filename, '.php') ? $filename : "$filename.php";
		$this->ttl = $ttl;
		$this->cacheEnabled = $enableCache;

		$this->fileContent = $this->compile();
    }

	public function render($data = []) {
		$cachingEngine = $this->app->cache_params()['CACHE_ENGINE'];
		$file_path = $this->app->cache_path() . $this->filename;
		
		file_put_contents($file_path, $this->fileContent);

		$this->requireFile($file_path, $data);

		unlink($file_path);

		return true;
	}

	private function requireFile($file_path, $data = []) {
		extract($data);

		require_once $file_path;
	}

    private function compile(): string {
		$filename = $this->filename;
		$file_path = $this->app->views_path().$filename;
	
		if (
			$this->cacheEnabled &&
			$this->cache->has($filename) && 
			filemtime($file_path) < time() - $this->ttl
		)
		{
			return $this->cache->get($filename); 
		}

		file_exists($file_path) ?: throw new MissingTemplateException(sprintf( 'File %s doesn\'t exist', $file_path));

		$code = $this->includeFiles($filename);
		$code = $this->compileTemplate($code);

		if ($this->cacheEnabled) { $this->cache->set($filename, $code, $this->ttl); }

		return $code;
    }

    private function compileTemplate($code):string {
		$code = $this->compilePhp($code);
		$code = $this->compileEcho($code);
	    $code = $this->compilePrettyPrint($code);
		return $code;
    }

    private function compilePhp($code):string {
		return preg_replace('~(?!{).{\s(.+)\s}~im', ' <?php $1 ?>', $code);
    }

    private function compileEcho($code):string {
		return preg_replace('~{{\s+?(?!pretty)(.+)\s+?}}~im', '<?php echo $1 ?>', $code);
    }

    private function compilePrettyPrint($code):string {
		return preg_replace('~^\s+?{{\s*pretty ([^{\s].+?)\s*}}\s+?$~im', '<pre style="text-align:left;"><?php print_r($1); ?></pre>', $code);
    }

    private function includeFiles($filename): string {
		$code = file_get_contents($this->app->views_path().$filename);
		$matches = [];
		preg_match_all('~^{{{\s*(extends|include)? ?\'?([^{\s].+?)\'?\s*}}}(\s+)?$~ism', $code, $matches, PREG_SET_ORDER);

		foreach ($matches as $value) {
			$code = str_replace($value[0], $this->includeFiles($value[2]), $code);
		}

		return $code;
    }

}
