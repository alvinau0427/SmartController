<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/common.php';
$config = require 'config.php';

$app = new \Slim\App($config);

// Get container
$container = $app->getContainer();

// Register component on container
$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig(__DIR__ . '/web', [
        'cache' => false
    ]);
    // $view->addExtension(new \Slim\Views\TwigExtension(
    //     $container['router'],
    //     $container['request']->getUri()
    // ));

    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));

    return $view;
};

$app->group('/', function () {
	
	foreach(glob(__DIR__ . "/src/controller/*.php") as $filename)
	{
		require_once $filename;
	}
	
	$this->get('[/]', 'HomeController:index' );
	$this->get('index[/]', 'HomeController:index' );
	
	$this->group('login', function () {
		$this->get('[/]', 'LoginController:index' );
		$this->post('/authentication[/]', 'LoginController:authentication' );
	});
	
	$this->group('room', function () {
		$this->get('/{roomID:[0-9]+}[/]', 'RoomController:index' );
	});
	
	$this->get('notification[/]', 'NotificationController:index' );
	
	$this->get('profile[/]', 'ProfileController:index' );
	
	$this->get('location[/]', 'LocationController:index' );
	
	$this->group('setting', function () {
		$this->get('/actuator[/]', 'SettingController:index' );
		$this->get('/sensor[/]', 'SettingController:index2' );
		$this->get('/room[/]', 'SettingController:index3' );
	});
	
	
});

$app->get('/show', function ($request, $response, $args) {
	$db = new Db\ModuleDB();
	$data = $db->getServoList();
	require 'templates/show.php';
    //return $this->view->render($response, 'show.php');
});

$app->group('/api', function () {
	require_once ROOT . '/src/api/actuators.php';
	require_once ROOT . '/src/api/sensors.php';
	require_once ROOT . '/src/api/regulations.php';
	require_once ROOT . '/src/api/conditions.php';
	require_once ROOT . '/src/api/users.php';
	require_once ROOT . '/src/api/rooms.php';
	require_once ROOT . '/src/api/times.php';
	require_once ROOT . '/src/api/weathers.php';
	//require_once ROOT . '/src/api/locations.php';
	require_once ROOT . '/src/api/devices.php';
	$this->get('[/{params:.*}]', function($request, $response, $args){
		return $response->withJson(formatOutput(false, null, 'No that page'));
	});
});
/*->add(function ($request, $response, $next) {
    $response->getBody()->write('It is now ');
    $response = $next($request, $response);
    $response->getBody()->write('. Enjoy!');
	return $response;
    return $response->withRedirect('/FYP/api/modules');
});*/


$app->run();