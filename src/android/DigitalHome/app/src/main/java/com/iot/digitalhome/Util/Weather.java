package com.iot.digitalhome.Util;

import org.json.JSONArray;
import org.json.JSONObject;

public interface Weather {
    public abstract void connection(String link);
    public abstract JSONObject getData(int id);
    public abstract JSONArray getData();
    public abstract String getDescription(int id);
    public abstract Long getTimeStamp(int id);
    public abstract Long getCurrentTemp(int id);
    public abstract Long getMinTemp(int id);
    public abstract Long getMaxTemp(int id);
    public abstract Double getHumidity(int id);
    public abstract Double getPressure(int id);
    public abstract Double getWindSpeed(int id);
    public abstract Double getCloud(int id);
    public abstract Double getRain(int id);
    public abstract String getIcon(int id);
    public abstract String getUpdateDateTime(int id);
    public abstract int getSize();
}
