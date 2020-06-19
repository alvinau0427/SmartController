<?php

require_once ROOT . '/src/db/RoomDB.php';
require_once ROOT . '/src/db/ActuatorDB.php';

$this->group('/rooms', function () {
	$this->get('[/]', function($request, $response, $args){
		$db = new RoomDB();
		$data = $db->getRoomList();
		return $response->withJson(formatOutput(true, $data, 'success'));
	})->setName('getAllRooms');

	$this->get('/{roomID:[0-9]+}[/]', function($request, $response, $args){
		$roomID = $request->getAttribute("roomID");
		$db = new RoomDB();
		$data = $db->getRoom($roomID);
		return $response->withJson(formatOutput(true, $data, 'success'));
	})->setName('getRoom');
	
	/*$this->get('/details/{location:[0-9]+}[/]', function($request, $response, $args){
		$location = $request->getAttribute("location");
		$db = new RoomDB();
		$data = $db->getRoomDetail($location);
		return $response->withJson(formatOutput(true, $data, 'success'));
	})->setName('getRoomDetail');*/
	
	$this->post('[/]', function($request, $response, $args){
		$body = $request->getParsedBody();
		$db = new RoomDB();
		$body["image"] = "img_room.png";
		$insertedID = $db->createRoom($body);
		if($insertedID){
			return $response->withJson(formatOutput(true, $insertedID, 'create room success'));
		}else{
			return $response->withJson(formatOutput(false, $insertedID, 'create room fail'));
		}
	})->setName('createRoom');
	
	$this->put('[/]', function($request, $response, $args){
		$body = $request->getParsedBody();
		$db = new RoomDB();
		$status = $db->updateRoom($body);
		if($status){
			return $response->withJson(formatOutput(true, $status, 'update success'));
		}else{
			return $response->withJson(formatOutput(false, $status, 'update fail'));
		}
	})->setName('updateRoom');
	
	$this->delete('[/]', function($request, $response, $args){
		$body = $request->getParsedBody();
		$db = new RoomDB();
		
		$actuatorDB = new ActuatorDB();
		// update null to all actuator in this room 
		$actuatorArr = $actuatorDB->getActuatorByRoomID($body['roomID']);
		
		foreach($actuatorArr as $actuator) {
			$status = $actuatorDB->updateActuatorRoom(array("actuatorID"=>$actuator['ActuatorID'],
															"roomID"=>"null"));
		}
		
		$deletedID = $db->deleteRoom($body);
		if($deletedID){
			return $response->withJson(formatOutput(true, $deletedID, 'delete success'));
		}else{
			return $response->withJson(formatOutput(false, $deletedID, 'delete fail'));
		}
	})->setName('deleteRoom');
	
});
?>