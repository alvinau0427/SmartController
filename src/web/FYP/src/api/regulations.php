<?php

require_once ROOT . '/src/db/RegulationDB.php';
require_once ROOT . '/src/db/ConditionDB.php';

$this->group('/regulations', function () {
	
	$this->get('[/]', function($request, $response, $args){
		$db = new RegulationDB();
		$data = $db->getRegulationList();
		return $response->withJson(formatOutput(true, $data, 'success'));
	})->setName('getRegulationList');

	$this->get('/{regulationID:[0-9]+}[/]', function($request, $response, $args){
		$regulationID = $request->getAttribute("regulationID");
		$db = new RegulationDB();
		$data = $db->getRegulation($regulationID);
		return $response->withJson(formatOutput(true, $data, 'success'));
	})->setName('getRegulation');
	
	$this->get('/actuators/{actuatorID:[0-9]+}[/]', function($request, $response, $args){
		$actuatorID = $request->getAttribute("actuatorID");
		$db = new RegulationDB();
		$data = $db->getRegulationByActuator($actuatorID);
		return $response->withJson(formatOutput(true, $data, 'success'));
	})->setName('getRegulationByActuator');


	$this->post('[/]', function($request, $response, $args){
		$body = $request->getParsedBody();
		$db = new RegulationDB();
		
		$regulationArr = $db->getRegulationByActuator($body['actuatorID']);
		$body['priority'] = (int)$regulationArr[count($regulationArr) - 1]['Priority'] + 1;
		
		$insertedID = $db->createRegulation($body);
		if($insertedID){
			return $response->withJson(formatOutput(true, $insertedID, 'create regulation success'));
		}else{
			return $response->withJson(formatOutput(false, $insertedID, 'create regulation fail'));
		}
	})->setName('createRegulation');
	
	$this->put('[/]', function($request, $response, $args){
		$body = $request->getParsedBody();
		$db = new RegulationDB();
		$status = $db->updateRegulation($body);
		if($status){
			return $response->withJson(formatOutput(true, $status, 'update success'));
		}else{
			return $response->withJson(formatOutput(false, $status, 'update fail'));
		}
	})->setName('updateRegulation');
	
	$this->put('/logicgate[/]', function($request, $response, $args){
		$body = $request->getParsedBody();
		$db = new RegulationDB();
		
		$reg = $db->getRegulation($body['regulationID']);
		$oldLogicGate = $reg['LogicGate'];
		
		if(strlen($body['logicGate']) < strlen($oldLogicGate)) {
			$deleParts = substr($oldLogicGate, strlen($body['logicGate']));
			$logicGateArr = explode(" ", $deleParts);
			$conDB = new ConditionDB();
			foreach($logicGateArr as $lg) {
			
				if(is_numeric($lg)) {	//is  a number
					$con['conditionID'] = $lg;
					$conDB->deleteCondition($con);
				}
			}
		}
		
		
		$status = $db->updateRegulationLogicGate($body);
		if($status){
			return $response->withJson(formatOutput(true, $status, 'update success'));
		}else{
			return $response->withJson(formatOutput(false, $status, 'update fail'));
		}
	})->setName('updateRegulationLogicGate');
	
	$this->put('/priority[/]', function($request, $response, $args){
		$body = $request->getParsedBody();
		$db = new RegulationDB();
		
		$regulationID = $body['regulationID'];
		$reg = $db->getRegulation($regulationID);
		
		$oldPriority = $reg['Priority'];
		$newPriority = $body['priority'];
		$actuatorID = $reg['ActuatorID'];
		
		if($newPriority < $oldPriority) { //up priority
		
			$regulationArr = $db->getRegulationBetweenPriority($actuatorID, $newPriority, $oldPriority - 1);
			
			foreach($regulationArr as $regulation) {
				$status = $db->updateRegulationPriority(array("regulationID"=>$regulation['RegulationID'],
													"priority"=>$regulation['Priority'] + 1));
				if(!$status) {
					break;
				}
			}
			
		} else { //down priority
		
			$regulationArr = $db->getRegulationBetweenPriority($actuatorID, $oldPriority + 1, $newPriority);
			
			foreach($regulationArr as $regulation) {
				$status = $db->updateRegulationPriority(array("regulationID"=>$regulation['RegulationID'],
													"priority"=>$regulation['Priority'] - 1));
													
				if(!$status) {
					break;
				}									
			}
		}
		
		if($status) {
			$status = $db->updateRegulationPriority(array("regulationID"=>$regulationID,
														"priority"=>$newPriority));
		}
		
		if($status){
			return $response->withJson(formatOutput(true, $status, 'update success'));
		}else{
			return $response->withJson(formatOutput(false, $status, 'update fail'));
		}
	})->setName('updateRegulationPriority');
	
	$this->delete('[/]', function($request, $response, $args){
		$body = $request->getParsedBody();
		$db = new RegulationDB();
		
		$regulationID = $body['regulationID'];
		$reg = $db->getRegulation($regulationID);
		
		$priority = $reg['Priority'];
		$actuatorID = $reg['ActuatorID'];
		
		$lastPriority = count($db->getRegulationByActuator($actuatorID));
		
		$regulationArr = $db->getRegulationBetweenPriority($actuatorID, $priority + 1, $lastPriority);
			
		foreach($regulationArr as $regulation) {
			$status = $db->updateRegulationPriority(array("regulationID"=>$regulation['RegulationID'],
												"priority"=>$regulation['Priority'] - 1));
		}
		
		$conDB = new ConditionDB();
		$deletedID = $conDB->deleteConditionByRegulation($body);
		if($deletedID){
			$deletedID = $db->deleteRegulation($body);
			if($deletedID){
				return $response->withJson(formatOutput(true, $deletedID, 'delete regulation success'));
			}else{
				return $response->withJson(formatOutput(false, $deletedID, 'delete regulation fail'));
			}
		}else{
			return $response->withJson(formatOutput(false, $deletedID, 'delete condition fail'));
		}
	})->setName('deleteRegulation');
	
});
?>