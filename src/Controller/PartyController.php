<?php

namespace Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Form\PartyType;
use Http\JsonResponse;
use Model\Entity\Party;
use Model\Finder\PartyFinder;
use Model\Finder\LocationFinder;
use Model\DataMapper\PartyDataMapper;

class PartyController
{

    /**
     * Get all Party
     *
     * @param Application $app
     */
    public function indexAction(Application $app)
    {
        $parties = (new PartyFinder($app['db']))->findAll();

        if ('json' === guessBestFormat()) {
            return new JsonResponse($parties);
        }

        return $app['twig']->render('parties.html', array(
                'parties' => $parties,
            ));
    }

    /**
     * Get a Party by his id
     *
     * @param Application $app
     * @param int         $id
     */
    public function getByIdAction(Application $app, $id)
    {
        $party = (new PartyFinder($app['db']))->findOneById($id);

        if (empty($party)) {
            if ('json' === guessBestFormat()) {
                return new JsonResponse('Party not found', 404);
            }

            return new Response('Party not found', 404);
        }

        if ('json' === guessBestFormat()) {
            return new JsonResponse($party);
        }

        return $app['twig']->render('party.html', array('party' => $party));
    }

    /**
     * Get all Parties for a Location
     *
     * @param Application $app
     * @param int         $location_id
     */
    public function getForLocationAction(Application $app, $location_id)
    {
        if ('json' !== guessBestFormat()) {
            return $app->redirect($app['url_generator']->generate('location_get', array('id' => $location_id)));
        }

        $location = (new LocationFinder($app['db']))->findOneById($location_id);

        if (empty($location)) {
            return new JsonResponse('Location not found', 404);
        }

        $parties = (new PartyFinder($app['db']))->findAllForLocation($location);

        return new JsonResponse($parties);
    }

    /**
     * Post a Party
     *
     * @param Request     $request
     * @param Application $app
     * @param int         $location_id
     */
    public function postAction(Request $request, Application $app, $location_id)
    {
        $location = (new LocationFinder($app['db']))->findOneById($location_id);

        if (empty($location)) {
            if ('json' === guessBestFormat()) {
                return new JsonResponse('Location not found', 404);
            }

            return new Response('Location not found', 404);
        }

        $party = new Party();
        $form = $app['form.factory']->create(new PartyType, $party);
        $form->bindRequest($request);

        if (!$form->isValid()) {
            $app['session']->setFlash('error', 'The party has not been added. Name and date are mandatory.');
        } else {
            $party->setLocation($location);

            (new PartyDataMapper($app['db']))->persist($party);

            $app['session']->setFlash('success', 'The party has been added.');
        }

        if ('json' === guessBestFormat()) {
            return new JsonResponse($party->getId(), 201);
        }

        return $app->redirect($app['url_generator']->generate('location_get', array('id' => $location->getId())));
    }

    /**
     * Admin get all Parties
     *
     * @param Application $app
     */
    public function adminIndexAction(Application $app)
    {
        $parties = (new PartyFinder($app['db']))->findAll();

        return $app['twig']->render('admin_parties.html', array('parties' => $parties));
    }

    /**
     * Admin get a Party for update
     *
     * @param Application $app
     * @param int         $id
     */
    public function adminGetForUpdateAction(Application $app, $id)
    {
        $party = (new PartyFinder($app['db']))->findOneById($id);

        if (empty($party)) {
            return new Response('Party not found', 404);
        }

        $form = $app['form.factory']->create(new PartyType, $party);

        return $app['twig']->render('admin_party_update.html', array(
                'form'  => $form->createView(),
                'party' => $party,
            ));
    }

    /**
     * Admin update a Party
     *
     * @param Request     $request
     * @param Application $app
     * @param int         $id
     */
    public function adminUpdateAction(Request $request, Application $app, $id)
    {
        $party = (new PartyFinder($app['db']))->findOneById($id);

        if (empty($party)) {
            return new Response('Party not found', 404);
        }

        $form = $app['form.factory']->create(new PartyType, $party);
        $form->bindRequest($request);

        if (!$form->isValid()) {
            $app['session']->setFlash('error', 'The party has not been added.');

            return $app->redirect($app['url_generator']->generate('admin_party_get', array('id' => $id)));
        }

        (new PartyDataMapper($app['db']))->persist($party);

        $app['session']->setFlash('success', 'The party has been updated.');

        return $app->redirect($app['url_generator']->generate('admin_parties_get'));
    }

    /**
     * Admin delete a Party
     *
     * @param Application $app
     * @param int         $id
     */
    public function adminDeleteAction(Application $app, $id)
    {
        $party = (new PartyFinder($app['db']))->findOneById($id);

        if (empty($party)) {
            return new Response('Party not found', 404);
        }

        $mapper = new PartyDataMapper($app['db']);
        $mapper->remove($party);

        $app['session']->setFlash('success', 'The party has been deleted.');

        return $app->redirect($app['url_generator']->generate('admin_parties_get'));
    }

}
