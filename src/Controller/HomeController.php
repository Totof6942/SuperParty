<?php

namespace Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;

class HomeController 
{
	public function indexAction(Request $request, Application $app)
	{
		return $app['twig']->render('index.html', array(
			'parties' => $app['db']->fetchAll('SELECT id, name, date FROM parties'),
		));
	}
}