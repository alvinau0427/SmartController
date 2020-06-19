<?php

require_once __DIR__ . '/DB.php';

class ActuatorDB extends DB {
	
	public function getActuatorList(){
		$conn = $this->getConnection();
		$sql = "SELECT a.*, at.*, r.*, p.* 
				FROM actuator as a 
				LEFT JOIN actuator_type as at ON a.ActuatorTypeID=at.ActuatorTypeID
				LEFT JOIN room as r ON r.RoomID=a.RoomID
				LEFT JOIN permission as p ON p.PermissionID=a.PermissionID";
		$rs = mysqli_query($conn, $sql);
		
		$data = mysqli_fetch_all($rs, MYSQLI_ASSOC);
		
		mysqli_free_result($rs);
		mysqli_close($conn);
		
		return $data;
	}

	public function getActuator($actuatorID){
		$conn = $this->getConnection();
		$sql = "SELECT a.*, at.*, r.*, p.* 
				FROM actuator as a
				LEFT JOIN actuator_type as at ON a.ActuatorTypeID=at.ActuatorTypeID
				LEFT JOIN room as r ON r.RoomID=a.RoomID
				LEFT JOIN permission as p ON p.PermissionID=a.PermissionID
				WHERE a.ActuatorID='$actuatorID'";
		$rs = mysqli_query($conn, $sql);
		
		$data = mysqli_fetch_array($rs, MYSQLI_ASSOC);
		
		mysqli_free_result($rs);
		mysqli_close($conn);
		
		return $data;
	}
	
	public function getActuatorStatus(){
		$conn = $this->getConnection();
		$sql = "SELECT DeviceID, ActuatorPin, ActuatorStatus FROM actuator";
		$rs = mysqli_query($conn, $sql);
		
		$data = mysqli_fetch_all($rs, MYSQLI_ASSOC);
		
		mysqli_free_result($rs);
		mysqli_close($conn);
		
		return $data;
	}
	
	public function getActuatorByRoomID($roomID){
		$conn = $this->getConnection();
		$sql = "SELECT a.*, at.*, r.*, p.* 
				FROM actuator as a, actuator_type as at, room as r, permission as p
				WHERE a.ActuatorTypeID=at.ActuatorTypeID AND r.RoomID=a.RoomID AND p.PermissionID=a.PermissionID AND a.RoomID='$roomID'";
		$rs = mysqli_query($conn, $sql);
		
		$data = mysqli_fetch_all($rs, MYSQLI_ASSOC);
		
		mysqli_free_result($rs);
		mysqli_close($conn);
		
		return $data;
	}
	
	public function getActuatorSensor($actuatorSensorID){
		$conn = $this->getConnection();
		$sql = "SELECT a_s.* 
				FROM actuator_sensor as a_s 
				WHERE a_s.ActuatorSensorID='$actuatorSensorID'";
		$rs = mysqli_query($conn, $sql);
		
		$data = mysqli_fetch_array($rs, MYSQLI_ASSOC);
		
		mysqli_free_result($rs);
		mysqli_close($conn);
		
		return $data;
	}
	
	/*public function getActuatorBySensor($sensorID, $sensorPin){
		$conn = $this->getConnection();
		$sql = "SELECT * FROM sensor_module WHERE SensorID='$sensorID' AND SensorPin='$sensorPin'";
		$rs = mysqli_query($conn, $sql);
		
		$data = mysqli_fetch_all($rs, MYSQLI_ASSOC);
		
		mysqli_free_result($rs);
		mysqli_close($conn);
		
		return $data;
	}*/
	
	public function getActuatorPin($device){
		$conn = $this->getConnection();
		$sql = "SELECT ActuatorPin, ActuatorPinName FROM actuator WHERE DeviceID='$device'";
		$rs = mysqli_query($conn, $sql);
		
		$pinArr;
		if($rs) {
			while($rc = mysqli_fetch_array($rs)) {
				$pinArr[$rc['ActuatorPinName']] = $rc['ActuatorPin'];
			}
		}
		
		mysqli_free_result($rs);
		mysqli_close($conn);
		
		return $pinArr;
	}
	
