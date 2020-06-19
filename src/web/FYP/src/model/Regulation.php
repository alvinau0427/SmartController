<?php
require_once __DIR__ . '/Condition.php';
foreach(glob(__DIR__ . "/LogicalOperator/*.php") as $filename)
{
	require_once $filename;
}

class Regulation{
	private $ID;
	private $header;
	private $logicGate;
	private $status;
	private $statusDescription;
	private $priority;
	private $condition = array();
	
	public function __construct(){}
	
	public function getID() {
		return $this->ID;
	}
	
	public function setID($ID) {
		$this->ID = $ID;
	}
	
	public function getHeader() {
		return $this->header;
	}
	
	public function setHeader($header) {
		$this->header = $header;
	}
	
	public function getLogicGate() {
		return $this->logicGate;
	}
	
	public function setLogicGate($logicGate) {
		$this->logicGate = $logicGate;
	}
	
	public function getStatus() {
		return $this->status;
	}
	
	public function setStatus($status) {
		$this->status = $status;
	}
	
	public function getStatusDescription() {
		return $this->statusDescription;
	}
	
	public function setStatusDescription($statusDescription) {
		$this->statusDescription = $statusDescription;
	}
	
	public function getPriority() {
		return $this->priority;
	}
	
	public function setPriority($priority) {
		$this->priority = $priority;
	}
	
	public function addCondition($c) {
		$this->condition[] = $c;
	}
	
	public function removeCondition($c) {
		$this->condition = array_diff($this->condition, [$c]);
	}
		
	public function getCondition() {
		return $this->condition;
	}
	
	public function check() {
		$logicGateArr = explode(" ",$this->logicGate);
		$temp = null;
		$logicalOperator = null;
		
		foreach($logicGateArr as $lg) {
			//echo $lg . PHP_EOL;
			if(is_numeric($lg)) {	//is  a number
			
				//find corresponding Condition object
				$con = null;
				for($i = 0; $i < count($this->condition); $i++) {
					$con = $this->condition[$i];
					
					if($con->getID() == $lg) {
						$condition = $con;
						if($logicalOperator == null) {
							$temp = $condition->check();
						} else {
							$temp = $logicalOperator->compare($temp, $condition->check());
							$logicalOperator = null;
						}
						break;
					}
				}
				//echo 'Condition ' . $con->getID() . ' ' . var_export($temp, true) . PHP_EOL;
			} else {
				if($lg == 'O') {
					$logicalOperator = new ORLogicalOperator();
				} else if($lg == 'N') {
					$logicalOperator = new ANDLogicalOperator();
				}
			}
		}
		
		return $temp;
	}
	
	public function jsonSerialize() {
		$vars = get_object_vars($this);

		return $vars;
    }
}
?>