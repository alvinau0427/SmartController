<?php

require_once ROOT . '/src/db/UserLocationDB.php';

$this->group('/locations', function () {
	$this->get('[/]', function($request, $response, $args){
		$db = new UserLocationDB();
		$data = $db->getUserLocationList();
		return $response->withJson(formatOutput(true, $data, 'success'));
	})->setName('getAllUserLocations');
	
	$this->get('/{userLocationID:[0-9]+}[/]', function($request, $response, $args){
		$userLocationID = $request->getAttribute("userLocationID");
		$db = new UserLocationDB();
		$data = $db->getUserLocation($userLocationID);
		return $response->withJson(formatOutput(true, $data, 'success'));
	})->setName('getUserLocation');
	
	$this->get('/users/{userID:[0-9]+}[/]', function($request, $response, $args){
		$userID = $request->getAttribute("userID");
		$db = new UserLocationDB();
		$data = $db->getUserLocationByUser($userID);
		return $response->withJson(formatOutput(true, $data, 'success'));
	})->setName('getUserLocationByUser');
	
	
	/*$this->get('/locations/{locationID:[0-9]+}[/]', function($request, $response, $args){
		$locationID = $request->getAttribute("locationID");
		$db = new UserLocationDB();
		$data = $db->getUserLocationByLocation($locationID);
		return $response->withJson(formatOutput(true, $data, 'success'));
	})->setName('getUserLocationByLocation');*/
	
	
	$this->post('[/]', function($request, $response, $args){
		$body = $request->getParsedBody();
		$db = new UserLocationDB();
		$insertedID = $db->createUserLocation($body);
		if($insertedID){
			return $response->withJson(formatOutput(true, $insertedID, 'create user location success'));
		}else{
			return $response->withJson(formatOutput(false, $insertedID, 'create user location fail'));
		}
	})->setName('createUserLocation');
	
	$this->put('[/]', function($request, $response, $args){
		$body = $request->getParsedBody();
		$db = new UserLocationDB();
		$status = $db->updateUserLocation($body);
		if($status){
			return $response->withJson(formatOutput(true, $status, 'update success'));
		}else{
			return $response->withJson(formatOutput(false, $status, 'update fail'));
		}
	})->setName('updateUserLocation');
});
?>