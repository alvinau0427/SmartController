<?php

require_once __DIR__ . '/../db/NotificationRuleDB.php';

$this->group('/notifications', function () {
	
	$this->get('[/]', function($request, $response, $args){
		$db = new NotificationRuleDB();
		$data = $db->getNotificationRuleList();
		return $response->withJson(formatOutput(true, $data, 'success'));
	})->setName('getAllNotifications');
	
	$this->get('/{ruleID:[0-9]+}[/]', function($request, $response, $args){
		$ruleID = $request->getAttribute("ruleID");
		$db = new NotificationRuleDB();
		$data = $db->getNotificationRule($ruleID);
		return $response->withJson(formatOutput(true, $data, 'success'));
	})->setName('getNotificationRule');
	
	$this->get('/{device:[0-9]+}/{pin:[0-9]+}[/]', function($request, $response, $args){
		$pin = $request->getAttribute("pin");
		$device = $request->getAttribute("device");
		$db = new NotificationRuleDB();
		$data = $db->getNotificationRuleBySensor($device, $pin);
		return $response->withJson(formatOutput(true, $data, 'success'));
	})->setName('getNotificationRuleBySensor');
	
	$this->put('[/]', function($request, $response, $args){
		$body = $request->getParsedBody();
		$db = new NotificationRuleDB();
		$status = $db->updateNotificationRule($body);
		if($status){
			return $response->withJson(formatOutput(true, $status, 'update success'));
		}else{
			return $response->withJson(formatOutput(false, $status, 'update fail'));
		}
	})->setName('updateNotificationRule');
	
	$this->group('/records', function () {
		
		$this->get('[/]', function($request, $response, $args){
			$db = new NotificationRuleDB();
			$data = $db->getNotificationRuleRecordList();
			return $response->withJson(formatOutput(true, $data, 'success'));
		})->setName('getAllNotificationRecords');
		
		$this->get('/{device:[0-9]+}/{pin:[0-9]+}[/]', function($request, $response, $args){
			$pin = $request->getAttribute("pin");
			$device = $request->getAttribute("device");
			$db = new NotificationRuleDB();
			$data = $db->getNotificationRuleRecord($device, $pin);
			return $response->withJson(formatOutput(true, $data, 'success'));
		})->setName('getNotificationRuleRecord');
		
	});
});
?>