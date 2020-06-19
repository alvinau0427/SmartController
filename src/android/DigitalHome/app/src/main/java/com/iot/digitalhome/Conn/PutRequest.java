package com.iot.digitalhome.Conn;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.HashMap;
import java.util.Map;

import okhttp3.MediaType;
import okhttp3.Request;
import okhttp3.RequestBody;

public class PutRequest implements RequestMethod {

    @Override
    public Request execute(String url, HashMap<String, String> params) {
        JSONObject json = new JSONObject();

        try {
            for (Map.Entry<String, String> entry : params.entrySet()) {
                json.put(entry.getKey(), entry.getValue());
            }
        } catch (JSONException e) {
            e.printStackTrace();
        }

        MediaType JSON = MediaType.parse("application/json: charset=utf-8");
        RequestBody body = RequestBody.create(JSON, json.toString());

        return new Request.Builder()
                    .url(url)
                    .addHeader("Content-type", "application/json")
                    .put(body)
                    .build();
    }
}
