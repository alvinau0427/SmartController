<?php

require_once __DIR__ . '/DB.php';

class HeartRateDB extends DB {
	
	public function getHeartRateList($startIndex, $numItems, $userID){
		$conn = $this->getConnection();
		$sql = "SELECT hr.* 
				FROM user_heart_rate as hr
				WHERE hr.UserID=IFNULL(NULLIF('$userID',''), hr.UserID)
				ORDER BY hr.HeartRateID DESC LIMIT $startIndex, $numItems";
		$rs = mysqli_query($conn, $sql);
		
		$data = mysqli_fetch_all($rs, MYSQLI_ASSOC);
		
		mysqli_free_result($rs);
		mysqli_close($conn);
		
		return $data;
	}
	
	public function getHeartRate($heartRateID){
		$conn = $this->getConnection();
		$sql = "SELECT hr.* 
				FROM user_heart_rate as hr
				WHERE hr.HeartRateID='$heartRateID'";
		$rs = mysqli_query($conn, $sql);
		
		$data = mysqli_fetch_array($rs, MYSQLI_ASSOC);
		
		mysqli_free_result($rs);
		mysqli_close($conn);
		
		return $data;
	}
	
	public function createHeartRate($body){
		$conn = $this->getConnection();
		$sql = "INSERT INTO user_heart_rate VALUES(NULL, '$body[heartRate]', '$body[userID]', DEFAULT)";
		mysqli_query($conn, $sql);
		$last_id = $conn->insert_id;
		
		mysqli_close($conn);
		
		if(!empty($last_id) && $last_id > 0){
			return $last_id;
		}else{
			return ;
		}
	}
	
	public function updateHeartRate($body){
		$conn = $this->getConnection();
		$sql = "UPDATE user_heart_rate SET HeartRate='$body[heartRate]', DateTime=DEFAULT WHERE HeartRateID='$body[heartRateID]'";
		
		mysqli_query($conn, $sql);
		
		$affected = mysqli_affected_rows($conn);
		mysqli_close($conn);
		
		if(!empty($affected) && $affected > 0){
			return $affected;
		}else{
			return 0;
		}
	}
	
	public function deleteHeartRateByUser($body){
		$conn = $this->getConnection();
		$sql = "DELETE FROM user_heart_rate WHERE UserID='$body[userID]'";
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