<?php

require_once __DIR__ . '/DB.php';

class LocationDB extends DB {
	
	public function getLocationList(){
		$conn = $this->getConnection();
		$sql = "SELECT * FROM location";
		$rs = mysqli_query($conn, $sql);
		
		$data = mysqli_fetch_all($rs, MYSQLI_ASSOC);
		
		mysqli_free_result($rs);
		mysqli_close($conn);
		
		return $data;
	}

	public function getLocation($locationID){
		$conn = $this->getConnection();
		$sql = "SELECT * FROM location WHERE LocationID='$locationID'";
		$rs = mysqli_query($conn, $sql);
		
		$data = mysqli_fetch_array($rs, MYSQLI_ASSOC);
		
		mysqli_free_result($rs);
		mysqli_close($conn);
		
		return $data;
	}
	
	public function updateLocationIP($body){
		$conn = $this->getConnection();
		$sql = "UPDATE location SET IP='$body[ip]' WHERE LocationID='$body[locationID]'";
		
		mysqli_query($conn, $sql);
		
		$affected = mysqli_affected_rows($conn);
		mysqli_close($conn);
		
		if(!empty($affected)){
			return $affected;
		}else{
			return 0;
		}
	}
}
?>