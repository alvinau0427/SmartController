package com.iot.digitalhome.Conn;

import android.util.Log;

import java.io.IOException;
import java.util.HashMap;
import java.util.concurrent.TimeUnit;
import java.util.concurrent.TimeoutException;

import okhttp3.Call;
import okhttp3.Callback;
import okhttp3.OkHttpClient;
import okhttp3.OkHttpClient.Builder;
import okhttp3.Request;
import okhttp3.Response;

public class ConnectionFromOkHttp3 implements Connection {

    private String responseData = null;
    private final String SUCCESS_MESSAGE = "success";
    private String url;
    private OkHttpClient client;
    private Request request;
    private HashMap<String, String> params;

    public ConnectionFromOkHttp3(String url) {
        this.url = url;
        Builder b = new Builder();
        b.connectTimeout(1, TimeUnit.SECONDS);
        b.readTimeout(1, TimeUnit.SECONDS);
        b.writeTimeout(1, TimeUnit.SECONDS);
        client = b.build();
        params = new HashMap<>();
    }

    @Override
    public String send(String method) throws TimeoutException {
        try {
            RequestMethod requestMethod = (RequestMethod) Class.forName("com.iot.digitalhome.Conn." + method).newInstance();
            request = requestMethod.execute(url, params);

            client.newCall(request).enqueue(new Callback() {
                @Override
                public void onFailure(Call call, IOException e) {
                    responseData = "";
                }

                @Override
                public void onResponse(Call call, Response response) throws IOException {
                    responseData = response.body().string();
                }
            });
            while (responseData == null) {
                Log.d("-----", "loading");
            }

            if (responseData.equals("")) {
                throw new TimeoutException();
            }
        } catch (Exception e) {
            throw new TimeoutException();
        }

        return SUCCESS_MESSAGE;
    }

    @Override
    public void post(String key, String value) {
        params.put(key, value);
    }

    @Override
    public String getData() {
        return responseData;
    }

    @Override
    public String getSuccessMessage() {
        return SUCCESS_MESSAGE;
    }
}
