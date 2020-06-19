package com.iot.digitalhome.Util;

import com.iot.digitalhome.Conn.Connection;
import com.iot.digitalhome.Conn.ConnectionFromOkHttp3;

import java.util.concurrent.TimeoutException;

public class NotificationMessage implements Message {

    @Override
    public void sendAPI(String path, String userID, int status) {
        try {
            Connection conn = new ConnectionFromOkHttp3(path + "users/receiveNotification");
            conn.post("userID", userID);
            conn.post("status", status + "");
            conn.send("PutRequest");
        } catch (TimeoutException e) {
            e.printStackTrace();
        }
    }
}
