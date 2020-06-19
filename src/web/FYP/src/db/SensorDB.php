<?php

require_once __DIR__ . '/DB.php';

class SensorDB extends DB {
	
	public function getSensorList(){
		$conn = $this->getConnection();
		$sql = "SELECT s.*, st.*, r.* 
				FROM sensor as s, sensor_type as st, room as r 
				WHERE s.SensorTypeID=st.SensorTypeID AND r.RoomID=s.RoomID";
		$rs = mysqli_query($conn, $sql);
		
		$data = mysqli_fetch_all($rs, MYSQLI_ASSOC);
		
		mysqli_free_result($rs);
		mysqli_close($conn);
		
		return $data;
	}

	public function getSensor($sensorID){
		$conn = $this->getConnection();
		$sql = "SELECT s.*, st.*, r.* 
				FROM sensor as s, sensor_type as st, room as r 
				WHERE s.SensorTypeID=st.SensorTypeID AND r.RoomID=s.RoomID AND s.SensorID='$sensorID'";
				
		$rs = mysqli_query($conn, $sql);
		
		$data = mysqli_fetch_array($rs, MYSQLI_ASSOC);
		
		mysqli_free_result($rs);
		mysqli_close($conn);
		
		return $data;
	}
	
	public function getSensorByRoomID($roomID){
		$conn = $this->getConnection();
		$sql = "SELECT s.*, st.*, r.*
				FROM sensor as s, sensor_type as st, room as r 
				WHERE s.SensorTypeID=st.SensorTypeID AND r.RoomID=s.RoomID AND s.RoomID='$roomID'";
		$rs = mysqli_query($conn, $sql);
		
		$data = mysqli_fetch_all($rs, MYSQLI_ASSOC);
		
		mysqli_free_result($rs);
		mysqli_close($conn);
		
		return $data;
	}
	
	public function getSensorByActuator($actuatorID){
		$conn = $this->getConnection();
		$sql = "SELECT s.*, st.*, a_s.* 
				FROM actuator as a, sensor as s, sensor_type as st, actuator_sensor as a_s 
				WHERE a.ActuatorID=a_s.ActuatorID AND s.SensorID=a_s.SensorID AND s.SensorTypeID=st.SensorTypeID AND a.ActuatorID='$actuatorID'";
		$rs = mysqli_query($conn, $sql);
		
		$data = mysqli_fetch_all($rs, MYSQLI_ASSOC);
		
		mysqli_free_result($rs);
		mysqli_close($conn);
		
		return $data;
	}
	
	public function getSensorPin($device){
		$conn = $this->getConnection();
		$sql = "SELECT SensorPin, SensorPinName FROM sensor WHERE DeviceID='$device'";
		$rs = mysqli_query($conn, $sql);
		
		$pinArr;
		if($rs) {
			while($rc = mysqli_fetch_array($rs)) {
				$pinArr[$rc['SensorPinName']] = $rc['SensorPin'];
			}
		}
		
		mysqli_free_result($rs);
		mysqli_close($conn);
		
		return $pinArr;
	}
	
	public function getSensorModel($sensorID){
		require_once __DIR__ . '/../model/Sensor.php';
		$conn = $this->getConnection();
		$sql = "SELECT s.*, st.* 
				FROM sensor as s, sensor_type as st
				WHERE s.SensorTypeID=st.SensorTypeID AND s.SensorID='$sensorID'";
		$rs = mysqli_query($conn, $sql);
		
		if($rs) {
			$rc = mysqli_fetch_array($rs);
			$sensor = new Sensor();
			$sensor->setID($rc['SensorID']);
			$sensor->setPin($rc['SensorPin']);
			$sensor->setPinName($rc['SensorPinName']);
			$sensor->setName($rc['SensorName']);
			$sensor->setImage($rc['SensorImage']);
			$sensor->setType($rc['SensorTypeID']);
			$sensor->setTypeDescription($rc['SensorTypeDescription']);
			$sensor->setValue($rc['SensorValue']);
			$sensor->setUnit($rc['SensorUnit']);
			$sensor->setRoomID($rc['RoomID']);
			$sensor->setDeviceID($rc['DeviceID']);
		}
		
		mysqli_free_result($rs);
		mysqli_close($conn);
		
		return $sensor;
	}
	
