package com.iot.digitalhome.Conn;

import java.util.HashMap;

import okhttp3.Request;

public class GetRequest implements RequestMethod {

    @Override
    public Request execute(String url, HashMap<String, String> params) {
        return new Request.Builder().url(url).build();
    }
}
