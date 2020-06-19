<?php
require_once __DIR__ . '/Controller.php';

class NotificationController extends Controller {
	
	public function index($request, $response, $args) {
		
		$userArr = $this->userDB->getUserModelList();
		$actuatorArr = $this->actuatorDB->getActuatorModelList();
		return require_once ROOT . '/web/notification.php';
	}
}

?>