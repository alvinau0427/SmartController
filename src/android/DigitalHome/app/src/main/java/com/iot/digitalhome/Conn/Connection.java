package com.iot.digitalhome.Conn;

import java.util.concurrent.TimeoutException;

public interface Connection {
    public abstract String getData();
    public abstract String getSuccessMessage();
    public abstract void post(String key, String value);
    public abstract String send(String method) throws TimeoutException;
}
