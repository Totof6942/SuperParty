<?php

namespace Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;

use Model\Finder\LocationFinder;

class HomeController 
{

    public function indexAction(Request $request, Application $app)
    {
        $locations  = (new LocationFinder($app['db']))->findWithParamAll(array(
                'limit'   => 5,
                'orderBy' => 'id',
                'order'   => 'DESC',
            ));

        return $app['twig']->render('index.html', array(
            'locations' => $locations,
            'parties'   => $app['db']->fetchAll('SELECT id, name, date FROM parties'),
        ));
    }

}