<?php
require_once __DIR__ . '/Controller.php';

class LocationController extends Controller {
	
	public function index($request, $response, $args) {
		
		return require_once ROOT . '/web/map.php';
	}
}

?>