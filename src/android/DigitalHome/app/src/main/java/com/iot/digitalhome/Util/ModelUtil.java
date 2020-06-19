package com.iot.digitalhome.Util;

import android.app.Activity;
import android.content.SharedPreferences;
import android.graphics.Color;
import android.util.Log;

import com.github.mikephil.charting.data.BarEntry;
import com.github.mikephil.charting.data.Entry;
import com.github.mikephil.charting.utils.ColorTemplate;
import com.iot.digitalhome.Activity.MainActivity;
import com.iot.digitalhome.Conn.Connection;
import com.iot.digitalhome.Conn.ConnectionFromOkHttp3;
import com.iot.digitalhome.Model.HeartRecordEntity;
import com.iot.digitalhome.Model.ModuleEntity;
import com.iot.digitalhome.Model.WeatherEntity;
import com.iot.digitalhome.Model.FunctionEntity;
import com.iot.digitalhome.Model.OperationEntity;
import com.iot.digitalhome.R;
import com.iot.digitalhome.Util.ListViewItems.ChartItemList;

import org.json.JSONArray;
import org.json.JSONObject;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.List;
import java.util.Locale;

public class ModelUtil {

    // Advertising data
    public static List<String> getBannerData() {
        List<String> adList = new ArrayList<>();
        //adList.add("http://i.imgur.com/T8PSF4j.jpg");
        adList.add("https://content.gototags.com/wp-content/uploads/internet_of_things.png");
        adList.add("http://ghk.h-cdn.co/assets/cm/15/11/54ff822674a54-living-rooms-modern-de.jpg");
        adList.add("http://www.amwell-systems.com/wp-content/uploads/2017/03/Amwell-5-Key-Steps-to-Successful-Washroom-Specification.jpg");
        adList.add("http://hbu.h-cdn.co/assets/16/08/1600x800/landscape-appliances-kitchen-1.jpg");
        adList.add("http://www.elmueble.com/medio/2016/06/14/cocina-con-isla-en-blanco-y-gris-y-azulejos-tipo-metro_1280x650_724737ec.jpg");
        return adList;
    }

    // Channel data
    public static List<WeatherEntity> getWeatherData(Activity activity) {
        try {
            Weather weather;
//            if (lat == 0 && lon == 0) {
//                weather = new DBWeather();
//            } else {
//                weather = new OpenWeatherMapWeather(lat, lon);
//            }
            weather = new DBWeather();

            List<WeatherEntity> weatherList = new ArrayList<>();
            weatherList.add(new WeatherEntity(Math.round(weather.getCurrentTemp(1)) + activity.getString(R.string.modeUtil_C), activity.getString(R.string.modeUtil_Temperature), activity.getString(R.string.modeUtil_Temperature_D), "https://cdn2.iconfinder.com/data/icons/coloured-weather-icon-set-svg/100/Coloured_Weather_Icon_Set_by_ViconsDesign-12-128.png"));
            weatherList.add(new WeatherEntity(weather.getHumidity(1) + activity.getString(R.string.modeUtil_percentage), activity.getString(R.string.modeUtil_Humidity), activity.getString(R.string.modeUtil_Humidity_D), "https://cdn4.iconfinder.com/data/icons/weather-conditions/512/cold_temperature-128.png"));
            weatherList.add(new WeatherEntity(Math.round(weather.getMinTemp(1)) + activity.getString(R.string.modeUtil_C), activity.getString(R.string.modeUtil_Min), activity.getString(R.string.modeUtil_Min_D), "https://cdn2.iconfinder.com/data/icons/coloured-weather-icon-set-svg/100/Coloured_Weather_Icon_Set_by_ViconsDesign-12-128.png"));
            weatherList.add(new WeatherEntity(Math.round(weather.getMaxTemp(1)) + activity.getString(R.string.modeUtil_C), activity.getString(R.string.modeUtil_Max), activity.getString(R.string.modeUtil_Max_D), "https://cdn2.iconfinder.com/data/icons/coloured-weather-icon-set-svg/100/Coloured_Weather_Icon_Set_by_ViconsDesign-12-128.png"));
            weatherList.add(new WeatherEntity(Math.round(weather.getPressure(1)) + activity.getString(R.string.modeUtil_Pa), activity.getString(R.string.modeUtil_Pressure), activity.getString(R.string.modeUtil_Pressure_D), "https://cdn0.iconfinder.com/data/icons/colorix-weather-and-climate/128/weather_climate_meteorology-05-256.png"));
            weatherList.add(new WeatherEntity(Math.round(weather.getWindSpeed(1)) + activity.getString(R.string.modeUtil_kmh), activity.getString(R.string.modeUtil_Speed), activity.getString(R.string.modeUtil_Speed_D), "https://cdn3.iconfinder.com/data/icons/aami-web-internet/64/aami9-72-256.png"));
            return weatherList;
        } catch (Exception e) {
            return getWeatherData(activity);
        }
    }

