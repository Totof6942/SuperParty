<?php

function debug($var)
{
    echo '<pre>';
    print_r($var);
    echo '</pre>';
}

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../src/autoload.php';

$app = require __DIR__.'/../src/app.php';
require __DIR__.'/../config/dev.php';
require __DIR__.'/../src/controllers.php';
$app->run();