<?php

require_once ROOT . '/src/db/DeviceDB.php';

$this->group('/devices', function () {
	$this->get('[/]', function($request, $response, $args){
		$db = new DeviceDB();
		$data = $db->getDeviceList();
		return $response->withJson(formatOutput(true, $data, 'success'));
	})->setName('getDeviceList');
	
	$this->get('/{deviceID:[0-9]+}[/]', function($request, $response, $args){
		$deviceID = $request->getAttribute("deviceID");
		$db = new DeviceDB();
		$data = $db->getDevice($deviceID);
		return $response->withJson(formatOutput(true, $data, 'success'));
	})->setName('getDevice');
	
	$this->put('[/]', function($request, $response, $args){
		$body = $request->getParsedBody();
		$db = new DeviceDB();
		$status = $db->updateDeviceIP($body);
		if($status){
			return $response->withJson(formatOutput(true, $status, 'update success'));
		}else{
			return $response->withJson(formatOutput(false, $status, 'update fail'));
		}
	})->setName('updateDeviceIP');
});
?>