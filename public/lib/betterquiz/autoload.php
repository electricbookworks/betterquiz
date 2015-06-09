<?php

spl_autoload_register(function($class_name) {
	echo ("Loading class.$class_name.php\n");
    include_once("class." . $class_name . '.php');
});