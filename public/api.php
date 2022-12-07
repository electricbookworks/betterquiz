<?php

include_once('lib/include.php');

class BQApi {
	static function HiThere() : string {
		return "Hi there";
	}
	static function CheckDatabase() : bool {
		$db = Database::Get();
		if (!$db) {
			return false;
		}
		return true;
	}
	/** 
	 * IsInstallActive returns true if installation can happen. This
	 * is the case if the lib.settings.local.php file DOES NOT exist.
	 */
	public static function IsInstallActive() : bool {
		return !file_exists(dirname(__FILE__) . '/lib.settings.local.php');
	}
	public static function Panacea() : array {
		return array(
			"PANACEA_USERNAME" => defined('PANACEA_USERNAME') ? PANACEA_USERNAME : "",
			"PANACEA_PASSWORD" => defined('PANACEA_PASSWORD') ? PANACEA_PASSWORD : "",
		);
	}
	public static function CheckConfigureDatabase(
		string $server, string $user, 
		string $password, string $database) {
		if (!self::IsInstallActive()) {
			return ["error_code"=>100, "error"=>"Installation not in progress."];
		}
		try {
			$db = new Database($server, $user, $password, $database);
		} catch (RuntimeException $e) {
			error_log("new Database generated error " . $e->getMessage());
			return ["error_code"=>110, "error"=>$e->getMessage()];
		}
		return ["ok"=>"Database is configured."];
	}
}

$api = new PHPOlait(new BQApi());
$api->handle();