<?php
require_once __DIR__ . '/Controller.php';

class LoginController extends Controller {
	
	public function index($request, $response, $args) {
		
		$param = $request->getParams();
		$message = "";
		
		if(isset($param['incoreect'])) {
			$message = "The Account or Password is incorrect. Please enter the correct Account and Password and try again.";
		}
		session_start();
		
		session_unset();
		session_destroy();
		
		require_once ROOT . '/web/login.php';
	}
	
	public function authentication($request, $response, $args) {
		
		//return $response->withRedirect('/FYP/api/modules');
		if(isset($_POST['account']) && isset($_POST['password']) ) {
			
			$hexPass = hash('sha256', $_POST['password']);
			
			$hexPass = substr($hexPass,0,10) . "a" . substr($hexPass,11,9) . "a" . substr($hexPass,21);
			
			$acc = array("account"=>$_POST['account'],
						"password"=>$hexPass);
			
			$data = $this->userDB->authentication($acc);
			if($data){
				session_start();
				
				$_SESSION['user'] = $data;
				$_SESSION['URLROOT'] = URLROOT;
				
				return $response->withRedirect( URLROOT );
			} else {
				
				return $response->withRedirect( URLROOT .'/login/?incoreect');
			}
		} else {
			return $response->withRedirect( URLROOT .'/login');
		}
	}
}

?>