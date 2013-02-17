<?php

namespace Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Form\PartyType;
use Form\CommentType;
use Form\LocationType;
use Http\JsonResponse;
use Model\Entity\Location;
use Model\Finder\LocationFinder;
use Model\DataMapper\LocationDataMapper;

class LocationController
{

    /**
     * Get all Location and create a form to add a Location
     *
     * @param Request     $request
     * @param Application $app
     */
    public function indexAction(Request $request, Application $app)
    {
        $form = $app['form.factory']->create(new LocationType());
        $locations = (new LocationFinder($app['db']))->findAll();

        if ('json' === guessBestFormat()) {
            return new JsonResponse($locations);
        }

        return $app['twig']->render('locations.html', array(
                'form'      => $form->createView(),
                'locations' => $locations,
            ));
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
        $location = (new LocationFinder($app['db']))->findOneByIdWithCommentsAndParties($id);

        if (empty($location)) {
            if ('json' === guessBestFormat()) {
                return new JsonResponse('Location not found', 404);
            }
            
            return new Response('Location not found', 404);
        }

        $formParty = $app['form.factory']->create(new PartyType());
        $commentParty = $app['form.factory']->create(new CommentType());

        $buzz     = new \Buzz\Browser(new \Buzz\Client\Curl());
        $adapter  = new \Geocoder\HttpAdapter\BuzzHttpAdapter($buzz);
        $geocoder = new \Geocoder\Geocoder();
        $geocoder->registerProviders(array(
                new \Geocoder\Provider\GoogleMapsProvider($adapter),
            ));
        try {
            $result = $geocoder->geocode($location->getAdress().', '.$location->getZipCode().' '.$location->getCity());
        } catch (\Exception $e) {}

        if ('json' === guessBestFormat()) {
            return new JsonResponse($location);
        }

        return $app['twig']->render('location.html', array(
                'location'     => $location,
                'formParty'    => $formParty->createView(),
                'commentParty' => $commentParty->createView(),
            ));
    }

    /**
     * Post a Location
     *
     * @param Request     $request
     * @param Application $app
     */
    public function postAction(Request $request, Application $app)
    {
        $location = new Location();
        $form = $app['form.factory']->create(new LocationType($location), $location);
        $form->bindRequest($request);

        if (!$form->isValid()) {
            $app['session']->setFlash('error', 'The location has not been added.');

            $locations = (new LocationFinder($app['db']))->findAll();

            return $app['twig']->render('locations.html', array(
                    'form'      => $form->createView(),
                    'locations' => $locations,
                ));
        }

        (new LocationDataMapper($app['db']))->persist($location);

        if ('json' === guessBestFormat()) {
            return new JsonResponse($location->getId(), 201);
        }

        $app['session']->setFlash('success', 'The location has been added.');

        return $app->redirect($app['url_generator']->generate('location_get', array('id' => $location->getId())));
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

        $form = $app['form.factory']->create(new LocationType($location), $location);

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
        $location = (new LocationFinder($app['db']))->findOneById($id);

        if (empty($location)) {
            return new Response('Location not found', 404);
        }

        $form = $app['form.factory']->create(new LocationType($location), $location);
        $form->bindRequest($request);

        if (!$form->isValid()) {
            $app['session']->setFlash('error', 'The location has not been updated.');

            return $app['twig']->render('admin_location_update.html', array(
                    'form'     => $form->createView(),
                    'location' => $location,
                ));
        }

        (new LocationDataMapper($app['db']))->persist($location);

        $app['session']->setFlash('success', 'The location has been updated.');

        return $app->redirect($app['url_generator']->generate('admin_locations_get'));
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

        $app['session']->setFlash('success', 'The location has been deleted.');

        return $app->redirect($app['url_generator']->generate('admin_locations_get'));
    }
}
