# ![Logo](./demo/img_logo.png) SmartController

> **IVE 2015/16 Final Year Project**
> 
> **IoT-Based Digital Home Ecosystem:** A multi-platform home automation and safety system integrating Android, Web CMS, Raspberry Pi 3, and Arduino sensor networks.

[![Android](https://img.shields.io/badge/Android-3DDC84?logo=android&logoColor=white)](#) &nbsp;
[![Java](https://img.shields.io/badge/Java-%23ED8B00.svg?logo=openjdk&logoColor=white)](#) &nbsp;
[![PHP](https://img.shields.io/badge/php-%23777BB4.svg?&logo=php&logoColor=white)](#) &nbsp;
[![JavaScript](https://img.shields.io/badge/Javacript-F9AB00?logo=javascript&logoColor=white)](#) &nbsp;
[![HTML](https://img.shields.io/badge/HTML-%23E34F26.svg?logo=html5&logoColor=white)](#) &nbsp;
[![CSS](https://img.shields.io/badge/CSS-639?logo=css&logoColor=fff)](#) &nbsp;
[![License](https://img.shields.io/badge/License-Apache%202.0-blue.svg)](LICENSE) &nbsp;

## Project Overview
**SmartController** is an integrated Digital Home System designed with a core focus on **family safety** and **accident prevention**. By leveraging IoT sensor models, the system automates environmental responses to protect vulnerable family members (children and elderly) from potential home hazards.

### Core Mission
* **Automation:** Real-time response to environmental changes (Rain, Gas, Temperature).
* **Safety Monitoring:** Specialized sensors to prevent children from climbing windows or accessing dangerous items.
* **Health Tracking:** Integrated biometric monitoring for family members.
* **Remote Control:** Seamless management of home appliances via mobile and web interfaces.

## Key Features
### IoT Sensor Network (Hardware Layer)
* **Living Room Module:** * Smart Windows: Automatically closes upon rain detection.
    * Climate Control: Real-time Temp/Humidity monitoring and automated lighting.
    * Biometrics: Integrated heart rate sensor for health tracking.
* **Kitchen Module:** * Appliance Safety: Remote cut-off for electric ranges.
    * Smart Storage: Automated drawer locks for hazardous items (knives/chemicals).
* **Bathroom Module:** * Air Quality: Automated exhaust fan activation upon Carbon Monoxide (CO) detection.

### Android Application
* **Dashboard:** Real-time actuator status, push notifications, and local weather.
* **Voice Control:** Hands-free command interface for smart home operations.
* **Geolocation:** Map-based tracking of family members (Admin/Root only).
* **Health History:** Chronological logging of heart rate measurements.

### Web CMS (Administrative End)
* **Actuator Management:** Granular control over all hardware components.
* **Policy Configuration:** Define automated rules and sensor trigger conditions.
* **Audit Logs:** Complete history of notification records and system alerts.

## Installation & Environment Setup
### 1. Server Configuration (XAMPP & Dynamic DNS)
1. **Dynamic DNS:** Register a hostname at [No-IP](https://www.noip.com/) and configure your router/Update Client to map your local IP.
2. **Database:** Import `src/database/iot.sql` via phpMyAdmin (`http://localhost/phpmyadmin`).
3. **PHP Pthreads Extension (Required for Multi-threading):**
    * Determine if your PHP is **TS (Thread Safe)** via `phpinfo()`.
    * Download matching version from [PECL releases](http://windows.php.net/downloads/pecl/releases/pthreads/2.0.9/).
    * Move `pthreadVC2.dll` to `X:/xampp/php/` and `php_pthreads.dll` to `X:/xampp/php/ext/`.
    * Add `extension=php_pthreads.dll` to your `php.ini` and restart Apache.

### 2. Client Setup
* **Android:** Open the project in Android Studio. In `MainActivity`, update the server URL to your No-IP Dynamic DNS path.
* **Web CMS:** Update `URLROOT` in `src/web/FYP/config.php`. 
* **Note:** Ensure `FYP/weather.bat` is running to maintain updated weather data.

## Screenshots
| Project Objective     |
| :-------------: |
| ![Object](./demo/img_android_web.png) |

| Home Page     |
| :-------------: |
| ![Home Page](./demo/img_index.gif) |

| Heart Rate     |
| :-------------: |
| ![Heart Rate](./demo/img_android_heartrate.gif) |

| Weather     |
| :-------------: |
| ![Weather](./demo/img_android_weather.gif) |

| Location     |
| :-------------: |
| ![Location](./demo/img_android_location.png) |

| Personal Page     |
| :-------------: |
| ![Personal Page](./demo/img_android_personal.png) |

| CMS Website     |
| :-------------: |
| ![Website](./demo/img_web.gif) |

## Demonstration
- For more information of the project demonstration, please click [here](https://youtu.be/rLazYdiA1WA) to watch the following video.

[![Demonstration](https://img.youtube.com/vi/rLazYdiA1WA/hqdefault.jpg)](https://youtu.be/rLazYdiA1WA)

## License
- SmartController is released under the [Apache Version 2.0 License](http://www.apache.org/licenses/LICENSE-2.0.html).
```
Copyright 2016 alvinau0427

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

   http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
