package com.iot.digitalhome.Fragment;

import android.content.SharedPreferences;
import android.os.Bundle;
import android.os.Handler;
import android.support.annotation.Nullable;
import android.support.v4.app.Fragment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.LinearLayout;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.github.mikephil.charting.data.BarEntry;
import com.iot.digitalhome.Activity.MainActivity;
import com.iot.digitalhome.Conn.Connection;
import com.iot.digitalhome.Conn.ConnectionFromOkHttp3;
import com.iot.digitalhome.Model.HeartRecordEntity;
import com.iot.digitalhome.R;
import com.iot.digitalhome.Util.ModelUtil;
import com.iot.digitalhome.Util.ToastUtil;
import com.pusher.client.Pusher;
import com.pusher.client.channel.Channel;
import com.pusher.client.channel.SubscriptionEventListener;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.List;
import java.util.concurrent.TimeoutException;
import java.util.concurrent.atomic.AtomicInteger;

import at.grabner.circleprogress.CircleProgressView;
import at.grabner.circleprogress.TextMode;

public class HeartPageMeasureFragment extends Fragment implements View.OnClickListener {

    private SharedPreferences setting;
    private View rootView;
    private CircleProgressView mCircleView;
    private TextView tvLastRecord;
    private RelativeLayout result;
    private RelativeLayout hints;

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {

        setting = getActivity().getSharedPreferences("account", 0);

        // Inflate the layout for this fragment
        rootView = inflater.inflate(R.layout.fragment_heart_measure, container, false);

        //RelativeLayout
        result = (RelativeLayout) rootView.findViewById(R.id.result);
        hints = (RelativeLayout) rootView.findViewById(R.id.hints);
        hints.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                mCircleView.callOnClick();
            }
        });

        //CircleProgressView
        mCircleView = (CircleProgressView) rootView.findViewById(R.id.circleView);

        mCircleView.setShowTextWhileSpinning(true); // Show or hide text in spinning mode

        mCircleView.setText(getString(R.string.fragmentHeartRate_start));
        mCircleView.setTextMode(TextMode.TEXT);
        mCircleView.setUnitVisible(false);

        mCircleView.setOnClickListener(this);

        //build pusher
        Pusher pusher = new Pusher(getString(R.string.pusher_key));

        Channel channel = pusher.subscribe(getString(R.string.pusher_channel_heartRate));

        //when receiving message, do the action
        channel.bind(getString(R.string.pusher_channel_update), new SubscriptionEventListener() {
            @Override
            public void onEvent(String channelName, String eventName, String jsonData) {
                try {
                    JSONObject jsonObject = new JSONObject(jsonData);
                    int heartRate = jsonObject.getInt("heartRate");
                    mCircleView.setValue(heartRate);
                    mCircleView.setTextMode(TextMode.VALUE);
                    mCircleView.setUnit(getString(R.string.fragmentHeartRate_unit));
                    mCircleView.setUnitVisible(true);
                    getActivity().runOnUiThread(new Runnable() {
                        @Override
                        public void run() {
                            hints.setVisibility(View.VISIBLE);
                        }
                    });
                } catch (JSONException e) {
                    e.printStackTrace();
                }
            }
        });

        pusher.connect();

        tvLastRecord = (TextView) rootView.findViewById(R.id.tvLastRecord);

        //update heart rate
        MainActivity mainActivity  = new MainActivity();
        List<List<HeartRecordEntity>> heartRateData = ModelUtil.getHeartRateGroupedData(mainActivity, Integer.valueOf(setting.getString("userID", "1")));
        if (heartRateData.size() != 0) {
            HeartRecordEntity lastHeartRateData = heartRateData.get(0).get(0);
            tvLastRecord.setText(lastHeartRateData.getHeartRate() + " " + getString(R.string.fragmentHeartRate_unit));
        } else {
            tvLastRecord.setText(getString(R.string.fragmentHeartRate_noRecord));
        }
        return rootView;
    }

    @Override
    public void onClick(View v) {

        //reset
        result.setVisibility(View.INVISIBLE);
        hints.setVisibility(View.INVISIBLE);

        mCircleView.spin();

        //start to measure
        final Handler handler = new Handler();
        final AtomicInteger n = new AtomicInteger(3);
        handler.postDelayed(new Runnable() {
            @Override
            public void run() {
                mCircleView.setText(n + "");
                if(n.getAndDecrement() >= 1 )
                    handler.postDelayed(this, 1000);
                else {
                    mCircleView.setText(getString(R.string.fragmentHeartRate_loading));
                    mCircleView.setTextMode(TextMode.TEXT); // show text while spinning
                    mCircleView.setUnitVisible(false);

                    //call server to start measure
                    try {
                        MainActivity mainActivity = new MainActivity();
                        Connection conn = new ConnectionFromOkHttp3(mainActivity.getPath() + "users/heartRates/starts/" + setting.getString("userID", null));
                        conn.send("GetRequest");

                        LinearLayout.LayoutParams layoutParams = new LinearLayout.LayoutParams(ViewGroup.LayoutParams.MATCH_PARENT, 800);
                        layoutParams.setMargins(0, 20, 0, 150);

                    } catch (TimeoutException e) {
                        //reset
                        e.printStackTrace();
                        mCircleView.stopSpinning();
                        mCircleView.setText(getString(R.string.fragmentHeartRate_start));
                        mCircleView.setTextMode(TextMode.TEXT);
                        mCircleView.setUnitVisible(false);
                    }
                }
            }
        }, 1000);
    }
}