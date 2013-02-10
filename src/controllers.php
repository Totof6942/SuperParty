<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Home page
 */
$app->get('/', function() use($app) {
	return $app['twig']->render('index.html', array(
			'parties' => $app['db']->fetchAll('SELECT id, name, date FROM parties'),
		));
})
->bind('homepage')
;

/**
 * All locations
 */
$app->get('/locations', function() use($app) {
    return $app['twig']->render('locations.html', array());
})
->bind('locations')
;

/**
 * One location
 */
$app->get('/locations/{id}', function($id) use($app) {
    return $app['twig']->render('location.html', array());
})
->bind('location')
;

/**
 * All parties
 */
$app->get('/parties', function() use($app) {
    return $app['twig']->render('parties.html', array());
})
->bind('parties')
;

$app->error(function (\Exception $e, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    $page = 404 == $code ? '404.html' : '500.html';

    return new Response($app['twig']->render($page, array('code' => $code)), $code);
});
