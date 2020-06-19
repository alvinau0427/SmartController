<?php
require_once __DIR__ . '/Rule.php';

class NotificationRule extends Rule{
	private $ruleID;
	private $type;
	private $notificationValue;
	private $message;
	
	public function __construct(){}
	
	public function getRuleID() {
		return $this->ruleID;
	}
	
	public function setRuleID($ruleID) {
		$this->ruleID = $ruleID;
	}
	
	public function getType() {
		return $this->type;
	}
	
	public function setType($type) {
		$this->type = $type;
	}
	
	public function getNotificationValue() {
		return $this->notificationValue;
	}
	
	public function setNotificationValue($notificationValue) {
		$this->notificationValue = $notificationValue;
	}
	
	public function getMessage() {
		return $this->message;
	}
	
	public function setMessage($message) {
		$this->message = $message;
	}
	
	public function isCompliance($value) {
		$class = $this->comparatorName[$this->type];
		$comparator = new $class();
		
		return $comparator->compare($value, $this->notificationValue);
	}
}

?>