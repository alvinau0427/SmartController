<?php

require_once __DIR__ . '/DB.php';

class DeviceDB extends DB {
	
	public function getDeviceList(){
		$conn = $this->getConnection();
		$sql = "SELECT * FROM device";
		$rs = mysqli_query($conn, $sql);
		
		$data = mysqli_fetch_all($rs, MYSQLI_ASSOC);
		
		mysqli_free_result($rs);
		mysqli_close($conn);
		
		return $data;
	}

	public function getDevice($deviceID){
		$conn = $this->getConnection();
		$sql = "SELECT * FROM device WHERE DeviceID='$deviceID'";
		$rs = mysqli_query($conn, $sql);
		
		$data = mysqli_fetch_array($rs, MYSQLI_ASSOC);
		
		mysqli_free_result($rs);
		mysqli_close($conn);
		
		return $data;
	}
	
	public function getDeviceModel($deviceID){
		require_once __DIR__ . '/../model/Device.php';
		$conn = $this->getConnection();
		$sql = "SELECT * FROM device WHERE DeviceID='$deviceID'";
		$rs = mysqli_query($conn, $sql);
		
		if($rs) {
			$rc = mysqli_fetch_array($rs);
			$device = new Device();
			$device->setID($rc['DeviceID']);
			$device->setName($rc['DeviceName']);
			$device->setIP($rc['IP']);
		}
		
		mysqli_free_result($rs);
		mysqli_close($conn);
		
		return $device;
	}
	
	public function updateDeviceIP($body){
		$conn = $this->getConnection();
		$sql = "UPDATE device SET IP='$body[ip]' WHERE DeviceID='$body[deviceID]'";
		
		mysqli_query($conn, $sql);
		
		$affected = mysqli_affected_rows($conn);
		mysqli_close($conn);
		
		if(!empty($affected) && $affected > 0){
			return $affected;
		}else{
			return 0;
		}
	}
}
?>