    // Operational data
    public static List<OperationEntity> getOperationData() {
        List<OperationEntity> operationList = new ArrayList<>();
        operationList.add(new OperationEntity("Home", "", R.drawable.img_live));
        operationList.add(new OperationEntity("", "", 0));
        return operationList;
    }

    // Function Data
    public static List<FunctionEntity> getFunctionData(MainActivity m) {

        try {
            Connection conn = new ConnectionFromOkHttp3(m.getPath() + "rooms");
            conn.send("GetRequest");
            String functionsResponse = conn.getData();
            JSONObject functionsJsonObject = new JSONObject(functionsResponse);
            JSONArray data = functionsJsonObject.getJSONArray("data");
            List<FunctionEntity> functionList = new ArrayList<>();

            for (int i = 0; i < data.length(); i++) {
                conn = new ConnectionFromOkHttp3(m.getPath() + "actuators/rooms/" + data.getJSONObject(i).getInt("RoomID"));
                conn.send("GetRequest");
                String modulesResponse = conn.getData();
                JSONObject modulesJsonObject = new JSONObject(modulesResponse);

                List<ModuleEntity> moduleEntityList = new ArrayList<>();
                JSONArray jsonArray = modulesJsonObject.getJSONArray("data");
                for (int j = 0; j < jsonArray.length(); j++) {
                    if (jsonArray.getJSONObject(j).getInt("Display") == 1) {
                        moduleEntityList.add(new ModuleEntity(jsonArray.getJSONObject(j).getString("ActuatorImage"),
                                jsonArray.getJSONObject(j).getInt("ActuatorID"),
                                jsonArray.getJSONObject(j).getString("ActuatorName"),
                                (jsonArray.getJSONObject(j).getInt("ActuatorStatusID") == 1),
                                jsonArray.getJSONObject(j).getInt("PermissionID"),
                                jsonArray.getJSONObject(j).getString("PermissionDescription")));
                    }
                }
                functionList.add(new FunctionEntity(data.getJSONObject(i).getString("RoomName"),
                        data.getJSONObject(i).getInt("RoomID"),
                        data.getJSONObject(i).getString("RoomImage"), moduleEntityList));
            }

            return functionList;

        } catch (Exception e) {
            return getFunctionData(m);
        }
    }

    // List Data
    public static List<FunctionEntity> getListData(MainActivity m) {

        List<FunctionEntity> functionList = null;
        try {
            Connection conn = new ConnectionFromOkHttp3(m.getPath() + "rooms");
            conn.send("GetRequest");
            String response = conn.getData();
            JSONObject jsonObject = new JSONObject(response);
            JSONArray data = jsonObject.getJSONArray("data");
            functionList = new ArrayList<>();

            for (int i = 0; i < data.length(); i++) {
                functionList.add(new FunctionEntity(data.getJSONObject(i).getString("RoomName"),
                        data.getJSONObject(i).getInt("RoomID"),
                        data.getJSONObject(i).getString("RoomImage"), null));
            }
            return functionList;

        } catch (Exception e) {
            return getListData(m);
        }
    }

