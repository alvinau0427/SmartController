package com.iot.digitalhome.Fragment;

import android.content.SharedPreferences;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentManager;
import android.support.v4.app.FragmentTransaction;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;

import com.google.android.youtube.player.YouTubeInitializationResult;
import com.google.android.youtube.player.YouTubePlayer;
import com.google.android.youtube.player.YouTubePlayerSupportFragment;
import com.iot.digitalhome.Conn.Connection;
import com.iot.digitalhome.Conn.ConnectionFromOkHttp3;
import com.iot.digitalhome.R;
import com.iot.digitalhome.Util.ToastUtil;

import org.json.JSONArray;
import org.json.JSONObject;

//no be used
public class LivePageFragment extends Fragment implements YouTubePlayer.OnInitializedListener {

    public static final String LINK = "https://www.googleapis.com/youtube/v3/search?part=snippet&channelId=UClpQ20z0VHSJXheLWpqR-SA&eventType=live&type=video&key=AIzaSyBKjiWG35Fm97wFP_iAaM0enPZJ3d5vgrY";
    public static final String API_KEY = "AIzaSyBKjiWG35Fm97wFP_iAaM0enPZJ3d5vgrY";
    private YouTubePlayerSupportFragment youTubePlayerSupportFragment;
    private SharedPreferences setting;
    private View rootView;
    private String videoId;

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        setting = getActivity().getSharedPreferences("account", 0);

        // Inflate the layout for this fragment
        rootView = inflater.inflate(R.layout.fragment_live, container, false);

        // YoutubePlayerView
        youTubePlayerSupportFragment = new YouTubePlayerSupportFragment();

        getVideoID();

        return rootView;
    }

    private void getVideoID() {
        try {
            Connection conn = new ConnectionFromOkHttp3(LINK);
            conn.send("GetRequest");
            String response = conn.getData();
            JSONObject jsonObject = new JSONObject(response);
            JSONArray jsonArray = jsonObject.getJSONArray("items");

            videoId = jsonArray.getJSONObject(0).getString("videoId");
            youTubePlayerSupportFragment.initialize(API_KEY, this);
            FragmentManager fragmentManager = getFragmentManager();
            FragmentTransaction fragmentTransaction = fragmentManager.beginTransaction();
            fragmentTransaction.replace(R.id.fragment_youtube_player, youTubePlayerSupportFragment);
            fragmentTransaction.commit();

        } catch (Exception e) {
            ToastUtil.show(getActivity(), "Get Video Failed");
        }
    }

    @Override
    public void onInitializationSuccess(YouTubePlayer.Provider provider, YouTubePlayer youTubePlayer, boolean wasRestored) {
        if (!wasRestored) {
            youTubePlayer.cueVideo(videoId);
            youTubePlayer.setFullscreen(true);
        }
    }

    @Override
    public void onInitializationFailure(YouTubePlayer.Provider provider, YouTubeInitializationResult youTubeInitializationResult) {
        ToastUtil.show(getActivity(), "Failured to Initialize!");
    }
}