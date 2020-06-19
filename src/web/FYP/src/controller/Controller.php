<?php
foreach(glob(ROOT . "/src/db/*.php") as $filename)
{
	require_once $filename;
}

foreach(glob(ROOT . "/src/model/*.php") as $filename)
{
	require_once $filename;
}

class Controller {
	protected $sensorDB;
	protected $actuatorDB;
	protected $timeDB;
	protected $roomDB;
	protected $deviceDB;
	protected $heartRateDB;
	protected $regulationDB;
	protected $conditionDB;
	protected $userDB;
	protected $weatherDB;
	
	protected $roomArr;
	protected $user;
	
	public function __construct() {
		$this->sensorDB = new SensorDB();
		$this->actuatorDB = new ActuatorDB();
		$this->timeDB = new TimeDB();
		$this->roomDB = new RoomDB();
		$this->deviceDB = new DeviceDB();
		$this->heartRateDB = new HeartRateDB();
		$this->regulationDB = new RegulationDB();
		$this->conditionDB = new ConditionDB();
		$this->userDB = new UserDB();
		$this->weatherDB = new WeatherDB();
		
		$this->getRooms();
		
		session_start();
		if(!empty($_SESSION['user'])) {
			$this->user = $this->userDB->getUserModel($_SESSION['user']['UserID']);
		} else {
			$this->user = null;
		}
		
	}
	
	public function getRooms() {
		$this->roomArr = $this->roomDB->getRoomModelList();
	}
	
	
}

?>