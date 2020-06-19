<?php

require_once ROOT . '/src/db/ConditionDB.php';

$this->group('/conditions', function () {
	
	$this->get('[/]', function($request, $response, $args){
		$db = new ConditionDB();
		$data = $db->getConditionList();
		return $response->withJson(formatOutput(true, $data, 'success'));
	})->setName('getConditionList');

	$this->get('/{conditionsID:[0-9]+}[/]', function($request, $response, $args){
		$conditionsID = $request->getAttribute("conditionsID");
		$db = new ConditionDB();
		$data = $db->getCondition($conditionsID);
		return $response->withJson(formatOutput(true, $data, 'success'));
	})->setName('getCondition');
	
	$this->get('/regulations/{regulationID:[0-9]+}[/]', function($request, $response, $args){
		$regulationID = $request->getAttribute("regulationID");
		$db = new ConditionDB();
		$data = $db->getConditionByRegulation($regulationID);
		return $response->withJson(formatOutput(true, $data, 'success'));
	})->setName('getConditionByRegulation');
	
	$this->get('/sensors/{sensorID:[0-9]+}[/]', function($request, $response, $args){
		$sensorID = $request->getAttribute("sensorID");
		$db = new ConditionDB();
		$data = $db->getConditionBySensor($sensorID);
		return $response->withJson(formatOutput(true, $data, 'success'));
	})->setName('getConditionBySensor');


	$this->post('[/]', function($request, $response, $args){
		$body = $request->getParsedBody();
		$db = new ConditionDB();
		$insertedID = $db->createCondition($body);
		if($insertedID){
			return $response->withJson(formatOutput(true, $insertedID, 'create condition success'));
		}else{
			return $response->withJson(formatOutput(false, $insertedID, 'create condition fail'));
		}
	})->setName('createCondition');
	
	$this->put('[/]', function($request, $response, $args){
		$body = $request->getParsedBody();
		$db = new ConditionDB();
		$status = $db->updateCondition($body);
		if($status){
			return $response->withJson(formatOutput(true, $status, 'update success'));
		}else{
			return $response->withJson(formatOutput(false, $status, 'update fail'));
		}
	})->setName('updateCondition');
	
	$this->delete('[/]', function($request, $response, $args){
		$body = $request->getParsedBody();
		$db = new ConditionDB();
		$deletedID = $db->deleteCondition($body);
		if($deletedID){
			return $response->withJson(formatOutput(true, $deletedID, 'delete success'));
		}else{
			return $response->withJson(formatOutput(false, $deletedID, 'delete fail'));
		}
	})->setName('deleteCondition');
	
});
?>