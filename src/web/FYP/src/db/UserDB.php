<?php

require_once __DIR__ . '/DB.php';

class UserDB extends DB {
	
	public function getUserList(){
		$conn = $this->getConnection();
		$sql = "SELECT UserID, UserName, LoginAccount, Email, Image, ReceiveNotification, 
				ReceiveEmail, LocationDisplay, ut.* 
				FROM user_account as ua, user_type as ut 
				WHERE ua.UserTypeID=ut.UserTypeID";
		$rs = mysqli_query($conn, $sql);
		
		$data = mysqli_fetch_all($rs, MYSQLI_ASSOC);
		
		mysqli_free_result($rs);
		mysqli_close($conn);
		
		return $data;
	}
	
	public function getUser($userID){
		$conn = $this->getConnection();
		$sql = "SELECT UserID, UserName, LoginAccount, Email, Image, ReceiveNotification, 
				ReceiveEmail, LocationDisplay, ut.* 
				FROM user_account as ua, user_type as ut 
				WHERE ua.UserTypeID=ut.UserTypeID AND ua.UserID='$userID'";
		$rs = mysqli_query($conn, $sql);
		
		$data = mysqli_fetch_array($rs, MYSQLI_ASSOC);
		
		mysqli_free_result($rs);
		mysqli_close($conn);
		
		return $data;
	}
	
	public function getUserImageByAccount($account){
		$conn = $this->getConnection();
		$sql = "SELECT Image FROM user_account WHERE LoginAccount='$account'";
		$rs = mysqli_query($conn, $sql);
		
		$data = mysqli_fetch_array($rs, MYSQLI_ASSOC);
		
		mysqli_free_result($rs);
		mysqli_close($conn);
		
		return $data;
	}

	public function getUserModelList(){
		require_once __DIR__ . '/../model/User.php';
		$conn = $this->getConnection();
		$sql = "SELECT UserID, UserName, LoginAccount, Email, Token, 
				Image, ReceiveNotification, ReceiveEmail, LocationDisplay, ut.* 
				FROM user_account as ua, user_type as ut
				WHERE ua.UserTypeID=ut.UserTypeID";
		$rs = mysqli_query($conn, $sql);
		
		$userArr = array();
		
		if($rs) {
			while($rc = mysqli_fetch_array($rs)) {
				$user = new User();
				$user->setID($rc['UserID']);
				$user->setName($rc['UserName']);
				$user->setType($rc['UserTypeID']);
				$user->setTypeDescription($rc['UserTypeDescription']);
				$user->setLoginAccount($rc['LoginAccount']);
				$user->setEmail($rc['Email']);
				$user->setToken($rc['Token']);
				$user->setImage($rc['Image']);
				$user->setReceiveNotification($rc['ReceiveNotification']);
				$user->setReceiveEmail($rc['ReceiveEmail']);
				$user->setLocationDisplay($rc['LocationDisplay']);
				$userArr[] = $user;
			}
		}
		
		mysqli_free_result($rs);
		mysqli_close($conn);
		
		return $userArr;
	}
	
	public function getUserModel($userID){
		require_once __DIR__ . '/../model/User.php';
		$conn = $this->getConnection();
		$sql = "SELECT UserID, UserName, LoginAccount, Email, Token, 
				Image, ReceiveNotification, ReceiveEmail, LocationDisplay, ut.* 
				FROM user_account as ua, user_type as ut
				WHERE ua.UserTypeID=ut.UserTypeID AND
				ua.UserID='$userID'";
		$rs = mysqli_query($conn, $sql);
		
		if($rs) {
			$rc = mysqli_fetch_array($rs);
			$user = new User();
			$user->setID($rc['UserID']);
			$user->setName($rc['UserName']);
			$user->setType($rc['UserTypeID']);
			$user->setTypeDescription($rc['UserTypeDescription']);
			$user->setLoginAccount($rc['LoginAccount']);
			$user->setEmail($rc['Email']);
			$user->setToken($rc['Token']);
			$user->setImage($rc['Image']);
			$user->setReceiveNotification($rc['ReceiveNotification']);
			$user->setReceiveEmail($rc['ReceiveEmail']);
			$user->setLocationDisplay($rc['LocationDisplay']);
		}
		
		mysqli_free_result($rs);
		mysqli_close($conn);
		
		return $user;
	}
	
