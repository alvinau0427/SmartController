package com.iot.digitalhome.Model;

import android.content.SharedPreferences;

import com.iot.digitalhome.Util.Location;

import java.util.ArrayList;

public interface UserEntity {
    public abstract void setData(String path, SharedPreferences setting);
    public abstract ArrayList<Location> getLocation();
}
