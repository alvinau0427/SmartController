<?php
require_once __DIR__ . '/Controller.php';

class AutoController extends Controller{
	
	public function updateValueSensor($sensorID) {
		
		//send updated sensor value
		$targetSensor = $this->sensorDB->getSensorModel($sensorID);
		$device = $this->deviceDB->getDeviceModel($targetSensor->getDeviceID());
		
		$data = array("deviceID"=>$device->getID(),
						"ip"=>$device->getIP());
		
		$data['devices'][] = array("sensorID"=>$targetSensor->getID(),
									"value"=>$targetSensor->getValue());
						
		$updateValueThread = new PusherThread("sensor", "updateValue", $data);
		$updateValueThread->start();
		
		
		
		//do auto function
		$hasActuatorUpdate = false;
		$sensoriIdenticalArr = array();
		//construct message
		$data = array("deviceID"=>$device->getID(),
						"ip"=>$device->getIP());
		
		$actuatorArr = $this->actuatorDB->getActuatorModelBySensor($targetSensor->getID());
		for($i = 0;$i < count($actuatorArr);$i++) {
			$actuator = $actuatorArr[$i];
			if($actuator->getMode() != 3) {
				//echo 'Mode : ' . $actuator->getMode() . PHP_EOL;
				
				//get Sensor and add to the actuator
				$sensorArr = $this->sensorDB->getSensorModelByActuator($actuator->getID());
				foreach($sensorArr as $sensor) {
					if( isset($sensoriIdenticalArr[$sensor->getID()]) ) {
						$sensor = $sensoriIdenticalArr[$sensor->getID()];
					} else {
						$sensoriIdenticalArr[$sensor->getID()] = $sensor;
					}
					ActuatorSensor::addActuatorSensor($actuator, $sensor);
				}
				
				
				//get Time and add to the actuator
				$timeArr = $this->timeDB->getTimeModelByActuator($actuator->getID());
				foreach($timeArr as $time) {
					$actuator->addTime($time);
				}
				
				
				//get Regulation and add to actuator
				$regulationArr = $this->regulationDB->getRegulationModelByActuator($actuator->getID());
				foreach($regulationArr as $regulation) {
					$conditionArr = $this->conditionDB->getConditionModelByRegulation($regulation->getID());
					
					foreach($conditionArr as $condition) {
						$s = $sensoriIdenticalArr[$condition->getSensorID()];
						$condition->setSensor($s);
						$regulation->addCondition($condition);
					}
					
					$actuator->addRegulation($regulation);
				}
				
				$regulation = $actuator->execute();
				//echo 'Auto Status ' . $actuator->getAutoStatus() . PHP_EOL;
				if($regulation != null && $regulation->getStatus() != $actuator->getStatus()) {
					//update actuator status to autoStauts
					$updateActuator = array("actuatorID"=>$actuator->getID(),
											"sensorName"=>$targetSensor->getName(),
											"actuatorName"=>$actuator->getName(),
											"toStatus"=>$regulation->getStatus(),
											"toStatusDescription"=>$regulation->getStatusDescription(),
											"regulationID"=>$regulation->getID(),
											"regulationHeader"=>$regulation->getHeader());
					
					$data['devices'][] = $updateActuator;
					if($actuator->getMode() == 1) {
						$this->actuatorDB->updateActuatorStatus(array("actuatorID"=>$actuator->getID(),
																		"status"=>$regulation->getStatus()));
						
						$this->actuatorDB->createActuatorRecord(array("actuatorID"=>$actuator->getID(),
																		"status"=>$regulation->getStatus(),
																		"userID"=>1));
						
						$updateStatusThread[$i] = new PusherThread("actuator", "autoUpdateStatus", $data);
					} elseif($actuator->getMode() == 2) {
						$updateStatusThread[$i] = new PusherThread("actuator", "notificationUpdateStatus", $data);
						
						//send notification to android mobile
						$tokenArr = array();
						$userArr = $this->userDB->getUserModelList();
						foreach($userArr as $user) {
							if($user->getReceiveNotification() == 1) {
								$tokenArr[] = $user->getToken();
							}
						}
						$title = 'Policy Activated';
						$body_message = $updateActuator['regulationHeader'] .
										"\nClick to " . $updateActuator['toStatusDescription'] . ' ' . $updateActuator['actuatorName']. '...';
						$message_result = sendAndroidNotification($tokenArr, array("title" => $title, "body" => $body_message));
						//echo $message_result;
					}
					
					$updateStatusThread[$i]->start();
				}
				$data['devices'] = null;
			}
			
		}
	
	}
	
