<?php

require_once __DIR__.'/../vendor/autoload.php'; 

$app = new Silex\Application(); 

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

$app = new Silex\Application(); 

$app['debug'] = true;

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
));

/**
 * Home page
 */
$app->get('/', function() use($app) {
    return $app['twig']->render('index.html.twig', array());
});

/**
 * All locations
 */
$app->get('/locations', function() use($app) {
    return $app['twig']->render('locations.html.twig', array());
});

/**
 * One location
 */
$app->get('/locations/{id}', function($id) use($app) {
    return $app['twig']->render('location.html.twig', array());
});

return $app;
