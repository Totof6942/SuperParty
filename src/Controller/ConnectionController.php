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
        ->getForm();

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
    }

    public function logoutAction(Request $request, Application $app)
    {
        $app['session']->clear();

        return $app->redirect($app['url_generator']->generate('homepage'));
    }

}