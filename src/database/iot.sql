SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+08:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `iot`
--
DROP DATABASE IF EXISTS `iot`;
CREATE DATABASE IF NOT EXISTS `iot` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `iot`;

-- --------------------------------------------------------

--
-- 資料表結構 `actuator`
--

CREATE TABLE `actuator` (
  `ActuatorID` int(11) NOT NULL,
  `ActuatorPin` int(11) NOT NULL,
  `ActuatorPinName` varchar(100) DEFAULT NULL,
  `ActuatorName` varchar(100) NOT NULL,
  `ActuatorImage` varchar(100) DEFAULT NULL,
  `ActuatorTypeID` int(11) NOT NULL,
  `ActuatorStatusID` int(11) DEFAULT NULL,
  `RoomID` int(11) DEFAULT NULL,
  `DeviceID` int(11) DEFAULT NULL,
  `WeatherAPI` int(11) NOT NULL,
  `ModeID` int(11) NOT NULL,
  `Display` int(11) NOT NULL,
  `PermissionID` int(11) NOT NULL,
  PRIMARY KEY (`ActuatorID`),
  KEY `actuator_fk1` (`ActuatorTypeID`),
  KEY `actuator_fk2` (`ActuatorStatusID`),
  KEY `actuator_fk3` (`RoomID`),
  KEY `actuator_fk4` (`DeviceID`),
  KEY `actuator_fk5` (`ModeID`),
  KEY `actuator_fk6` (`PermissionID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 資料表的匯出資料 `actuator`
--

INSERT INTO `actuator` (`ActuatorID`, `ActuatorPin`, `ActuatorPinName`, `ActuatorName`, `ActuatorImage`, `ActuatorTypeID`, `ActuatorStatusID`, `RoomID`, `DeviceID`, `WeatherAPI`, `ModeID`, `Display`, `PermissionID`) VALUES
(1, 11, "WINDOW_PIN", "Window", 'img_window.png', 1, 1, 2, 1, 1, 2, 1, 4),
(2, 3, "LIGHT_PIN", "Lamp", 'img_light.png', 2, 2, 2, 1, 0, 1, 1, 4),
(3, 32, "DRAWER_PIN", "Drawer Lock", 'img_drawer.png', 1, 1, 1, 1, 0, 1, 1, 4),
(4, 33, "ELECTRONIC_RANGE_PIN", "Electric Range", 'img_electronic_range.png', 2, 2, 1, 1, 0, 1, 1, 3),
(5, 15, "SPRINKLER_LIGHT_PIN", "Sprinkler", 'img_sprinkler.png', 2, 2, 1, 1, 0, 2, 1, 4),
(6, 40, "K_LIGHT_PIN", "Light", 'img_light.png', 2, 2, 1, 1, 0, 1, 1, 4),
(7, 18, "W_LIGHT_PIN", "Light Tube", 'img_light.png', 2, 2, 3, 1, 0, 1, 1, 4),
(8, 5, "FAN_PIN", "Fan", 'img_fan.png', 3, 2, 3, 1, 0, 1, 1, 4);

-- --------------------------------------------------------

--
-- 資料表結構 `actuator_record`
--

CREATE TABLE `actuator_record` (
  `RecordID` int(11) NOT NULL AUTO_INCREMENT,
  `DateTime` DateTime default CURRENT_TIMESTAMP,
  `ActuatorStatusID` int(11) NOT NULL,
  `ActuatorID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  PRIMARY KEY (`RecordID`),
  KEY `actuator_record_fk1` (`ActuatorStatusID`),
  KEY `actuator_record_fk2` (`ActuatorID`),
  KEY `actuator_record_fk3` (`UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 資料表的匯出資料 `actuator_record`
--

INSERT INTO `actuator_record` (`RecordID`, `DateTime`, `ActuatorStatusID`, `ActuatorID`, `UserID`) VALUES
(1, '2017-01-16 12:00', 1, 1, 2);

-- -------------------------------------------------------

--
-- 資料表結構 `actuator_sensor`
--

CREATE TABLE `actuator_sensor` (
  `ActuatorSensorID` int(11) NOT NULL AUTO_INCREMENT,
  `ActuatorID` int(11) NOT NULL,
  `SensorID` int(11) NOT NULL,
  PRIMARY KEY (`ActuatorSensorID`),
  KEY `actuator_sensor_fk1` (`ActuatorID`),
  KEY `actuator_sensor_fk2` (`SensorID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 資料表的匯出資料 `actuator_sensor`
--

INSERT INTO `actuator_sensor` (`ActuatorSensorID`, `ActuatorID`, `SensorID`) VALUES
(1, 1, 1),
(2, 1, 5),
(3, 1, 6),
(4, 6, 8),
(5, 3, 3),
(6, 4, 4),
(7, 5, 4),
(8, 2, 2),
(9, 7, 8),
(10, 8, 9),
(11, 3, 8);

-- --------------------------------------------------------

--
-- 資料表結構 `actuator_status`
--

CREATE TABLE `actuator_status` (
  `ActuatorStatusID` int(11) NOT NULL AUTO_INCREMENT,
  `ActuatorStatusDescription` varchar(100) NOT NULL,
  PRIMARY KEY (`ActuatorStatusID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 資料表的匯出資料 `actuator_status`
--

INSERT INTO `actuator_status` (`ActuatorStatusID`, `ActuatorStatusDescription`) VALUES
(1, 'Open'),
(2, 'Close');

-- --------------------------------------------------------

--
-- 資料表結構 `actuator_type`
--

CREATE TABLE `actuator_type` (
  `ActuatorTypeID` int(11) NOT NULL AUTO_INCREMENT,
  `ActuatorTypeDescription` varchar(100) NOT NULL,
  PRIMARY KEY (`ActuatorTypeID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 資料表的匯出資料 `actuator_type`
--

INSERT INTO `actuator_type` (`ActuatorTypeID`, `ActuatorTypeDescription`) VALUES
(1, 'Servo'),
(2, 'Led'),
(3, 'Fan');

-- --------------------------------------------------------

--
-- 資料表結構 `comparison_operator`
--

CREATE TABLE `comparison_operator` (
  `ComparisonOperatorID` int(11) NOT NULL AUTO_INCREMENT,
  `ComparisonOperatorDescription` varchar(100) NOT NULL,
  `ComparisonOperatorName` varchar(100) NOT NULL,
  PRIMARY KEY (`ComparisonOperatorID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 資料表的匯出資料 `comparison_operator`
--

INSERT INTO `comparison_operator` (`ComparisonOperatorID`, `ComparisonOperatorDescription`, `ComparisonOperatorName`) VALUES
(1, "==", "equal to"),
(2, "!=", "not equal to"),
(3, "<", "less than"),
(4, "<=", "less than or equal to"),
(5, ">", "greater than"),
(6, ">=", "greater than or equal to");

-- --------------------------------------------------------

--
-- 資料表結構 `condition`
--

CREATE TABLE `condition` (
  `ConditionID` int(11) NOT NULL AUTO_INCREMENT,
  `SensorID` int(11) NOT NULL,
  `ComparisonOperatorID` int(11) NOT NULL,
  `Value` int(11) NOT NULL,
  `RegulationID` int(11) NOT NULL,
  PRIMARY KEY (`ConditionID`),
  KEY `condition_fk1` (`SensorID`),
  KEY `condition_fk2` (`ComparisonOperatorID`),
  KEY `condition_fk3` (`RegulationID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 資料表的匯出資料 `condition`
--

INSERT INTO `condition` (`ConditionID`, `SensorID`, `ComparisonOperatorID`, `Value`, `RegulationID`) VALUES
(1, 1, 1, 1, 1),
(2, 8, 1, 1, 2),
(3, 3, 4, 20, 3),
(4, 4, 1, 1, 4),
(5, 4, 1, 1, 7),
(6, 5, 4, 15, 5),
(7, 6, 6, 95, 6),
(8, 4, 1, 0, 8),
(9, 2, 1, 1, 9),
(10, 8, 1, 1, 10),
(11, 9, 1, 1, 11),
(12, 9, 1, 0, 12),
(13, 8, 1, 0, 13),
(14, 8, 1, 0, 14),
(15, 2, 1, 0, 15),
(16, 8, 1, 0, 16);

-- --------------------------------------------------------

--
-- 資料表結構 `device`
--

CREATE TABLE `device` (
  `DeviceID` int(11) NOT NULL AUTO_INCREMENT,
  `DeviceName` varchar(100) NOT NULL,
  `IP` varchar(100) NOT NULL,
  PRIMARY KEY (`DeviceID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 資料表的匯出資料 `device`
--

INSERT INTO `device` (`DeviceID`, `DeviceName`, `IP`) VALUES
(1, 'Raspberry PI', '123.202.126.226');

-- --------------------------------------------------------

--
-- 資料表結構 `mode`
--

CREATE TABLE `mode` (
  `ModeID` int(11) NOT NULL AUTO_INCREMENT,
  `ModeDescription` varchar(100) NOT NULL,
  PRIMARY KEY (`ModeID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 資料表的匯出資料 `mode`
--

INSERT INTO `mode` (`ModeID`, `ModeDescription`) VALUES
(1, 'Auto'),
(2, 'Notification'),
(3, 'On');

-- --------------------------------------------------------

--
-- 資料表結構 `permission`
--

CREATE TABLE `permission` (
  `PermissionID` int(11) NOT NULL AUTO_INCREMENT,
  `PermissionDescription` varchar(100) NOT NULL,
  PRIMARY KEY (`PermissionID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 資料表的匯出資料 `permission`
--

INSERT INTO `permission` (`PermissionID`, `PermissionDescription`) VALUES
(1, 'None'),
(2, 'On Only'),
(3, 'Off Only'),
(4, 'On and Off');

-- --------------------------------------------------------

--
-- 資料表結構 `regulation`
--

CREATE TABLE `regulation` (
  `RegulationID` int(11) NOT NULL AUTO_INCREMENT,
  `Header` varchar(100) NOT NULL,
  `RegulationDescription` varchar(100) NOT NULL,
  `LogicGate` varchar(100) DEFAULT NULL,
  `ActuatorStatusID` int(11) NOT NULL,
  `Priority` int(11) NOT NULL,
  `ActuatorID` int(11) NOT NULL,
  PRIMARY KEY (`RegulationID`),
  KEY `regulation_fk1` (`ActuatorStatusID`),
  KEY `regulation_fk2` (`ActuatorID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 資料表的匯出資料 `regulation`
--

INSERT INTO `regulation` (`RegulationID`, `Header`, `RegulationDescription`, `LogicGate`, `ActuatorStatusID`, `Priority`, `ActuatorID`) VALUES
(1, 'Rain-Detected Close Window', 'If rain is detected, the window will be closed.', '1 ', 2, 1, 1),
(2, 'People-Detected Open Light', 'If someone is detected, the light will be opened.', '2', 1, 1, 6),
(3, 'Elderly-Detected Open Drawer Lock', 'If an elderly is detected, the drawer lock will be opened.', '3', 2, 2, 3),
(4, 'Flame-Detected Open Sprinkler', 'If a flame is detected, the sprinkler will be opened.', '4', 1, 2, 5),
(5, 'Temperature-Detected Close Window', 'If the temperature is low, the window will be closed.', '6', 2, 2, 1),
(6, 'Humidity-Detected Close Window', 'If the humidity is high, the window will be closed.', '7', 2, 3, 1),
(7, 'Flame-Detected Close Electronic Range', 'If a flame is detected, the electronic range will be closed.', '5', 2, 1, 4),
(8, 'Flame-not-Detected Close Sprinkler', 'If a flame is not detected, the sprinkler will be closed.', '8', 2, 1, 5),
(9, 'People-Detected Open Light', 'If someone is detected, the light will be opened.', '9', 1, 2, 2),
(10, 'People-Detected Open Light', 'If someone is detected, the light will be opened.', '10', 1, 1, 7),
(11, 'CarbonMonoxide-Detected Open Fan', 'If carbon monoxide is detected, the fan will be opened.', '11', 1, 2, 8),
(12, 'CarbonMonoxide-not-Detected Close Fan', 'If carbon monoxide is not detected, the fan will be closed.', '12', 2, 1, 8),
(13, 'People-not-Detected Close Light', 'If someone is not detected, the light will be closed.', '13', 2, 1, 6),
(14, 'People-not-Detected Close Light', 'If someone is not detected, the light will be closed.', '14', 2, 1, 7),
(15, 'People-not-Detected Close Light', 'If someone is not detected, the light will be closed.', '15', 2, 1, 2),
(16, 'Elderly-not-Detected Close Drawer Lock', 'If an elderly is not detected, the drawer lock will be closed.', '16', 1, 1, 3);

-- --------------------------------------------------------

--
-- 資料表結構 `room`
--

CREATE TABLE `room` (
  `RoomID` int(11) NOT NULL AUTO_INCREMENT,
  `RoomName` varchar(100) NOT NULL,
  `RoomImage` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`RoomID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 資料表的匯出資料 `room`
--

INSERT INTO `room` (`RoomID`, `RoomName`, `RoomImage`) VALUES
(1, 'Kitchen', 'img_kitchen.png'),
(2, 'Living Room', 'img_living_room.png'),
(3, 'Washroom', 'img_washroom.png');

-- --------------------------------------------------------

--
-- 資料表結構 `sensor`
--

CREATE TABLE `sensor` (
  `SensorID` int(11) NOT NULL,
  `SensorPin` int(11) NOT NULL,
  `SensorPinName` varchar(100) DEFAULT NULL,
  `SensorName` varchar(100) NOT NULL,
  `SensorImage` varchar(100) DEFAULT NULL,
  `SensorTypeID` int(11) NOT NULL,
  `SensorValue` int(11) NOT NULL,
  `SensorUnit` varchar(100) NOT NULL,
  `RoomID` int(11) DEFAULT NULL,
  `DeviceID` int(11) DEFAULT NULL,
  PRIMARY KEY (`SensorID`),
  KEY `sensor_fk1` (`SensorTypeID`),
  KEY `sensor_fk2` (`RoomID`),
  KEY `sensor_fk3` (`DeviceID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 資料表的匯出資料 `sensor`
--

INSERT INTO `sensor` (`SensorID`, `SensorPin`, `SensorPinName`, `SensorName`, `SensorImage`, `SensorTypeID`, `SensorValue`, `SensorUnit`, `RoomID`, `DeviceID`) VALUES
(1, 7, "RAIN_PIN", "Rain Detector", "img_rain.png", 1, 0, null, 2, 1),
(2, 38, "PIR_PIN", "People Detector", "img_people_detector.png", 2, 0, null, 1, 1),
(3, 37, "ULTRASONIC_PIN", "Height Detector", "img_height.png", 3, 30, "cm", 1, 1),
(4, 29, "FIRE_PIN", "Fire Detector", "img_fire.png", 4, 0, null, 1, 1),
(5, 27, "TEMP_PIN", "Temperature Detector", "img_temp.png", 5, 37, "C", 2, 1),
(6, 27, "HUM_PIN", "Humidity Detector", "img_hum.png", 6, 90, "%", 2, 1),
(7, 22, "BUZZER_PIN", "Warning Sound", 'img_sound.png', 7, 0, null, 1, 1),
(8, 35, "PIR_LIGHT_PIN", "People Detector", "img_people_detector.png", 2, 0, null, 2, 1),
(9, 12, "CM_PIN", "Carbon Monoxide Detector", "img_carbon_monoxide.png", 8, 0, null, 3, 1);

-- --------------------------------------------------------

--
-- 資料表結構 `sensor_type`
--

CREATE TABLE `sensor_type` (
  `SensorTypeID` int(11) NOT NULL AUTO_INCREMENT,
  `SensorTypeDescription` varchar(100) NOT NULL,
  PRIMARY KEY (`SensorTypeID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 資料表的匯出資料 `sensor_type`
--

INSERT INTO `sensor_type` (`SensorTypeID`, `SensorTypeDescription`) VALUES
(1, 'Rain'),
(2, 'Motion'),
(3, 'Ultrasonic'),
(4, 'Fire'),
(5, 'Temperature'),
(6, 'Humidity'),
(7, 'Buzzer'),
(8, 'CarbonMonoxide');

-- --------------------------------------------------------

--
-- 資料表結構 `time_setting`
--

CREATE TABLE `time_setting` (
  `TimeID` int(11) NOT NULL AUTO_INCREMENT,
  `StartTime` time NOT NULL,
  `EndTime` time NOT NULL,
  `ActuatorID` int(11) NOT NULL,
  PRIMARY KEY (`TimeID`),
  KEY `rule_time_fk1` (`ActuatorID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 資料表的匯出資料 `time_setting`
--

INSERT INTO `time_setting` (`TimeID`, `StartTime`, `EndTime`, `ActuatorID`) VALUES
(1, '00:00', '23:59:59', 1),
(2, '00:00', '23:59:59', 2),
(3, '00:00', '23:59:59', 3),
(4, '00:00', '23:59:59', 4),
(5, '00:00', '23:59:59', 5),
(6, '00:00', '23:59:59', 6),
(7, '00:00', '23:59:59', 7),
(8, '00:00', '23:59:59', 8);

-- --------------------------------------------------------

--
-- 資料表結構 `user_account`
--

CREATE TABLE `user_account` (
  `UserID` int(11) NOT NULL AUTO_INCREMENT,
  `UserName` varchar(100) NOT NULL,
  `UserTypeID` int(11) NOT NULL,
  `LoginAccount` varchar(500) NOT NULL,
  `Password` varchar(500) NOT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Token` varchar(200) DEFAULT NULL,
  `Image` varchar(100) DEFAULT NULL,
  `ReceiveNotification` int(11) DEFAULT 1,
  `ReceiveEmail` int(11) DEFAULT 1,
  `LocationDisplay` int(11) DEFAULT 1,
  PRIMARY KEY (`UserID`),
  KEY `user_account_fk1` (`UserTypeID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 資料表的匯出資料 `user_account`
--

INSERT INTO `user_account` (`UserID`, `UserName`, `UserTypeID`, `LoginAccount`, `Password`, `Email`, `Token`, `Image`, `ReceiveNotification`, `ReceiveEmail`, `LocationDisplay`) VALUES
(1, 'SYSTEM', 1, '', '', 'systemhome0001@gmail.com', null, null, 0, 0, 0),
(2, 'Parent', 2, 'root', '4813494d13ae1631bba3a1d5acab6e7bb7aa74ce1185d456565ef51d737677b2', 'publicstatics@gmail.com', null, 'img_admin_user.jpg', 0, 0, 0),
(3, 'Dennis', 3, 'dennis', 'bb647e29b3a334079a9da53184f6cb58abd93182f8a4f7e95caf4bea0c2b2171', 'dennis_yu105@yahoo.com.hk', null, 'img_normal_user.jpg', 1, 1, 1);

-- --------------------------------------------------------

--
-- 資料表結構 `user_heart_rate`
--

CREATE TABLE `user_heart_rate` (
  `HeartRateID` int(11) NOT NULL AUTO_INCREMENT,
  `HeartRate` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `DateTime` DateTime default CURRENT_TIMESTAMP,
  PRIMARY KEY (`HeartRateID`),
  KEY `user_heart_rate_fk1` (`UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 資料表的匯出資料 `user_heart_rate`
--

INSERT INTO `user_heart_rate` (`HeartRateID`, `HeartRate`, `UserID`, `DateTime`) VALUES
(1, 80, 2, '2017-01-16 12:00'),
(2, 90, 2, '2017-01-17 14:30'),
(3, 103, 2, '2017-01-20 18:00'),
(4, 121, 2, '2017-02-03 17:30'),
(5, 85, 2, '2017-03-10 11:30'),
(6, 70, 3, '2017-01-16 13:30');

-- --------------------------------------------------------

--
-- 資料表結構 `user_location`
--

CREATE TABLE `user_location` (
  `UserLocationID` int(11) NOT NULL AUTO_INCREMENT,
  `Latitude` decimal(8,5) NOT NULL,
  `Longitude` decimal(9,5) NOT NULL,
  `UserID` int(11) NOT NULL,
  `DateTime` DateTime default CURRENT_TIMESTAMP,
  PRIMARY KEY (`UserLocationID`),
  KEY `user_location_fk1` (`UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 資料表的匯出資料 `user_location`
--

INSERT INTO `user_location` (`UserLocationID`, `Latitude`, `Longitude`, `UserID`, `DateTime`) VALUES
(1, 22.3423082, 114.1042155, 2, '2017-01-16 12:00'),
(2, 22.3463082, 114.1042155, 3, '2017-01-16 12:00');

-- --------------------------------------------------------

--
-- 資料表結構 `user_type`
--

CREATE TABLE `user_type` (
  `UserTypeID` int(11) NOT NULL AUTO_INCREMENT,
  `UserTypeDescription` varchar(100) NOT NULL,
  PRIMARY KEY (`UserTypeID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 資料表的匯出資料 `user_type`
--

INSERT INTO `user_type` (`UserTypeID`, `UserTypeDescription`) VALUES
(1, 'System'),
(2, 'Root'),
(3, 'User');

-- --------------------------------------------------------

--
-- 資料表結構 `weather_record`
--

CREATE TABLE `weather_record` (
  `RecordID` int(11) NOT NULL AUTO_INCREMENT,
  `Description` varchar(100) NOT NULL,
  `TimeStamp` int(11) NOT NULL,
  `CurrentTemp` decimal(11, 2) NOT NULL,
  `MinTemp` decimal(11, 2) NOT NULL,
  `MaxTemp` decimal(11, 2) NOT NULL,
  `Humidity` decimal(11, 2) NOT NULL,
  `Pressure` decimal(11, 2) NOT NULL,
  `WindSpeed` decimal(11, 2) NOT NULL,
  `Rain` decimal(11, 2) NOT NULL,
  `Icon` varchar(100) NOT NULL,
  `UpdateDateTime` DateTime default CURRENT_TIMESTAMP,
  PRIMARY KEY (`RecordID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 資料表的匯出資料 `weather_record`
--

INSERT INTO `weather_record` (`RecordID`, `Description`, `TimeStamp`, `CurrentTemp`, `MinTemp`, `MaxTemp`, `Humidity`, `Pressure`, `WindSpeed`, `Rain`, `Icon`, `UpdateDateTime`) VALUES
(1, 'Rain', 1492920000, 20.00, 20.00, 20.68, 100, 1030.22, 7.51, 1.97, '10d', '2017-04-23 18:22:26'),
(2, 'Rain', 1493006400, 21.55, 20.64, 23.03, 100, 1031.89, 7.98, 9.91, '10d', '2017-04-23 18:22:26'),
(3, 'Rain', 1493092800, 24.49, 23.53, 25.18, 100, 1032.72, 9.31, 0.69, '10d', '2017-04-23 18:22:26'),
(4, 'Rain', 1493179200, 26.56, 25.41, 26.56, 100, 1033.82, 8.51, 7.63, '10d', '2017-04-23 18:22:26'),
(5, 'Rain', 1493265600, 25.83, 23.77, 25.83, 100, 1032.01, 5.45, 32.42, '10d', '2017-04-23 18:22:26'),
(6, 'Rain', 1493352000, 22.99, 22.28, 22.99, 100, 1034.59, 8.65, 11.47, '10d', '2017-04-23 18:22:26'),
(7, 'Rain', 1493438400, 22.02, 21.96, 22.60, 100, 1037.88, 8.61, 11.49, '10d', '2017-04-23 18:22:26'),
(8, 'Rain', 1493524800, 24.15, 22.93, 24.31, 100, 1038.57, 7.97, 1.61, '10d', '2017-04-23 18:22:26');

-- --------------------------------------------------------

--
-- 已匯出資料表的限制(Constraint)
--
 
--
-- 資料表的 Constraints `actuator`
--
ALTER TABLE `actuator`
  ADD CONSTRAINT `actuator_fk1` FOREIGN KEY (`ActuatorTypeID`) REFERENCES `actuator_type` (`ActuatorTypeID`),
  ADD CONSTRAINT `actuator_fk2` FOREIGN KEY (`ActuatorStatusID`) REFERENCES `actuator_status` (`ActuatorStatusID`),
  ADD CONSTRAINT `actuator_fk3` FOREIGN KEY (`RoomID`) REFERENCES `room` (`RoomID`),
  ADD CONSTRAINT `actuator_fk4` FOREIGN KEY (`DeviceID`) REFERENCES `device` (`DeviceID`),
  ADD CONSTRAINT `actuator_fk5` FOREIGN KEY (`ModeID`) REFERENCES `mode` (`ModeID`),
  ADD CONSTRAINT `actuator_fk6` FOREIGN KEY (`PermissionID`) REFERENCES `permission` (`PermissionID`);
  
--
-- 資料表的 Constraints `actuator_record`
--
ALTER TABLE `actuator_record`
  ADD CONSTRAINT `actuator_record_fk1` FOREIGN KEY (`ActuatorStatusID`) REFERENCES `actuator_status` (`ActuatorStatusID`),
  ADD CONSTRAINT `actuator_record_fk2` FOREIGN KEY (`ActuatorID`) REFERENCES `actuator` (`ActuatorID`),
  ADD CONSTRAINT `actuator_record_fk3` FOREIGN KEY (`UserID`) REFERENCES `user_account` (`UserID`);
  
--
-- 資料表的 Constraints `actuator_sensor`
--
ALTER TABLE `actuator_sensor`
  ADD CONSTRAINT `actuator_sensor_fk1` FOREIGN KEY (`ActuatorID`) REFERENCES `actuator` (`ActuatorID`),
  ADD CONSTRAINT `actuator_sensor_fk2` FOREIGN KEY (`SensorID`) REFERENCES `sensor` (`SensorID`);

--
-- 資料表的 Constraints `condition`
--
ALTER TABLE `condition`
  ADD CONSTRAINT `condition_fk1` FOREIGN KEY (`SensorID`) REFERENCES `sensor` (`SensorID`),
  ADD CONSTRAINT `condition_fk2` FOREIGN KEY (`ComparisonOperatorID`) REFERENCES `comparison_operator` (`ComparisonOperatorID`),
  ADD CONSTRAINT `condition_fk3` FOREIGN KEY (`RegulationID`) REFERENCES `regulation` (`RegulationID`);
  
--
-- 資料表的 Constraints `regulation`
--
ALTER TABLE `regulation`
  ADD CONSTRAINT `regulation_fk1` FOREIGN KEY (`ActuatorStatusID`) REFERENCES `actuator_status` (`ActuatorStatusID`),
  ADD CONSTRAINT `regulation_fk2` FOREIGN KEY (`ActuatorID`) REFERENCES `actuator` (`ActuatorID`);
  
--
-- 資料表的 Constraints `sensor`
--
ALTER TABLE `sensor`
  ADD CONSTRAINT `sensor_fk1` FOREIGN KEY (`SensorTypeID`) REFERENCES `sensor_type` (`SensorTypeID`),
  ADD CONSTRAINT `sensor_fk2` FOREIGN KEY (`RoomID`) REFERENCES `room` (`RoomID`),
  ADD CONSTRAINT `sensor_fk3` FOREIGN KEY (`DeviceID`) REFERENCES `device` (`DeviceID`);
  
--
-- 資料表的 Constraints `time_setting`
--
ALTER TABLE `time_setting`
  ADD CONSTRAINT `time_setting_fk1` FOREIGN KEY (`ActuatorID`) REFERENCES `actuator` (`ActuatorID`);
  
--
-- 資料表的 Constraints `user_account`
--
ALTER TABLE `user_account`
  ADD CONSTRAINT `user_account_fk1` FOREIGN KEY (`UserTypeID`) REFERENCES `user_type` (`UserTypeID`);
  
--
-- 資料表的 Constraints `user_heart_rate`
--
ALTER TABLE `user_heart_rate`
  ADD CONSTRAINT `user_heart_rate_fk1` FOREIGN KEY (`UserID`) REFERENCES `user_account` (`UserID`);

--
-- 資料表的 Constraints `user_location`
--
ALTER TABLE `user_location`
  ADD CONSTRAINT `user_location_fk1` FOREIGN KEY (`UserID`) REFERENCES `user_account` (`UserID`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
