<?php

// Search the autoloader in vendor folder
$loader = require __DIR__ . '/../vendor/autoload.php';

// Add the uframework folder for the autoload
$loader->add('', __DIR__);
