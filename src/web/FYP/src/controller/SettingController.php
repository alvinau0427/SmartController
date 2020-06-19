<?php
require_once __DIR__ . '/Controller.php';

class SettingController extends Controller {
	
	public function index($request, $response, $args) {
		
		$actuatorArr = $this->actuatorDB->getActuatorModelList();
		
		return require_once ROOT . '/web/setting-actuator.php';
	}
	
	public function index2($request, $response, $args) {
		
		$sensorArr = $this->sensorDB->getSensorModelList();
		
		return require_once ROOT . '/web/setting-sensor.php';
	}
	
	public function index3($request, $response, $args) {
		
		$roomArr = $this->roomDB->getRoomModelList();
		foreach($roomArr as $room) {
			
			$actuatorArr = $this->actuatorDB->getActuatorModelByRoom($room->getID());
			foreach($actuatorArr as $actuator) {
				$room->addActuator($actuator);
			}
		}
		
		return require_once ROOT . '/web/setting-room.php';
	}
}

?>