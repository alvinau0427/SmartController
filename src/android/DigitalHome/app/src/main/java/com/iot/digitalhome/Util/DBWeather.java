package com.iot.digitalhome.Util;

import com.iot.digitalhome.Conn.Connection;
import com.iot.digitalhome.Conn.ConnectionFromOkHttp3;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.concurrent.TimeoutException;

public class DBWeather implements Weather {

    private JSONArray items;

    public DBWeather() {
        connection("http://fypgroup4.ddns.net/FYP/api/weathers");
    }

    public DBWeather(long lat, long lon) {
        connection("http://fypgroup4.ddns.net/FYP/api/weathers/" + lat + "/" + lon);
    }

    @Override
    public void connection(String link) {
        try {
            Connection conn = new ConnectionFromOkHttp3(link);
            conn.send("GetRequest");
            String response = conn.getData();
            JSONObject jsonObject = new JSONObject(response);
            JSONArray items = jsonObject.getJSONArray("data");
            this.items = items;
        } catch (TimeoutException e) {
            e.printStackTrace();
        } catch (JSONException e) {
            e.printStackTrace();
        }
    }

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
            return items.getJSONObject(id - 1).getString("Description");
        } catch (JSONException e) {
            e.printStackTrace();
        }
        return null;
    }

    @Override
    public Long getTimeStamp(int id) {
        try {
            return items.getJSONObject(id - 1).getLong("TimeStamp");
        } catch (JSONException e) {
            e.printStackTrace();
        }
        return null;
    }

    @Override
    public Long getCurrentTemp(int id) {
        try {
            return Math.round(items.getJSONObject(id - 1).getDouble("CurrentTemp"));
        } catch (JSONException e) {
            e.printStackTrace();
        }
        return 0l;
    }

    @Override
    public Long getMinTemp(int id) {
        try {
            return Math.round(items.getJSONObject(id - 1).getDouble("MinTemp"));
        } catch (JSONException e) {
            e.printStackTrace();
        }
        return 0l;
    }

    @Override
    public Long getMaxTemp(int id) {
        try {
            return Math.round(items.getJSONObject(id - 1).getDouble("MaxTemp"));
        } catch (JSONException e) {
            e.printStackTrace();
        }
        return 0l;
    }

    @Override
    public Double getHumidity(int id) {
        try {
            return items.getJSONObject(id - 1).getDouble("Humidity");
        } catch (JSONException e) {
            e.printStackTrace();
        }
        return null;
    }

    @Override
    public Double getPressure(int id) {
        try {
            return items.getJSONObject(id - 1).getDouble("Pressure");
        } catch (JSONException e) {
            e.printStackTrace();
        }
        return null;
    }

    @Override
    public Double getWindSpeed(int id) {
        try {
            return items.getJSONObject(id - 1).getDouble("WindSpeed");
        } catch (JSONException e) {
            e.printStackTrace();
        }
        return null;
    }

    @Override
    public Double getRain(int id) {
        try {
            return items.getJSONObject(id - 1).getDouble("Rain");
        } catch (JSONException e) {
            e.printStackTrace();
        }
        return null;
    }

    @Override
    public Double getCloud(int id) {
        return 0.0;
    }

    @Override
    public String getIcon(int id) {
        try {
            return items.getJSONObject(id - 1).getString("Icon");
        } catch (JSONException e) {
            e.printStackTrace();
        }
        return null;
    }

    @Override
    public String getUpdateDateTime(int id) {
        try {
            return items.getJSONObject(id - 1).getString("UpdateDateTime");
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
