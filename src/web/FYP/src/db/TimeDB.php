<?php

require_once __DIR__ . '/DB.php';

class TimeDB extends DB {
	
	public function getTimeList(){
		$conn = $this->getConnection();
		$sql = "SELECT * FROM time_setting";
		$rs = mysqli_query($conn, $sql);
		
		$data = mysqli_fetch_all($rs, MYSQLI_ASSOC);
		
		mysqli_free_result($rs);
		mysqli_close($conn);
		
		return $data;
	}
	
	public function getTime($ruleID){
		$conn = $this->getConnection();
		$sql = "SELECT * FROM time_setting WHERE RuleID='$ruleID'";
		$rs = mysqli_query($conn, $sql);
		
		$data = mysqli_fetch_array($rs, MYSQLI_ASSOC);
		
		mysqli_free_result($rs);
		mysqli_close($conn);
		
		return $data;
	}
	
	public function getTimeByActuator($actuatorID){
		$conn = $this->getConnection();
		$sql = "SELECT * FROM time_setting WHERE ActuatorID='$actuatorID'";
		$rs = mysqli_query($conn, $sql);
		
		$data = mysqli_fetch_all($rs, MYSQLI_ASSOC);
		
		mysqli_free_result($rs);
		mysqli_close($conn);
		
		return $data;
	}
	
	/*public function getTimeRecord($device, $pin){
		$conn = $this->getConnection();
		$sql = "SELECT * FROM rule_time_record WHERE DeviceID='$device' AND Pin='$pin'";
		$rs = mysqli_query($conn, $sql);
		
		$data = mysqli_fetch_all($rs, MYSQLI_ASSOC);
		
		mysqli_free_result($rs);
		mysqli_close($conn);
		
		return $data;
	}*/

	public function getTimeModelByActuator($actuatorID){
		require_once __DIR__ . '/../model/Time.php';
		$conn = $this->getConnection();
		$sql = "SELECT * FROM time_setting WHERE ActuatorID='$actuatorID'";
		$rs = mysqli_query($conn, $sql);
		
		$timeArr = array();
		
		if($rs) {
			while($rc = mysqli_fetch_array($rs)) {
				$time = new Time();
				$time->setTimeID($rc['TimeID']);
				$time->setStartTime($rc['StartTime']);
				$time->setEndTime($rc['EndTime']);
				$timeArr[] = $time;
			}
		}
		
		mysqli_free_result($rs);
		mysqli_close($conn);
		
		return $timeArr;
	}
	
	public function createTime($body){
		$conn = $this->getConnection();
		$sql = "INSERT INTO time_setting VALUES(NULL, '$body[startTime]', '$body[endTime]', '$body[actuatorID]')";
		mysqli_query($conn, $sql);
		$last_id = $conn->insert_id;
		
		mysqli_close($conn);
		
		if(!empty($last_id) && $last_id > 0){
			return $last_id;
		}else{
			return ;
		}
	}

	public function updateTime($body){
		$conn = $this->getConnection();
		$sql = "UPDATE time_setting SET StartTime='$body[startTime]', EndTime='$body[endTime]' WHERE TimeID='$body[timeID]'";
		mysqli_query($conn, $sql);
		
		$affected = mysqli_affected_rows($conn);
		mysqli_close($conn);
		
		if(!empty($affected)){
			return $body;
		}else{
			return ;
		}
	}

	public function deleteTime($body){
		$conn = $this->getConnection();
		$sql = "DELETE FROM time_setting WHERE TimeID='$body[timeID]'";
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