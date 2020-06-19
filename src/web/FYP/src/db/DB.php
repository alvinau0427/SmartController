<?php

class DB {
	//protected $conn;
	
	/*public function __construct(){
		require("conn.php");
		$this->conn = $conn;
	}*/
	
	public function getConnection() {
		require("conn.php");
		return $conn;
	}
	
	/*public function __destruct() {
        mysqli_close($this->conn)
            OR die("There was a problem disconnecting from the database.");
    }*/

}
?>