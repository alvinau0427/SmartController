<?php

require_once __DIR__ . '/DB.php';

class RegulationDB extends DB {
	
	public function getRegulationList(){
		$conn = $this->getConnection();
		$sql = "SELECT r.*, a_s.* 
				FROM regulation as r, actuator_status as a_s 
				WHERE r.ActuatorStatusID=a_s.ActuatorStatusID";
		$rs = mysqli_query($conn, $sql);
		
		$data = mysqli_fetch_all($rs, MYSQLI_ASSOC);
		
		mysqli_free_result($rs);
		mysqli_close($conn);
		
		return $data;
	}

	public function getRegulation($regulationID){
		$conn = $this->getConnection();
		$sql = "SELECT r.*, a_s.* 
				FROM regulation as r, actuator_status as a_s 
				WHERE r.ActuatorStatusID=a_s.ActuatorStatusID AND RegulationID='$regulationID'";
				
		$rs = mysqli_query($conn, $sql);
		
		$data = mysqli_fetch_array($rs, MYSQLI_ASSOC);
		
		mysqli_free_result($rs);
		mysqli_close($conn);
		
		return $data;
	}
	
	public function getRegulationByActuator($actuatorID){
		$conn = $this->getConnection();
		$sql = "SELECT r.*, a_s.* 
				FROM regulation as r, actuator_status as a_s 
				WHERE r.ActuatorStatusID=a_s.ActuatorStatusID AND ActuatorID='$actuatorID' ORDER BY Priority";
		$rs = mysqli_query($conn, $sql);
		
		$data = mysqli_fetch_all($rs, MYSQLI_ASSOC);
		
		mysqli_free_result($rs);
		mysqli_close($conn);
		
		return $data;
	}
	
	public function getRegulationBetweenPriority($actuatorID, $startPriority, $endPriority){
		$conn = $this->getConnection();
		$sql = "SELECT r.*, a_s.* 
				FROM regulation as r, actuator_status as a_s 
				WHERE r.ActuatorStatusID=a_s.ActuatorStatusID AND ActuatorID='$actuatorID' AND Priority BETWEEN $startPriority AND $endPriority ORDER BY Priority";
		$rs = mysqli_query($conn, $sql);
		
		$data = mysqli_fetch_all($rs, MYSQLI_ASSOC);
		
		mysqli_free_result($rs);
		mysqli_close($conn);
		
		return $data;
	}
	
	public function getRegulationModelByActuator($actuatorID){
		require_once __DIR__ . '/../model/Regulation.php';
		$conn = $this->getConnection();
		$sql = "SELECT r.*, a_s.* 
				FROM regulation as r, actuator_status as a_s
				WHERE r.ActuatorStatusID=a_s.ActuatorStatusID AND ActuatorID='$actuatorID' ORDER BY Priority";
		$rs = mysqli_query($conn, $sql);
		
		$regulationArr = array();
		
		if($rs) {
			while($rc = mysqli_fetch_array($rs)) {
				$regulation = new Regulation();
				$regulation->setID($rc['RegulationID']);
				$regulation->setHeader($rc['Header']);
				$regulation->setLogicGate($rc['LogicGate']);
				$regulation->setStatus($rc['ActuatorStatusID']);
				$regulation->setStatusDescription($rc['ActuatorStatusDescription']);
				$regulation->setPriority($rc['Priority']);
				$regulationArr[] = $regulation;
			}
		}
		
		mysqli_free_result($rs);
		mysqli_close($conn);
		
		return $regulationArr;
	}

	public function createRegulation($body){
		$conn = $this->getConnection();
		$sql = "INSERT INTO regulation VALUES(NULL,'$body[header]', '$body[description]', NULL, '$body[status]', '$body[priority]', 
				'$body[actuatorID]')";
		mysqli_query($conn, $sql);
		$last_id = $conn->insert_id;
		
		mysqli_close($conn);
		
		if(!empty($last_id) && $last_id > 0){
			return $last_id;
		}else{
			return ;
		}
	}

	public function updateRegulation($body){
		$conn = $this->getConnection();
		$sql = "UPDATE regulation SET Header='$body[header]', RegulationDescription='$body[description]', ActuatorStatusID='$body[status]', ActuatorID='$body[actuatorID]' 
		WHERE RegulationID='$body[regulationID]'";
		mysqli_query($conn, $sql);
		$affected = mysqli_affected_rows($conn);
		
		mysqli_close($conn);
		
		if(!empty($affected) && $affected > 0){
			return $body;
		}else{
			return ;
		}
	}
	
	public function updateRegulationLogicGate($body){
		$conn = $this->getConnection();
		$sql = "UPDATE regulation SET LogicGate='$body[logicGate]' 
		WHERE RegulationID='$body[regulationID]'";
		mysqli_query($conn, $sql);
		$affected = mysqli_affected_rows($conn);
		
		mysqli_close($conn);
		
		if(!empty($affected) && $affected > 0){
			return $body;
		}else{
			return ;
		}
	}
	
	public function updateRegulationPriority($body){
		$conn = $this->getConnection();
		$sql = "UPDATE regulation SET Priority='$body[priority]' 
		WHERE RegulationID='$body[regulationID]'";
		mysqli_query($conn, $sql);
		$affected = mysqli_affected_rows($conn);
		
		mysqli_close($conn);
		
		if(!empty($affected) && $affected > 0){
			return $body;
		}else{
			return ;
		}
	}

	public function deleteRegulation($body){
		$conn = $this->getConnection();
		$sql = "DELETE FROM regulation WHERE RegulationID='$body[regulationID]'";
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