    // List Data
    public static ArrayList<ChartItemList> getWeatherTempData(Weather weather, Activity activity) {
        ArrayList<Entry> e1 = new ArrayList<Entry>();
        ArrayList<Entry> e2 = new ArrayList<Entry>();
        ArrayList<Entry> e3 = new ArrayList<Entry>();

        for (int i = 1; i <= weather.getSize(); i++) {
            SimpleDateFormat sdf = new SimpleDateFormat("dd", Locale.ENGLISH);
            long date = weather.getTimeStamp(i);
            Date transferDate = new Date(date * 1000);
            e1.add(new Entry(Float.valueOf(sdf.format(transferDate)), weather.getCurrentTemp(i).floatValue()));
            e2.add(new Entry(Float.valueOf(sdf.format(transferDate)), weather.getMaxTemp(i).floatValue()));
            e3.add(new Entry(Float.valueOf(sdf.format(transferDate)), weather.getMinTemp(i).floatValue()));
        }

        ArrayList<ChartItemList> chartItemListArray = new ArrayList<ChartItemList>();

        ChartItemList chartItemList1 = new ChartItemList(e1, activity.getString(R.string.fragmentWeather_Average), 2.5f, 4.5f, Color.rgb(244, 117, 117));
        chartItemList1.setColor(Color.RED);
        chartItemList1.setCircleColor(Color.RED);
        chartItemListArray.add(chartItemList1);

        ChartItemList chartItemList2 = new ChartItemList(e2, activity.getString(R.string.fragmentWeather_Maximum), 2.5f, 4.5f, Color.rgb(244, 117, 117));
        chartItemList2.setColor(ColorTemplate.JOYFUL_COLORS[0]);
        chartItemList2.setCircleColor(ColorTemplate.JOYFUL_COLORS[0]);
        chartItemListArray.add(chartItemList2);

        ChartItemList chartItemList3 = new ChartItemList(e3, activity.getString(R.string.fragmentWeather_Minimum), 2.5f, 4.5f, Color.rgb(244, 117, 117));
        chartItemListArray.add(chartItemList3);

        return chartItemListArray;
    }

    // List Data
    public static ArrayList<ChartItemList> getWeatherHumData(Weather weather, Activity activity) {
        ArrayList<Entry> e1 = new ArrayList<Entry>();

        for (int i = 1; i <= weather.getSize(); i++) {
            SimpleDateFormat sdf = new SimpleDateFormat("dd", Locale.ENGLISH);
            long date = weather.getTimeStamp(i);
            Date transferDate = new Date(date * 1000);
            e1.add(new Entry(Float.valueOf(sdf.format(transferDate)), weather.getHumidity(i).floatValue()));
        }

        ArrayList<ChartItemList> chartItemListArray = new ArrayList<ChartItemList>();

        ChartItemList chartItemList1 = new ChartItemList(e1, activity.getString(R.string.fragmentWeather_Percentage), 2.5f, 4.5f, Color.rgb(244, 117, 117));
        chartItemListArray.add(chartItemList1);

        return chartItemListArray;
    }

    // List Data
    public static ArrayList<ChartItemList> getWeatherPresData(Weather weather, Activity activity) {
        ArrayList<Entry> e1 = new ArrayList<Entry>();

        for (int i = 1; i <= weather.getSize(); i++) {
            SimpleDateFormat sdf = new SimpleDateFormat("dd", Locale.ENGLISH);
            long date = weather.getTimeStamp(i);
            Date transferDate = new Date(date * 1000);
            e1.add(new Entry(Float.valueOf(sdf.format(transferDate)), weather.getPressure(i).floatValue()));
        }

        ArrayList<ChartItemList> chartItemListArray = new ArrayList<ChartItemList>();

        ChartItemList chartItemList1 = new ChartItemList(e1, activity.getString(R.string.fragmentWeather_Pascal), 2.5f, 4.5f, Color.rgb(244, 117, 117));
        chartItemList1.setColor(Color.GRAY);
        chartItemList1.setCircleColor(Color.GRAY);
        chartItemListArray.add(chartItemList1);

        return chartItemListArray;
    }

