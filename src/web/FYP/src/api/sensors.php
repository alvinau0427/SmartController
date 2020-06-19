<?php

require_once ROOT . '/src/db/SensorDB.php';
require_once ROOT . '/src/controller/AutoController.php';

$this->group('/sensors', function () {
	
	$this->get('[/]', function($request, $response, $args){
		$db = new SensorDB();
		$data = $db->getSensorList();
		return $response->withJson(formatOutput(true, $data, 'success'));
	})->setName('getAllSensor');

	$this->get('/{sensorID:[0-9]+}[/]', function($request, $response, $args){
		$sensorID = $request->getAttribute("sensorID");
		$db = new SensorDB();
		$data = $db->getSensor($sensorID);
		return $response->withJson(formatOutput(true, $data, 'success'));
	})->setName('getSensor');

	$this->get('/rooms/{roomID:[0-9]+}[/]', function($request, $response, $args){
		$roomID = $request->getAttribute("roomID");
		$db = new SensorDB();
		$data = $db->getSensorByRoomID($roomID);
		return $response->withJson(formatOutput(true, $data, 'success'));
	})->setName('getSensorByRoomID');
	
	$this->get('/actuators/{actuatorID:[0-9]+}[/]', function($request, $response, $args){
		$actuatorID = $request->getAttribute("actuatorID");
		$db = new SensorDB();
		$data = $db->getSensorByActuator($actuatorID);
		return $response->withJson(formatOutput(true, $data, 'success'));
	})->setName('getSensorByActuator');
	
	$this->put('[/]', function($request, $response, $args){
		$body = $request->getParsedBody();
		$db = new SensorDB();
		
		$status = $db->updateSensorValue($body);
		if($status){
			
			$auto = new AutoController();
			$auto->updateValueSensor($body['sensorID']);
			
			return $response->withJson(formatOutput(true, $status, 'update success'));
		}else{
			return $response->withJson(formatOutput(false, $status, 'update fail'));
		}
	})->setName('updateSensorValue');
	
	$this->group('/records', function () {
		
		$this->get('/{device:[0-9]+}/{pin:[0-9]+}[/]', function($request, $response, $args){
			$pin = $request->getAttribute("pin");
			$device = $request->getAttribute("device");
			$db = new SensorDB();
			$data = $db->getSensorRecord($device, $pin);
			return $response->withJson(formatOutput(true, $data, 'success'));
		})->setName('getSensorRecord');
		
	});
	
	$this->group('/pins', function () {
		
		$this->get('/{device:[0-9]+}[/]', function($request, $response, $args){
			$device = $request->getAttribute("device");
			$db = new SensorDB();
			$data = $db->getSensorPin($device);
			return $response->withJson(formatOutput(true, $data, 'success'));
		})->setName('getModulePin');
		
	});
});

?>