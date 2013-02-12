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
->bind('locations_get')
;

/* One location */
$app->get('/locations/{id}', controller('location/getById'))
->assert('id', '\d+')
->bind('location_get')
;

/* Add a location */
$app->post('/locations', controller('location/post'))
->bind('location_post')
;

/* Admin get all locations */
$app->get('/admin/locations', controller('location/adminIndex'))
->bind('admin_locations_get')
;

/* Admin update a location */
$app->put('/admin/locations/{id}', controller('location/adminUpdate'))
->assert('id', '\d+')
->bind('admin_location_update')
;

/* Admin delete a location */
$app->delete('/admin/locations/{id}', controller('location/adminDelete'))
->assert('id', '\d+')
->bind('admin_location_delete')
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

