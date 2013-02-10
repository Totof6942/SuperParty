<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Form\FormError;

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
 * Login
 */
$app->match('/login', function() use ($app) {

    $form = $app['form.factory']->createBuilder('form')
        ->add('email', 'email', array(
            'label'       => 'Email',
            'constraints' => array(
                new Constraints\NotBlank(),
                new Constraints\Email(),
            ),
        ))
        ->add('password', 'password', array(
            'label'       => 'Password',
            'constraints' => array(
                new Constraints\NotBlank(),
            ),
        ))
        ->getForm()
    ;

    if ('POST' === $app['request']->getMethod()) {
        $form->bindRequest($app['request']);

        if ($form->isValid()) {

            $email    = $form->get('email')->getData();
            $password = $form->get('password')->getData();

            if ('email@example.com' == $email && 'password' == $password) {
                $app['session']->set('user', array(
                    'email' => $email,
                ));

                $app['session']->setFlash('notice', 'You are now connected');

                return $app->redirect($app['url_generator']->generate('homepage'));
            }

            $form->addError(new FormError('Email / password does not match (email@example.com / password)'));
        }
    }

    return $app['twig']->render('login.html', array('form' => $form->createView()));
})
->bind('login')
;

/**
 * Logout
 */
$app->match('/logout', function() use ($app) {
    $app['session']->clear();

    return $app->redirect($app['url_generator']->generate('homepage'));
})
->bind('logout')
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
->assert('id', '\d+')
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
