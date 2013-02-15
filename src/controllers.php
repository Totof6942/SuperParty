<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Form\FormError;

use Http\JsonResponse;

/* Login page */
$app->get('/login', controller('connection/login'))
->bind('login')
;

/* Logout */
$app->get('/admin/logout', controller('connection/logout'))
->bind('logout')
;

/* Home page */
$app->get('/', controller('home/index'))
->bind('homepage')
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

/* Admin update form location */
$app->get('/admin/locations/{id}', controller('location/adminGetForUpdate'))
->assert('id', '\d+')
->bind('admin_location_get')
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
$app->get('/parties', controller('party/index'))
->bind('parties_get')
;

/* One party */
$app->get('/parties/{id}', controller('party/getById'))
->assert('id', '\d+')
->bind('party_get')
;

/* Get all parties for a location */
$app->get('/locations/{location_id}/parties', controller('party/getForLocation'))
->assert('location_id', '\d+')
;

/* Add a party */
$app->post('/locations/{location_id}/parties', controller('party/post'))
->assert('location_id', '\d+')
->bind('party_post')
;

/* Admin get all parties */
$app->get('/admin/parties', controller('party/adminIndex'))
->bind('admin_parties_get')
;

/* Admin update form party */
$app->get('/admin/parties/{id}', controller('party/adminGetForUpdate'))
->assert('id', '\d+')
->bind('admin_party_get')
;

/* Admin update a party */
$app->put('/admin/parties/{id}', controller('party/adminUpdate'))
->assert('id', '\d+')
->bind('admin_party_update')
;

/* Admin delete a party */
$app->delete('/admin/parties/{id}', controller('party/adminDelete'))
->assert('id', '\d+')
->bind('admin_party_delete')
;

/* Get all comments for a location */
$app->get('/locations/{location_id}/comments', controller('comment/getForLocation'))
->assert('location_id', '\d+')
;

/* Add a comment */
$app->post('/locations/{location_id}/comments', controller('comment/post'))
->assert('location_id', '\d+')
->bind('comment_post')
;

/* Admin get all comments */
$app->get('/admin/comments', controller('comment/adminIndex'))
->bind('admin_comments_get')
;

/* Admin update form comment */
$app->get('/admin/comments/{id}', controller('comment/adminGetForUpdate'))
->assert('id', '\d+')
->bind('admin_comment_get')
;

/* Admin update a comment */
$app->put('/admin/comments/{id}', controller('comment/adminUpdate'))
->assert('id', '\d+')
->bind('admin_comment_update')
;

/* Admin delete a comment */
$app->delete('/admin/comments/{id}', controller('comment/adminDelete'))
->assert('id', '\d+')
->bind('admin_comment_delete')
;

$app->error(function (\Exception $e, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    $page = 404 == $code ? '404.html' : '500.html';

    return new Response($app['twig']->render($page, array('code' => $code)), $code);
});

