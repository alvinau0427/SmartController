package com.iot.digitalhome.Model;

import android.content.SharedPreferences;

import com.iot.digitalhome.Conn.Connection;
import com.iot.digitalhome.Conn.ConnectionFromOkHttp3;
import com.iot.digitalhome.Model.UserEntity;
import com.iot.digitalhome.Util.Location;

import org.json.JSONArray;
import org.json.JSONObject;

import java.util.ArrayList;

public class User implements UserEntity {

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
            Connection conn = new ConnectionFromOkHttp3(path + "users/locations/" + setting.getString("userID", null));
            conn.send("GetRequest");
            String response = conn.getData();
            JSONObject jsonObject = new JSONObject(response);
            JSONObject data = jsonObject.getJSONObject("data");
            ArrayList<Location> locationList = new ArrayList<Location>();
            Location location = new Location();
            location.setUserName(data.getString("UserName"));
            location.setLatitude((float) data.getDouble("Latitude"));
            location.setLongitude((float) data.getDouble("Longitude"));
            locationList.add(location);
            return locationList;
        } catch (Exception e) {
            return getLocation();
        }
    }
}
