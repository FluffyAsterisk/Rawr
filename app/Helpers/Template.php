<?php

namespace App\Helpers;

use App\Core\App;
use App\Exceptions\MissingTemplateException;

class Template {
    public static $data;
    private static $cacheEnabled = False;

    public static function prepare($file): string {
		return self::cache($file);
    }

    public static function cache($filename): string {
	$file = App::views_path().$filename.'.php';

	file_exists($file) ?: throw new MissingTemplateException(sprintf( 'File %s doesn\'t exist', $file));

	if (!file_exists(App::cache_path())) {
	    mkdir(App::cache_path(), 0774);
	}

	$filename = $filename.'.php';
	$cachedFile = App::cache_path().str_replace(array('/','.html'), array('_', '.php'), $filename);

	if (true || !self::$cacheEnabled || !file_exists($cachedFile) || filemtime($cachedFile) < filemtime($file))
	{
	    $code = self::includeFiles($filename);
	    $code = self::compileTemplate($code);
	    file_put_contents($cachedFile, $code);
	}
    
	return $cachedFile;
    }

    public static function compileTemplate($code):string {
		$code = self::compilePhp($code);
		$code = self::compileEcho($code);
	    $code = self::compilePrettyPrint($code);
		return $code;
    }

    private static function compilePhp($code):string {
	return preg_replace('~^\s+{\s*([^{\s].+?)\s*}\s+?$~ism', '<?php $1 ?>', $code);
    }

    private static function compileEcho($code):string {
		// .+?{{\s+?(?!pretty)(.+)\s+?}}
	return preg_replace('~{{\s+?(?!pretty)(.+)\s+?}}~im', '<?php echo $1 ?>', $code);
    }

    private static function compilePrettyPrint($code):string {
	return preg_replace('~^\s+?{{\s*pretty ([^{\s].+?)\s*}}\s+?$~ism', '<pre style="text-align:left;"><?php print_r($1); ?></pre>', $code);
    }

    private static function includeFiles($filename): string {
	$code = file_get_contents(App::views_path().$filename);
	$matches = array();
	preg_match_all('~^{{{\s*(extends|include)? ?\'?([^{\s].+?)\'?\s*}}}(\s+)?$~ism', $code, $matches, PREG_SET_ORDER);

	foreach ($matches as $value) {
    	$code = str_replace($value[0], self::includeFiles($value[2]), $code);
	}

	return $code;
    }

}
