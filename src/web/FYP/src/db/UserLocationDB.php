<?php

require_once __DIR__ . '/DB.php';

class UserLocationDB extends DB {
	
	public function getUserLocationList(){
		$conn = $this->getConnection();
		$sql = "SELECT ul.*, ua.UserName, ua.LocationDisplay  
				FROM user_location as ul, user_account as ua 
				WHERE ua.UserID=ul.UserID";
		$rs = mysqli_query($conn, $sql);
		
		$data = mysqli_fetch_all($rs, MYSQLI_ASSOC);
		
		mysqli_free_result($rs);
		mysqli_close($conn);
		
		return $data;
	}
	
	public function getUserLocation($userID){
		$conn = $this->getConnection();
		$sql = "SELECT ul.*, ua.UserName, ua.LocationDisplay 
				FROM user_location as ul, user_account as ua 
				WHERE ua.UserID=ul.UserID AND ul.UserID='$userID'";
		$rs = mysqli_query($conn, $sql);
		
		$data = mysqli_fetch_array($rs, MYSQLI_ASSOC);
		
		mysqli_free_result($rs);
		mysqli_close($conn);
		
		return $data;
	}
	
	/*public function getUserLocationByUser($userID){
		$conn = $this->getConnection();
		$sql = "SELECT UserLocationID, Latitude, Longitude, ul.UserID, ua.Name, DateTime 
				FROM user_location as ul, user_account as ua 
				WHERE ua.UserID=ul.UserID AND ul.UserID='$userID'";
		$rs = mysqli_query($conn, $sql);
		
		$data = mysqli_fetch_all($rs, MYSQLI_ASSOC);
		
		mysqli_free_result($rs);
		mysqli_close($conn);
		
		return $data;
	}*/
	
	/*public function getUserLocationByLocation($locationID){
		$conn = $this->getConnection();
		$sql = "SELECT UserLocationID, Latitude, Longitude, ul.UserID, ua.Name, DateTime 
				FROM user_location as ul, user_account as ua, location as l 
				WHERE ua.Location=l.LocationID AND ua.UserID=ul.UserID AND l.LocationID=$locationID";
		$rs = mysqli_query($conn, $sql);
		
		$data = mysqli_fetch_all($rs, MYSQLI_ASSOC);
		
		mysqli_free_result($rs);
		mysqli_close($conn);
		
		return $data;
	}*/
	
	public function createUserLocation($body){
		$conn = $this->getConnection();
		$sql = "INSERT INTO user_location VALUES(NULL, '$body[latitude]', '$body[longitude]', '$body[userID]', DEFAULT)";
		mysqli_query($conn, $sql);
		$last_id = $conn->insert_id;
		
		mysqli_close($conn);
		
		if(!empty($last_id) && $last_id > 0){
			return $last_id;
		}else{
			return ;
		}
	}
	
	public function updateUserLocation($body){
		$conn = $this->getConnection();
		$sql = "UPDATE user_location SET Latitude='$body[latitude]', Longitude='$body[longitude]', DateTime=DEFAULT WHERE UserID='$body[userID]'";
		
		mysqli_query($conn, $sql);
		
		$affected = mysqli_affected_rows($conn);
		mysqli_close($conn);
		
		if(!empty($affected) && $affected > 0){
			return $affected;
		}else{
			return 0;
		}
	}
	
	public function deleteUserLocationByUser($body){
		$conn = $this->getConnection();
		$sql = "DELETE FROM user_location WHERE UserID='$body[userID]'";
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