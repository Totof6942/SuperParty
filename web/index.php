<?php

function debug($var)
{
    echo '<pre>';
    print_r($var);
    echo '</pre>';
}

$app = require __DIR__ . '/../app/app.php';
$app->run();
