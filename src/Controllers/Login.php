<?php

namespace App\Controllers;

use Silex\Application;

class Login {
	public function index (Application $app) 
	{
		return $app['twig']->render('login/index.twig', array(
		    'error' => $app['security.last_error']($app['request']),
            'last_username' => $app['session']->get('_security.last_username')
		));
	}
}