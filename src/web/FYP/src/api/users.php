<?php

require_once ROOT . '/src/db/UserDB.php';
require_once ROOT . '/src/db/ActuatorDB.php';
require_once ROOT . '/src/db/HeartRateDB.php';
require_once ROOT . '/src/db/UserLocationDB.php';

$this->group('/users', function () {
	$this->get('[/]', function($request, $response, $args){
		$db = new UserDB();
		$data = $db->getUserList();
		return $response->withJson(formatOutput(true, $data, 'success'));
	})->setName('getAllUser');

	$this->get('/{userID:[0-9]+}[/]', function($request, $response, $args){
		$userID = $request->getAttribute("userID");
		$db = new UserDB();
		$data = $db->getUser($userID);
		return $response->withJson(formatOutput(true, $data, 'success'));
	})->setName('getUser');
	
	$this->get('/image/{account}[/]', function($request, $response, $args){
		$account = $request->getAttribute("account");
		$db = new UserDB();
		$data = $db->getUserImageByAccount($account);
		return $response->withJson(formatOutput(true, $data, 'success'));
	})->setName('getUserImage');
	
	$this->post('/authentication[/]', function($request, $response, $args){
		$body = $request->getParsedBody();
		$db = new UserDB();
		$data = $db->authentication($body);
		if($data){
			return $response->withJson(formatOutput(true, $data, 'success'));
		}else{
			return $response->withJson(formatOutput(false, $data, 'wrong account, password'));
		}
	})->setName('authenticationAccount');
	
	$this->post('[/]', function($request, $response, $args){
		$body = $request->getParsedBody();
		$db = new UserDB();
		
		$hexPass = hash('sha256', $body['password']);
		$hexPass = substr($hexPass,0,10) . "a" . substr($hexPass,11,9) . "a" . substr($hexPass,21);
		
		$body['password'] = $hexPass;
		
		$body['image'] = ($body['type'] == 2) ? "img_admin_user.jpg":"img_normal_user.jpg";
		
		$body['receiveNotification'] = ($body['type'] == 2) ? "1":"0";
		$body['receiveEmail'] = ($body['type'] == 2) ? "1":"0";
		$body['locationDisplay'] = ($body['type'] == 2) ? "0":"1";
		
		$insertedID = $db->createUser($body);
		if($insertedID){
			return $response->withJson(formatOutput(true, $insertedID, 'register success'));
		}else{
			return $response->withJson(formatOutput(false, $insertedID, 'register fail'));
		}
	})->setName('createUser');
	
	$this->put('[/]', function($request, $response, $args){
		$body = $request->getParsedBody();
		$db = new UserDB();
		
		if(!isset($body['password'])) {
			$body['password'] = '';
		}
		
		$body['image'] = ($body['type'] == 2) ? "img_admin_user.jpg":"img_normal_user.jpg";
		
		$status = $db->updateUser($body);
		if($status){
			return $response->withJson(formatOutput(true, $status, 'update success'));
		}else{
			return $response->withJson(formatOutput(false, $status, 'update fail'));
		}
	})->setName('updateUser');
	
	$this->put('/token[/]', function($request, $response, $args){
		$body = $request->getParsedBody();
		$db = new UserDB();
		$status = $db->updateToken($body);
		if($status){
			return $response->withJson(formatOutput(true, $status, 'update success'));
		}else{
			return $response->withJson(formatOutput(false, $status, 'update fail'));
		}
	})->setName('updateToken');
	
	$this->put('/receiveNotification[/]', function($request, $response, $args){
		$body = $request->getParsedBody();
		$db = new UserDB();
		$status = $db->updateReceiveNotification($body);
		if($status){
			return $response->withJson(formatOutput(true, $status, 'update success'));
		}else{
			return $response->withJson(formatOutput(false, $status, 'update fail'));
		}
	})->setName('updateReceiveNotification');
	
	$this->put('/receiveEmail[/]', function($request, $response, $args){
		$body = $request->getParsedBody();
		$db = new UserDB();
		$status = $db->updateReceiveEmail($body);
		if($status){
			return $response->withJson(formatOutput(true, $status, 'update success'));
		}else{
			return $response->withJson(formatOutput(false, $status, 'update fail'));
		}
	})->setName('updateReceiveEmail');
	
	$this->put('/locationDisplay[/]', function($request, $response, $args){
		$body = $request->getParsedBody();
		$db = new UserDB();
		$status = $db->updateLocationDisplay($body);
		if($status){
			return $response->withJson(formatOutput(true, $status, 'update success'));
		}else{
			return $response->withJson(formatOutput(false, $status, 'update fail'));
		}
	})->setName('updateLocationDisplay');
	
	$this->delete('[/]', function($request, $response, $args){
		$body = $request->getParsedBody();
		$db = new UserDB();
		
		$actuatorDB = new ActuatorDB();
		$heartRateDB = new HeartRateDB();
		$userLocationDB = new UserLocationDB();
		
		$actuatorDB->deleteActuatorRecord($body);
		$heartRateDB->deleteHeartRateByUser($body);
		$userLocationDB->deleteUserLocationByUser($body);
		
		$deletedID = $db->deleteUser($body);
		if($deletedID){
			return $response->withJson(formatOutput(true, $deletedID, 'delete success'));
		}else{
			return $response->withJson(formatOutput(false, $deletedID, 'delete fail'));
		}
	})->setName('deleteUser');
	
	require_once __DIR__ . '/heartRates.php';
	require_once __DIR__ . '/userLocations.php';
});
?>