#!/usr/bin/env php
<?php
// We start by bootstrapping ourselves by downloading and installing composer, and then
// running composer to install any dependencies.
$docoptFile = 'vendor/docopt/docopt/src/docopt.php';
if (!file_exists($docoptFile)) {
	if (!file_exists('composer.phar')) {
		$stdout = [];
		$resultCode = 0;
		echo "Installing composer..." . PHP_EOL;
		exec('/usr/bin/env bash -- ./lib-cli/install-composer.sh', $stdout, $resultCode);
		if (0!=$resultCode) {
			echo "Installing composer failed:\n" . 
				implode(PHP_EOL, $stdout) . 
				"\nPlease see https://getcomposer.org/download/ to download composer.phar" . PHP_EOL;
			exit($resultCode);
		}
	}
	echo "Running composer.phar install" . PHP_EOL;
	exec('php composer.phar install',$stdout, $resultCode);
	if (0!=$resultCode) {
		echo "Running `php composer.phar install` failed:\n" . 
			implode(PHP_EOL, $stdout) . 
			"\nPlease try it yourself and debug any issues.".PHP_EOL;
		exit($resultCode);
	}
	if (!file_exists($docoptFile)) {
		echo <<<EOCOMPOSER
		OOPS!
		It seems docopt isn't installed.

		I've tried to install composer.phar and have run `php composer.phar install`, so it should be here. I'm afraid you'll have to report a bug.
		EOCOMPOSER;
		echo "\n";
		exit(1);
	}
}

include_once($docoptFile);
include_once('vendor/autoload.php');
include_once('public/lib/include.php');

$doc = <<<DOC
BetterQuiz command line utility.

This utility simplifies deploying and configuring a betterquiz instance.

Usage:
  betterquiz.php (-h | --help)
  betterquiz.php --version
  betterquiz.php bulksms [--username=USERNAME --password=PASSWORD]
  betterquiz.php download [--remotedir=REMOTEDIR] [--localdir=LOCALDIR]
  betterquiz.php database [--host=HOST --username=USERNAME --password=PASSWORD --database=DATABASE]
  betterquiz.php panacea [--username=USERNAME --password=PASSWORD]
  betterquiz.php apache --fqdn=FQDN [--php=PHPVERSION] [--port=PORT] [--no-ssl]  
  betterquiz.php caddy --fqdn=FQDN [--php=PHPVERSION] [--port=PORT]

Options:
  -h --help                  Show this screen.
  --version                  Show version.
  --localdir=LOCALDIR        Local directory to store site backup [default: ./live-site-backup].
  --remotedir=REMOTEDIR      Remote directory on website for web site files [default: /betterquiz:/usr/www/users/quizbakqhw/].
  --fqdn=FQDN                Fully qualified domain name of the betterquiz instance.
  --host=HOST                Server host of the database [default: localhost].
  --database=DATABASE        Database name [default: betterquiz].
  --username=USERNAME        Username on Panacea SMS, BulkSMS or MySQL.
  --password=PASSWORD        Password on Panacea SMS, BulkSMS or MySQL.
  --port=PORT                Port to run webserver [default: 443].
  --php=PHPVERSION           Set the PHP Version to use for betterquiz [default: 8.0].
  --no-ssl                   Do not use ssl on the webserver.

DOC;

$args = Docopt::handle($doc, array('version'=>'betterquiz 1.0.0'));

if ($args['apache']) {
	ApacheCommand($args);
} elseif ($args['bulksms']) {
	BulksmsCommand($args);
} elseif ($args['database']) {
	DatabaseCommand($args);
} elseif ($args['deploy']) {
	DeployCommand($args);
} elseif ($args['download'] || $args['upload']) {
	DownloadOrUploadCommand($args);
} elseif ($args['caddy']) {
	CaddyCommand($args);
} elseif ($args['panacea']) {
	PanaceaCommand($args);
}

/**
 *  argsToSetArray converts arguments to a dictionary through the map
 */
