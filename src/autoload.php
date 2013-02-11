<?php

spl_autoload_register(function($class_name) {
	$path = str_replace('\\', DIRECTORY_SEPARATOR, $class_name);
	$path = __DIR__.DIRECTORY_SEPARATOR.$path.'.php';
	if (file_exists($path)) {
		include($path);
	}
});

