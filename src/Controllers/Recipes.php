<?php

namespace App\Controllers;

use Silex\Application;

class Recipes {
	public function index (Application $app) 
	{
		$model = new \App\Models\Recipes($app['db']);

		return $app['twig']->render('recipes/index.twig', array(
		    'recipes' => $model->fetch(),
		));
	}

	public function details (Application $app, $id)
	{
		$model = new \App\Models\Recipes($app['db']);

		return $app['twig']->render('recipes/detail.twig', array(
	        'recipe' => $model->fetchOne($id),
    	));
	}


	public function add (Application $app)
	{
		$model = new \App\Models\Recipes($app['db']);

		if (isset($_POST['submit'])) {
			$userId=$app['user']->getId();
		    $recipetitle=$_POST['title']; //get POST values
		    $recipeingredients=$_POST['ingredients'];
		    $recipeinstructions=$_POST['instructions'];
		    $recipecategory=$_POST['category'];
		    $recipecuisine=$_POST['cuisine'];
		    
		    $result = $model->insert($recipetitle, $recipeingredients, $recipeinstructions, $recipecategory, $recipecuisine, $userId);

		    if ($result == false){
		    	// An error happened
		    } else {
		    	// All good
		    }
		}
		return $app['twig']->render('recipes/add.twig');
	}

	public function edit (Application $app, $id)
	{
		$recipesModel = new \App\Models\Recipes($app['db']);
		$categoriesModel = new \App\Models\Categories($app['db']);
		$cuisineModel = new \App\Models\Cuisine($app['db']);

		if (isset($_POST['submit'])) {
		    $recipetitle=$_POST['title']; //get POST values
		    $recipeingredients=$_POST['ingredients'];
		    $recipeinstructions=$_POST['instructions'];
		    $recipecategory=$_POST['category'];
		    $recipecuisine=$_POST['cuisine'];
		    
		    $recipesModel->update($id, $recipetitle, $recipeingredients, $recipeinstructions, $recipecategory, $recipecuisine);
		}

		return $app['twig']->render('recipes/edit.twig', array(
	        'recipe' => $recipesModel->fetchOne($id),
	        'categories' => $categoriesModel->fetch(), 
	        'cuisines' => $cuisineModel->fetch(), 
    	));

    }

    public function delete (Application $app, $id) {
    	// FILL ME IN
    	$model = new \App\Models\Recipes($app['db']);
    	$userId=$app['user']->getId();

    	$status = $model->delete($id);

    	return $app->redirect('users/' . $userId);
    }

    public function favorite (Application $app, $id) {
    	// create new favorites model and redirect back to recipe page
    	$model = new \App\Models\Favorites($app['db']);
    	$userId=$app['user']->getId();
    	$model->add($userId, $id);
    }
}

