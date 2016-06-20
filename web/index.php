<?php

require_once __DIR__.'/../vendor/autoload.php'; // this loads the framework

$app = new Silex\Application(); // this creates a new instance of an application.

$app['debug'] = true;

// The three lines that follow use Twig to set the path for the views
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/../src/Views',
));

// Register the Doctrine service and connect to the database
$app->register(new Silex\Provider\DoctrineServiceProvider());

$app['db.options'] = array(
    'driver'    => 'pdo_mysql',
    'host'      => 'localhost',
    'dbname'    => 'recipedata',
    'user'      => 'root',
    'password'  => 'root'
);

$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\SecurityServiceProvider(), array(
    'security.firewalls' => array(
        'default' => array(
            'pattern' => '^.*$',
            'anonymous' => true,
            'form' => array(
            	'login_path' => '/login',
            	'check_path' => 'login_check'
            ),
            'logout' => array('logout_path' => '/logout'),
            'users' => function($app) {
                return new App\User\UserProvider($app['db']);
            },
        ),
    ),
    'security.access_rules' => array(
        array('/recipes/add', 'ROLE_USER'),
        array('/recipes/edit', 'ROLE_USER'),
        array('/users', 'ROLE_USER'),
    )
));

$app['request'] = $app->factory(function ($app) {
    return $app['request_stack']->getCurrentRequest();
});

$app->get('/', 'App\Controllers\Index::index')->bind('home');

// This is a route for the list of recipes. This will be moved to its own controller file.
$app->get('/recipes', 'App\Controllers\Recipes::index')->bind('recipe_index');

// This route is for adding/creating new recipes
$app->get('/recipes/add', 'App\Controllers\Recipes::add')->bind('recipe_add');

$app->post('/recipes/add', 'App\Controllers\Recipes::add')->bind('recipe_addpost');

// This route is for editing recipes
$app->get('/recipes/edit/{id}', 'App\Controllers\Recipes::edit')->bind('recipe_edit');

$app->post('/recipes/edit/{id}', 'App\Controllers\Recipes::edit')->bind('recipe_editpost');


//This is a route for the recipe details. This will be moved to its own controller file.
$app->get('/recipes/{id}', 'App\Controllers\Recipes::details')->bind('recipe_detail');

$app->get('/recipes/delete/{id}', 'App\Controllers\Recipes::delete')->bind('recipe_delete');

$app->get('/login', 'App\Controllers\Login::index')->bind('login');

$app->get('/users/{id}', 'App\Controllers\Users::index')->bind('user_account');

$app->run();