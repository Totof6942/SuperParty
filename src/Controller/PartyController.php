<?php

namespace Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;

// use Form\PartyType;
use Model\Entity\Party;
use Model\Finder\PartyFinder;
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

}