	public function authentication($body){
		$conn = $this->getConnection();
		$sql = "SELECT UserID, UserName, LoginAccount, Email, Token, 
				Image, ReceiveNotification, ReceiveEmail, LocationDisplay, ut.* 
				FROM user_account as ua, user_type as ut
				WHERE ua.UserTypeID=ut.UserTypeID AND UserID!=1 AND LoginAccount='$body[account]' AND Password='$body[password]'";
		$rs = mysqli_query($conn, $sql);
		
		$data = mysqli_fetch_array($rs, MYSQLI_ASSOC);
		
		mysqli_free_result($rs);
		mysqli_close($conn);

		return $data;
	}
	
	public function createUser($body){
		$conn = $this->getConnection();
		$sql = "INSERT INTO user_account VALUES(NULL, '$body[name]', '$body[type]', '$body[loginAccount]', 
				'$body[password]', '$body[email]', NULL, '$body[image]', '$body[receiveNotification]', 
				'$body[receiveEmail]', '$body[locationDisplay]')";
		mysqli_query($conn, $sql);
		$last_id = $conn->insert_id;
		
		mysqli_close($conn);
		
		if(!empty($last_id) && $last_id > 0){
			return $last_id;
		}else{
			return ;
		}
	}

	public function updateUser($body){
		$conn = $this->getConnection();
		$sql = "UPDATE user_account SET UserName='$body[name]',
				UserTypeID='$body[type]',
				Email='$body[email]',
				Image='$body[image]',
				Password=IFNULL(NULLIF('$body[password]',''), Password)
				WHERE UserID='$body[userID]' AND UserID!=1";
		mysqli_query($conn, $sql);
		$affected = mysqli_affected_rows($conn);
		
		mysqli_close($conn);
		
		if(!empty($affected) && $affected > 0){
			return $body;
		}else{
			return ;
		}
	}
	
	public function updateToken($body){
		$conn = $this->getConnection();
		$sql = "UPDATE user_account SET Token='$body[token]' WHERE UserID='$body[userID]' AND UserID!=1";
		mysqli_query($conn, $sql);
		$affected = mysqli_affected_rows($conn);
		
		mysqli_close($conn);
		
		if(!empty($affected) && $affected > 0){
			return $body;
		}else{
			return ;
		}
	}
	
	public function updateReceiveNotification($body){
		$conn = $this->getConnection();
		$sql = "UPDATE user_account SET ReceiveNotification='$body[status]' WHERE UserID='$body[userID]' AND UserID!=1";
		mysqli_query($conn, $sql);
		$affected = mysqli_affected_rows($conn);
		
		mysqli_close($conn);
		
		if(!empty($affected) && $affected > 0){
			return $body;
		}else{
			return ;
		}
	}
	
	public function updateReceiveEmail($body){
		$conn = $this->getConnection();
		$sql = "UPDATE user_account SET ReceiveEmail='$body[status]' WHERE UserID='$body[userID]' AND UserID!=1";
		mysqli_query($conn, $sql);
		$affected = mysqli_affected_rows($conn);
		
		mysqli_close($conn);
		
		if(!empty($affected) && $affected > 0){
			return $body;
		}else{
			return ;
		}
	}
	
	public function updateLocationDisplay($body){
		$conn = $this->getConnection();
		$sql = "UPDATE user_account SET LocationDisplay='$body[status]' WHERE UserID='$body[userID]' AND UserID!=1";
		mysqli_query($conn, $sql);
		$affected = mysqli_affected_rows($conn);
		
		mysqli_close($conn);
		
		if(!empty($affected) && $affected > 0){
			return $body;
		}else{
			return ;
		}
	}

	public function deleteUser($body){
		$conn = $this->getConnection();
		$sql = "DELETE FROM user_account WHERE UserID='$body[userID]'";
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