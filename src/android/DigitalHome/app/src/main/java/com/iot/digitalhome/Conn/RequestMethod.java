package com.iot.digitalhome.Conn;

import java.util.HashMap;

import okhttp3.Request;

public interface RequestMethod {
    public abstract Request execute(String url, HashMap<String, String> params);
}
