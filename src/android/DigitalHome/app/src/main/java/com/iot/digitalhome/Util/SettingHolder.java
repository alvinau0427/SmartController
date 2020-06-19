package com.iot.digitalhome.Util;

import android.view.View;
import android.widget.CompoundButton;
import android.widget.Switch;
import android.widget.TextView;

import com.iot.digitalhome.R;

import org.json.JSONException;
import org.json.JSONObject;

public class SettingHolder extends Holder {
    private TextView tvTitle;
    private TextView tvDescription;
    private Switch sw;
    private View convertView;
    private JSONObject jsonObject;

    @Override
    public void setValue(JSONObject jsonObject, View convertView) {
        tvTitle = (TextView) convertView.findViewById(R.id.tvTitle);
        tvDescription = (TextView) convertView.findViewById(R.id.tvDescription);
        sw = (Switch) convertView.findViewById(R.id.sw);

        this.convertView = convertView;
        this.jsonObject = jsonObject;
    }

    @Override
    public void show() {
        try {
            tvTitle.setText(jsonObject.getString("name"));
            tvDescription.setText(jsonObject.getString("description"));
            sw.setChecked(jsonObject.getInt("value") == 1);
            sw.setOnCheckedChangeListener(new CompoundButton.OnCheckedChangeListener() {
                @Override
                public void onCheckedChanged(CompoundButton buttonView, boolean isChecked) {
                    //update status
                    int status = (!isChecked) ? 0 : 1;
                    try {
                        Message message = (Message) (Class.forName(mContext.getPackageName() + ".Util." + jsonObject.getString("class")).newInstance());
                        message.sendAPI(mainActivity.getPath(), mContext.getSharedPreferences("account", 0).getString("userID", null), status);
                    } catch (InstantiationException e) {
                        e.printStackTrace();
                    } catch (IllegalAccessException e) {
                        e.printStackTrace();
                    } catch (ClassNotFoundException e) {
                        e.printStackTrace();
                    } catch (JSONException e) {
                        e.printStackTrace();
                    }
                }
            });
        } catch (Exception e) {
            e.printStackTrace();
        }
    }
}