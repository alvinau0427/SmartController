<?php

require_once __DIR__ . '/DB.php';

class ConditionDB extends DB {
	
	public function getConditionList(){
		$conn = $this->getConnection();
		$sql = "SELECT c.*, co.*, s.* 
				FROM `condition` as c, comparison_operator as co, sensor as s 
				WHERE c.ComparisonOperatorID=co.ComparisonOperatorID";
		$rs = mysqli_query($conn, $sql);
		
		$data = mysqli_fetch_all($rs, MYSQLI_ASSOC);
		
		mysqli_free_result($rs);
		mysqli_close($conn);
		
		return $data;
	}

	public function getCondition($conditionID){
		$conn = $this->getConnection();
		$sql = "SELECT c.*, co.*, s.* 
				FROM `condition` as c, comparison_operator as co, sensor as s  
				WHERE c.ComparisonOperatorID=co.ComparisonOperatorID AND s.sensorID=c.SensorID AND c.ConditionID='$conditionID'";
				
		$rs = mysqli_query($conn, $sql);
		
		$data = mysqli_fetch_array($rs, MYSQLI_ASSOC);
		
		mysqli_free_result($rs);
		mysqli_close($conn);
		
		return $data;
	}
	
	public function getConditionByRegulation($regulationID){
		$conn = $this->getConnection();
		$sql = "SELECT c.*, co.*, s.* 
				FROM `condition` as c, comparison_operator as co, sensor as s  
				WHERE c.ComparisonOperatorID=co.ComparisonOperatorID AND s.sensorID=c.SensorID AND c.RegulationID='$regulationID'";
				
		$rs = mysqli_query($conn, $sql);
		
		$data = mysqli_fetch_all($rs, MYSQLI_ASSOC);
		
		mysqli_free_result($rs);
		mysqli_close($conn);
		
		return $data;
	}
	
	public function getConditionBySensor($sensorID){
		$conn = $this->getConnection();
		$sql = "SELECT c.*, co.* 
				FROM `condition` as c, comparison_operator as co, sensor as s 
				WHERE c.ComparisonOperatorID=co.ComparisonOperatorID AND s.sensorID=c.SensorID AND c.SensorID='$sensorID'";
		$rs = mysqli_query($conn, $sql);
		
		$data = mysqli_fetch_all($rs, MYSQLI_ASSOC);
		
		mysqli_free_result($rs);
		mysqli_close($conn);
		
		return $data;
	}
	
	public function getConditionModelByRegulation($regulationID){
		require_once __DIR__ . '/../model/Condition.php';
		foreach(glob(__DIR__ . "/../model/ComparisonOperator/*.php") as $filename)
		{
			require_once $filename;
		}
		$conn = $this->getConnection();
		$sql = "SELECT *
				FROM `condition` as c
				WHERE RegulationID='$regulationID'";
		$rs = mysqli_query($conn, $sql);
		
		$conditionArr = array();
		
		if($rs) {
			while($rc = mysqli_fetch_array($rs)) {
				$condition = new Condition();
				$condition->setID($rc['ConditionID']);
				$condition->setSensorID($rc['SensorID']);
				$condition->setValue($rc['Value']);
				
				$comparisonOperator = null;
				switch($rc['ComparisonOperatorID']) {
					case 1:
						$comparisonOperator = new EQComparisonOperator();
						break;
					
					case 2:
						$comparisonOperator = new NEComparisonOperator();
						break;
					
					case 3:
						$comparisonOperator = new LTComparisonOperator();
						break;
					
					case 4:
						$comparisonOperator = new LEComparisonOperator();
						break;
					
					case 5:
						$comparisonOperator = new GTComparisonOperator();
						break;
					
					case 6:
						$comparisonOperator = new GEComparisonOperator();
						break;
				}
				$condition->setComparisonOperator($comparisonOperator);
				
				$conditionArr[] = $condition;
			}
		}
		
		mysqli_free_result($rs);
		mysqli_close($conn);
		
		return $conditionArr;
	}

	public function createCondition($body){
		$conn = $this->getConnection();
		$sql = "INSERT INTO `condition` VALUES(NULL, '$body[sensorID]', '$body[operatorID]', '$body[value]', 
				'$body[regulationID]')";
		mysqli_query($conn, $sql);
		$last_id = $conn->insert_id;
		
		mysqli_close($conn);
		
		if(!empty($last_id) && $last_id > 0){
			return $last_id;
		}else{
			return ;
		}
	}

	public function updateCondition($body){
		$conn = $this->getConnection();
		$sql = "UPDATE `condition` SET SensorID='$body[sensorID]', ComparisonOperatorID='$body[operatorID]', Value='$body[value]' 
		WHERE ConditionID='$body[conditionID]'";
		mysqli_query($conn, $sql);
		$affected = mysqli_affected_rows($conn);
		
		mysqli_close($conn);
		
		if(!empty($affected) && $affected > 0){
			return $body;
		}else{
			return ;
		}
	}

	public function deleteCondition($body){
		$conn = $this->getConnection();
		$sql = "DELETE FROM `condition` WHERE ConditionID='$body[conditionID]'";
		mysqli_query($conn, $sql);
		
		$affected_id = $conn->affected_rows;
		mysqli_close($conn);
		
		if(!empty($affected_id) && $affected_id > 0){
			return $affected_id;
		}else{
			return ;
		}
	}
	
	public function deleteConditionByRegulation($body){
		$conn = $this->getConnection();
		$sql = "DELETE FROM `condition` WHERE RegulationID='$body[regulationID]'";
		mysqli_query($conn, $sql);
		
		$affected_id = $conn->affected_rows;
		mysqli_close($conn);
		
		if(!empty($affected_id) && $affected_id > 0){
			return $affected_id;
		}else{
			return ;
		}
	}
}
?>