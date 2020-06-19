package com.iot.digitalhome.Fragment;

import android.content.ActivityNotFoundException;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.os.Handler;
import android.speech.RecognizerIntent;
import android.support.design.widget.FloatingActionButton;
import android.support.v4.app.Fragment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AbsListView;
import android.widget.BaseAdapter;

import com.iot.digitalhome.Activity.MainActivity;
import com.iot.digitalhome.Adapter.FunctionAdapter;
import com.iot.digitalhome.Conn.Connection;
import com.iot.digitalhome.Conn.ConnectionFromOkHttp3;
import com.iot.digitalhome.Model.WeatherEntity;
import com.iot.digitalhome.Model.FunctionEntity;
import com.iot.digitalhome.Model.OperationEntity;
import com.iot.digitalhome.R;
import com.iot.digitalhome.Util.DensityUtil;
import com.iot.digitalhome.Util.ModelUtil;
import com.iot.digitalhome.Util.StatusBarUtil;
import com.iot.digitalhome.Util.ToastUtil;
import com.iot.digitalhome.View.HeaderBannerView;
import com.iot.digitalhome.View.HeaderFunctionView;
import com.iot.digitalhome.View.HeaderWeatherView;
import com.iot.digitalhome.View.HeaderDividerView;
import com.iot.digitalhome.View.HeaderOperationView;
import com.iot.digitalhome.View.SmoothListView.SmoothListView;
import com.pusher.client.Pusher;
import com.pusher.client.channel.Channel;
import com.pusher.client.channel.SubscriptionEventListener;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.List;

import butterknife.ButterKnife;

import static android.app.Activity.RESULT_OK;

public class HomePageFragment extends Fragment implements SmoothListView.ISmoothListViewListener {

    private static final int RESULT_SPEECH = 1;

    private SmoothListView smoothListView;
    // ----- Hide for the complementary -----
//    FilterView filterView;
    // ----- Hide for the complementary -----

    private SharedPreferences setting;
    private com.iot.digitalhome.Activity.MainActivity mainActivity;
    private View rootView;

//    private int mScreenHeight; // Height of screen

    private List<String> bannerList = new ArrayList<>(); // Advertising data
    private List<WeatherEntity> weatherList = new ArrayList<>(); // Weather data
    private List<OperationEntity> operationList = new ArrayList<>(); // Operation data
    private List<FunctionEntity> functionList = new ArrayList<>(); // Function data
    private List<FunctionEntity> list = new ArrayList<>(); // ListView data

    private HeaderBannerView headerBannerView; // Banner Image
    private HeaderWeatherView headerWeatherView; // Weather Image
    private HeaderOperationView headerOperationView; // Operation View
    private HeaderDividerView headerDividerView; // Split line occupying image
    private HeaderFunctionView headerFunctionView; //  Function View

//    private HeaderFilterView headerFilterView; // Category filter image
//    private FilterData filterData; // Filter image
    private BaseAdapter mAdapter; // Main page image

    private View itemHeaderBannerView; // Advertising in ListView
//    private View itemHeaderFilterView; // Header filter in ListView
    private boolean isScrollIdle = true; // Scroll in ListView
//    private boolean isStickyTop = false; // Weather in the top of the screen
//    private boolean isSmooth = false; // Scroll without sticky on top
//    private int titleViewHeight = 65; // Height of title bar
//    private int filterPosition = -1; // Click of the location with FilterView: Category (0), sorting (1), sorting (2)

    private int bannerViewHeight = 180; // Height of advertising
    private int bannerViewTopMargin; // Margin between top of screen and the image of advertising

//    private int filterViewPosition = 4; // FilterView location
//    private int filterViewTopMargin; // Margin between selection image and top of the screen

    private FloatingActionButton fab;

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        setting = getActivity().getSharedPreferences("account", 0);
        mainActivity = new MainActivity();

        // Inflate the layout for this fragment
        rootView = inflater.inflate(R.layout.fragment_home, container, false);

        smoothListView = (SmoothListView) rootView.findViewById(R.id.listView);
//        filterView = (FilterView) rootView.findViewById(R.id.filterView);

        ButterKnife.bind(getActivity());
        StatusBarUtil.setStatusBarTranslucent(getActivity(), false);

        initData();
        initView();
        initListener();

        headerBannerView.enqueueBannerLoopMessage();