	public function getActuatorRecordList($startIndex, $numItems, $userID, $status, $startTime, $endTime){
		$conn = $this->getConnection();
		$sql = "SELECT ar.*, a.ActuatorName, a.ActuatorImage, a_s.ActuatorStatusDescription, ua.UserName, r.RoomName
				FROM actuator_record as ar, actuator as a, actuator_status as a_s, user_account as ua, room as r
				WHERE ar.ActuatorStatusID=a_s.ActuatorStatusID AND ar.ActuatorID=a.ActuatorID AND 
				ar.UserID=ua.UserID AND a.roomID=r.roomID AND 
				ar.UserID=IFNULL(NULLIF('$userID',''), ar.UserID) AND
				ar.ActuatorStatusID=IFNULL(NULLIF('$status',''), ar.ActuatorStatusID) AND
				ar.DateTime BETWEEN IFNULL(NULLIF('$startTime',''), ar.DateTime) AND IFNULL(NULLIF('$endTime',''), ar.DateTime)
				ORDER BY ar.RecordID DESC LIMIT $startIndex, $numItems";
		$rs = mysqli_query($conn, $sql);
		
		$data = mysqli_fetch_all($rs, MYSQLI_ASSOC);
		
		mysqli_free_result($rs);
		mysqli_close($conn);
		
		return $data;
	}
	
	public function getActuatorRecord($actuatorID, $startIndex, $numItems, $userID, $status, $startTime, $endTime){
		$conn = $this->getConnection();
		$sql = "SELECT ar.*, a.ActuatorName, a.ActuatorImage, a_s.ActuatorStatusDescription, ua.UserName, r.RoomName
				FROM actuator_record as ar, actuator as a, actuator_status as a_s, user_account as ua, room as r
				WHERE ar.ActuatorStatusID=a_s.ActuatorStatusID AND ar.ActuatorID=a.ActuatorID AND 
				ar.UserID=ua.UserID AND a.roomID=r.roomID AND ar.ActuatorID='$actuatorID' AND
				ar.UserID=IFNULL(NULLIF('$userID',''), ar.UserID) AND
				ar.ActuatorStatusID=IFNULL(NULLIF('$status',''), ar.ActuatorStatusID) AND
				ar.DateTime BETWEEN IFNULL(NULLIF('$startTime',''), ar.DateTime) AND IFNULL(NULLIF('$endTime',''), ar.DateTime)
				ORDER BY ar.RecordID DESC LIMIT $startIndex, $numItems";
		$rs = mysqli_query($conn, $sql);
		
		$data = mysqli_fetch_all($rs, MYSQLI_ASSOC);
		
		mysqli_free_result($rs);
		mysqli_close($conn);
		
		return $data;
	}
	
	public function getActuatorModelList(){
		require_once __DIR__ . '/../model/Actuator.php';
		$conn = $this->getConnection();
		$sql = "SELECT a.*, at.*, p.*
				FROM actuator as a, actuator_type as at, permission as p 
				WHERE p.PermissionID=a.PermissionID AND a.ActuatorTypeID=at.ActuatorTypeID";
		$rs = mysqli_query($conn, $sql);
		
		$actuatorArr = array();
		
		if($rs) {
			while($rc = mysqli_fetch_array($rs)) {
				$actuator = new Actuator();
				$actuator->setID($rc['ActuatorID']);
				$actuator->setPin($rc['ActuatorPin']);
				$actuator->setPinName($rc['ActuatorPinName']);
				$actuator->setName($rc['ActuatorName']);
				$actuator->setImage($rc['ActuatorImage']);
				$actuator->setType($rc['ActuatorTypeID']);
				$actuator->setTypeDescription($rc['ActuatorTypeDescription']);
				$actuator->setStatus($rc['ActuatorStatusID']);
				$actuator->setWeatherAPI($rc['WeatherAPI']);
				$actuator->setMode($rc['ModeID']);
				$actuator->setDisplay($rc['Display']);
				$actuator->setPermission($rc['PermissionID']);
				$actuator->setPermissionDescription($rc['PermissionDescription']);
				$actuator->setRoomID($rc['RoomID']);
				$actuator->setDeviceID($rc['DeviceID']);
				$actuatorArr[] = $actuator;
			}
		}
		
		mysqli_free_result($rs);
		mysqli_close($conn);
		
		return $actuatorArr;
	}
	
