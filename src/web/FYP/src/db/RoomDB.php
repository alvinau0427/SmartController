<?php

require_once __DIR__ . '/DB.php';

class RoomDB extends DB {
	
	public function getRoomList(){
		$conn = $this->getConnection();
		$sql = "SELECT * FROM room";
		$rs = mysqli_query($conn, $sql);
		
		$data = mysqli_fetch_all($rs, MYSQLI_ASSOC);
		
		mysqli_free_result($rs);
		mysqli_close($conn);
		
		return $data;
	}

	public function getRoom($roomID){
		$conn = $this->getConnection();
		$sql = "SELECT * FROM room WHERE roomID='$roomID'";
		$rs = mysqli_query($conn, $sql);
		
		$data = mysqli_fetch_array($rs, MYSQLI_ASSOC);
		
		mysqli_free_result($rs);
		mysqli_close($conn);
		
		return $data;
	}
	
	/*public function getRoomDetail($location) {
		$conn = $this->getConnection();
		$sql = "SELECT FunctionID, f.Name, l.Name as Location, f.Image, f.Status as FunctionStatus, DeviceID, Pin, 
				m.Name as ModuleName, mt.Description as ModuleType, m.Status as ModuleStatus
				FROM function as f, location as l, module as m, module_type as mt 
				WHERE f.Location=l.LocationID AND m.Function=f.FunctionID AND mt.TypeID=m.Type AND f.Location='$location'";
		$rs = mysqli_query($conn, $sql);
		
		$data = mysqli_fetch_all($rs, MYSQLI_ASSOC);
		
		mysqli_free_result($rs);
		mysqli_close($conn);
		
		return $data;
	}
	*/
	/*public function getRoomBySensor($device, $pin) {
		$conn = $this->getConnection();
		$sql = "SELECT * FROM sensor, function WHERE Function=FunctionID AND DeviceID='$device' AND Pin='$pin'";
		$rs = mysqli_query($conn, $sql);
		
		$data = mysqli_fetch_array($rs, MYSQLI_ASSOC);
		
		mysqli_free_result($rs);
		mysqli_close($conn);
		
		return $data;
	}*/
	
	
	public function getRoomModelById($roomID) {
		require_once __DIR__ . '/../model/Room.php';
		$conn = $this->getConnection();
		$sql = "SELECT * FROM room WHERE RoomID='$roomID'";
		$rs = mysqli_query($conn, $sql);
		
		if($rs) {
			$rc = mysqli_fetch_array($rs);
			$room = new Room();
			$room->setID($rc['RoomID']);
			$room->setName($rc['RoomName']);
			$room->setImage($rc['RoomImage']);
		}
		
		mysqli_free_result($rs);
		mysqli_close($conn);
		
		return $room;
	}
	
	public function getRoomModelList() {
		require_once __DIR__ . '/../model/Room.php';
		$conn = $this->getConnection();
		$sql = "SELECT * FROM room";
		$rs = mysqli_query($conn, $sql);
		
		$roomArr = array();
		
		if($rs) {
			while($rc = mysqli_fetch_array($rs)) {
				$room = new Room();
				$room->setID($rc['RoomID']);
				$room->setName($rc['RoomName']);
				$room->setImage($rc['RoomImage']);
				$roomArr[] = $room;
			}
		}
		
		mysqli_free_result($rs);
		mysqli_close($conn);
		
		return $roomArr;
	}
	
	/*public function getRoomModelBySensor($device, $pin) {
		require_once __DIR__ . '/../model/SmartFunction.php';
		$conn = $this->getConnection();
		$sql = "SELECT * FROM sensor, function WHERE Function=FunctionID AND DeviceID='$device' AND Pin='$pin'";
		$rs = mysqli_query($conn, $sql);
		
		if($rs) {
			$rc = mysqli_fetch_array($rs);
			$func = new SmartFunction();
			$func->setFunctionID($rc['FunctionID']);
			$func->setName($rc['Name']);
			$func->setLocation($rc['Location']);
			$func->setImage($rc['Image']);
			$func->setStatus($rc['Status']);
		}
		
		mysqli_free_result($rs);
		mysqli_close($conn);
		
		return $func;
	}*/
	
