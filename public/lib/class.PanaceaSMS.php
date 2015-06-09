<?php

include_once("class.PanaceaApi.php");

/**
 * PanaceaSMS provides a simple interface to Panacea for
 * sending SMS's.
 */
class PanaceaSMS {
	public function Send($to, $msg) {
		$p = new PanaceaApi();
		$p->setUsername($this->getUsername());
		$p->setPassword($this->getPassword());
		if (!($p->message_send("+". $to, $msg))) {
			die("Error: " . $p->getError());
		}
		die("PanaceaSMS sent reminder");
	}
	/**
	 * Return the username to use on the PanaceaAPI calls.
	 * This value should be set in lib/settings.local.php
	 */
	public function getUsername() {
		return PANACEA_USERNAME;
	}
	/**
	 * Return the password to use on the PanaceaAPI calls.
	 * This value should be set in lib/settings.local.php
	 */
	public function getPassword() {
		return PANACEA_PASSWORD;
	}
}