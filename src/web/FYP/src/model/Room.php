<?php
require_once __DIR__ . '/Sensor.php';
require_once __DIR__ . '/Actuator.php';

class Room {
	private $ID;
	private $name;
	private $image;
	private $sensor = array();
	private $actuator = array();
	
	public function __construct() {}
	
	public function getID() {
		return $this->ID;
	}
	
	public function setID($ID) {
		$this->ID = $ID;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function setName($name) {
		$this->name = $name;
	}
	
	public function getImage() {
		return $this->image;
	}
	
	public function setImage($image) {
		$this->image = $image;
	}
	
	public function getSensor() {
		return $this->sensor;
	}
	
	public function addSensor($s) {
		$this->sensor[] = $s;
	}
	
	public function removeSensor($s) {
		$this->sensor = array_diff($this->sensor, [$s]);
	}
	
	public function getActuator() {
		return $this->actuator;
	}
	
	public function addActuator($a) {
		$this->actuator[] = $a;
	}
	
	public function removeActuator($a) {
		$this->actuator = array_diff($this->actuator, [$a]);
	}
}

?>