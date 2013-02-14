<?php

namespace Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;

class ConnectionController 
{

    public function loginAction(Request $request, Application $app)
    {
        $token = $app['security']->getToken();
        if (null !== $token) {
            debug($token->getUser());
        }


        if (null !== $app['security']->getToken()) {
            $app['session']->setFlash('notice', 'You are already logged.');
            return $app->redirect($app['url_generator']->generate('homepage'));
        }

        return $app['twig']->render('login.html', array(
                'error'         => $app['security.last_error']($request),
                'last_username' => $app['session']->get('_security.last_username'),
            ));
    }

    public function logoutAction(Request $request, Application $app)
    {
        $app['session']->clear();

        return $app->redirect($app['url_generator']->generate('homepage'));
    }

}