	public function getActuatorModel($actuatorID){
		require_once __DIR__ . '/../model/Actuator.php';
		$conn = $this->getConnection();
		$sql = "SELECT a.*, at.*, p.* 
				FROM actuator as a, actuator_type as at, permission as p 
				WHERE a.ActuatorTypeID=at.ActuatorTypeID AND p.PermissionID=a.PermissionID AND a.ActuatorID='$actuatorID'";
		$rs = mysqli_query($conn, $sql);
		
		if($rs) {
			$rc = mysqli_fetch_array($rs);
			$actuator = new Actuator();
			$actuator->setID($rc['ActuatorID']);
			$actuator->setPin($rc['ActuatorPin']);
			$actuator->setPinName($rc['ActuatorPinName']);
			$actuator->setName($rc['ActuatorName']);
			$actuator->setImage($rc['ActuatorImage']);
			$actuator->setType($rc['ActuatorTypeID']);
			$actuator->setTypeDescription($rc['ActuatorTypeDescription']);
			$actuator->setStatus($rc['ActuatorStatusID']);
			$actuator->setWeatherAPI($rc['WeatherAPI']);
			$actuator->setMode($rc['ModeID']);
			$actuator->setDisplay($rc['Display']);
			$actuator->setPermission($rc['PermissionID']);
			$actuator->setPermissionDescription($rc['PermissionDescription']);
			$actuator->setRoomID($rc['RoomID']);
			$actuator->setDeviceID($rc['DeviceID']);
		}
		
		mysqli_free_result($rs);
		mysqli_close($conn);
		
		return $actuator;
	}
	
	public function getActuatorModelBySensor($sensorID){
		require_once __DIR__ . '/../model/Actuator.php';
		$conn = $this->getConnection();
		$sql = "SELECT a.*, at.*, p.*
				FROM actuator as a, sensor as s, actuator_type as at, actuator_sensor as a_s, permission as p 
				WHERE a.ActuatorID=a_s.ActuatorID AND s.SensorID=a_s.SensorID AND 
				a.ActuatorTypeID=at.ActuatorTypeID AND p.PermissionID=a.PermissionID AND s.SensorID='$sensorID'";
		$rs = mysqli_query($conn, $sql);
		
		$actuatorArr = array();
		
		if($rs) {
			while($rc = mysqli_fetch_array($rs)) {
				$actuator = new Actuator();
				$actuator->setID($rc['ActuatorID']);
				$actuator->setPin($rc['ActuatorPin']);
				$actuator->setPinName($rc['ActuatorPinName']);
				$actuator->setName($rc['ActuatorName']);
				$actuator->setImage($rc['ActuatorImage']);
				$actuator->setType($rc['ActuatorTypeID']);
				$actuator->setTypeDescription($rc['ActuatorTypeDescription']);
				$actuator->setStatus($rc['ActuatorStatusID']);
				$actuator->setWeatherAPI($rc['WeatherAPI']);
				$actuator->setMode($rc['ModeID']);
				$actuator->setDisplay($rc['Display']);
				$actuator->setPermission($rc['PermissionID']);
				$actuator->setPermissionDescription($rc['PermissionDescription']);
				$actuator->setRoomID($rc['RoomID']);
				$actuator->setDeviceID($rc['DeviceID']);
				$actuatorArr[] = $actuator;
			}
		}
		
		mysqli_free_result($rs);
		mysqli_close($conn);
		
		return $actuatorArr;
	}
	
