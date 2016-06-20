<?php

namespace App\Controllers;

use Silex\Application;

class Users {
	public function index (Application $app) 
	{
		$model = new \App\Models\Recipes($app['db']);

		return $app['twig']->render('users/index.twig', array(
			'my_recipes' => $model->fetch($app['user']->getId())
		));
	}
}