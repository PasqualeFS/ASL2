<?php

require_once __DIR__.'/../vendor/autoload.php'; // this loads the framework

$app = new Silex\Application(); // this creates a new instance of an application.

// The three lines that follow this comment are the connection to the database.
$user = "root";
$pass = "root";
$dbh = new PDO('mysql:host=localhost;dbname=recipedata;port=8889', $user, $pass);


$app->get('/', function () {
	return '<h1>Pasquale\'s Recipes</h1><a href="/recipes">Browse Recipes</a>';
});


// This is a route for the list of recipes. This will be moved to its own controller file.
$app->get('/recipes', function () use ($dbh) {
	$sqlquery = $dbh->prepare("SELECT id, title, ingredients, instructions FROM recipes");
	$sqlquery->execute();
	$result = $sqlquery->fetchAll();

	$response = "<h1>Recipes</h1>";

	foreach ($result as $row) {
        $response .= '<a href="/recipes/' . $row['id'] . '">' . $row['title'] . '</a><br />';
    }

    return $response;
});

//This is a route for the recipe details. This will be moved to its own controller file.
$app->get('/recipes/{id}', function ($id) use ($dbh) {
	$sqlquery = $dbh->prepare("SELECT title, ingredients, instructions FROM recipes WHERE id = :id");
	$sqlquery->bindParam(':id', $id);
	$sqlquery->execute();
	$result = $sqlquery->fetch();

	return '<h1>' . $result['title'] . '</h1><p>' . $result['ingredients'] . '</p><p>' . $result['instructions'] . '</p>';
});

$app->run();