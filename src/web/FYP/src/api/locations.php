<?php

require_once __DIR__ . '/../db/LocationDB.php';

$this->group('/locations', function () {
	$this->get('[/]', function($request, $response, $args){
		$db = new LocationDB();
		$data = $db->getLocationList();
		return $response->withJson(formatOutput(true, $data, 'success'));
	})->setName('getLocationList');
	
	$this->get('/{locationID:[0-9]+}[/]', function($request, $response, $args){
		$locationID = $request->getAttribute("locationID");
		$db = new LocationDB();
		$data = $db->getLocation($locationID);
		return $response->withJson(formatOutput(true, $data, 'success'));
	})->setName('getLocation');
	
});
?>