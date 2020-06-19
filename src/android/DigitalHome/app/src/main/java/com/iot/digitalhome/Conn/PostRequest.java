package com.iot.digitalhome.Conn;

import java.util.HashMap;
import java.util.Map;

import okhttp3.FormBody;
import okhttp3.Request;
import okhttp3.RequestBody;

public class PostRequest implements RequestMethod {

    @Override
    public Request execute(String url, HashMap<String, String> params) {
        FormBody.Builder builder = new FormBody.Builder();

        for (Map.Entry<String, String> entry : params.entrySet()) {
            builder.add(entry.getKey(), entry.getValue());
        }
        RequestBody body = builder.build();
        return new Request.Builder().url(url).post(body).build();
    }
}
