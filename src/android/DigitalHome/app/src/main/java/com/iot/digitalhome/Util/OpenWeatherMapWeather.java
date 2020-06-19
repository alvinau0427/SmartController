package com.iot.digitalhome.Util;

import com.iot.digitalhome.Conn.Connection;
import com.iot.digitalhome.Conn.ConnectionFromOkHttp3;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.concurrent.TimeoutException;

public class OpenWeatherMapWeather implements Weather {

    private JSONArray items;

    public OpenWeatherMapWeather() {
        connection("http://api.openweathermap.org/data/2.5/forecast/daily?units=Metric&q=hk&APPID=03ab0344ffde3138709be808dfe0d126&cnt=7");
    }

    public OpenWeatherMapWeather(float lat, float lon) {
        connection("http://api.openweathermap.org/data/2.5/forecast/daily?units=Metric&lat=" + lat + "&lon=" + lon + "&APPID=03ab0344ffde3138709be808dfe0d126&cnt=7");
    }

    @Override
    public void connection(String link) {
        try {
            Connection conn = new ConnectionFromOkHttp3(link);
            conn.send("GetRequest");
            String response = conn.getData();
            JSONObject jsonObject = new JSONObject(response);
            JSONArray items = jsonObject.getJSONArray("list");
            this.items = items;
        } catch (TimeoutException e) {
            e.printStackTrace();
        } catch (JSONException e) {
            e.printStackTrace();
        }
    }

    //make json for easy organization
    @Override
    public JSONArray getData() {
        JSONArray jsonArray = new JSONArray();
        try {
            for (int i = 1; i <= items.length(); i++) {
                JSONObject jsonObject = new JSONObject();
                jsonObject.put("RecordID", i);
                jsonObject.put("Description", getDescription(i));
                jsonObject.put("TimeStamp", getTimeStamp(i));
                jsonObject.put("CurrentTemp", getCurrentTemp(i));
                jsonObject.put("MinTemp", getMinTemp(i));
                jsonObject.put("MaxTemp", getMaxTemp(i));
                jsonObject.put("Humidity", getHumidity(i));
                jsonObject.put("Pressure", getPressure(i));
                jsonObject.put("WindSpeed", getWindSpeed(i));
                jsonObject.put("Cloud", getCloud(i));
                jsonObject.put("Rain", getRain(i));
                jsonObject.put("Icon", getIcon(i));
                jsonObject.put("UpdateDateTime", getUpdateDateTime(i));
                jsonArray.put(jsonObject);
            }
        } catch (JSONException e) {
            e.printStackTrace();
        }
        return jsonArray;
    }

    @Override
    public JSONObject getData(int id) {
        try {
            return items.getJSONObject(id - 1);
        } catch (JSONException e) {
            e.printStackTrace();
        }
        return null;
    }

    @Override
    public String getDescription(int id) {
        try {
            return items.getJSONObject(id - 1).getJSONArray("weather").getJSONObject(0).getString("main");
        } catch (JSONException e) {
            e.printStackTrace();
        }
        return "";
    }

    @Override
    public Long getTimeStamp(int id) {
        try {
            return items.getJSONObject(id - 1).getLong("dt");
        } catch (JSONException e) {
            e.printStackTrace();
        }
        return 0l;
    }

    @Override
    public Long getCurrentTemp(int id) {
        try {
            return Math.round(items.getJSONObject(id - 1).getJSONObject("temp").getDouble("day"));
        } catch (JSONException e) {
            e.printStackTrace();
        }
        return 0l;
    }

    @Override
    public Long getMinTemp(int id) {
        try {
            return Math.round(items.getJSONObject(id - 1).getJSONObject("temp").getDouble("min"));
        } catch (JSONException e) {
            e.printStackTrace();
        }
        return 0l;
    }

    @Override
    public Long getMaxTemp(int id) {
        try {
            return Math.round(items.getJSONObject(id - 1).getJSONObject("temp").getDouble("max"));
        } catch (JSONException e) {
            e.printStackTrace();
        }
        return 0l;
    }

    @Override
    public Double getHumidity(int id) {
        try {
            return items.getJSONObject(id - 1).getDouble("humidity");
        } catch (JSONException e) {
            e.printStackTrace();
        }
        return 0.0;
    }

    @Override
    public Double getPressure(int id) {
        try {
            return items.getJSONObject(id - 1).getDouble("pressure");
        } catch (JSONException e) {
            e.printStackTrace();
        }
        return 0.0;
    }

    @Override
    public Double getWindSpeed(int id) {
        try {
            return items.getJSONObject(id - 1).getDouble("speed");
        } catch (JSONException e) {
            e.printStackTrace();
        }
        return 0.0;
    }

    @Override
    public Double getRain(int id) {
        try {
            return items.getJSONObject(id - 1).getDouble("rain");
        } catch (JSONException e) {
            return 0.0;
        }
    }

    @Override
    public Double getCloud(int id) {
        try {
            return items.getJSONObject(id - 1).getDouble("clouds");
        } catch (JSONException e) {
            return 0.0;
        }
    }

    @Override
    public String getIcon(int id) {
        try {
            return items.getJSONObject(id - 1).getJSONArray("weather").getJSONObject(0).getString("icon");
        } catch (JSONException e) {
            e.printStackTrace();
        }
        return null;
    }

    @Override
    public String getUpdateDateTime(int id) {
        try {
            return items.getJSONObject(id - 1).getString("dt");
        } catch (JSONException e) {
            e.printStackTrace();
        }
        return null;
    }

    @Override
    public int getSize() {
        return items.length();
    }
}
