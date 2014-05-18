<?php

namespace Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

use Http\JsonResponse;
use Model\Finder\LocationFinder;
use Model\Finder\PartyFinder;

class HomeController
{

    public function indexAction(Application $app)
    {
        $locations  = (new LocationFinder($app['db']))->findWithParamAll(array(
                'limit'   => 5,
                'orderBy' => 'id',
                'order'   => 'DESC',
            ));

        $parties = (new PartyFinder($app['db']))->findAllFuture();

        if ('json' === guessBestFormat()) {
            return new JsonResponse(array($locations, $parties));
        }

        return $app['twig']->render('index.html', array(
            'locations' => $locations,
            'parties'   => $parties,
        ));
    }

    public function adminAction(Application $app)
    {
        return $app['twig']->render('admin.html');
    }

}
