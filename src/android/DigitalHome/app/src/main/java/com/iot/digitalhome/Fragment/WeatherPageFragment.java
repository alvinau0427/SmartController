package com.iot.digitalhome.Fragment;

import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ListAdapter;
import android.widget.TextView;

import com.github.mikephil.charting.data.LineData;
import com.github.mikephil.charting.data.LineDataSet;
import com.github.mikephil.charting.interfaces.datasets.ILineDataSet;
import com.iot.digitalhome.Activity.MainActivity;
import com.iot.digitalhome.Activity.TimeoutErrorActivity;
import com.iot.digitalhome.R;
import com.iot.digitalhome.Adapter.ChartDataAdapter;
import com.iot.digitalhome.Util.DBWeather;
import com.iot.digitalhome.Util.ListViewItems.ChartItem;
import com.iot.digitalhome.Util.ListViewItems.ChartItemList;
import com.iot.digitalhome.Util.ListViewItems.LineChartItem;
import com.iot.digitalhome.Adapter.MyListAdapter;
import com.iot.digitalhome.Util.ModelUtil;
import com.iot.digitalhome.Util.NonScrollListView;
import com.iot.digitalhome.Util.Weather;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.Locale;
import java.util.TimeZone;

public class WeatherPageFragment extends Fragment {
    private SharedPreferences setting;
    private MainActivity mainActivity;
    private View rootView;

    private TextView tvUpdateDate;

    private ListAdapter la;

    private NonScrollListView list_view, lv;

    private Weather weather;

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {

        setting = getActivity().getSharedPreferences("account", 0);
        mainActivity = (MainActivity)getActivity();

        // Inflate the layout for this fragment
        rootView = inflater.inflate(R.layout.fragment_weather, container, false);

        // TextView
        tvUpdateDate = (TextView) rootView.findViewById(R.id.tvUpdateDate);

        // NonScrollListView
        list_view = (NonScrollListView) rootView.findViewById(R.id.list_view);
        lv = (NonScrollListView) rootView.findViewById(R.id.lv);
        //createLocationService();
        showData();
        return rootView;
    }
//
//    private void createLocationService() {
//        if (ActivityCompat.checkSelfPermission(getActivity(), Manifest.permission.ACCESS_FINE_LOCATION) == PackageManager.PERMISSION_GRANTED || ActivityCompat.checkSelfPermission(getActivity(), Manifest.permission.ACCESS_COARSE_LOCATION) == PackageManager.PERMISSION_GRANTED) {
//            mLocationManager = (LocationManager) getActivity().getSystemService(getContext().LOCATION_SERVICE);
//            if (mLocationManager.isProviderEnabled(LocationManager.GPS_PROVIDER)){
//                provider = mLocationManager.getProvider(LocationManager.NETWORK_PROVIDER);
//                Location location = mLocationManager.getLastKnownLocation(provider.getName());
//                setLocation(location);
//                mLocationManager.requestLocationUpdates(provider.getName(), 60000, 100, this);
//            }else{
//                if (setting.getBoolean("weatherSetting", true)) {
//                    showGPSDisabledAlertToUser();
//                }
//            }
//            showData();
//        }
//    }

    @Override
    public void unregisterForContextMenu(View view) {
        super.unregisterForContextMenu(view);
    }

//    private void showGPSDisabledAlertToUser(){
//        AlertDialog.Builder alertDialogBuilder = new AlertDialog.Builder(getActivity());
//        alertDialogBuilder.setMessage("GPS is disabled in your device. The Weather may not be accurate. Would you like to enable it?")
//                .setCancelable(false)
//                .setPositiveButton("Enable GPS",
//                        new DialogInterface.OnClickListener(){
//                            public void onClick(DialogInterface dialog, int id){
//                                Intent callGPSSettingIntent = new Intent(
//                                        android.provider.Settings.ACTION_LOCATION_SOURCE_SETTINGS);
//                                startActivity(callGPSSettingIntent);
//                            }
//                        });
//        alertDialogBuilder.setNegativeButton("Cancel",
//                new DialogInterface.OnClickListener(){
//                    public void onClick(DialogInterface dialog, int id){
//                        // Remember user cancel setting
//                        SharedPreferences.Editor editor = setting.edit();
//                        editor.putBoolean("weatherSetting", false);
//                        editor.commit();
//
//                        dialog.cancel();
//                    }
//                });
//        AlertDialog alert = alertDialogBuilder.create();
//        alert.show();
//    }

//    private void setLocation(Location location) {
//        if (location != null) {
//            SharedPreferences.Editor editor = setting.edit();
//            editor.putFloat("latitude", (float) location.getLatitude());
//            editor.putFloat("longitude", (float) location.getLongitude());
//            editor.commit();
//        }
//    }

