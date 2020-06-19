<?php

require_once ROOT . '/src/db/HeartRateDB.php';
require_once ROOT . '/src/controller/AutoController.php';

$this->group('/heartRates', function () {
	$this->get('[/]', function($request, $response, $args){
		$startIndex = $request->getQueryParam('startIndex', 0);
		$numItems = $request->getQueryParam('numItems', 10);
		$userID = $request->getQueryParam('userID', '');
		
		$db = new HeartRateDB();
		$data = $db->getHeartRateList($startIndex, $numItems, $userID);
		return $response->withJson(formatOutput(true, $data, 'success'));
	})->setName('getAllHeartRates');
	
	$this->get('/{heartRateID:[0-9]+}[/]', function($request, $response, $args){
		
		$heartRateID = $request->getAttribute("heartRateID");
		$db = new HeartRateDB();
		$data = $db->getHeartRate($heartRateID);
		return $response->withJson(formatOutput(true, $data, 'success'));
	})->setName('getHeartRate');
	
	$this->post('[/]', function($request, $response, $args){
		$body = $request->getParsedBody();
		$db = new HeartRateDB();
		$insertedID = $db->createHeartRate($body);
		if($insertedID){
			
			$auto = new AutoController();
			$auto->updateHeartRateDetector($insertedID);
			
			return $response->withJson(formatOutput(true, $insertedID, 'create heart rate success'));
		}else{
			return $response->withJson(formatOutput(false, $insertedID, 'create heart rate fail'));
		}
	})->setName('createHeartRate');
	
	$this->put('[/]', function($request, $response, $args){
		$body = $request->getParsedBody();
		$db = new HeartRateDB();
		$status = $db->updateHeartRate($body);
		if($status){
			return $response->withJson(formatOutput(true, $status, 'update success'));
		}else{
			return $response->withJson(formatOutput(false, $status, 'update fail'));
		}
	})->setName('updateHeartRate');
	
	$this->group('/starts', function () {
		
		$this->get('/{userID:[0-9]+}[/]', function($request, $response, $args){
			
			$userID = $request->getAttribute("userID");
			
			$auto = new AutoController();
			$auto->startHeartRateDetector($userID);
			
			return $response->withJson(formatOutput(true, null, 'start heart rate success'));
		})->setName('startHeartRateDetector');
		
	});
});
?>