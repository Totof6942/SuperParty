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
        $locations = new LocationFinder($app['db']);
        $locations->findAll();

        return $app['twig']->render('index.html', array(
            'parties' => $app['db']->fetchAll('SELECT id, name, date FROM parties'),
        ));
    }

}