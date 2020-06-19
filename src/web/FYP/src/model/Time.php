<?php

class Time {
	private $timeID;
	private $startTime;
	private $endTime;
	
	public function __construct(){}
	
	public function getTimeID() {
		return $this->timeID;
	}
	
	public function setTimeID($timeID) {
		$this->timeID = $timeID;
	}
	
	public function getStartTime() {
		return $this->startTime;
	}
	
	public function setStartTime($startTime) {
		$this->startTime = $startTime;
	}
	
	public function getEndTime() {
		return $this->endTime;
	}
	
	public function setEndTime($endTime) {
		$this->endTime = $endTime;
	}
	
	public function check($value) {
		$currentTime = $value;//$_SERVER['REQUEST_TIME'];
		$sTime = strtotime($this->startTime);
		$eTime = strtotime($this->endTime);
		return ($currentTime >= $sTime && $currentTime <= $eTime);
	}
}

?>