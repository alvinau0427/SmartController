<?php
require_once __DIR__ . '/ComparisonOperator.php';

class GEComparisonOperator implements ComparisonOperator {
	
	public function compare($value1, $value2){
		if($value1 >= $value2)
			return true;
		else
			return false;
	}
	
}

?>