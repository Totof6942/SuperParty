<?php

namespace Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class ConnectionController
{

    public function loginAction(Request $request, Application $app)
    {
        if ($app['security']->isGranted('ROLE_ADMIN')) {
            return $app->redirect($app['url_generator']->generate('homepage'));
        }

        return $app['twig']->render('login.html', array(
                'error'         => $app['security.last_error']($request),
                'last_username' => $app['session']->get('_security.last_username'),
            ));
    }

    public function logoutAction(Application $app)
    {
        $app['session']->clear();

        return $app->redirect($app['url_generator']->generate('homepage'));
    }

}
