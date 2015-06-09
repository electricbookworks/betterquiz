<?php
/**
 * @file
 * ReturnSite contains the site to which the user 
 * should return after completing the quiz.
 */
class ReturnSite {
	public static function StoreSession($r) {
		SessionStore::Store("return_site", $r);
	}
	public static function ClearSession() {
		SessionStore::Clear("return_site");
	}
	public static function GetSession() {
		return SessionStore::Get("return_site");
	}
}