	/*public function stuffDeviceModelToRoom($function) {
		require_once __DIR__ . '/../model/SmartFunction.php';
		require_once __DIR__ . '/../model/Sensor.php';
		require_once __DIR__ . '/../model/Module.php';
		require_once __DIR__ . '/../model/AutoRule.php';
		require_once __DIR__ . '/../model/SensorModule.php';
		$conn = $this->getConnection();
		$sql = "SELECT SELECT a_s.*, a.*, s.* 
				FROM actuator_sensor as a_s, sensor as s, actuator as a 
				WHERE a_s.SensorID=s.SensorID AND a_s.ActuatorID=a.ActuatorID AND 
				s.RoomID='" . $function->getFunctionID() . "' AND a.RoomID='" . $function->getFunctionID() . "' ORDER BY ID";
		/1*$sql = "SELECT sm.ID, SensorID, SensorPin, ModuleID, ModulePin, ra.RuleID, ra.Type as RuleType, ra.AutoValue 
				FROM sensor_module as sm, sensor as s, module as m, rule_auto as ra 
				WHERE SensorID=s.DeviceID AND SensorPin=s.Pin AND ModuleID=m.DeviceID AND ModulePin=m.Pin AND 
				sm.ID=ra.ID AND s.Function='1' AND m.Function='1' ORDER BY ID";*1/
		$rs = mysqli_query($conn, $sql);
		
		if($rs) {
			$moduleIDArr = array();
			$modulePinArr = array();
			$moduleArr = array();
			
			$SensorIDArr = array();
			$sensorPinArr = array();
			$sensorArr = array();
			while($rc = mysqli_fetch_array($rs)) {
				$module;
				$sensor;
				if( in_array($rc['ModuleID'], $moduleIDArr) == NULL || 
						in_array($rc['ModulePin'], $modulePinArr) == NULL ) {
					$module = new module();
					$module->setDeviceID($rc['ModuleID']);
					$module->setPin($rc['ModulePin']);
					$module->setName($rc['ModuleName']);
					$module->setImage($rc['ModuleImage']);
					$module->setType($rc['ModuleType']);
					$module->setStatus($rc['ModuleStatus']);
					$module->setAutoStatus($rc['ModuleAutoStatus']);
					$module->setWeatherAPI($rc['ModuleWeatherAPI']);
					$module->setLogicGate($rc['ModuleLogicGate']);
					
					$moduleIDArr[] = $rc['ModuleID'];
					$modulePinArr[] = $rc['ModulePin'];
					$moduleArr[$rc['ModulePinName']] = $module;
					
					$function->addModule($module);
				} else {
					$module = $moduleArr[$rc['ModulePinName']];
				}
				
				if( in_array($rc['SensorID'], $SensorIDArr) == NULL || 
						in_array($rc['SensorPin'], $sensorPinArr) == NULL) {
					$sensor = new Sensor();
					$sensor->setDeviceID($rc['SensorID']);
					$sensor->setPin($rc['SensorPin']);
					$sensor->setName($rc['SensorName']);
					$sensor->setImage($rc['SensorImage']);
					$sensor->setType($rc['SensorType']);
					$sensor->setValue($rc['SensorValue']);
					$sensor->setUnit($rc['SensorUnit']);
					
					$SensorIDArr[] = $rc['SensorID'];
					$sensorPinArr[] = $rc['SensorPin'];
					$sensorArr[$rc['SensorPinName']] = $sensor;
					
					$function->addSensor($sensor);
				} else {
					$sensor = $sensorArr[$rc['SensorPinName']];
				}
				
				$autoRule = new AutoRule();
				$autoRule->setRuleID($rc['RuleID']);
				$autoRule->setType($rc['RuleType']);
				$autoRule->setAutoValue($rc['AutoValue']);
				
				SensorModule::addSensorModule($sensor, $module, $rc['ID'], $autoRule);
			}
		}
		
		mysqli_free_result($rs);
		mysqli_close($conn);
		
		return $function;
	}*/

	public function createRoom($body){
		$conn = $this->getConnection();
		$sql = "INSERT INTO room VALUES(NULL, '$body[name]', '$body[image]')";
		mysqli_query($conn, $sql);
		$last_id = $conn->insert_id;
		
		mysqli_close($conn);
		
		if(!empty($last_id) && $last_id > 0){
			return $last_id;
		}else{
			return ;
		}
	}
	
	public function updateRoom($body){
		$conn = $this->getConnection();
		$sql = "UPDATE room SET RoomName='$body[name]' WHERE RoomID='$body[roomID]'";
		
		mysqli_query($conn, $sql);
		
		$affected = mysqli_affected_rows($conn);
		mysqli_close($conn);
		
		if(!empty($affected) && $affected > 0){
			return $affected;
		}else{
			return 0;
		}
	}

	public function deleteRoom($body){
		$conn = $this->getConnection();
		$sql = "DELETE FROM room WHERE RoomID='$body[roomID]'";
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