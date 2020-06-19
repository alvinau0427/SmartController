<?php

require_once __DIR__ . '/DB.php';

class NotificationRuleDB extends DB {
	
	public function getNotificationRuleList(){
		$conn = $this->getConnection();
		$sql = "SELECT * FROM rule_notification";
		$rs = mysqli_query($conn, $sql);
		
		$data = mysqli_fetch_all($rs, MYSQLI_ASSOC);
		
		mysqli_free_result($rs);
		mysqli_close($conn);
		
		return $data;
	}
	
	public function getNotificationRule($ruleID){
		$conn = $this->getConnection();
		$sql = "SELECT * FROM rule_notification WHERE RuleID='$ruleID'";
		$rs = mysqli_query($conn, $sql);
		
		$data = mysqli_fetch_array($rs, MYSQLI_ASSOC);
		
		mysqli_free_result($rs);
		mysqli_close($conn);
		
		return $data;
	}
	
	public function getNotificationRuleBySensor($device, $pin){
		$conn = $this->getConnection();
		$sql = "SELECT * FROM rule_notification WHERE DeviceID='$device' AND Pin='$pin'";
		$rs = mysqli_query($conn, $sql);
		
		$data = mysqli_fetch_all($rs, MYSQLI_ASSOC);
		
		mysqli_free_result($rs);
		mysqli_close($conn);
		
		return $data;
	}
	
	public function getNotificationRuleRecordList(){
		$conn = $this->getConnection();
		$sql = "SELECT * FROM rule_notification_record ORDER BY DateTime DESC";
		$rs = mysqli_query($conn, $sql);
		
		$data = mysqli_fetch_all($rs, MYSQLI_ASSOC);
		
		mysqli_free_result($rs);
		mysqli_close($conn);
		
		return $data;
	}

	public function getNotificationRuleRecord($device, $pin){
		$conn = $this->getConnection();
		$sql = "SELECT * FROM rule_notification_record WHERE DeviceID='$device' AND Pin='$pin' ORDER BY DateTime DESC";
		$rs = mysqli_query($conn, $sql);
		
		$data = mysqli_fetch_all($rs, MYSQLI_ASSOC);
		
		mysqli_free_result($rs);
		mysqli_close($conn);
		
		return $data;
	}
	
	public function getNotificationRuleModelBySensor($device, $pin){
		require_once __DIR__ . '/../model/NotificationRule.php';
		$conn = $this->getConnection();
		$sql = "SELECT * FROM rule_notification WHERE DeviceID='$device' AND Pin='$pin'";
		$rs = mysqli_query($conn, $sql);
		
		$ruleArr = array();
		
		if($rs) {
			while($rc = mysqli_fetch_array($rs)) {
				$nRule = new NotificationRule();
				$nRule->setRuleID($rc['RuleID']);
				$nRule->setType($rc['Type']);
				$nRule->setNotificationValue($rc['NotificationValue']);
				$nRule->setMessage($rc['Message']);
				$ruleArr[] = $nRule;
			}
		}
		
		mysqli_free_result($rs);
		mysqli_close($conn);
		
		return $ruleArr;
	}

	public function createNotificationRule($body){
		$conn = $this->getConnection();
		$sql = "INSERT INTO rule_notification VALUES(NULL, '$body[type]', '$body[notificationValue]', '$body[deviceID]', '$body[pin]')";
		mysqli_query($conn, $sql);
		$last_id = $conn->insert_id;
		
		mysqli_close($conn);
		
		if(!empty($last_id)){
			return $last_id;
		}else{
			return ;
		}
	}
	
	public function createNotificationRuleRecord($body){
		$conn = $this->getConnection();
		$sql = "INSERT INTO rule_notification_record VALUES(NULL, Default, '$body[type]', '$body[notificationValue]', '$body[message]', '$body[ruleID]')";
		mysqli_query($conn, $sql);
		$last_id = $conn->insert_id;
		
		mysqli_close($conn);
		
		if(!empty($last_id)){
			return $last_id;
		}else{
			return ;
		}
	}
	
	public function updateNotificationRule($body){
		$conn = $this->getConnection();
		$sql = "UPDATE rule_notification SET NotificationValue='$body[notificationValue]', Message='$body[message]' WHERE RuleID='$body[ruleID]'";
		
		mysqli_query($conn, $sql);
		
		$affected = mysqli_affected_rows($conn);
		mysqli_close($conn);
		
		if(!empty($affected)){
			return $body;
		}else{
			return ;
		}
	}

	public function deleteNotificationRule($body){
		$conn = $this->getConnection();
		$sql = "DELETE FROM rule_notification WHERE RuleID='$body[ruleID]'";
		mysqli_query($conn, $sql);
		
		$affected_id = $conn->affected_rows;
		mysqli_close($conn);
		
		if(!empty($affected_id)){
			return $affected_id;
		}else{
			return ;
		}
	}
}
?>