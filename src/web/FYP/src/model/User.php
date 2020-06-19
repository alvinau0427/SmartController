<?php

class User {
	private $ID;
	private $name;
	private $type;
	private $typeDescription;
	private $loginAccount;
	private $email;
	private $token;
	private $image;
	private $receiveNotification;
	private $receiveEmail;
	private $locationDisplay;
	
	public function __construct(){}
	
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
	
	public function getLoginAccount() {
		return $this->loginAccount;
	}
	
	public function setLoginAccount($loginAccount) {
		$this->loginAccount = $loginAccount;
	}
	
	public function getEmail() {
		return $this->email;
	}
	
	public function setEmail($email) {
		$this->email = $email;
	}
	
	public function getToken() {
		return $this->token;
	}
	
	public function setToken($token) {
		$this->token = $token;
	}
	
	public function getImage() {
		return $this->image;
	}
	
	public function setImage($image) {
		$this->image = $image;
	}
	
	public function getReceiveNotification() {
		return $this->receiveNotification;
	}
	
	public function setReceiveNotification($receiveNotification) {
		$this->receiveNotification = $receiveNotification;
	}
	
	public function getReceiveEmail() {
		return $this->receiveEmail;
	}
	
	public function setReceiveEmail($receiveEmail) {
		$this->receiveEmail = $receiveEmail;
	}
	
	public function getLocationDisplay() {
		return $this->locationDisplay;
	}
	
	public function setLocationDisplay($locationDisplay) {
		$this->locationDisplay = $locationDisplay;
	}
	
}

?>