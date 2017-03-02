<?php
require 'config/connection.php';
require 'lib/Bramus/Router.php';
require 'models/City.php';
require 'models/Property.php';
require 'controllers/HomeController.php';
require 'controllers/PropertiesController.php';
require 'controllers/CitiesController.php';
require 'controllers/SessionsController.php';

// Create a Router
$router = new \Bramus\Router\Router();

$router->set404(function () {
    header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
    echo '404, route not found!';
});

$router->get('/log', 'Login::index');

$router->get('/test/', function () {
    phpinfo();
});


$get_admin_routes = [
    '/properties/create',
    '/properties/(\d+)/edit',
    '/properties/(\d+)/edit'
];

$get_admin_routes_patterns = implode('|', $get_admin_routes);
// Middleware functions

$redirect_to_home = function () {
    if (!isset($_SESSION['user'])) {
        $_SESSION['error'] = 'Please login to proceed';
        header('location: /');
        exit();
    }
};

$router->before('GET', "${get_admin_routes_patterns}", $redirect_to_home);

$router->before('PUT', '/.*', function () {
    parse_str(file_get_contents('php://input'), $_POST);
});


$router->get('/', 'Controllers\HomeController@index');

$router->get('/about', 'Controllers\HomeController@about');

$router->post('/login', 'SessionsController@create');

$router->delete('/login', 'SessionsController@destroy');

$router->mount('/cities', function () use ($router) {

    $router->get('/(\d+)/neighborhoods', 'Controllers\CitiesController@neighborhoods');

    $router->get('/statistics', 'Controllers\CitiesController@statistics');

    $router->get('/(\d+)/properties_per_neighborhood', 'Controllers\CitiesController@properties_per_neighborhood');

});

$router->mount('/properties', function () use ($router) {

    $router->get('/', 'Controllers\PropertiesController@index');

    $router->get('/create', 'Controllers\PropertiesController@create');

    $router->post('/', 'Controllers\PropertiesController@store');

    $router->get('/(\d+)', 'Controllers\PropertiesController@show');

    $router->get('/(\d+)/edit', 'Controllers\PropertiesController@edit');

    $router->put('/(\d+)', 'Controllers\PropertiesController@update');

});

$router->run();