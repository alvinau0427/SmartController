<?php
function formatOutput($status, $data, $info){
	$output = array(
		"status" => $status,
		"data" => $data,
		"info" => $info
	);
	return $output;
}


function sensorAuto($body) {
	
	$sensorID = $body['deviceID'];
	$sensorPin = $body['pin'];
	$value = $body['value'];
	
	//check notification?
	require_once __DIR__ . '/db/NotificationRuleDB.php';
	$notificationRuleDB = new NotificationRuleDB();
	$notificationRule = $notificationRuleDB->getNotificationRule($sensorID, $sensorPin);
	
	$notificationValue = $notificationRule['NotificationValue'];
	if(isset($notificationValue)) {
		//pusher send notification
		
		require_once __DIR__ . '/thread/SendNotificationThread.php';
		$pusher = getPusher();
		$sendNotification = new SendNotificationThread($pusher, '123');
		$sendNotification->start();
	}
	
	//check function is auto mode?
	require_once __DIR__ . '/db/FunctionDB.php';
	$functionDB = new FunctionDB();
	$function = $functionDB->getFunctionBySensor($sensorID, $sensorPin);
	
	//if auto mode
	if($function['Status'] == 1) {
		//get sensor corresponding module
		require_once __DIR__ . '/db/ModuleDB.php';
		$moduleDB = new ModuleDB();
		$module = $moduleDB->getModuleBySensor($sensorID, $sensorPin);
		
		foreach($module as $m)
		{
			$moduleID = $m['ModuleID'];
			$modulePin = $m['ModulePin'];
			
			echo '1. Target: ModuleID:'.$moduleID . " | ModulePin:" . $modulePin . "<br>";
			
			require_once __DIR__ . '/db/TimeRuleDB.php';
			$timeRuleDB = new TimeRuleDB();
			$timeRule = $timeRuleDB->getTimeRule($moduleID, $modulePin);
			
			// check is the run time?
			$isBetweenTime = false;
			$currentTime = $_SERVER['REQUEST_TIME'];
			foreach($timeRule as $time) 
			{
				$startTime = strtotime($time['StartTime']);
				$endTime = strtotime($time['EndTime']);
				if($currentTime >= $startTime && $currentTime <= $endTime){
					$isBetweenTime = true;
					echo '2. here<br>';
					break;
				}
			}
			echo '2. Current:'.date('H:i', $currentTime).'<br>Start:'.date('H:i', $startTime).'<br>End:'.date('H:i', $endTime)."<br>";
			
			// if between the timestamp
			if($isBetweenTime) {
				
				// get the auto value
				require_once __DIR__ . '/db/AutoRuleDB.php';
				$autoRuleDB = new AutoRuleDB();
				$autoRule = $autoRuleDB->getAutoRule($sensorID, $sensorPin);
				
				$autoOperator = $autoRule['Type'];
				$autoValue = $autoRule['AutoValue'];
				$autoStatus = $autoRule['AutoStatus'];
				echo "3. ".$value ." | ". $autoOperator ." | ". $autoValue."<br>";
				if(compareValue($value, $autoOperator, $autoValue)) {
					echo "4. match the auto value requirement<br>";
					//update module to corresponding status
					$moduleDB->updateModuleStatus(array("status"=>$autoStatus, 
														"deviceID"=>$moduleID, 
														"pin"=>$modulePin));
					echo "4. Status change to : ".$autoStatus."<br>";
					
					// pusher to other device
					$allUpdatedModule = $moduleDB->getModuleStatus();
					
					require_once __DIR__ . '/thread/SendUpdatedModuleThread.php';
					$pusher = getPusher();
					$sendUpdatedModule = new SendUpdatedModuleThread($pusher, $allUpdatedModule);
					$sendUpdatedModule->start();
				}
			}
		}
	}
}

function compareValue($value, $operator, $ruleValue) {
	switch($operator) {
		case 0:
			return ($value < $ruleValue);
			break;
			
		case 1:
			return ($value == $ruleValue);
			break;
			
		case 2:
			return ($value > $ruleValue);
			break;
			
		default:
			return false;
	}
}

?>