<?php

class Weather {
	private $recordID;
	private $description;
	private $timeStamp;
	private $currentTemp;
	private $minTemp;
	private $maxTemp;
	private $humidity;
	private $pressure;
	private $windSpeed;
	private $rain;
	private $icon;
	private $updateDateTime;
	
	public function __construct() {}
	
	public function getRecordID() {
		return $this->recordID;
	}
	
	public function setRecordID($recordID) {
		$this->recordID = $recordID;
	}
	
	public function getDescription() {
		return $this->description;
	}
	
	public function setDescription($description) {
		$this->description = $description;
	}
	
	public function getTimeStamp() {
		return $this->timeStamp;
	}
	
	public function setTimeStamp($timeStamp) {
		$this->timeStamp = $timeStamp;
	}
	public function getCurrentTemp() {
		return $this->currentTemp;
	}
	
	public function setCurrentTemp($currentTemp) {
		$this->currentTemp = $currentTemp;
	}
	
	public function getMinTemp() {
		return $this->minTemp;
	}
	
	public function setMinTemp($minTemp) {
		$this->minTemp = $minTemp;
	}
	
	public function getMaxTemp() {
		return $this->maxTemp;
	}
	
	public function setMaxTemp($maxTemp) {
		$this->maxTemp = $maxTemp;
	}
	
	public function getHumidity() {
		return $this->humidity;
	}
	
	public function setHumidity($humidity) {
		$this->humidity = $humidity;
	}
	
	public function getPressure() {
		return $this->pressure;
	}
	
	public function setPressure($pressure) {
		$this->pressure = $pressure;
	}
	
	public function getWindSpeed() {
		return $this->windSpeed;
	}
	
	public function setWindSpeed($windSpeed) {
		$this->windSpeed = $windSpeed;
	}
	
	public function getRain() {
		return $this->rain;
	}
	
	public function setRain($rain) {
		$this->rain = $rain;
	}
	
	public function getIcon() {
		return $this->icon;
	}
	
	public function setIcon($icon) {
		$this->icon = $icon;
	}
	
	public function getUpdateDateTime() {
		return $this->updateDateTime;
	}
	
	public function setUpdateDateTime($updateDateTime) {
		$this->updateDateTime = $updateDateTime;
	}
}

?>