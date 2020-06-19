<?php
require_once __DIR__ . '/Controller.php';

class RoomController extends Controller {
	
	public function index($request, $response, $args) {
		$roomID = $request->getAttribute("roomID");
		
		return $this->renderRoomPage($request, $response, $args, $roomID);
	}
	
	public function renderRoomPage($request, $response, $args, $roomID) {
		
		$sensoriIdenticalArr = array();
		
		$room = $this->roomDB->getRoomModelById($roomID);
		
		$actuatorArr = $this->actuatorDB->getActuatorModelByRoom($room->getID());
		foreach($actuatorArr as $actuator) {
			
			//get Sensor and add to the actuator
			$sensorArr = $this->sensorDB->getSensorModelByActuator($actuator->getID());
			foreach($sensorArr as $sensor) {
				if( isset($sensoriIdenticalArr[$sensor->getID()]) ) {
					$sensor = $sensoriIdenticalArr[$sensor->getID()];
				} else {
					$sensoriIdenticalArr[$sensor->getID()] = $sensor;
				}
				ActuatorSensor::addActuatorSensor($actuator, $sensor);
			}
			
			
			//get Time and add to the actuator
			$timeArr = $this->timeDB->getTimeModelByActuator($actuator->getID());
			foreach($timeArr as $time) {
				$actuator->addTime($time);
			}
			
			
			//get Regulation and add to actuator
			$regulationArr = $this->regulationDB->getRegulationModelByActuator($actuator->getID());
			foreach($regulationArr as $regulation) {
				$conditionArr = $this->conditionDB->getConditionModelByRegulation($regulation->getID());
				
				foreach($conditionArr as $condition) {
					$s = $sensoriIdenticalArr[$condition->getSensorID()];
					$condition->setSensor($s);
					
					$regulation->addCondition($condition);
				}
				
				$actuator->addRegulation($regulation);
			}
			
		}
		
		return require_once ROOT . '/web/room.php';
		//$this->view->render($response,'function.php');
	}
}

?>