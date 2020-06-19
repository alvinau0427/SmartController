<?php

abstract class Component implements \JsonSerializable{
	protected $ID;
	protected $pin;
	protected $pinName;
	protected $name;
	protected $image;
	protected $type;
	protected $typeDescription;
	protected $roomID;
	protected $deviceID;
	
	
	public function __construct(){}
	
	public function getID() {
		return $this->ID;
	}
	
	public function setID($ID) {
		$this->ID = $ID;
	}
	
	public function getPin() {
		return $this->pin;
	}
	
	public function setPin($pin) {
		$this->pin = $pin;
	}
	
	public function getPinName() {
		return $this->pinName;
	}
	
	public function setPinName($pinName) {
		$this->pinName = $pinName;
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
	
	public function getType() {
		return $this->type;
	}
	
	public function setType($type) {
		$this->type = $type;
	}
	
	public function getTypeDescription() {
		return $this->typeDescription;
	}
	
	public function setTypeDescription($typeDescription) {
		$this->typeDescription = $typeDescription;
	}
	
	public function getRoomID() {
		return $this->roomID;
	}
	
	public function setRoomID($roomID) {
		$this->roomID = $roomID;
	}
	
	public function getDeviceID() {
		return $this->deviceID;
	}
	
	public function setDeviceID($deviceID) {
		$this->deviceID = $deviceID;
	}
	
	public function jsonSerialize() {
        $vars = get_object_vars($this);

        return $vars;
    }
	
}

?>