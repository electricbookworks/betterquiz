<?php

/**
 * Flash is used for temporary messages that appear on the site.
 *  For instance, it is used
 * if a username or password are not recognized.
 * To create a Flash just construct a new Flash: 
 * <code>new Flash('a message');</code>.
 * The Flashes will appear on the next page load 
 * (they are stored in the session variable).
 *
 */
class Flash {
	var $_class;
	var $_msg;

	/** Utility method New creates a new Flash message, but doesn't return it. */
	public static function New($msg, $class="info") : void {
		$_ = new Flash($msg, $class);
	}

	public function __construct($msg, $class="info") {
		SessionStore::Start();
		$this->_class = $class;
		$this->_msg = $msg;

		if (array_key_exists("_flash", $_SESSION)) {
			$flashes = $_SESSION["_flash"];
		} else {
			$flashes = array();
		}
		$flashes[] = $this;
		$_SESSION["_flash"] = $flashes;
		error_log("Set _flash = " . json_encode($flashes));
		SessionStore::Commit();
	}

	public static function Load() {
		@session_start();
		$flashes = array();
		if (array_key_exists("_flash", $_SESSION)) {
			$flashes = $_SESSION["_flash"];
		}
		unset($_SESSION["_flash"]);
		session_commit();
		return $flashes;
	}

	public static function Render() {
		$flashes = self::Load();
		if (0<count($flashes)) {
			echo '<div class="flashes">';
			foreach ($flashes as $f) {
				$f->renderFlash();
			}
			echo '</div>';
		}
	}

	public function renderFlash() {
		echo '<div class="flash ' . $this->_class . '">';
		echo $this->_msg;
		echo '</div>';
	}
}