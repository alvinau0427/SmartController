<?php

require_once ROOT . '/src/db/TimeDB.php';

$this->group('/times', function () {
	
	$this->get('[/]', function($request, $response, $args){
		$db = new TimeDB();
		$data = $db->getTimeList();
		return $response->withJson(formatOutput(true, $data, 'success'));
	})->setName('getAllTimes');
	
	$this->get('/{ruleID:[0-9]+}[/]', function($request, $response, $args){
		$ruleID = $request->getAttribute("ruleID");
		$db = new TimeDB();
		$data = $db->getTime($ruleID);
		return $response->withJson(formatOutput(true, $data, 'success'));
	})->setName('getTime');
	
	$this->get('/actuators/{actuatorID:[0-9]+}[/]', function($request, $response, $args){
		$actuatorID = $request->getAttribute("actuatorID");
		$db = new TimeDB();
		$data = $db->getTimeByActuator($actuatorID);
		return $response->withJson(formatOutput(true, $data, 'success'));
	})->setName('getTimeByActuator');
	
	$this->post('[/]', function($request, $response, $args){
		$body = $request->getParsedBody();
		$db = new TimeDB();
		$insertedID = $db->createTime($body);
		if($insertedID){
			return $response->withJson(formatOutput(true, $insertedID, 'create Time Rule success'));
		}else{
			return $response->withJson(formatOutput(false, $insertedID, 'create Time Rule fail'));
		}
	})->setName('createTime');
	
	$this->put('[/]', function($request, $response, $args){
		$body = $request->getParsedBody();
		$db = new TimeDB();
		$status = $db->updateTime($body);
		if($status){
			return $response->withJson(formatOutput(true, $status, 'update success'));
		}else{
			return $response->withJson(formatOutput(false, $status, 'update fail'));
		}
	})->setName('updateTime');
	
	$this->delete('[/]', function($request, $response, $args){
		$body = $request->getParsedBody();
		$db = new TimeDB();
		$deletedID = $db->deleteTime($body);
		if($deletedID){
			return $response->withJson(formatOutput(true, $deletedID, 'delete success'));
		}else{
			return $response->withJson(formatOutput(false, $deletedID, 'delete fail'));
		}
	})->setName('deleteTime');
	
});
?>