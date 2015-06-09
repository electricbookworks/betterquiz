<?php

/**
 * Simplifies inclusion of Javascript and CSS into the
 * admin site build process.
 */
class AdminSite extends Site {
	public function __construct() {
		parent::__construct();
		$this->AddScripts(array(
			'/bower_components/platform/platform.js',
			'/bower_components/jquery/dist/jquery.js',
			'/bower_components/underscore/underscore.js',
			'/bower_components/modernizr/modernizr.js',
			'/bower_components/foundation/js/foundation.js',
			'/bower_components/hogan/lib/template.js',
			'/bower_components/js-signals/dist/signals.js',
			'/bower_components/crossroads/dist/crossroads.js',
			'/bower_components/hasher/dist/js/hasher.js',
			'/bower_components/messageformat/messageformat.js',
			'/bower_components/dropzone/dist/dropzone.js',
			'https://login.persona.org/include.js',
			'/js/admin.js',
			// '/admin/jsonrpc.php'
			));
		$this->AddStyles(array(
			"/css/admin/font-awesome-4.3.0/css/font-awesome.min.css",
			// '//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css',
			'/bower_components/dropzone/dist/dropzone.css',
			'/css/admin/admin.css'
			));
		$this->AddImports(array(
			'/components/bq-components.all.html'));
	}
}