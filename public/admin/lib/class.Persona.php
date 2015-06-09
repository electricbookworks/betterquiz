<?php

/**
 * Persona handles the Persona assertion when logging
 * in with Mozilla Persona.
 */
class Persona {
	public static function Js($user=null) {
		$checkAssertUrl = Site::url('/admin/login_assert.php');
		$logoutUrl = Site::url('/admin/logout.php');
		$email = (null==$user) ? "null" : ('"' . $user->Email . '"');
	return <<<EOJS
	navigator.id.watch({
		loggedinUser: $email,
		onlogin: function(assert) {
			document.location = '$checkAssertUrl' + '?assertion=' + encodeURIComponent(assert) + 
				'&audience=' + encodeURIComponent(document.location.origin);
		},
		onlogout: function() {
			document.location = '$logoutUrl';
		}
	});
EOJS;
	}

	public static function Assert($assertion, $audience) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://verifier.login.persona.org/verify");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,
			http_build_query(array("assertion"=>$assertion, "audience"=>$audience)));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$res = curl_exec($ch);
		if (!$res) {
			$err = curl_error($ch);
			curl_close($ch);
			error_log("ERROR fetching persona_assertion: " . $err);
			throw new Exception($err);
		}
		curl_close($ch);

		$js = json_decode($res);
		if (FALSE===$js) {
			error_log("ERROR json decoding: " . $res);
			throw new Exception("ERROR json decoding: " . $res);
		}
		if ("failure" == $js->status ) {
			error_log("PERSONA assertion failure: " . $js->reason);
			throw new Exception("PERSONA assertion failure: " . $js->reason);
		}
		if ("okay" != $js->status) {
			error_log("PERSONA unknown status: " . $res);
			throw new Exception("PERSONA unknown status: " . $res);
		}
		if (!BQAdmin::IsAdmin($js->email)) {
			error_log("Not admin user attempted to login as admin: " . $js->email);
			throw new Exception($js->email . " is not a recognized site administrator.");			
		}
		return new AdminUser($js->email, $js->issuer);
	}
}