        // FloatingActionButton
        fab = (FloatingActionButton) rootView.findViewById(R.id.fab);
        fab.setOnLongClickListener(new View.OnLongClickListener() {
            @Override
            public boolean onLongClick(View v) {
                try {
                    String language = "en-US";
                    Intent intent = new Intent(RecognizerIntent.ACTION_RECOGNIZE_SPEECH);
                    intent.putExtra(RecognizerIntent.EXTRA_LANGUAGE_MODEL,language);
                    intent.putExtra(RecognizerIntent.EXTRA_LANGUAGE, language);
                    intent.putExtra(RecognizerIntent.EXTRA_LANGUAGE_PREFERENCE, language);
                    intent.putExtra(RecognizerIntent.EXTRA_ONLY_RETURN_LANGUAGE_PREFERENCE, language);
                    intent.putExtra(RecognizerIntent.EXTRA_PROMPT, getString(R.string.main_speak_listen));
                    startActivityForResult(intent, RESULT_SPEECH);
                } catch (ActivityNotFoundException a) {
                    ToastUtil.show(getActivity(), getString(R.string.main_speak_nonSupport));
                }
                return false;
            }
        });

        //build pusher
        Pusher pusher = new Pusher(getString(R.string.pusher_key));

        Channel channel = pusher.subscribe(getString(R.string.pusher_channel_actuator));

        //when receiving message, do the action
        channel.bind(getString(R.string.pusher_channel_updateStatus), new SubscriptionEventListener() {
            @Override
            public void onEvent(String channelName, String eventName, String jsonData) {
                try {
                    headerFunctionView.getHeaderFunctionAdapter().setFunctions(ModelUtil.getFunctionData(mainActivity));
                    headerFunctionView.refresh();
                } catch (Exception e) {
                    e.printStackTrace();
                }
            }
        });

        pusher.connect();

