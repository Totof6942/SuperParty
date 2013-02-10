<?php

use Silex\Application;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\DoctrineServiceProvider;

$app = new Application();
$app->register(new UrlGeneratorServiceProvider());
$app->register(new ValidatorServiceProvider());
$app->register(new ServiceControllerServiceProvider());
$app->register(new DoctrineServiceProvider());
$app->register(new TwigServiceProvider(), array(
    'twig.path'    => array(__DIR__.'/../templates'),
    // 'twig.options' => array('cache' => __DIR__.'/../cache/twig'),
));

/**
 * Home page
 */
$app->get('/', function() use($app) {
	return $app['twig']->render('index.html', array(
			'parties' => $app['db']->fetchAll('SELECT id, name, date FROM parties'),
		));
})
->bind('homepage');

/**
 * All locations
 */
$app->get('/locations', function() use($app) {
    return $app['twig']->render('locations.html', array());
})
->bind('locations');

/**
 * One location
 */
$app->get('/locations/{id}', function($id) use($app) {
    return $app['twig']->render('location.html', array());
})
->bind('location');

/**
 * All parties
 */
$app->get('/parties', function() use($app) {
    return $app['twig']->render('parties.html', array());
})
->bind('parties');


return $app;