	public function getSensorModelByActuator($actuatorID){
		require_once __DIR__ . '/../model/Sensor.php';
		$conn = $this->getConnection();
		$sql = "SELECT s.*, st.* 
				FROM actuator as a, sensor as s, sensor_type as st, actuator_sensor as a_s 
				WHERE a.ActuatorID=a_s.ActuatorID AND s.SensorID=a_s.SensorID AND s.SensorTypeID=st.SensorTypeID AND a.ActuatorID='$actuatorID'";
		$rs = mysqli_query($conn, $sql);
		
		$sensorArr = array();
		
		if($rs) {
			while($rc = mysqli_fetch_array($rs)) {
				$sensor = new Sensor();
				$sensor->setID($rc['SensorID']);
				$sensor->setPin($rc['SensorPin']);
				$sensor->setPinName($rc['SensorPinName']);
				$sensor->setName($rc['SensorName']);
				$sensor->setImage($rc['SensorImage']);
				$sensor->setType($rc['SensorTypeID']);
				$sensor->setTypeDescription($rc['SensorTypeDescription']);
				$sensor->setValue($rc['SensorValue']);
				$sensor->setUnit($rc['SensorUnit']);
				$sensor->setRoomID($rc['RoomID']);
				$sensor->setDeviceID($rc['DeviceID']);
				$sensorArr[] = $sensor;
			}
		}
		
		mysqli_free_result($rs);
		mysqli_close($conn);
		
		return $sensorArr;
	}
	
	/*public function getSensorModelByFunctionId($functionID){
		require_once __DIR__ . '/../model/Sensor.php';
		$conn = $this->getConnection();
		$sql = "SELECT * FROM sensor WHERE Function='$functionID'";
		$rs = mysqli_query($conn, $sql);
		
		$sensorArr = array();
		
		if($rs) {
			while($rc = mysqli_fetch_array($rs)) {
				$sensor = new Sensor();
				$sensor->setDeviceID($rc['DeviceID']);
				$sensor->setPin($rc['Pin']);
				$sensor->setName($rc['Name']);
				$sensor->setImage($rc['Image']);
				$sensor->setType($rc['Type']);
				$sensor->setValue($rc['Value']);
				$sensor->setUnit($rc['Unit']);
				$sensorArr[] = $sensor;
			}
		}
		
		mysqli_free_result($rs);
		mysqli_close($conn);
		
		return $sensorArr;
	}*/

	/*public function createUser($user){
		$sql = "insert into user(name, gender, age, job) VALUES('$user[name]','$user[gender]','$user[age]','$user[job]')";
		$rs = $this->conn->query($sql);
		$last_userid = $this->conn->insert_id;
		if(!empty($last_userid)){
			return $last_userid;
		}else{
			return ;
		}
	}*/

	public function updateSensorValue($body){
		$conn = $this->getConnection();
		$sql = "UPDATE sensor SET SensorValue='$body[value]' WHERE SensorID='$body[sensorID]'";
		mysqli_query($conn, $sql);
		$affected = mysqli_affected_rows($conn);
		
		mysqli_close($conn);
		
		if(!empty($affected) && $affected > 0){
			return $body;
		}else{
			return ;
		}
	}

	/*public function deleteUser($user){
		$sql = "DELETE FROM user WHERE id='$user[id]'";
		$rs = $this->conn->query($sql);
		$affected_userid = $this->conn->affected_rows;
		if(!empty($affected_userid)){
			return $affected_userid;
		}else{
			return ;
		}
	}*/
}
?>