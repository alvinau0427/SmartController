<?php

require_once __DIR__ . '/DB.php';

class AutoRuleDB extends DB {
	
	public function getAutoRuleList(){
		$conn = $this->getConnection();
		$sql = "SELECT * FROM rule_auto";
		$rs = mysqli_query($conn, $sql);
		
		$data = mysqli_fetch_all($rs, MYSQLI_ASSOC);
		
		mysqli_free_result($rs);
		mysqli_close($conn);
		
		return $data;
	}
	
	public function getAutoRule($ruleID){
		$conn = $this->getConnection();
		$sql = "SELECT * FROM rule_auto WHERE RuleID='$ruleID'";
		$rs = mysqli_query($conn, $sql);
		
		$data = mysqli_fetch_array($rs, MYSQLI_ASSOC);
		
		mysqli_free_result($rs);
		mysqli_close($conn);
		
		return $data;
	}
	
	public function getAutoRuleByDevice($device, $pin){
		$conn = $this->getConnection();
		$sql = "SELECT RuleID, Type, AutoValue, sm.ID, SensorID, SensorPin, ModuleID, ModulePin 
				FROM rule_auto as ra, sensor_module as sm 
				WHERE sm.ID=ra.ID AND ((sm.SensorID='$device' AND sm.SensorPin='$pin') OR (sm.ModuleID='$device' AND sm.ModulePin='$pin'))";
		$rs = mysqli_query($conn, $sql);
		
		$data = mysqli_fetch_all($rs, MYSQLI_ASSOC);
		
		mysqli_free_result($rs);
		mysqli_close($conn);
		
		return $data;
	}
	
	/*public function getAutoRuleRecord($device, $pin){
		$conn = $this->getConnection();
		$sql = "SELECT * FROM rule_auto_record WHERE DeviceID='$device' AND Pin='$pin'";
		$rs = mysqli_query($conn, $sql);
		
		$data = mysqli_fetch_all($rs, MYSQLI_ASSOC);
		
		mysqli_free_result($rs);
		mysqli_close($conn);
		
		return $data;
	}*/

	/*public function createAutoRule($body){
		$conn = $this->getConnection();
		$sql = "INSERT INTO rule_auto VALUES(NULL, '$body[type]', '$body[autoValue]', '$body[deviceID]', '$body[pin]')";
		mysqli_query($conn, $sql);
		$last_id = $conn->insert_id;
		
		mysqli_close($conn);
		
		if(!empty($last_id)){
			return $last_id;
		}else{
			return ;
		}
	}*/

	public function updateAutoRule($body){
		$conn = $this->getConnection();
		$sql = "UPDATE rule_auto SET AutoValue='$body[autoValue]', Type='$body[type]' WHERE RuleID='$body[ruleID]';";
		
		mysqli_query($conn, $sql);
		
		$affected = mysqli_affected_rows($conn);
		mysqli_close($conn);
		
		if(!empty($affected)){
			return $body;
		}else{
			return ;
		}
	}

	/*public function deleteAutoRule($body){
		$conn = $this->getConnection();
		$sql = "DELETE FROM rule_auto WHERE RuleID='$body[ruleID]'";
		mysqli_query($conn, $sql);
		
		$affected_id = $conn->affected_rows;
		mysqli_close($conn);
		
		if(!empty($affected_id)){
			return $affected_id;
		}else{
			return ;
		}
	}*/
}
?>