	public function updateStatusActuator($actuatorID) {
		
		$targetActuator = $this->actuatorDB->getActuatorModel($actuatorID);
		
		$device = $this->deviceDB->getDeviceModel($targetActuator->getDeviceID());
		
		$data = array("deviceID"=>$device->getID(),
						"ip"=>$device->getIP());
		
		$data['devices'][] = array("actuatorID"=>$targetActuator->getID(), 
									"actuatorName"=>$targetActuator->getName(),
									"status"=>$targetActuator->getStatus());
						
		$updateStatusThread = new PusherThread("actuator", "updateStatus", $data);
		$updateStatusThread->start();
	}
	
	public function startHeartRateDetector($userID) {
		
		$deviceDetail = $this->deviceDB->getDevice(1);
		
		$data = array("deviceID"=>$deviceDetail['DeviceID'],
						"ip"=>$deviceDetail['IP'],
						"userID"=>$userID);
						
		$startHeartRateThread = new PusherThread("heartRate", "start", $data);
		$startHeartRateThread->start();
	}
	
	public function updateHeartRateDetector($heartRateID) {
		
		$heartRate = $this->heartRateDB->getHeartRate($heartRateID);
		
		$data = array("heartRateID"=>$heartRate['HeartRateID'],
						"heartRate"=>$heartRate['HeartRate'],
						"userID"=>$heartRate['UserID'],
						"dateTime"=>$heartRate['DateTime']);
		
		$updateHeartRateThread = new PusherThread("heartRate", "update", $data);
		$updateHeartRateThread->start();
	}
	
	public function updateWeather($rain) {
		
		if( isset($rain) && $rain > 0 ) {
			
			$actuatorArr = $this->actuatorDB->getActuatorModelList();
			
			foreach($actuatorArr as $actuator) {
				if($actuator->getWeatherAPI() == 1 && $actuator->getStatus() == 1) {
					$data['devices'][] = array("actuatorID"=>$actuator->getID(),
												"actuatorName"=>$actuator->getName(),
												"toStatus"=>0);
					
				}
			}
			
			if(isset($data)) {
				//send notification to android mobile
				$tokenArr = array();
				$userArr = $this->userDB->getUserModelList();
				foreach($userArr as $user) {
					if($user->getReceiveNotification() == 1) {
						$tokenArr[] = $user->getToken();
					}
				}
				
				$title = "Weather warning [HK Observatory]";
				$body_message = "It is highly probable that it will rain today." . "\nClick to ";
				foreach($data['devices'] as $act) {
					$body_message .= 'Close ' . $act['actuatorName'];
					
					if(!next($data['devices'])) {
						$body_message .= '...';
					}
				}
				$message_result = sendAndroidNotification($tokenArr, array("title" => $title,"body" => $body_message));
				
				$updateWeatherThread = new PusherThread("weather", "update", $data);
				$updateWeatherThread->start();
			}
		}
	}
	
	
}

class PusherThread extends Thread {
	private $channel;
	private $event;
	private $data;
	
	public function __construct($channel, $event, $data) {
		$this->channel = $channel;
		$this->event = $event;
		$this->data = $data;
	}
	
	public function getPusherConnection() {
		require __DIR__ .'/../../vendor/autoload.php';
		$pusher = new Pusher(
			'1bc88481522c1ebbf138',
			'5d1bfb2ee41234df1b2a',
			'280590'
		);
		return $pusher;
	}
	
	public function run(){
		$pusher = $this->getPusherConnection();
		$pusher->trigger($this->channel, $this->event, $this->data);
	}
}

function sendAndroidNotification($tokens, $message) {
	$url = 'https://fcm.googleapis.com/fcm/send';
	$api_key = 'AIzaSyC8EJknkkLt40plH9ApE0qkYtPr1AMRGDU';
	
	$fields = array();
	$fields['data'] = $message;
	$fields['notification'] = $message;
	if (is_array($tokens)) {
		$fields['registration_ids'] = $tokens;
	} else {
		$fields['to'] = $tokens;
	}

	$headers = array(
					'Authorization: key=' . $api_key,
					'Content-Type: application/json'
				);
	
	$ch = curl_init();
	curl_setopt( $ch,CURLOPT_URL, $url );
	curl_setopt( $ch,CURLOPT_POST, true );
	curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
	curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch,CURLOPT_TIMEOUT, 10);
	curl_setopt( $ch,CURLOPT_SSL_VERIFYHOST, 0 );
	curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
	curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
	$result = curl_exec($ch);
	curl_close( $ch );
	return $result;
}
?>