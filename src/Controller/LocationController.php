<?php

namespace Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Model\Entity\Location;
use Model\Finder\LocationFinder;
use Model\DataMapper\LocationDataMapper;

class LocationController 
{

    public function indexAction(Request $request, Application $app)
    {
        $form = $app['form.factory']->createBuilder('form')
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
        $location = (new LocationFinder($app['db']))->findOneById($id);

        if (empty($location)) {
            return new Response('Location not found', 404);
        }

        return $app['twig']->render('location.html', array('location' => $location));
    }

    /**
     * Post a Location
     * 
     * @param Request     $request
     * @param Application $app
     */
    public function postAction(Request $request, Application $app) 
    {
        
        return $app['twig']->render('location.html', array());
    }

    /**
     * Admin get all Locations
     * 
     * @param Request     $request
     * @param Application $app
     */
    public function adminIndexAction(Request $request, Application $app)
    {
        $locations = (new LocationFinder($app['db']))->findAll();
        return $app['twig']->render('admin_locations.html', array('locations' => $locations));
    }

    /**
     * Admin get a Location for update
     * 
     * @param Request     $request
     * @param Application $app
     * @param int         $id 
     */
    public function adminGetForUpdateAction(Request $request, Application $app, $id)
    {
        $location = (new LocationFinder($app['db']))->findOneById($id);
        
        if (empty($location)) {
            return new Response('Location not found', 404);
        }
        
        $form = $app['form.factory']->createBuilder('form', $location)
            ->add('name',        'text')
            ->add('adress',      'text')
            ->add('zip_code',    'number')
            ->add('city',        'text')
            ->add('phone',       'number',   array('required' => false,))
            ->add('description', 'textarea', array('required' => false, 'attr' => array('rows' => 3,)))
            ->getForm();

        return $app['twig']->render('admin_location_update.html', array(
                'form'     => $form->createView(),
                'location' => $location,
            ));
    }

    /**
     * Admin update a Location
     * 
     * @param Request     $request
     * @param Application $app
     * @param int         $id 
     */
    public function adminUpdateAction(Request $request, Application $app, $id) 
    {
        echo 'passe'; die;
        return $app->redirect('admin_locations_get');
    }

    /**
     * Admin delete a Location
     * 
     * @param Request     $request
     * @param Application $app
     * @param int         $id 
     */
    public function adminDeleteAction(Request $request, Application $app, $id) 
    {
        $location = (new LocationFinder($app['db']))->findOneById($id);
        
        if (empty($location)) {
            return new Response('Location not found', 404);
        }

        $mapper = new LocationDataMapper($app['db']);
        $mapper->remove($location);

        // return 204
        $app['session']->setFlash('success', 'The location has been deleted.');

        return $app->redirect('admin_locations_get');
    }
}