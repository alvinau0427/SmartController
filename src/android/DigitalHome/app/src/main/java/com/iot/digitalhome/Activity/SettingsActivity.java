package com.iot.digitalhome.Activity;

import android.app.Activity;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.Toolbar;
import android.widget.ListAdapter;

import com.iot.digitalhome.Adapter.MyListAdapter;
import com.iot.digitalhome.R;
import com.iot.digitalhome.Util.ModelUtil;
import com.iot.digitalhome.Util.NonScrollListView;

public class SettingsActivity extends AppCompatActivity {

    private Toolbar toolbar; // Mobile status bar
    private NonScrollListView list_view_A;
    private NonScrollListView list_view_P;
    private ListAdapter la;
    private MainActivity mainActivity;
    private SharedPreferences setting; // For getting account information saved

    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_settings);

        setting = getSharedPreferences("account", 0);
        mainActivity = new MainActivity();

        // ActionBar
        toolbar = (Toolbar) findViewById(R.id.toolbar);
        toolbar.setTitle(R.string.main_tag_setting);
        setSupportActionBar(toolbar);
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);

        //Account Setting
        list_view_A = (NonScrollListView) findViewById(R.id.list_view_A);
        la = new MyListAdapter(mainActivity, this, ModelUtil.getUserData(mainActivity, Integer.valueOf(setting.getString("userID", null)), this), "SettingHolder", R.layout.activity_settings_item);
        list_view_A.setAdapter(la);

        //Preference Setting
        list_view_P = (NonScrollListView) findViewById(R.id.list_view_P);
        la = new MyListAdapter(mainActivity, this, ModelUtil.getPreferenceData(setting, this), "SettingPreferenceHolder", R.layout.activity_settings_item);
        list_view_P.setAdapter(la);
    }
}
