package com.iot.digitalhome.Util;

import android.content.SharedPreferences;
import android.view.View;
import android.widget.CompoundButton;
import android.widget.Switch;
import android.widget.TextView;

import com.iot.digitalhome.R;

import org.json.JSONException;
import org.json.JSONObject;

public class SettingPreferenceHolder extends Holder {
    private TextView tvTitle;
    private TextView tvDescription;
    private Switch sw;
    private View convertView;
    private JSONObject jsonObject;
    private SharedPreferences setting;

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

            final SharedPreferences setting = ((SharedPreferences)jsonObject.get("setting"));
            sw.setChecked(setting.getInt("weatherNotification", 1) == 1);
            sw.setOnCheckedChangeListener(new CompoundButton.OnCheckedChangeListener() {
                @Override
                public void onCheckedChanged(CompoundButton buttonView, boolean isChecked) {
                    //update status
                    int status = (!isChecked) ? 0 : 1;
                    SharedPreferences.Editor editor = setting.edit();
                    editor.putInt("weatherNotification", status);
                    editor.commit();
                }
            });
        } catch (Exception e) {
            e.printStackTrace();
        }
    }
}