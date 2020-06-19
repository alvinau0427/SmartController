<?php
require_once __DIR__ . '/Actuator.php';
require_once __DIR__ . '/Sensor.php';

class ActuatorSensor {
	//private $id;
	private $actuator;
	private $sensor;
	
	private function __construct($a, $s){
		$this->actuator = $a;
		$this->sensor = $s;
		//$this->id = $id;
	}
	
	public static function addActuatorSensor($a, $s) {
		$as = new ActuatorSensor($a, $s);
		$s->addActuatorSensor($as);
		$a->addActuatorSensor($as);
	}
	
	public function removeActuatorSensor() {
		$this->sensor->removeActuatorSensor($this);
		$this->actuator->removeActuatorSensor($this);
	}
	
	public function getActuator() {
		return $this->actuator;
	}
	
	public function getSensor() {
		return $this->sensor;
	}
	
	/*public function getId() {
		return $this->id;
	}*/
}

?>