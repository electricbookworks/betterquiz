<?php

class WPOLaitTest {
	public static function Add($x, $y) {
		return $x + $y;
	}
	public static function Version() {
		return "0.0.1";
	}
}

add_action('wpolait_register', function($wp) {
	$wp->register('WPOLaitTest');
});
add_action('wp_enqueue_scripts', function() {
	wp_enqueue_script('wpolait_test',plugins_url('/wpolait/wpolait_test.js'), array());
});