        return rootView;
    }

    private void initData() {
        // Advertising data
        bannerList = ModelUtil.getBannerData();
        // Weather data
        weatherList = ModelUtil.getWeatherData(getActivity());
        // Operation data
        operationList = ModelUtil.getOperationData();
        //Function data
        functionList = ModelUtil.getFunctionData(mainActivity);
        // ListView data
        list = ModelUtil.getListData(mainActivity);
    }

    private void initView() {
        // Setting of advertising data
        headerBannerView = new HeaderBannerView(getActivity());
        headerBannerView.fillView(bannerList, smoothListView);

        // Setting of channel data
        headerWeatherView = new HeaderWeatherView(getActivity());
        headerWeatherView.fillView(weatherList, smoothListView);

        // Setting of header divider
        headerDividerView = new HeaderDividerView(getActivity());
        headerDividerView.fillView("Control", smoothListView);

        // Setting of function divider
        headerFunctionView = new HeaderFunctionView(getActivity(), setting.getString("userID", null));
        headerFunctionView.fillView(ModelUtil.getFunctionData(mainActivity), smoothListView);

        // Setting of ListView data
        mAdapter = new FunctionAdapter((MainActivity) getActivity(), list);
        smoothListView.setAdapter(mAdapter);

    }

    private void initListener() {

        smoothListView.setRefreshEnable(true);
        smoothListView.setSmoothListViewListener(this);
        smoothListView.setOnScrollListener(new SmoothListView.OnSmoothScrollListener() {
            @Override
            public void onSmoothScrolling(View view) {}

            @Override
            public void onScrollStateChanged(AbsListView view, int scrollState) {
                isScrollIdle = (scrollState == SCROLL_STATE_IDLE);
            }

            @Override
            public void onScroll(AbsListView view, int firstVisibleItem, int visibleItemCount, int totalItemCount) {
                if (isScrollIdle && bannerViewTopMargin < 0) return;

                // Get for the advertising header view, height, and the margin for the top of screen
                if (itemHeaderBannerView == null) {
                    itemHeaderBannerView = smoothListView.getChildAt(1-firstVisibleItem);
                }
                if (itemHeaderBannerView != null) {
                    bannerViewTopMargin = DensityUtil.px2dip(getActivity(), itemHeaderBannerView.getTop());
                    bannerViewHeight = DensityUtil.px2dip(getActivity(), itemHeaderBannerView.getHeight());
                }
            }
        });
    }

    @Override
    protected void finalize() throws Throwable {
        super.finalize();
        headerBannerView.removeBannerLoopMessage();
    }

    @Override
    public void onRefresh() {
        new Handler().postDelayed(new Runnable() {
            @Override
            public void run() {
                headerFunctionView.getHeaderFunctionAdapter().setFunctions(ModelUtil.getFunctionData(mainActivity));
                headerFunctionView.refresh();
                smoothListView.stopRefresh();
                smoothListView.setRefreshTime(getString(R.string.main_just));
            }
        }, 2000);
    }

    @Override
    public void onLoadMore() {}

    @Override
    public void onActivityResult(int requestCode, int resultCode, Intent data) {
        super.onActivityResult(requestCode, resultCode, data);

        switch (requestCode) {
            //received the speech message
            case RESULT_SPEECH:
                if (resultCode == RESULT_OK && null != data) {
                    ArrayList<String> text = data.getStringArrayListExtra(RecognizerIntent.EXTRA_RESULTS);
                    analyzeText(text.get(0));
                }
                break;
        }
    }

    private void analyzeText(String s) {
        if (s != null) {
            //check the actuator whether it exists
            try {
                String[] text = s.split(" ");
                Connection conn = new ConnectionFromOkHttp3(mainActivity.getPath() + "actuators");
                conn.send("GetRequest");
                String response = conn.getData();
                JSONObject jsonObject = new JSONObject(response);
                JSONArray jsonArray = jsonObject.getJSONArray("data");
                int originalLength = jsonArray.length();
                String action = null;
                for (int i = 0; i < text.length; i++) {
                    jsonArray = checkText(text[i], jsonArray);
                    if (action == null) {
                        action = checkAction(text[i]);
                    }
                }

                if (jsonArray.length() == 1) {
                    if (action != null) {
                        String actuatorID = jsonArray.getJSONObject(0).getString("ActuatorID");
                        String userID = setting.getString("userID", null);

                        //get actuators' data
                        conn = new ConnectionFromOkHttp3(mainActivity.getPath() + "actuators/" + actuatorID);
                        conn.send("GetRequest");
                        response = conn.getData();
                        jsonObject = new JSONObject(response).getJSONObject("data");

                        //check permission
                        if ((action == "1" && ( jsonObject.getInt("PermissionID") == 1 || jsonObject.getInt("PermissionID") == 3)) ||
                            action == "2" && ( jsonObject.getInt("PermissionID") == 1 || jsonObject.getInt("PermissionID") == 2)) {
                            ((MainActivity) getActivity()).speak(getString(R.string.main_noPermission));
                        } else {
                            // update actuators' status
                            conn = new ConnectionFromOkHttp3(mainActivity.getPath() + "actuators");
                            conn.post("userID", userID);
                            conn.post("actuatorID", actuatorID);
                            conn.post("status", action);
                            conn.send("PutRequest");
                            response = conn.getData();
                            jsonObject = new JSONObject(response);
                            //success
                            if (jsonObject.getString("status").equals("true")) {
                                ((MainActivity) getActivity()).speak(getString(R.string.main_update_success));
                            //failed
                            } else {
                                ((MainActivity) getActivity()).speak(getString(R.string.main_update_fail));
                            }
                        }
                    } else {
                        ((MainActivity) getActivity()).speak(getString(R.string.main_update_unknown));
                    }
                } else if (jsonArray.length() == originalLength) {
                    ((MainActivity) getActivity()).speak(getString(R.string.main_update_notFound));
                } else {
                    for (int i = 0; i < jsonArray.length(); i++) {
                        ((MainActivity) getActivity()).speak(getString(R.string.main_update_error));
                    }
                }

            } catch (Exception e) {
                new MainActivity().toTimeoutErrorActivity();
            }
        }
    }

    //check user action -- check keyword with "open/close and on/off" only
    private String checkAction(String text) {
        if (text.contains("open") || text.contains("on")) {
            return "1";
        } else if (text.contains("close") || text.contains("off")) {
            return "2";
        } else {
            return null;
        }
    }

    //analyze message text
    public JSONArray checkText(String text, JSONArray jsonArray) {
        JSONArray targetArray = new JSONArray();
        try {
            for (int i = 0; i < jsonArray.length(); i++) {
                String data = jsonArray.getJSONObject(i).toString();
                if (data.contains(text)) {
                    JSONObject item = new JSONObject(data);
                    targetArray.put(item);
                } else if (text.length() > 3) {
                    int begin = 0;
                    do {
                        if (checkChar(text.substring(begin, begin++ + 3), data)) {
                            JSONObject item = new JSONObject(data);
                            targetArray.put(item);
                            break;
                        }
                    } while (begin + 3 <= text.length());
                }
            }
        } catch (JSONException e) {
            e.printStackTrace();
        }
        return (targetArray.length() == 0)? jsonArray : targetArray;
    }

    private Boolean checkChar(String text, String data) {
        return data.contains(text);
    }
}