<?php
require_once __DIR__ . '/Sensor.php';
foreach(glob(__DIR__ . "/ComparisonOperator/*.php") as $filename)
{
	require_once $filename;
}

class Condition{
	private $ID;
	private $value;
	private $sensor;
	private $sensorID;
	private $comparisonOperator;
	
	public function __construct(){}
	
	public function getID() {
		return $this->ID;
	}
	
	public function setID($ID) {
		$this->ID = $ID;
	}
	
	public function getValue() {
		return $this->value;
	}
	
	public function setValue($value) {
		$this->value = $value;
	}
	
	public function getSensor() {
		return $this->sensor;
	}
	
	public function setSensor($sensor) {
		$this->sensor = $sensor;
	}
	
	public function getSensorID() {
		return $this->sensorID;
	}
	
	public function setSensorID($sensorID) {
		$this->sensorID = $sensorID;
	}
	
	public function getComparisonOperator() {
		return $this->comparisonOperator;
	}
	
	public function setComparisonOperator($comparisonOperator) {
		$this->comparisonOperator = $comparisonOperator;
	}
	
	public function check() {
		//echo $this->sensor->getValue() . ' ' . $this->value . PHP_EOL;
		return $this->comparisonOperator->compare($this->sensor->getValue(),$this->value);
	}
	
	public function jsonSerialize() {
		$vars = get_object_vars($this);

		return $vars;
    }
}
?>