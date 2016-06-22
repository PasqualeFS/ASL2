<?php

namespace App\Controllers;

use Silex\Application;

class Users {
	public function index (Application $app)
	{
		$recipeModel = new \App\Models\Recipes($app['db']);
        $favoriteModel = new \App\Models\Favorites($app['db']);

        $userId=$app['user']->getId();

		return $app['twig']->render('users/index.twig', array(
			'my_recipes' => $recipeModel->fetch($userId),
            'favorites' => $favoriteModel->fetch($userId)
		));
	}
}