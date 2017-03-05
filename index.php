<?php
require 'config/connection.php';
require 'lib/Bramus/Router.php';
require 'models/City.php';
require 'models/Property.php';
require 'models/User.php';
require 'models/DismissReason.php';
require 'controllers/BaseController.php';
require 'controllers/HomeController.php';
require 'controllers/PropertiesController.php';
require 'controllers/CitiesController.php';
require 'controllers/SessionsController.php';

session_start();

// Create a Router
$router = new \Bramus\Router\Router();

$router->set404(function () {
    header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
    echo '404, route not found!';
});


$router->get('/test/', function () {
    phpinfo();
});


$get_admin_routes = [
    '/properties/create',
    '/properties/manage_properties',
    '/properties/(\d+)/edit',
];

$post_admin_routes = [
    '/properties/(\d+)'
];

$get_admin_routes_patterns = implode('|', $get_admin_routes);
$post_admin_routes_patterns = implode('|', $post_admin_routes);
// Middleware functions

$check_if_logged_in = function () {
    if (!isset($_SESSION['user'])) {
        $_SESSION['error'] = 'Please login to proceed';
        header('location: /');
        exit();
    }
};

$router->before('GET', "${get_admin_routes_patterns}", $check_if_logged_in);
$router->before('POST', "${post_admin_routes_patterns}", $check_if_logged_in);

$router->before('PUT', '/.*', function () {
    parse_str(file_get_contents('php://input'), $_POST);
});


$router->get('/', 'Controllers\HomeController@index');

$router->post('/login', 'Controllers\SessionsController@create');

$router->get('/logout', 'Controllers\SessionsController@destroy');

$router->mount('/cities', function () use ($router) {

    $router->get('/(\d+)/neighborhoods', 'Controllers\CitiesController@neighborhoods');

    $router->get('/statistics', 'Controllers\CitiesController@statistics');

    $router->get('/(\d+)/properties_per_neighborhood', 'Controllers\CitiesController@properties_per_neighborhood');

});

$router->mount('/properties', function () use ($router) {

    $router->get('/', 'Controllers\PropertiesController@index');

    $router->get('/manage_properties', 'Controllers\PropertiesController@manage_properties');

    $router->get('/create', 'Controllers\PropertiesController@create');

    $router->post('/', 'Controllers\PropertiesController@store');

    $router->get('/(\d+)', 'Controllers\PropertiesController@show');

    $router->get('/(\d+)/edit', 'Controllers\PropertiesController@edit');

    $router->put('/(\d+)', 'Controllers\PropertiesController@update');

    $router->post('/(\d+)', 'Controllers\PropertiesController@destroy');

});

$router->run();