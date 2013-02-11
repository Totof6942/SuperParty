<?php

namespace Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class LocationController 
{

    public function indexAction(Request $request, Application $app)
    {
    	echo 'toto';
    }

}