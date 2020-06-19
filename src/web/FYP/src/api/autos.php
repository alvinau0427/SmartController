<?php

require_once __DIR__ . '/../db/AutoRuleDB.php';

$this->group('/autos', function () {
	
	$this->get('[/]', function($request, $response, $args){
		$db = new AutoRuleDB();
		$data = $db->getAutoRuleList();
		return $response->withJson(formatOutput(true, $data, 'success'));
	})->setName('getAllAutoRules');
	
	$this->get('/{ruleID:[0-9]+}[/]', function($request, $response, $args){
		$ruleID = $request->getAttribute("ruleID");
		$db = new AutoRuleDB();
		$data = $db->getAutoRule($ruleID);
		return $response->withJson(formatOutput(true, $data, 'success'));
	})->setName('getAutoRule');
	
	$this->get('/{device:[0-9]+}/{pin:[0-9]+}[/]', function($request, $response, $args){
		$pin = $request->getAttribute("pin");
		$device = $request->getAttribute("device");
		$db = new AutoRuleDB();
		$data = $db->getAutoRuleByDevice($device, $pin);
		return $response->withJson(formatOutput(true, $data, 'success'));
	})->setName('getAutoRuleByDevice');
	
	/*$this->post('[/]', function($request, $response, $args){
		$body = $request->getParsedBody();
		$db = new AutoRuleDB();
		$insertedID = $db->createTimeRule($body);
		if($insertedID){
			return $response->withJson(formatOutput(true, $insertedID, 'create Time Rule success'));
		}else{
			return $response->withJson(formatOutput(false, $insertedID, 'create Time Rule fail'));
		}
	})->setName('createTimeRule');*/
	
	$this->put('[/]', function($request, $response, $args){
		$body = $request->getParsedBody();
		$db = new AutoRuleDB();
		$status = $db->updateAutoRule($body);
		if($status){
			return $response->withJson(formatOutput(true, $status, 'update success'));
		}else{
			return $response->withJson(formatOutput(false, $status, 'update fail'));
		}
	})->setName('updateAutoRule');
	
	/*$this->delete('[/]', function($request, $response, $args){
		$body = $request->getParsedBody();
		$db = new AutoRuleDB();
		$deletedID = $db->deleteTimeRule($body);
		if($deletedID){
			return $response->withJson(formatOutput(true, $deletedID, 'delete success'));
		}else{
			return $response->withJson(formatOutput(false, $deletedID, 'delete fail'));
		}
	})->setName('deleteTimeRule');*/
	
	$this->group('/records', function () {
		
		$this->get('/{device:[0-9]+}/{pin:[0-9]+}[/]', function($request, $response, $args){
			$pin = $request->getAttribute("pin");
			$device = $request->getAttribute("device");
			$db = new AutoRuleDB();
			$data = $db->getAutoRuleRecord($device, $pin);
			return $response->withJson(formatOutput(true, $data, 'success'));
		})->setName('getAutoRuleRecord');
		
	});
});
?>