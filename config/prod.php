<?php

// Local
$app['locale'] = 'fr';
$app['session.default_locale'] = $app['locale'];

// Doctrine (db)
$app['db.options'] = array(
    'driver'   => 'pdo_mysql',
    'host'     => 'localhost',
    'dbname'   => 'project',
    'user'     => 'project',
    'password' => 'project123',
);

