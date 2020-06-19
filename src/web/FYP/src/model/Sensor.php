<?php
require_once __DIR__ . '/Component.php';
require_once __DIR__ . '/Actuator.php';
require_once __DIR__ . '/ActuatorSensor.php';

class Sensor extends Component{
	private $value;
	private $unit;
	private $actuatorSensor = array();
	
	public function __construct(){}
	
	public function getValue() {
		return $this->value;
	}
	
	public function setValue($value) {
		$this->value = $value;
	}
	
	public function getUnit() {
		return $this->unit;
	}
	
	public function setUnit($unit) {
		$this->unit = $unit;
	}
	
	public function addActuatorSensor($as) {
		$this->actuatorSensor[] = $as;
	}
	
	public function removeActuatorSensor($as) {
		$this->actuatorSensor = array_diff($this->actuatorSensor, [$as]);
	}
		
	public function getActuator() {
		$actuator = array();
		foreach($this->actuatorSensor as $val) {
			$actuator[] = $val->getActuator();
		}
		return $actuator;
	}
	
	public function jsonSerialize() {
		$vars = get_object_vars($this);

		return $vars;
    }
}
?>