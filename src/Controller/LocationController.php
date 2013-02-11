<?php

namespace Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

use Model\Entity\Location;

class LocationController 
{

    public function indexAction(Request $request, Application $app)
    {
        $location = new Location();
        $form = $app['form.factory']->createBuilder('form', $location)
            ->add('name',        'text')
            ->add('adress',      'text')
            ->add('zip_code',    'number')
            ->add('city',        'text')
            ->add('phone',       'number',   array('required' => false,))
            ->add('description', 'textarea', array('required' => false, 'attr' => array('rows' => 3,)))
            ->getForm();

        return $app['twig']->render('locations.html', array('form' => $form->createView()));
    }

    /**
     * Get a Location by his id
     * 
     * @param Request     $request
     * @param Application $app
     * @param int         $id 
     */
    public function getByIdAction(Request $request, Application $app, $id) 
    {

        return $app['twig']->render('location.html', array());
    }

    /**
     * Post a Location
     * 
     * @param Request     $request
     * @param Application $app
     */
    public function postAction(Request $request, Application $app) 
    {
        debug('passe');
        return $app['twig']->render('location.html', array());
    }

}