package com.iot.digitalhome.Model;

import android.content.SharedPreferences;
import android.util.Log;

import com.iot.digitalhome.Conn.Connection;
import com.iot.digitalhome.Conn.ConnectionFromOkHttp3;
import com.iot.digitalhome.Model.UserEntity;
import com.iot.digitalhome.Util.Location;

import org.json.JSONArray;
import org.json.JSONObject;

import java.util.ArrayList;

public class Root implements UserEntity {

    private String path;
    private SharedPreferences setting;

    @Override
    public void setData(String path, SharedPreferences setting) {
        this.path = path;
        this.setting = setting;
    }

    @Override
    public ArrayList<Location> getLocation() {
        try {
            Connection conn = new ConnectionFromOkHttp3(path + "users/locations");
            conn.send("GetRequest");
            String response = conn.getData();
            JSONObject jsonObject = new JSONObject(response);
            JSONArray jsonArray = jsonObject.getJSONArray("data");
            ArrayList<Location> locationList = new ArrayList<Location>();
            for (int i = 0; i < jsonArray.length(); i++) {
                if (jsonArray.getJSONObject(i).getInt("LocationDisplay") == 1 || jsonArray.getJSONObject(i).getInt("UserID") == Integer.valueOf(setting.getString("userID", "0"))) {
                    Location location = new Location();
                    location.setUserName(jsonArray.getJSONObject(i).getString("UserName"));
                    location.setLatitude((float) jsonArray.getJSONObject(i).getDouble("Latitude"));
                    location.setLongitude((float) jsonArray.getJSONObject(i).getDouble("Longitude"));
                    locationList.add(location);
                }
            }
            return locationList;
        } catch (Exception e) {
            return getLocation();
        }
    }
}
