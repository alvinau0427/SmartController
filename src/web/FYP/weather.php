<?php
require_once 'config.php';
require_once __DIR__ . '/src/db/WeatherDB.php';
require_once __DIR__ . '/src/controller/AutoController.php';

while(true) {
	$result = start();
	echo $result.PHP_EOL;
	sleep(60*60);
}


function getWeather1() {
	$ch = curl_init();
	$url = 'http://api.openweathermap.org/data/2.5/weather?units=Metric&lat=22.317499&lon=114.243286&APPID=03ab0344ffde3138709be808dfe0d126&cnt=1';
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL, $url);
	$result = curl_exec($ch);
	if ($result === FALSE) {
		die("Failed:" . curl_error($ch));
	}
	curl_close($ch);
	
    return json_decode($result, true);
}

function getWeather2() {
	//call openweathermap api
	$ch = curl_init();
    $url = 'http://api.openweathermap.org/data/2.5/forecast/daily?units=Metric&lat=22.317499&lon=114.243286&APPID=03ab0344ffde3138709be808dfe0d126&cnt=8';
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL, $url);
	$result = curl_exec($ch);
	if ($result === FALSE) {
		die("Failed:" . curl_error($ch));
	}
    curl_close($ch);
	
    return json_decode($result, true);
}

function start() {
	$w = getWeather2();
	
	//CURRENT TEMPERATURE, Humidity
	if(!empty($w)) {
		
		$weatherDB = new WeatherDB();
		
		$affectedRow = 0;
		for($i = 0; $i < count($w['list']); $i++) {
			$data['description'] = $w['list'][$i]['weather'][0]['main'];
			$data['timeStamp'] = $w['list'][$i]['dt'];
			$data['currentTemp'] = $w['list'][$i]['temp']['day'];
			$data['minTemp'] = $w['list'][$i]['temp']['min'];
			$data['maxTemp'] = $w['list'][$i]['temp']['max'];
			$data['humidity'] = $w['list'][$i]['humidity'];
			$data['pressure'] = $w['list'][$i]['pressure'];
			$data['windSpeed'] = $w['list'][$i]['speed'];
			$data['rain'] = (isset($w['list'][$i]['rain'])) ? $w['list'][$i]['rain'] : 0;
			$data['icon'] = $w['list'][$i]['weather'][0]['icon'];
			$data['recordID'] = $i+1;
			
			$affected = $weatherDB->updateWeather($data);
			
			$affectedRow += $affected;
		}
		
		$auto = new AutoController();
		$auto->updateWeather((isset($w['list'][0]['rain'])) ? $w['list'][0]['rain'] : 0);
		
		if($affectedRow == count($w['list'])){
			return date("Y-m-d H:i:s").': Update success, affected rows : '. $affectedRow;
		}else{
			return date("Y-m-d H:i:s").'Update fail affected rows : '. $affectedRow;
		}
	} else {
		return date("Y-m-d H:i:s").'get weather api fail';
	}
}

?>