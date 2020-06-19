<?php
require_once __DIR__ . '/LogicalOperator.php';

class ORLogicalOperator implements LogicalOperator {
	
	public function compare($value1, $value2){
		return ($value1 || $value2);
	}
	
}

?>