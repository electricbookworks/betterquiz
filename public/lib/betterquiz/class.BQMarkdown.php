<?php

# Install PSR-0-compatible class autoloader
spl_autoload_register(function($class){
	require preg_replace('{\\\\|_(?!.*\\\\)}',  DIRECTORY_SEPARATOR, ltrim("php-markdown/" . $class, '\\')).'.php';
});
use \Michelf\Markdown;


/**
 * BQMarkdown is a wrapper around whichever Markdown processor is used to
 * transform Markdown to HTML
 */
class BQMarkdown {
	public static function render($in) {
		$html = Markdown::defaultTransform($in);
		// Remove any leading / trailing <p></p> wrapper
		$html = trim($html);
		$html = preg_replace("&^<p>(.*)</p>$&", "$1", $html);
		return $html;
	}
}