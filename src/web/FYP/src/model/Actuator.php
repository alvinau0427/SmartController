<?php
require_once __DIR__ . '/Component.php';
require_once __DIR__ . '/Sensor.php';
require_once __DIR__ . '/Time.php';
require_once __DIR__ . '/Regulation.php';
require_once __DIR__ . '/ActuatorSensor.php';

class Actuator extends Component{
	private $status;
	private $statusDescription;
	private $weatherAPI;
	private $mode;
	private $display;
	private $permission;
	private $permissionDescription;
	private $time = array();
	private $regulation = array();
	private $actuatorSensor = array();
	
	public function __construct(){}
	
	public function getStatus() {
		return $this->status;
	}
	
	public function setStatus($status) {
		$this->status = $status;
	}
	
	public function getStatusDescription() {
		return $this->statusDescription;
	}
	
	public function setStatusDescription($statusDescription) {
		$this->statusDescription = $statusDescription;
	}
	
	
	public function getWeatherAPI() {
		return $this->weatherAPI;
	}
	
	public function setWeatherAPI($weatherAPI) {
		$this->weatherAPI = $weatherAPI;
	}
	
	public function getMode() {
		return $this->mode;
	}
	
	public function setMode($mode) {
		$this->mode = $mode;
	}
	
	public function getDisplay() {
		return $this->display;
	}
	
	public function setDisplay($display) {
		$this->display = $display;
	}
	
	public function getPermission() {
		return $this->permission;
	}
	
	public function setPermission($permission) {
		$this->permission = $permission;
	}
	
	public function getPermissionDescription() {
		return $this->permissionDescription;
	}
	
	public function setPermissionDescription($permissionDescription) {
		$this->permissionDescription = $permissionDescription;
	}
	
	public function addTime($time) {
		$this->time[] = $time;
	}
	
	public function removeTime($time) {
		$this->time = array_diff($this->time, [$time]);
	}
		
	public function getTimes() {
		return $this->time;
	}
	
	public function addRegulation($regulation) {
		$this->regulation[] = $regulation;
	}
	
	public function removeRegulation($regulation) {
		$this->regulation = array_diff($this->regulation, [$regulation]);
	}
		
	public function getRegulation() {
		return $this->regulation;
	}
	
	public function addActuatorSensor($as) {
		$this->actuatorSensor[] = $as;
	}
	
	public function removeActuatorSensor($as) {
		$this->actuatorSensor = array_diff($this->actuatorSensor, [$as]);
	}
		
	public function getSensor() {
		$sensor = array();
		foreach($this->actuatorSensor as $val) {
			$sensor[] = $val->getSensor();
		}
		return $sensor;
	}
	
	/*public function getAutoRule() {
		$autoRule = array();
		foreach($this->actuatorSensor as $val) {
			$autoRule[] = $val->getAutoRule();
		}
		return $autoRule;
	}
	
	public function getAutoRuleBySensor($sensor) {
		$autoRule;
		foreach($this->actuatorSensor as $sm) {
			if($sm->getSensor() === $sensor) {
				$autoRule = $sm->getAutoRule();
				break;
			}
		}
		return $autoRule;
	}*/
	
	public function checkTime() {
		$isCompliance = false;
		foreach($this->time as $tRule) {
			if($tRule->check($_SERVER['REQUEST_TIME'])) {
				$isCompliance = true;
				//echo 'between time' . PHP_EOL;
				break;
			}
		}
		return $isCompliance;
	}
	
	public function checkAuto() {
		foreach($this->regulation as $reg) {
			//echo 'regulation ' . $reg->getID() . PHP_EOL;
			if($reg->check()) {
				//echo 'regulation ' . $reg->getID() . ' matched' . PHP_EOL;
				return $reg;
				break;
			}
		}
		return false;
		
	}
	
	public function execute() {
		if($this->checkTime() && ($regulation = $this->checkAuto()) ) {
			return $regulation;
			//($regulation = $this->checkAuto()) ? $this->autoStatus = $regulation->getStatus() : null;
		} else {
			return false;
		}
	}
	
	public function jsonSerialize() {
		$vars = get_object_vars($this);

		return $vars;
    }
}
/*$sName = array("TWGS", "KCTS", "LKP", "CMT", "KKY");
$mName = array("Peter Chan", "Alan Tong", "John Lee", "Venice Tsui", "Mary Lui");
$s = array(5);
$m = array(5);
for ($i = 0; $i < 5; $i++) {
	$s[$i] = new Sensor();
	$s[$i]->setName($sName[$i]);
	$m[$i] = new Module();
	$m[$i]->setName($mName[$i]);
}
ActuatorSensor::addActuatorSensor($s[0],$m[0],1241);
ActuatorSensor::addActuatorSensor($s[1],$m[1],1234);
ActuatorSensor::addActuatorSensor($s[2],$m[1],1111);
ActuatorSensor::addActuatorSensor($s[3],$m[2],9878);
ActuatorSensor::addActuatorSensor($s[4],$m[3],6782);
ActuatorSensor::addActuatorSensor($s[4],$m[4],9807);
ActuatorSensor::addActuatorSensor($s[4],$m[0],9080);
echo json_encode($s[4]->getModule());*/
?>