    // Heart Rate Grouped data
    public static List<List<HeartRecordEntity>> getHeartRateGroupedData(MainActivity m, int userID) {

        List<List<HeartRecordEntity>> heartRecordList = new ArrayList<>();

        try {
            Connection conn = new ConnectionFromOkHttp3(m.getPath() + "users/heartRates?userID=" + userID + "&numItems=20");
            conn.send("GetRequest");
            JSONObject jsonObject = new JSONObject(conn.getData());
            JSONArray heartRateRecords = jsonObject.getJSONArray("data");

            List<HeartRecordEntity> heartRecordDetailList = new ArrayList<>();

            String dateFormat = "";
            for (int i = 0; i < heartRateRecords.length(); i++) {
                int heartRateID = heartRateRecords.getJSONObject(i).getInt("HeartRateID");
                String date = heartRateRecords.getJSONObject(i).getString("DateTime");
                int heartRate = heartRateRecords.getJSONObject(i).getInt("HeartRate");
                int id = heartRateRecords.getJSONObject(i).getInt("UserID");

                if (dateFormat == "") {
                    dateFormat = date.substring(0, 7);
                    heartRecordDetailList.add(new HeartRecordEntity(heartRateID, date, heartRate, id));
                } else if (date.substring(0, 7).compareTo(dateFormat) == 0) {
                    heartRecordDetailList.add(new HeartRecordEntity(heartRateID, date, heartRate, id));
                } else {
                    heartRecordList.add(heartRecordDetailList);
                    heartRecordDetailList = new ArrayList<>();
                    dateFormat = date.substring(0, 7);
                    heartRecordDetailList.add(new HeartRecordEntity(heartRateID, date, heartRate, id));
                }

                if (i == heartRateRecords.length() - 1) {
                    heartRecordList.add(heartRecordDetailList);
                }
            }
        } catch (Exception e) {
            return getHeartRateGroupedData(m, userID);
        }
        return heartRecordList;
    }

    // Heart Rate data
    public static ArrayList<BarEntry> getHeartRateData(MainActivity m, int userID) {

        ArrayList<BarEntry> heartValues = new ArrayList<>();

        try {
            Connection conn = new ConnectionFromOkHttp3(m.getPath() + "users/heartRates?userID=" + userID + "&numItems=20");
            conn.send("GetRequest");
            JSONObject jsonObject = new JSONObject(conn.getData());
            JSONArray heartRateRecords = jsonObject.getJSONArray("data");

            for (int i = heartRateRecords.length() - 1; i >= 0 ; i--) {
                int heartRate = heartRateRecords.getJSONObject(i).getInt("HeartRate");
                heartValues.add(new BarEntry(heartRateRecords.length() - 1 - i, Float.valueOf(heartRate)));
            }
        } catch (Exception e) {
            return getHeartRateData(m, userID);
        }
        return heartValues;
    }

    // User data
    public static JSONArray getUserData(MainActivity m, int userID, Activity activity) {

        try {
            Connection conn = new ConnectionFromOkHttp3(m.getPath() + "users/" + userID);
            conn.send("GetRequest");
            String response = conn.getData();
            JSONObject jsonObject = new JSONObject(response);
            JSONObject data = jsonObject.getJSONObject("data");

            JSONArray settingListArray = new JSONArray();
            JSONObject settingList;

            settingList = new JSONObject();
            settingList.put("name", activity.getString(R.string.fragmentSetting_receiveNotification));
            settingList.put("class", "NotificationMessage");
            settingList.put("description", activity.getString(R.string.fragmentSetting_receiveNotification_D));
            settingList.put("value", data.getInt("ReceiveNotification"));
            settingListArray.put(settingList);

            settingList = new JSONObject();
            settingList.put("name", activity.getString(R.string.fragmentSetting_receiveEmail));
            settingList.put("class", "EmailMessage");
            settingList.put("description", activity.getString(R.string.fragmentSetting_receiveEmail_D));
            settingList.put("value", data.getInt("ReceiveEmail"));
            settingListArray.put(settingList);

            settingList = new JSONObject();
            settingList.put("name", activity.getString(R.string.fragmentSetting_locationDisplay));
            settingList.put("class", "LocationMessage");
            settingList.put("description", activity.getString(R.string.fragmentSetting_locationDisplay_D));
            settingList.put("value", data.getInt("LocationDisplay"));
            settingListArray.put(settingList);

            return settingListArray;

        } catch (Exception e) {
            return getUserData(m, userID, activity);
        }
    }

    // User data
    public static JSONArray getPreferenceData(SharedPreferences setting, Activity activity) {

        try {
            JSONArray settingListArray = new JSONArray();
            JSONObject settingList;

            settingList = new JSONObject();
            settingList.put("name", activity.getString(R.string.fragmentSetting_weatherNotification));
            settingList.put("description", activity.getString(R.string.fragmentSetting_weatherNotification_D));
            settingList.put("setting", setting);
            settingListArray.put(settingList);

            return settingListArray;

        } catch (Exception e) {
            return getPreferenceData(setting, activity);
        }
    }
}