function argsToSetArray(array $map, docopt\Response $args) : array {
	$arr = [];
	$argsArray = [];
	foreach ($args as $k=>$v) {
		if ($v) {
			$argsArray[$k] = $v;
		}
	}
	foreach ($map as $k=>$v) {
		if (array_key_exists($k, $argsArray)) {
			$arr[$v] = $argsArray[$k];
		}
	}
	return $arr;
}

// printValues echoes each key-value with the value json-decoded
function printValues(array $values) {
	foreach ($values as $k=>$v) {
		echo "$k: " . json_decode($v) . PHP_EOL;
	}
}

// ApacheCommand prints to stdout the apache
// configuration file for the betterquiz instance.
function ApacheCommand(docopt\Response $args) {
	$loader = new \Twig\Loader\FilesystemLoader('./lib-cli/twig-templates');
	$twig = new \Twig\Environment($loader);

	echo $twig->render('apache-site.conf.twig', [
		'fqdn' => $args['--fqdn'],
		'workingdir' => dirname(__FILE__),
		'phpversion' => $args['--php'],
		'port' => $args['--port'],
		'ssl' => ($args['--no-ssl']) ? false : true,
	]);;
}


/**
 * BulksmsCommand sets the BulkSMS username 
 * and password values.
 */
function BulksmsCommand(docopt\Response  $args) {		
	$settings = parseBetterquizSettings(argsToSetArray([
		'--username'=>'BULKSMS_USERNAME',
		'--password'=>'BULKSMS_PASSWORD',
	], $args));
	printValues($settings);
}

// Caddy installs Caddy webserver, if necessary, and starts betterquiz
// on the defined port (with or without ssl)
function CaddyCommand(docopt\Response $args) {
	if (!file_exists('lib-cli/caddy')) {
		$wd = getcwd();
		chdir("lib-cli");
		$result = [];
		$code = 0;
		exec("./install-caddy.sh", $result, $code);
		chdir($wd);
		if (0!=$code) {
			die("install-caddy.sh failed: " . implode(PHP_EOL, $result));
		};
	}
}

/**
 * DatabaseCommand sets the database
 * host, username, password and database.
 */
function DatabaseCommand(docopt\Response $args) {
	$settings = parseBetterquizSettings(argsToSetArray([
		'--host'=>'DBHOST',
		'--username'=>'DBUSERNAME',
		'--password'=>'DBPASSWORD',
		'--database'=>'DBDATABASE',
	], $args));
	printValues($settings);
}

/**
 * DeployCommand deploys the code in the repo to the live site.
 */
function DeployCommand(docopt\Response $args) {
	$src = "./public/";
	$dest= $args["--remote-dir"];

	$cmd = "rsync -avz $src $dest";

	$stdout = [];
	$resultCode = 0;
	echo $cmd . PHP_EOL;
	exec($cmd, $stdout, $resultCode);
	if (0!=$resultCode) {
		echo "ERROR $cmd:\n" . 
			implode(PHP_EOL, $stdout) . PHP_EOL;
		exit($resultCode);
	}
}
}

/**
 * DownloadOrUploadCommand downloads or uploads a backup copy
 * of the site.
 */
function DownloadOrUploadCommand(docopt\Response $args) {
	$downloading = $args['download'];
	$localDir = './live-site-backup/';
	$remoteDir = 'betterquiz:/usr/www/users/quizbakqhw/';
	if ($downloading) {
		$src = $remoteDir;
		$dest = $localDir;
	} else {
		$src = $localDir;
		$dest = $remoteDir;
	}
	$cmd = "rsync -avz $src $dest";

	$stdout = [];
	$resultCode = 0;
	echo $cmd . PHP_EOL;
	exec($cmd, $stdout, $resultCode);
	if (0!=$resultCode) {
		echo "ERROR $cmd:\n" . 
			implode(PHP_EOL, $stdout) . PHP_EOL;
		exit($resultCode);
	}
}


/**
 * PanaceaCommand sets panacea sms username and password
 */
function PanaceaCommand(docopt\Response  $args) {
		$settings = parseBetterquizSettings(argsToSetArray([
		'--username'=>'PANACEA_USERNAME',
		'--password'=>'PANACEA_PASSWORD',
	], $args));
	printValues($settings);
}
