<?php

namespace Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class PartyController 
{
	public function partiesAction(Request $request, Application $app)
	{
		return $app['twig']->render('parties.html', array());
	}
}