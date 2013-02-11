<?php

namespace Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class LocationController 
{

    public function indexAction(Request $request, Application $app)
    {
        return $app['twig']->render('locations.html', array());
    }

    public function locationByIdAction(Request $request, Application $app) 
    {
        return $app['twig']->render('location.html', array());
    }
    
}