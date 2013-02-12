<?php

namespace Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;

class ConnectionController 
{

    public function loginAction(Request $request, Application $app)
    {
        $form = $app['form.factory']->createBuilder('form')
        ->add('login', 'text', array(
            'label'       => 'Login',
            'constraints' => array(
                new Constraints\NotBlank(),
            ),
        ))
        ->add('password', 'password', array(
            'label'       => 'Password',
            'constraints' => array(
                new Constraints\NotBlank(),
            ),
        ))
        ->getForm();

        if ('POST' === $app['request']->getMethod()) {
            $form->bindRequest($app['request']);

            if ($form->isValid()) {

                $login    = $form->get('login')->getData();
                $password = $form->get('password')->getData();

                if ('admin' === $login && 'admin' === $password) {
                    $app['session']->set('user', array(
                        'login' => $login,
                    ));

                    $app['session']->setFlash('notice', 'You are now connected');

                    return $app->redirect($app['url_generator']->generate('homepage'));
                }

                $form->addError(new FormError('Login / password does not match (admin/ admin)'));
            }
        }

        return $app['twig']->render('login.html', array('form' => $form->createView()));
    }

    public function logoutAction(Request $request, Application $app)
    {
        $app['session']->clear();

        return $app->redirect($app['url_generator']->generate('homepage'));
    }

}