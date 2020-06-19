<?php
require_once __DIR__ . '/Sensor.php';
require_once __DIR__ . '/Actuator.php';

class Device {
	private $ID;
	private $name;
	private $IP;
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
	
	public function getIP() {
		return $this->IP;
	}
	
	public function setIP($IP) {
		$this->IP = $IP;
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
	
	public function getModule() {
		return $this->actuator;
	}
	
	public function addModule($m) {
		$this->actuator[] = $m;
	}
	
	public function removeModule($m) {
		$this->actuator = array_diff($this->actuator, [$m]);
	}
}

?>