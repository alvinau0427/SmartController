package com.iot.digitalhome.Fragment;

import android.content.SharedPreferences;
import android.graphics.Color;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ExpandableListView;

import com.github.mikephil.charting.charts.BarChart;
import com.github.mikephil.charting.components.XAxis;
import com.github.mikephil.charting.components.YAxis;
import com.github.mikephil.charting.data.BarData;
import com.github.mikephil.charting.data.BarDataSet;
import com.github.mikephil.charting.data.Entry;
import com.github.mikephil.charting.highlight.Highlight;
import com.github.mikephil.charting.listener.OnChartValueSelectedListener;
import com.iot.digitalhome.Activity.MainActivity;
import com.iot.digitalhome.Adapter.HeartRecordAdapter;
import com.iot.digitalhome.Model.HeartRecordEntity;
import com.iot.digitalhome.R;
import com.iot.digitalhome.Util.ModelUtil;
import com.pusher.client.Pusher;
import com.pusher.client.channel.Channel;
import com.pusher.client.channel.SubscriptionEventListener;

import java.util.ArrayList;
import java.util.List;

public class HeartPageHistoryFragment extends Fragment implements OnChartValueSelectedListener {

    private SharedPreferences setting;
    private View rootView;
    private MainActivity mainActivity;
    private BarChart mChart;

    private ExpandableListView expandableListView;
    private HeartRecordAdapter heartRecordAdapter;

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {

        setting = getActivity().getSharedPreferences("account", 0);

        // Inflate the layout for this fragment
        rootView = inflater.inflate(R.layout.fragment_heart_history, container, false);

        mainActivity = new MainActivity();

        //set chart
        mChart = (BarChart) rootView.findViewById(R.id.chart);
        mChart.setDrawValueAboveBar(true);
        mChart.setMaxVisibleValueCount(50);
        mChart.setOnChartValueSelectedListener(this);
        mChart.getDescription().setEnabled(false);
        mChart.moveViewTo(5, 5, YAxis.AxisDependency.LEFT);

        BarData data = new BarData(createSet());
        data.setBarWidth(0.5f);

        mChart.getAxisLeft().setAxisMinimum(50);
        mChart.getAxisLeft().setAxisMaximum(150);
        mChart.getAxisRight().setEnabled(false);
        mChart.getXAxis().setPosition(XAxis.XAxisPosition.BOTTOM);
        mChart.getXAxis().setAxisMinimum(-0.5f);
        mChart.getXAxis().setAxisMaximum(20);
        mChart.setData(data);
        mChart.animateXY(2000, 2000);
        mChart.invalidate();

        expandableListView = (ExpandableListView) rootView.findViewById(R.id.expandableListView);
        heartRecordAdapter = new HeartRecordAdapter(getActivity(), ModelUtil.getHeartRateGroupedData(mainActivity, Integer.valueOf(setting.getString("userID", null))));
        expandableListView.setAdapter(heartRecordAdapter);

        //build pusher
        Pusher pusher = new Pusher(getString(R.string.pusher_key));

        Channel channel = pusher.subscribe(getString(R.string.pusher_channel_heartRate));

        //when receiving message, do the action
        channel.bind(getString(R.string.pusher_channel_update), new SubscriptionEventListener() {
            @Override
            public void onEvent(String channelName, String eventName, String jsonData) {
                try {
                    //update chart
                    BarData data = new BarData(createSet());
                    data.setBarWidth(0.5f);
                    mChart.setData(data);
                    mChart.notifyDataSetChanged();
                    mChart.invalidate();

                    //update list
                    heartRecordAdapter.setHeartRecord(ModelUtil.getHeartRateGroupedData(mainActivity, Integer.valueOf(setting.getString("userID", null))));
                    heartRecordAdapter.refresh();
                    expandableListView.invalidate();
                } catch (Exception e) {
                    e.printStackTrace();
                }
            }
        });

        pusher.connect();

        return rootView;
    }

    private BarDataSet createSet() {
        BarDataSet barDataSet = new BarDataSet(ModelUtil.getHeartRateData(mainActivity, Integer.valueOf(setting.getString("userID", null))), "Heart Rate");
        barDataSet.setColor(Color.RED);
        return barDataSet;
    }

    @Override
    public void onValueSelected(Entry e, Highlight h) {}

    @Override
    public void onNothingSelected() {}
}