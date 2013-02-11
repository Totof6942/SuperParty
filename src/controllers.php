<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Form\FormError;

/* Home page */
$app->get('/', controller('home/index'))
->bind('homepage')
;

/* Login */
$app->match('/login', controller('connection/login'))
->bind('login')
;

/* Logout */
$app->match('/logout', controller('connection/logout'))
->bind('logout')
;

/* All Locations */
$app->get('/locations', controller('location/index'))
->bind('locations')
;

/* One location */
$app->get('/locations/{id}', controller('location/getById'))
->assert('id', '\d+')
->bind('location')
;

/* Add a location */
$app->post('/locations', controller('location/post'))
->bind('location')
;

/* All parties */
$app->get('/parties', controller('party/parties'))
->bind('parties')
;

$app->error(function (\Exception $e, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    $page = 404 == $code ? '404.html' : '500.html';

    return new Response($app['twig']->render($page, array('code' => $code)), $code);
});

