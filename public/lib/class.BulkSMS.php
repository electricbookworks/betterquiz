<?php

/**
 * BulkSMS provides a simple interface to BulkSMS for
 * sending SMS's.
 */
class BulkSMS {
	/**
	 * Send send the msg parameter as a 7-bit SMS to the given MSISDN.
	 */
	public function Send($msisdn, $msg) {
		/*
		* Please see the FAQ regarding HTTPS (port 443) and HTTP (port 80/5567)
		*/
		$url = 'https://bulksms.2way.co.za/eapi/submission/send_sms/2/2.0';
		$port = 443;

		/*
		* A 7-bit GSM SMS message can contain up to 160 characters (longer messages can be
		* achieved using concatenation).
		*
		* All non-alphanumeric 7-bit GSM characters are included in this example. Note that Greek characters,
		* and extended GSM characters (e.g. the caret "^"), may not be supported
		* to all networks. Please let us know if you require support for any characters that
		* do not appear to work to your network.
		*/
		$seven_bit_msg = $msg;
		/*
		* These error codes will be retried if encountered. For your final application,
		* you may wish to include statuses such as "25: You do not have sufficient credits"
		* in this list, and notify yourself upon such errors. However, if you are writing a
		* simple application which does no queueing (e.g. a Web page where a user simply
		* POSTs a form back to the page that will send the message), then you should not
		* add anything to this list (perhaps even removing the item below), and rather just
		* display an error to your user immediately.
		*/
		$transient_errors = array(
		40 => 1 # Temporarily unavailable
		);

		/*
		* Sending 7-bit message
		*/
		$post_body = self::seven_bit_sms( $this->getUsername(), $this->getPassword(), $seven_bit_msg, $msisdn );
		$result = self::send_message( $post_body, $url, $port );
		if( $result['success'] ) {
			return TRUE;
		}
		else {
			echo "ERROR: ".  self::formatted_server_response( $result );
			throw new Exception(self::formatted_server_response( $result ) );
		}
	}

	/**
	 * Return the username to use on the BulkSMS calls.
	 * This value should be set in lib/settings.local.php
	 */
	public function getUsername() {
		return BULKSMS_USERNAME;
	}
	/**
	 * Return the password to use on the BulkSMS calls.
	 * This value should be set in lib/settings.local.php
	 */
	public function getPassword() {
		return BULKSMS_PASSWORD;
	}



	protected static function formatted_server_response( $result ) {
	  $this_result = "";

	  if ($result['success']) {
	    $this_result .= "Success: batch ID " .$result['api_batch_id']. "API message: ".$result['api_message']. "\nFull details " .$result['details'];
	  }
	  else {
	    $this_result .= "Fatal error: HTTP status " .$result['http_status_code']. ", API status " .$result['api_status_code']. " API message " .$result['api_message']. " full details " .$result['details'];

	    if ($result['transient_error']) {
	      $this_result .=  "This is a transient error - you should retry it in a production environment";
	    }
	  }
	  return $this_result;
	}

	protected static function send_message ( $post_body, $url, $port ) {
	  /*
	  * Do not supply $post_fields directly as an argument to CURLOPT_POSTFIELDS,
	  * despite what the PHP documentation suggests: cUrl will turn it into in a
	  * multipart formpost, which is not supported:
	  */

	  $ch = curl_init( );
	  curl_setopt ( $ch, CURLOPT_URL, $url );
	  curl_setopt ( $ch, CURLOPT_PORT, $port );
	  curl_setopt ( $ch, CURLOPT_POST, 1 );
	  curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
	  curl_setopt ( $ch, CURLOPT_POSTFIELDS, $post_body );
	  // Allowing cUrl funtions 20 second to execute
	  curl_setopt ( $ch, CURLOPT_TIMEOUT, 20 );
	  // Waiting 20 seconds while trying to connect
	  curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, 20 );

	  $response_string = curl_exec( $ch );
	  $curl_info = curl_getinfo( $ch );

	  $sms_result = array();
	  $sms_result['success'] = 0;
	  $sms_result['details'] = '';
	  $sms_result['transient_error'] = 0;
	  $sms_result['http_status_code'] = $curl_info['http_code'];
	  $sms_result['api_status_code'] = '';
	  $sms_result['api_message'] = '';
	  $sms_result['api_batch_id'] = '';

