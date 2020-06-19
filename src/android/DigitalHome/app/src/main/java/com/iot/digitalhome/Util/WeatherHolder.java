package com.iot.digitalhome.Util;

import android.view.View;
import android.widget.ImageView;
import android.widget.TextView;

import com.bumptech.glide.Glide;
import com.bumptech.glide.load.resource.drawable.GlideDrawable;
import com.bumptech.glide.request.animation.GlideAnimation;
import com.bumptech.glide.request.target.SimpleTarget;
import com.iot.digitalhome.R;

import org.json.JSONObject;

import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.Locale;

public class WeatherHolder extends Holder {
    private TextView tvTitle;
    private TextView tvDescription;
    private ImageView iv_icon;
    private TextView tvDate;
    private TextView tvTemp;
    private TextView tvHum;
    private TextView tvPres;
    private TextView tvWind;
    private TextView tvCloud;
    private TextView tvRain;
    private View convertView;
    private JSONObject jsonObject;
    @Override
    public void setValue(JSONObject jsonObject, View convertView) {
        tvTitle = (TextView) convertView.findViewById(R.id.tvTitle);
        tvDescription = (TextView) convertView.findViewById(R.id.tvDescription);
        iv_icon = (ImageView) convertView.findViewById(R.id.iv_icon);
        tvDate = (TextView) convertView.findViewById(R.id.tvDate);
        tvTemp = (TextView) convertView.findViewById(R.id.tvTemp);
        tvHum = (TextView) convertView.findViewById(R.id.tvHum);
        tvPres = (TextView) convertView.findViewById(R.id.tvPres);
        tvWind = (TextView) convertView.findViewById(R.id.tvWind);
        tvCloud = (TextView) convertView.findViewById(R.id.tvCloud);
        tvRain = (TextView) convertView.findViewById(R.id.tvRain);

        this.convertView = convertView;
        this.jsonObject = jsonObject;
    }

    @Override
    public void show() {
        try {
            long date = jsonObject.getLong("TimeStamp");
            Date transferDate = new Date(date * 1000);

            SimpleDateFormat sdf;

            sdf = new SimpleDateFormat("dd/MM", Locale.ENGLISH);
            tvDate.setText(sdf.format(transferDate));

            sdf = new SimpleDateFormat("E", Locale.ENGLISH);

            //check today, tomorrow or others
            if (jsonObject.getInt("RecordID") == 1) {
                tvTitle.setText("Today");
            } else if (jsonObject.getInt("RecordID") == 2) {
                tvTitle.setText("Tomorrow");
            } else {
                tvTitle.setText(sdf.format(transferDate));
            }

            //set data
            tvDescription.setText(jsonObject.getString("Description"));
            tvTemp.setText(String.valueOf(jsonObject.getLong("MinTemp")) + " - " + String.valueOf(jsonObject.getLong("MaxTemp")) + " ℃");
            tvHum.setText(String.valueOf(jsonObject.getLong("Humidity")) + " %");
            tvPres.setText(String.valueOf(jsonObject.getLong("Pressure")) + " hpa");
            tvWind.setText(String.valueOf(jsonObject.getLong("WindSpeed")) + " m/s");
            tvCloud.setText(String.valueOf(jsonObject.getLong("Cloud")) + " %");

            double rain = jsonObject.getDouble("Rain");
            if (rain == 0) {
                tvRain.setText("No Rain");
            } else if (rain < 3) {
                tvRain.setText("Light Rain");
            } else {
                tvRain.setText("Heavy Rain");
            }


            String icon = jsonObject.getString("Icon");
            Glide.with(mContext)
                    .load("http://openweathermap.org/img/w/" + icon + ".png")
                    .fitCenter()
                    .dontAnimate()
                    .into(new SimpleTarget<GlideDrawable>() {
                        @Override
                        public void onResourceReady(GlideDrawable resource, GlideAnimation<? super GlideDrawable> glideAnimation) {
                            iv_icon.setImageDrawable(resource.getCurrent());
                        }
                    });
        } catch (Exception e) {
            e.printStackTrace();
        }
    }
}