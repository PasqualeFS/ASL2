<?php

namespace App\Controllers;

use Silex\Application;

class Recipes {
	public function index (Application $app)
	{
		$recipesModel = new \App\Models\Recipes($app['db']);
        $categoriesModel = new \App\Models\Categories($app['db']);
        $cuisineModel = new \App\Models\Cuisine($app['db']);

        $titleSearch = null;
        $categorySearch = null;
        $cuisineSearch = null;

        if (isset($_GET['titleSearch'])) {
            $titleSearch = $_GET['titleSearch'];
        }

        if (isset($_GET['categorySearch'])) {
            $categorySearch = $_GET['categorySearch'];
        }

        if (isset($_GET['cuisineSearch'])) {
            $cuisineSearch = $_GET['cuisineSearch'];
        }

        $recipes = $recipesModel->fetch(null, $titleSearch, $categorySearch, $cuisineSearch);

		return $app['twig']->render('recipes/index.twig', array(
		    'recipes' => $recipes,
            'categories' => $categoriesModel->fetch(),
            'cuisines' => $cuisineModel->fetch(),
            'titleSearch' => $titleSearch,
            'categorySearch' => $categorySearch,
            'cuisineSearch' => $cuisineSearch
		));
	}

	public function details (Application $app, $id)
	{
		$recipeModel = new \App\Models\Recipes($app['db']);
        $favoriteModel = new \App\Models\Favorites($app['db']);
        $is_favorited = false;

        if ($app['user']) {
            $is_favorited = (bool)$favoriteModel->fetch($app['user']->getId(), $id);
        }

		return $app['twig']->render('recipes/detail.twig', array(
	        'recipe' => $recipeModel->fetchOne($id),
            'is_favorited' => $is_favorited
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
    	$model = new \App\Models\Recipes($app['db']);
    	$status = $model->delete($id);

    	return $app->redirect('/user');
    }

    public function addFavorite (Application $app, $recipe_id) {
    	// create new favorites model and redirect back to recipe page
    	$model = new \App\Models\Favorites($app['db']);
    	$userId=$app['user']->getId();
    	$model->add($userId, $recipe_id);

        return $app->redirect('/recipes/' . $recipe_id);
    }

    public function removeFavorite (Application $app, $recipe_id) {
        $model = new \App\Models\Favorites($app['db']);
        $userId=$app['user']->getId();
        $model->delete($recipe_id, $userId);

        // redirects to the previous page
        return $app->redirect($app['request']->server->get('HTTP_REFERER'));
    }
}

