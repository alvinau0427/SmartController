<?php
require_once __DIR__ . '/Controller.php';

class ProfileController extends Controller {
	
	public function index($request, $response, $args) {
		$users = $this->userDB->getUserList();
		
		return require_once ROOT . '/web/profile.php';
	}
}

?>