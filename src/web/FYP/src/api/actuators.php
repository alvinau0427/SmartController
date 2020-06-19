<?php

require_once ROOT . '/src/db/ActuatorDB.php';
require_once ROOT . '/src/db/SensorDB.php';
require_once ROOT . '/src/db/ConditionDB.php';
require_once ROOT . '/src/controller/AutoController.php';

$this->group('/actuators', function () {
	
	$this->get('[/]', function($request, $response, $args){
		$db = new ActuatorDB();
		$data = $db->getActuatorList();
		return $response->withJson(formatOutput(true, $data, 'success'));
	})->setName('getAllActuators');

	$this->get('/{actuatorID:[0-9]+}[/]', function($request, $response, $args){
		$actuatorID = $request->getAttribute("actuatorID");
		$db = new ActuatorDB();
		$data = $db->getActuator($actuatorID);
		return $response->withJson(formatOutput(true, $data, 'success'));
	})->setName('getActuator');
	
	$this->get('/rooms/{roomID:[0-9]+}[/]', function($request, $response, $args){
		$roomID = $request->getAttribute("roomID");
		$db = new ActuatorDB();
		$data = $db->getActuatorByRoomID($roomID);
		return $response->withJson(formatOutput(true, $data, 'success'));
	})->setName('getActuatorByRoomID');
	
	$this->post('/sensors[/]', function($request, $response, $args){
		$body = $request->getParsedBody();
		$db = new ActuatorDB();
		
		$sensorDB = new SensorDB();
		$sensorArr = $sensorDB->getSensorByActuator($body['actuatorID']);
		foreach($sensorArr as $sensor) {
			if($sensor['SensorID'] == $body['sensorID'])
				return $response->withJson(formatOutput(false, $sensor, 'sensor already added'));
		}
		
		$insertedID = $db->createActuatorSensor($body);
		if($insertedID){
			return $response->withJson(formatOutput(true, $insertedID, 'add sensor success'));
		}else{
			return $response->withJson(formatOutput(false, $insertedID, 'add sensor fail'));
		}
	})->setName('createActuatorSensor');
	
	$this->put('[/]', function($request, $response, $args){
		$body = $request->getParsedBody();
		$db = new ActuatorDB();
		
		$status = $db->updateActuatorStatus($body);
		if(isset($body['userID'])) {
			$recordStatus = $db->createActuatorRecord($body);
		}
		
		if($status){
			
			$auto = new AutoController();
			$auto->updateStatusActuator($body['actuatorID']);
			
			return $response->withJson(formatOutput(true, $status, 'update success'));
		}else{
			return $response->withJson(formatOutput(false, $status, 'update fail'));
		}
	})->setName('updateActuatorStatus');
	
	$this->put('/mode[/]', function($request, $response, $args){
		$body = $request->getParsedBody();
		$db = new ActuatorDB();
		
		$status = $db->updateActuatorMode($body);
		if($status){
			return $response->withJson(formatOutput(true, $status, 'update success'));
		}else{
			return $response->withJson(formatOutput(false, $status, 'update fail'));
		}
	})->setName('updateActuatorMode');
	
	$this->put('/room[/]', function($request, $response, $args){
		$body = $request->getParsedBody();
		$db = new ActuatorDB();
		
		$status = $db->updateActuatorRoom($body);
		if($status){
			return $response->withJson(formatOutput(true, $status, 'update success'));
		}else{
			return $response->withJson(formatOutput(false, $status, 'update fail'));
		}
	})->setName('updateActuatorRoom');
	
	$this->delete('/sensors[/]', function($request, $response, $args){
		$body = $request->getParsedBody();
		$db = new ActuatorDB();
		
		$actuatorSensor = $db->getActuatorSensor($body['actuatorSensorID']);
		
		$conDB = new ConditionDB();
		$conditionArr = $conDB->getConditionBySensor($actuatorSensor['SensorID']);
		
		if(count($conditionArr) > 0) {
			return $response->withJson(formatOutput(false, $conditionArr, 'Please remove the relevant Policy first'));
		}
		
		$deletedID = $db->deleteActuatorSensor($body);
		if($deletedID){
			return $response->withJson(formatOutput(true, $deletedID, 'delete success'));
		}else{
			return $response->withJson(formatOutput(false, $deletedID, 'delete fail'));
		}
	})->setName('deleteActuatorSensor');
	
	
	
	$this->group('/records', function () {
		
		$this->get('[/]', function($request, $response, $args){
			$startIndex = $request->getQueryParam('startIndex', 0);
			$numItems = $request->getQueryParam('numItems', 10);
			$userID = $request->getQueryParam('userID', '');
			$status = $request->getQueryParam('status', '');
			$startTime = $request->getQueryParam('startTime', '');
			$endTime = $request->getQueryParam('endTime', '');
			
			$db = new ActuatorDB();
			$data = $db->getActuatorRecordList($startIndex, $numItems, $userID, $status, $startTime, $endTime);
			return $response->withJson(formatOutput(true, $data, 'success'));
		})->setName('getAllActuatorRecords');
		
		$this->get('/{actuatorID:[0-9]+}[/]', function($request, $response, $args){
			$startIndex = $request->getQueryParam('startIndex', 0);
			$numItems = $request->getQueryParam('numItems', 10);
			$userID = $request->getQueryParam('userID', '');
			$status = $request->getQueryParam('status', '');
			$startTime = $request->getQueryParam('startTime', '');
			$endTime = $request->getQueryParam('endTime', '');
			
			$actuatorID = $request->getAttribute("actuatorID");
			$db = new ActuatorDB();
			$data = $db->getActuatorRecord($actuatorID, $startIndex, $numItems, $userID, $status, $startTime, $endTime);
			return $response->withJson(formatOutput(true, $data, 'success'));
		})->setName('getActuatorRecord');
		
	});
	
	$this->group('/pins', function () {
		
		$this->get('/{device:[0-9]+}[/]', function($request, $response, $args){
			$device = $request->getAttribute("device");
			$db = new ActuatorDB();
			$data = $db->getActuatorPin($device);
			return $response->withJson(formatOutput(true, $data, 'success'));
		})->setName('getActuatorPin');
		
	});
});
?>