	  if ( $response_string == FALSE ) {
	    $sms_result['details'] .= "cURL error: " . curl_error( $ch ) . "\n";
	  } elseif ( $curl_info[ 'http_code' ] != 200 ) {
	    $sms_result['transient_error'] = 1;
	    $sms_result['details'] .= "Error: non-200 HTTP status code: " . $curl_info[ 'http_code' ] . "\n";
	  }
	  else {
	    $sms_result['details'] .= "Response from server: $response_string\n";
	    $api_result = explode( '|', $response_string );
	    $status_code = $api_result[0];
	    $sms_result['api_status_code'] = $status_code;
	    $sms_result['api_message'] = $api_result[1];
	    if ( count( $api_result ) != 3 ) {
	      $sms_result['details'] .= "Error: could not parse valid return data from server.\n" . count( $api_result );
	    } else {
	      if ($status_code == '0') {
	        $sms_result['success'] = 1;
	        $sms_result['api_batch_id'] = $api_result[2];
	        $sms_result['details'] .= "Message sent - batch ID $api_result[2]\n";
	      }
	      else if ($status_code == '1') {
	        # Success: scheduled for later sending.
	        $sms_result['success'] = 1;
	        $sms_result['api_batch_id'] = $api_result[2];
	      }
	      else {
	        $sms_result['details'] .= "Error sending: status code [$api_result[0]] description [$api_result[1]]\n";
	      }
	    }
	  }
	  curl_close( $ch );

	  return $sms_result;
	}

	protected static function seven_bit_sms ( $username, $password, $message, $msisdn ) {
	  $post_fields = array (
	  'username' => $username,
	  'password' => $password,
	  'message'  => self::character_resolve( $message ),
	  'msisdn'   => $msisdn
	  );

	  return self::make_post_body($post_fields);
	}

	protected static function unicode_sms ( $username, $password, $message, $msisdn ) {
	  $post_fields = array (
	  'username' => $username,
	  'password' => $password,
	  'message'  => self::string_to_utf16_hex( $message ),
	  'msisdn'   => $msisdn,
	  'dca'      => '16bit'
	  );

	  return self::make_post_body($post_fields);
	}

	protected static function eight_bit_sms( $username, $password, $message, $msisdn ) {
	  $post_fields = array (
	  'username' => $username,
	  'password' => $password,
	  'message'  => $message,
	  'msisdn'   => $msisdn,
	  'dca'      => '8bit'
	  );

	  return self::make_post_body($post_fields);

	}

	protected static function make_post_body($post_fields) {
	  $stop_dup_id = self::make_stop_dup_id();
	  if ($stop_dup_id > 0) {
	    $post_fields['stop_dup_id'] = self::make_stop_dup_id();
	  }
	  $post_body = '';
	  foreach( $post_fields as $key => $value ) {
	    $post_body .= urlencode( $key ).'='.urlencode( $value ).'&';
	  }
	  $post_body = rtrim( $post_body,'&' );

	  return $post_body;
	}

	protected static function character_resolve($body) {
	  $special_chrs = array(
	  'Δ'=>'0xD0', 'Φ'=>'0xDE', 'Γ'=>'0xAC', 'Λ'=>'0xC2', 'Ω'=>'0xDB',
	  'Π'=>'0xBA', 'Ψ'=>'0xDD', 'Σ'=>'0xCA', 'Θ'=>'0xD4', 'Ξ'=>'0xB1',
	  '¡'=>'0xA1', '£'=>'0xA3', '¤'=>'0xA4', '¥'=>'0xA5', '§'=>'0xA7',
	  '¿'=>'0xBF', 'Ä'=>'0xC4', 'Å'=>'0xC5', 'Æ'=>'0xC6', 'Ç'=>'0xC7',
	  'É'=>'0xC9', 'Ñ'=>'0xD1', 'Ö'=>'0xD6', 'Ø'=>'0xD8', 'Ü'=>'0xDC',
	  'ß'=>'0xDF', 'à'=>'0xE0', 'ä'=>'0xE4', 'å'=>'0xE5', 'æ'=>'0xE6',
	  'è'=>'0xE8', 'é'=>'0xE9', 'ì'=>'0xEC', 'ñ'=>'0xF1', 'ò'=>'0xF2',
	  'ö'=>'0xF6', 'ø'=>'0xF8', 'ù'=>'0xF9', 'ü'=>'0xFC',
	  );

	  $ret_msg = '';
	  if( mb_detect_encoding($body, 'UTF-8') != 'UTF-8' ) {
	    $body = utf8_encode($body);
	  }
	  for ( $i = 0; $i < mb_strlen( $body, 'UTF-8' ); $i++ ) {
	    $c = mb_substr( $body, $i, 1, 'UTF-8' );
	    if( isset( $special_chrs[ $c ] ) ) {
	      $ret_msg .= chr( $special_chrs[ $c ] );
	    }
	    else {
	      $ret_msg .= $c;
	    }
	  }
	  return $ret_msg;
	}

	/*
	* Unique ID to eliminate duplicates in case of network timeouts - see
	* EAPI documentation for more. You may want to use a database primary
	* key. Warning: sending two different messages with the same
	* ID will result in the second being ignored!
	*
	* Don't use a timestamp - for instance, your application may be able
	* to generate multiple messages with the same ID within a second, or
	* part thereof.
	*
	* You can't simply use an incrementing counter, if there's a chance that
	* the counter will be reset.
	*/
	protected static function make_stop_dup_id() {
	  return 0;
	}

	protected static function string_to_utf16_hex( $string ) {
	  return bin2hex(mb_convert_encoding($string, "UTF-16", "UTF-8"));
	}
	
}