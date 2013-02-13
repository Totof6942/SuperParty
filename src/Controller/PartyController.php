<?php

namespace Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Form\PartyType;
use Model\Entity\Party;
use Model\Finder\PartyFinder;
use Model\Finder\LocationFinder;
use Model\DataMapper\PartyDataMapper;

class PartyController 
{

    /**
     * Get all Party
     * 
     * @param Request     $request
     * @param Application $app
     */
    public function indexAction(Request $request, Application $app)
    {
    	$parties = (new PartyFinder($app['db']))->findAll();

        return $app['twig']->render('parties.html', array(
                'parties' => $parties,
            ));
    }

    /**
     * Get a Party by his id
     * 
     * @param Request     $request
     * @param Application $app
     * @param int         $id 
     */
    public function getByIdAction(Request $request, Application $app, $id) 
    {
        $party = (new PartyFinder($app['db']))->findOneById($id);

        if (empty($party)) {
            return new Response('Party not found', 404);
        }

        return $app['twig']->render('party.html', array('party' => $party));
    }

    /**
     * Get a Party by his id
     * 
     * @param Request     $request
     * @param Application $app
     * @param int         $location_id 
     */
    public function postAction(Request $request, Application $app, $location_id)
    {
        $location = (new LocationFinder($app['db']))->findOneById($location_id);
        
        if (empty($location)) {
            return new Response('Location not found', 404);
        }

        $form = $app['form.factory']->create(new PartyType());
        $form->bindRequest($request);
        $data = $form->getData();

        $party = new Party(
                $data['name'],
                // $data['date'],
                new \DateTime(),
                false,
                $data['message']
            );

        $party->setLocation($location);

        (new PartyDataMapper($app['db']))->persist($party);

        $app['session']->setFlash('success', 'The party has been added.');

        return $app->redirect($app['url_generator']->generate('location_get', array('id' => $location->getId())));
    }

    /**
     * Admin get all Parties
     * 
     * @param Request     $request
     * @param Application $app
     */
    public function adminIndexAction(Request $request, Application $app)
    {
        $parties = (new PartyFinder($app['db']))->findAll();
        return $app['twig']->render('admin_parties.html', array('parties' => $parties));
    }

    /**
     * Admin get a Party for update
     * 
     * @param Request     $request
     * @param Application $app
     * @param int         $id 
     */
    public function adminGetForUpdateAction(Request $request, Application $app, $id)
    {
        $party = (new PartyFinder($app['db']))->findOneById($id);
        
        if (empty($party)) {
            return new Response('Party not found', 404);
        }

        $form = $app['form.factory']->create(new PartyType(), $party);

        return $app['twig']->render('admin_party_update.html', array(
                'form'     => $form->createView(),
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

        $form = $app['form.factory']->create(new PartyType());
        $form->bindRequest($request);
        $data = $form->getData();

        $party->setName($data['name']);
        // $party->setDate(new \DateTime($data['date']));
        $party->setMessage($data['message']);

        (new PartyDataMapper($app['db']))->persist($party);

        $app['session']->setFlash('success', 'The party has been updated.');

        return $app->redirect($app['url_generator']->generate('admin_parties_get'));
    }

    /**
     * Admin delete a Party
     * 
     * @param Request     $request
     * @param Application $app
     * @param int         $id 
     */
    public function adminDeleteAction(Request $request, Application $app, $id) 
    {
        $party = (new PartyFinder($app['db']))->findOneById($id);
        
        if (empty($party)) {
            return new Response('Party not found', 404);
        }

        $mapper = new PartyDataMapper($app['db']);
        $mapper->remove($party);

        // return 204
        $app['session']->setFlash('success', 'The party has been deleted.');

        return $app->redirect($app['url_generator']->generate('admin_parties_get'));
    }

}