<?php

require_once ROOT . '/src/db/WeatherDB.php';
require_once ROOT . '/src/controller/AutoController.php';

$this->group('/weathers', function () {
	$this->get('[/]', function($request, $response, $args){
		$db = new WeatherDB();
		$data = $db->getWeatherList();
		return $response->withJson(formatOutput(true, $data, 'success'));
	})->setName('getAllWeather');
	
	$this->get('/{recordID:[0-9]+}[/]', function($request, $response, $args){
		$recordID = $request->getAttribute("recordID");
		$db = new WeatherDB();
		$data = $db->getWeather($recordID);
		return $response->withJson(formatOutput(true, $data, 'success'));
	})->setName('getWeather');
	
	$this->put('[/]', function($request, $response, $args){
		$body = $request->getParsedBody();
		$db = new WeatherDB();
		$status = $db->updateWeather($body);
		if($status){
			return $response->withJson(formatOutput(true, $status, 'update success'));
		}else{
			return $response->withJson(formatOutput(false, $status, 'update fail'));
		}
	})->setName('updateWeather');

	$this->put('/simulations[/]', function($request, $response, $args){
		$body = $request->getParsedBody();
		$db = new WeatherDB();
		
		$status = $db->simulateWeather($body);

		if($status){

			$auto = new AutoController();
			$auto->updateWeather($body['rain']);

			return $response->withJson(formatOutput(true, $status, 'update success'));
		}else{
			return $response->withJson(formatOutput(false, $status, 'update fail'));
		}
	})->setName('updateWeather');
});
?>