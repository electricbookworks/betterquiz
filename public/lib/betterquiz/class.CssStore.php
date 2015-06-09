<?php
/**
 * @file
 * CssStore contains the CSS stylesheet to include
 * for this quiz. This is passed by parameter 'css'
 * when the user is forwarded to the site.
 */
class CssStore {
	public static function StoreSession($c) {
		SessionStore::Store("css", $c);
	}
	public static function ClearSession() {
		SessionStore::Clear("css");
	}
	public static function GetSession() {
		return SessionStore::Get("css");
	}
	/**
	 * Link adds the CSS link to the desired stylesheet
	 * if the css has been set by the caller.
	 */
	public static function Link() {
		$css = self::GetSession();
		if (0 < strlen($css)) {
			echo '<link rel="stylesheet" href="' . $css . '">';
		}
	}
}