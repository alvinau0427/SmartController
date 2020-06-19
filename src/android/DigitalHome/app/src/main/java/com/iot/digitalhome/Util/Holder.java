package com.iot.digitalhome.Util;

import android.content.Context;
import android.view.View;

import com.iot.digitalhome.Activity.MainActivity;

import org.json.JSONObject;

public abstract class Holder {

    protected Context mContext;
    protected MainActivity mainActivity;

    public void setContext(Context mContext) {this.mContext = mContext;}
    public void setMainActivity(MainActivity mainActivity) {this.mainActivity = mainActivity;}
    public abstract void setValue(JSONObject jsonObject, View convertView);
    public abstract void show();
}