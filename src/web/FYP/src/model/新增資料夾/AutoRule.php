<?php
require_once __DIR__ . '/Rule.php';

class AutoRule extends Rule{
	private $ruleID;
	private $type;
	private $autoValue;
	
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
	
	public function getAutoValue() {
		return $this->autoValue;
	}
	
	public function setAutoValue($autoValue) {
		$this->autoValue = $autoValue;
	}
	
	public function isCompliance($value) {
		$class = $this->comparatorName[$this->type];
		$comparator = new $class();
		
		return $comparator->compare($value, $this->autoValue);
	}
}

?>