<?php
require_once 'config/connection.php';
require_once 'lib/Bramus/Router.php';
require_once 'lib/ImageUploader.php';
require_once 'lib/PDFGenerator.php';
require_once 'models/City.php';
require_once 'models/Image.php';
require_once 'models/Property.php';
require_once 'models/User.php';
require_once 'models/Question.php';
require_once 'models/DismissReason.php';
require_once 'controllers/BaseController.php';
require_once 'controllers/HomeController.php';
require_once 'controllers/PropertiesController.php';
require_once 'controllers/CitiesController.php';
require_once 'controllers/SessionsController.php';
require_once 'controllers/QuestionsController.php';

session_start();

// Create a Router
$router = new \Bramus\Router\Router();

$router->set404(function () {
    header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
    echo '404, route not found!';
});

$get_admin_routes = [
    '/properties/create',
    '/properties/manage_properties',
    '/properties/(\d+)/edit',
    '/questions/unanswered'
];

$post_admin_routes = [
    '/properties/(\d+)',
    '/properties/',
    '/properties/(\d+)/update',
    '/questions/',
    '/questions/answer'
];

$get_admin_routes_patterns  = implode('|', $get_admin_routes);
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

$router->before('GET', '/.*', function () {
    if (isset($_SESSION['user'])) {
        $_SESSION['unanswered_questions'] = \Models\Question::where('response IS NULL')->count();
    }
});

$router->get('/', 'Controllers\HomeController@index');

$router->post('/login', 'Controllers\SessionsController@create');

$router->get('/logout', 'Controllers\SessionsController@destroy');

$router->mount('/cities', function () use ($router) {

    $router->get('/(\d+)/neighborhoods', 'Controllers\CitiesController@neighborhoods');

    $router->get('/statistics', 'Controllers\CitiesController@statistics');

    $router->get('/(\d+)/properties_per_neighborhood', 'Controllers\CitiesController@properties_per_neighborhood');

});

$router->mount('/questions', function () use ($router) {

    $router->get('/unanswered', 'Controllers\QuestionsController@unanswered_questions');

    $router->post('/', 'Controllers\QuestionsController@store');

    $router->post('/answer', 'Controllers\QuestionsController@answer');

});

$router->mount('/properties', function () use ($router) {

    $router->get('/', 'Controllers\PropertiesController@index');

    $router->get('/manage_properties', 'Controllers\PropertiesController@manage_properties');

    $router->get('/create', 'Controllers\PropertiesController@create');

    $router->post('/', 'Controllers\PropertiesController@store');

    $router->get('/(\d+)', 'Controllers\PropertiesController@show');

    $router->get('/(\d+)/modal', 'Controllers\PropertiesController@modal');

    $router->get('/(\d+)/edit', 'Controllers\PropertiesController@edit');

    $router->post('/(\d+)/update', 'Controllers\PropertiesController@update');

    $router->post('/(\d+)', 'Controllers\PropertiesController@destroy');

    $router->get('/(\d+)/generate_pdf', 'Controllers\PropertiesController@generate_pdf');

});

$router->run();