    private void showData() {
        try {
//            float lat = setting.getFloat("latitude", 0);
//            float lon = setting.getFloat("longitude", 0);
//            if (lat == 0 && lon == 0) {
//                weather = new DBWeather();
//            } else {
//                weather = new OpenWeatherMapWeather(lat, lon);
//            }
            weather = new DBWeather();

            setChart();

            la = new MyListAdapter(mainActivity, getContext(), weather.getData(), "WeatherHolder", R.layout.fragment_weather_item);
            list_view.setAdapter(la);

            SimpleDateFormat sdf = new SimpleDateFormat("dd-MM-yyyy hh:mm:ss", Locale.ENGLISH);
            sdf.setTimeZone(TimeZone.getTimeZone("UTC"));

            // Get date
            long date = weather.getTimeStamp(1);
            Date transferDate = new Date(date * 1000);

            tvUpdateDate.setText(getString(R.string.fragmentWeather_updateDate) + sdf.format(transferDate));
        } catch (Exception e) {
            toTimeoutErrorActivity();
        }
    }

    private void setChart() {
        ArrayList<ChartItem> list = new ArrayList<ChartItem>();
        list.add(new LineChartItem(generateDataLine(ModelUtil.getWeatherTempData(weather, getActivity())), getString(R.string.modeUtil_Temperature), getActivity()));
        list.add(new LineChartItem(generateDataLine(ModelUtil.getWeatherHumData(weather, getActivity())), getString(R.string.modeUtil_Humidity), getActivity()));
        list.add(new LineChartItem(generateDataLine(ModelUtil.getWeatherPresData(weather, getActivity())), getString(R.string.modeUtil_Pressure), getActivity()));
        // list.add(new BarChartItem(generateDataBar(), getActivity().getApplicationContext()));
        // list.add(new PieChartItem(generateDataPie(3), getActivity().getApplicationContext()));

        ChartDataAdapter cda = new ChartDataAdapter(getActivity().getApplicationContext(), list);
        lv.setAdapter(cda);
    }

//    @Override
//    public void onPause() {
//        super.onPause();
//        if (ActivityCompat.checkSelfPermission(getActivity(), Manifest.permission.ACCESS_FINE_LOCATION) != PackageManager.PERMISSION_GRANTED && ActivityCompat.checkSelfPermission(getActivity(), Manifest.permission.ACCESS_COARSE_LOCATION) != PackageManager.PERMISSION_GRANTED) {
//            return;
//        }
//        if (mLocationManager != null) {
//            mLocationManager.removeUpdates(this);
//        }
//    }

    public void toTimeoutErrorActivity() {
        Intent intent = new Intent(getActivity(), TimeoutErrorActivity.class);
        getActivity().finish();
        startActivity(intent);
    }

//    @Override
//    public void onLocationChanged(Location location) {setLocation(location);}

//    @Override
//    public void onStatusChanged(String s, int i, Bundle bundle) {}
//
//    @Override
//    public void onProviderEnabled(String s) {}
//
//    @Override
//    public void onProviderDisabled(String s) {}

    // Generates a random ChartData object with just one DataSet
    private LineData generateDataLine(ArrayList<ChartItemList> chartItemList) {
        ArrayList<ILineDataSet> sets = new ArrayList<ILineDataSet>();

        for (int i = 0; i < chartItemList.size(); i++) {
            LineDataSet d = new LineDataSet(chartItemList.get(i).getItemList(), chartItemList.get(i).getName());
            d.setLineWidth(chartItemList.get(i).getLineWidth());
            d.setCircleRadius(chartItemList.get(i).getCircleRadius());
            d.setHighLightColor(chartItemList.get(i).getHighLightColor());
            if (chartItemList.get(i).getColor() != 0) {
                d.setColor(chartItemList.get(i).getColor());
            }
            if (chartItemList.get(i).getCircleColor() != 0) {
                d.setCircleColor(chartItemList.get(i).getCircleColor());
            }
            d.setDrawValues(false);

            sets.add(d);
        }

        LineData cd = new LineData(sets);
        return cd;
    }

    // Generates a random ChartData object with just one DataSet
//    private BarData generateDataBar(int cnt, ArrayList<BarEntry> entries) {
//
//        BarDataSet d = new BarDataSet(entries, "New DataSet " + cnt);
//        d.setColors(ColorTemplate.VORDIPLOM_COLORS);
//        d.setHighLightAlpha(255);
//
//        BarData cd = new BarData(d);
//        cd.setBarWidth(0.9f);
//        return cd;
//    }

    // Generates a random ChartData object with just one DataSet
//    private PieData generateDataPie(int cnt, ArrayList<PieEntry> entries) {
//
//        PieDataSet d = new PieDataSet(entries, "");
//
//        // Space between slices
//        d.setSliceSpace(2f);
//        d.setColors(ColorTemplate.VORDIPLOM_COLORS);
//
//        PieData cd = new PieData(d);
//        return cd;
//    }
}