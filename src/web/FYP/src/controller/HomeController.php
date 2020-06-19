<?php
require_once __DIR__ . '/Controller.php';

class HomeController extends Controller {
	
	public function index($request, $response, $args) {
		$weather = $this->weatherDB->getWeatherList();
		$actuatorArr = $this->actuatorDB->getActuatorModelList();
		return require_once ROOT . '/web/index.php';
	}

}

?>