	public function getActuatorModelByRoom($roomID){
		require_once __DIR__ . '/../model/Actuator.php';
		$conn = $this->getConnection();
		$sql = "SELECT a.*, at.*, p.* 
				FROM actuator as a, actuator_type as at, room as r, permission as p 
				WHERE a.ActuatorTypeID=at.ActuatorTypeID AND r.RoomID=a.RoomID AND p.PermissionID=a.PermissionID AND a.RoomID='$roomID'";
		$rs = mysqli_query($conn, $sql);
		
		$actuatorArr = array();
		
		if($rs) {
			while($rc = mysqli_fetch_array($rs)) {
				$actuator = new Actuator();
				$actuator->setID($rc['ActuatorID']);
				$actuator->setPin($rc['ActuatorPin']);
				$actuator->setPinName($rc['ActuatorPinName']);
				$actuator->setName($rc['ActuatorName']);
				$actuator->setImage($rc['ActuatorImage']);
				$actuator->setType($rc['ActuatorTypeID']);
				$actuator->setTypeDescription($rc['ActuatorTypeDescription']);
				$actuator->setStatus($rc['ActuatorStatusID']);
				$actuator->setWeatherAPI($rc['WeatherAPI']);
				$actuator->setMode($rc['ModeID']);
				$actuator->setDisplay($rc['Display']);
				$actuator->setPermission($rc['PermissionID']);
				$actuator->setPermissionDescription($rc['PermissionDescription']);
				$actuator->setRoomID($rc['RoomID']);
				$actuator->setDeviceID($rc['DeviceID']);
				$actuatorArr[] = $actuator;
			}
		}
		
		mysqli_free_result($rs);
		mysqli_close($conn);
		
		return $actuatorArr;
	}
	
	public function createActuatorSensor($body){
		$conn = $this->getConnection();
		$sql = "INSERT INTO actuator_sensor VALUES(NULL, '$body[actuatorID]', '$body[sensorID]')";
		mysqli_query($conn, $sql);
		$last_id = $conn->insert_id;
		
		mysqli_close($conn);
		
		if(!empty($last_id) && $last_id > 0){
			return $last_id;
		}else{
			return ;
		}
	}
	
	public function createActuatorRecord($body){
		$conn = $this->getConnection();
		$sql = "INSERT INTO actuator_record VALUES(NULL, DEFAULT, '$body[status]', '$body[actuatorID]', '$body[userID]')";
		mysqli_query($conn, $sql);
		$last_id = $conn->insert_id;
		
		mysqli_close($conn);
		
		if(!empty($last_id) && $last_id > 0){
			return $last_id;
		}else{
			return ;
		}
	}

	public function updateActuatorStatus($body){
		$conn = $this->getConnection();
		$sql = "UPDATE actuator SET ActuatorStatusID='$body[status]' WHERE ActuatorID='$body[actuatorID]'";
		mysqli_query($conn, $sql);
		$affected = mysqli_affected_rows($conn);
		
		mysqli_close($conn);
		
		if(!empty($affected) && $affected > 0){
			return $body;
		}else{
			return;
		}
	}
	
	public function updateActuatorMode($body){
		$conn = $this->getConnection();
		$sql = "UPDATE actuator SET ModeID='$body[mode]' WHERE ActuatorID='$body[actuatorID]'";
		mysqli_query($conn, $sql);
		$affected = mysqli_affected_rows($conn);
		
		mysqli_close($conn);
		
		if(!empty($affected) && $affected > 0){
			return $body;
		}else{
			return;
		}
	}
	
	public function updateActuatorRoom($body){
		$conn = $this->getConnection();
		$sql = "UPDATE actuator SET RoomID=$body[roomID] WHERE ActuatorID='$body[actuatorID]'";
		mysqli_query($conn, $sql);
		$affected = mysqli_affected_rows($conn);
		
		mysqli_close($conn);
		
		if(!empty($affected) && $affected > 0){
			return $body;
		}else{
			return;
		}
	}

	public function deleteActuatorSensor($body){
		$conn = $this->getConnection();
		$sql = "DELETE FROM actuator_sensor WHERE ActuatorSensorID='$body[actuatorSensorID]'";
		mysqli_query($conn, $sql);
		
		$affected_id = $conn->affected_rows;
		mysqli_close($conn);
		
		if(!empty($affected_id) && $affected_id > 0){
			return $affected_id;
		}else{
			return ;
		}
	}
	
	public function deleteActuatorRecord($body){
		$conn = $this->getConnection();
		$sql = "DELETE FROM actuator_record WHERE UserID='$body[userID]'";
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