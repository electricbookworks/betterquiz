<?php
/*
 * This function parses the settings file and allows setting particular configuration values
 * for betterquiz without having to edit the file itself. It's designed to work without
 * editing the settings file, so could be problematic if the file has been hand-edited. Your mileage
 * may vary!
 */
const BQSETTING_STATE_SCANNING = 1;					// general scanning
const BQSETTING_STATE_NEW = 2;						// we've seen 'new'
const BQSETTING_STATE_NEW_DATABASE_HOST = 3;		// we've seen 'new Database', so we're looking for Host
const BQSETTING_STATE_NEW_DATABASE_USER = 4;		// we've seen Host, so we're looking for User
const BQSETTING_STATE_NEW_DATABASE_PASSWORD = 5;	// we've seen User, so we're looking for Password
const BQSETTING_STATE_NEW_DATABASE_DATABASE = 6;	// we've seen Password, so we're looking for Database
const BQSETTING_STATE_DEFINE = 7;					// we've seen define
const BQSETTING_STATE_DEFINE_VALUE = 8;				// we've seen the key, we're looking for the define Value

// writeBetterquizSettings parses the settings file, and replaces the values
// for the database or the SMS clients with the values defined in $setValues. It returns
// the values it finds/sets.
function parseBetterquizSettings(array $setValues) : array {
	$gotValues = [];
	$settingsFile = "public/lib/settings.local.php";
	if (!file_exists($settingsFile)) {
		$source = file_get_contents(dirname($settingsFile) . "/settings.template.php");
	} else {
		$source = file_get_contents($settingsFile);
	}
	$out = fopen($settingsFile, "w");

	$tokens = token_get_all($source);
	$state = BQSETTING_STATE_SCANNING;
	$defineKey = '';

	foreach ($tokens as $token) {
	   if (is_string($token)) {
	       // simple 1-character token
	       fputs($out, $token);
	   } else {
	       // token array
	       list($id, $text) = $token;

	       switch ($id) { 
	           case T_COMMENT: 
	             break;
	           case T_DOC_COMMENT:
	              break;
	           default:
	           	switch ($id) {
	           		case 262: // define / keyword (fn. call perhaps) (eg. Database)
	           			switch($state) {
	           				case BQSETTING_STATE_SCANNING: 				
	           					if ("define"==$text) {
	           				  	 $state = BQSETTING_STATE_DEFINE;
	           				    }
           				    break;
	           				case BQSETTING_STATE_NEW:
	           					if ("Database"==$text) {
	           						$state = BQSETTING_STATE_NEW_DATABASE_HOST;
	           					} else {
	           						$state = BQSETTING_STATE_SCANNING;
	           					}
	           					break;
	           			}
	           			break;
	           		case 269: // string value
	           			switch ($state) {
	           				case BQSETTING_STATE_DEFINE:
	           					$clearKey = json_decode($text);
	           					if ($clearKey == "PANACEA_USERNAME" || $clearKey=="PANACEA_PASSWORD" || $clearKey=="BULKSMS_USERNAME" || $clearKey=="BULKSMS_PASSWORD") {
	           						$defineKey = $clearKey;
	           						$state = BQSETTING_STATE_DEFINE_VALUE;
	           					} else {
	           						$state = BQSETTING_STATE_SCANNING;
	           					}
	           					break;
	           				case BQSETTING_STATE_DEFINE_VALUE;
	           					if (array_key_exists($defineKey, $setValues)) {
	           						$text = json_encode($setValues[$defineKey]);
	           					}
	           					$gotValues[$defineKey] = $text;
	           				  $state = BQSETTING_STATE_SCANNING;
	           				  break;
	           				case BQSETTING_STATE_NEW_DATABASE_HOST:
	           					if (array_key_exists('DBHOST', $setValues)) {
	           						$text = json_encode($setValues['DBHOST']);
	           					}
	           					$gotValues['DBHOST'] = $text;
	           					$state = BQSETTING_STATE_NEW_DATABASE_USER;
	           					break;
	           				case BQSETTING_STATE_NEW_DATABASE_USER:
	           					if (array_key_exists('DBUSERNAME', $setValues)) {
	           						$text = json_encode($setValues['DBUSERNAME']);
	           					}
	           					$gotValues['DBUSERNAME'] = $text;
	           					$state = BQSETTING_STATE_NEW_DATABASE_PASSWORD;
	           					break;
	           				case BQSETTING_STATE_NEW_DATABASE_PASSWORD:
	           					if (array_key_exists('DBPASSWORD', $setValues)) {
	           						$text = json_encode($setValues['DBPASSWORD']);
	           					}
	           					$gotValues['DBPASSWORD'] = $text;
	           					$state = BQSETTING_STATE_NEW_DATABASE_DATABASE;
	           					break;
	           				case BQSETTING_STATE_NEW_DATABASE_DATABASE:
	           					if (array_key_exists('DBDATABASE', $setValues)) {
	           						$text = json_encode($setValues['DBDATABASE']);
	           					}
	           					$gotValues['DBDATABASE'] = $text;
	           					$state = BQSETTING_STATE_SCANNING;
	           					break;	           					
	           			}
	           			break;
	           		case 284: // keyword
	           			if ("new"==$text) {
	           				$state = BQSETTING_STATE_NEW;
	           			}
	           			break;
	           		case 392: // whitespace
	           		  break;
	           		default:
	           			// nothing to do on other cases
	           	}
	       }
	       fputs($out, $text);
	   }
	}
	fclose($out);
	return $gotValues;
}
