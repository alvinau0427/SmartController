<?php

require_once __DIR__ . '/DB.php';

class WeatherDB extends DB {
	
	public function getWeatherList(){
		$conn = $this->getConnection();
		$sql = "SELECT * FROM weather_record";
		$rs = mysqli_query($conn, $sql);
		
		$data = mysqli_fetch_all($rs, MYSQLI_ASSOC);
		
		mysqli_free_result($rs);
		mysqli_close($conn);
		
		return $data;
	}

	public function getWeather($recordID){
		$conn = $this->getConnection();
		$sql = "SELECT * FROM weather_record WHERE RecordID='$recordID'";
		$rs = mysqli_query($conn, $sql);
		
		$data = mysqli_fetch_array($rs, MYSQLI_ASSOC);
		
		mysqli_free_result($rs);
		mysqli_close($conn);
		
		return $data;
	}
	
	public function updateWeather($body){
		$conn = $this->getConnection();
		$sql = "UPDATE `weather_record` SET 
				Description='$body[description]', 
				TimeStamp='$body[timeStamp]', 
				CurrentTemp='$body[currentTemp]', 
				MinTemp='$body[minTemp]', 
				MaxTemp='$body[maxTemp]', 
				Humidity='$body[humidity]', 
				Pressure='$body[pressure]', 
				WindSpeed='$body[windSpeed]', 
				Rain='$body[rain]', 
				Icon='$body[icon]', 
				UpdateDateTime=DEFAULT WHERE RecordID='$body[recordID]'";
		
		mysqli_query($conn, $sql);
		
		$affected = mysqli_affected_rows($conn);
		mysqli_close($conn);
		
		if(!empty($affected) && $affected > 0){
			return $affected;
		}else{
			return 0;
		}
	}
	
	public function simulateWeather($body){
		$conn = $this->getConnection();
		$sql = "UPDATE `weather_record` SET 
				Description='$body[description]', 
				TimeStamp='$body[timeStamp]', 
				Rain='$body[rain]', 
				Icon='$body[icon]', 
				UpdateDateTime=DEFAULT WHERE RecordID='$body[recordID]'";
		
		mysqli_query($conn, $sql);
		
		$affected = mysqli_affected_rows($conn);
		mysqli_close($conn);
		
		if(!empty($affected) && $affected > 0){
			return $affected;
		}else{
			return 0;
		}
	}
}
?>