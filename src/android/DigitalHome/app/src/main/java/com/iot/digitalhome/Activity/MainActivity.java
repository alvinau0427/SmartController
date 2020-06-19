package com.iot.digitalhome.Activity;

import android.content.DialogInterface;
import android.content.Intent;
import android.content.SharedPreferences;
import android.net.Uri;
import android.os.Build;
import android.os.Bundle;
import android.speech.tts.TextToSpeech;
import android.speech.tts.UtteranceProgressListener;
import android.support.design.widget.NavigationView;
import android.support.v4.app.Fragment;
import android.support.v4.view.GravityCompat;
import android.support.v4.widget.DrawerLayout;
import android.support.v7.app.ActionBar;
import android.support.v7.app.ActionBarDrawerToggle;
import android.support.v7.app.AlertDialog;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.Toolbar;
import android.util.Log;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.ImageView;
import android.widget.TextView;

import com.bumptech.glide.Glide;
import com.bumptech.glide.load.resource.drawable.GlideDrawable;
import com.bumptech.glide.request.animation.GlideAnimation;
import com.bumptech.glide.request.target.SimpleTarget;
import com.iot.digitalhome.R;
import com.roughike.bottombar.BottomBar;
import com.roughike.bottombar.OnTabSelectListener;

import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.HashMap;
import java.util.Locale;

import cat.ereza.customactivityoncrash.CustomActivityOnCrash;
import de.hdodenhof.circleimageview.CircleImageView;

public class MainActivity extends AppCompatActivity implements NavigationView.OnNavigationItemSelectedListener, TextToSpeech.OnInitListener {

    private static final String TAG = "DigitalHome"; // Application name
    private String path = "http://fypgroup4.ddns.net/FYP/api/"; // Server path
    private SharedPreferences setting; // For getting account information saved

    private HashMap<Integer, String> items; // Bottom bar items
    private View headerView; //  Navigation header
    private DrawerLayout layout_drawer; // Navigation layout
    private NavigationView navigationView; // Navigation drawer view
    private TextView nav_tvName, nav_tvType;
    private CircleImageView nav_ivIcon; // User icon
    private Toolbar toolbar; // Mobile status bar
    private ActionBar actionBar; // Application bar
    private ActionBarDrawerToggle toggle; // Application bar toggle
    private BottomBar bottomBar; // Bottom bar tab
    private TextToSpeech mTts; // For handling the speak function
    private String message; //For speech message

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        // Enable the crash activity
        // ----- Hide for the complementary -----
//        // Enable the CustomErrorActivity and actvity_custom_error to the application restart action
//        CustomActivityOnCrash.setErrorActivityClass(CustomErrorActivity.class);
//        // The code of the crash testing
//        throw new RuntimeException("Boom!");
        // ----- Hide for the complementary -----
        CustomActivityOnCrash.setLaunchErrorActivityWhenInBackground(false);
        CustomActivityOnCrash.setRestartActivityClass(LoginActivity.class);
        CustomActivityOnCrash.setShowErrorDetails(false);
        CustomActivityOnCrash.setEnableAppRestart(true);
        CustomActivityOnCrash.setDefaultErrorActivityDrawable(R.drawable.img_digitalhome_crash);
        CustomActivityOnCrash.setErrorActivityClass(CustomErrorActivity.class);
        CustomActivityOnCrash.setEventListener(new CustomEventListener());
        CustomActivityOnCrash.install(this);

        // Basic setting
        setting = getSharedPreferences("account", 0);
        mTts = new TextToSpeech(getApplicationContext(), this);
        mTts.setOnUtteranceProgressListener(new UtteranceProgressListener() {
            @Override
            public void onStart(String s) {
            }

            @Override
            public void onDone(String s) {//STOP AND SHUTDOWN TTS WHEN COMPLETED TALKING
                try {
                    mTts.stop();
                } catch (Exception ignore) {
                }
            }

            @Override
            public void onError(String s) {
                mTts.stop();
            }
        });

        // ActionBar
        toolbar = (Toolbar) findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);
        actionBar = getSupportActionBar();
        actionBar.setHomeAsUpIndicator(R.drawable.ic_menu);
        actionBar.setDisplayHomeAsUpEnabled(true);

        //DrawerLayout
        layout_drawer = (DrawerLayout) findViewById(R.id.layout_drawer);
        toggle = new ActionBarDrawerToggle(this, layout_drawer, toolbar, R.string.navigation_drawer_open, R.string.navigation_drawer_close);
        layout_drawer.setDrawerListener(toggle);
        toggle.syncState();

        // NavigationView
        navigationView = (NavigationView) findViewById(R.id.nav_view);
        headerView = navigationView.getHeaderView(0);
        nav_ivIcon = (CircleImageView) headerView.findViewById(R.id.nav_ivIcon);
        nav_tvName = (TextView) headerView.findViewById(R.id.nav_tvName);
        nav_tvName.setText(setting.getString("name", null));
        nav_tvType = (TextView) headerView.findViewById(R.id.nav_tvType);
        nav_tvType.setText(setting.getString("type", null));
        navigationView.setNavigationItemSelectedListener(this);

        // Intent BottomBar
        Intent intent = getIntent();
        items = getFragment();
        bottomBar = (BottomBar) findViewById(R.id.bottomBar);
        int position = intent.getIntExtra("position", 0);
        String className = items.get(position);
        bottomBar.selectTabAtPosition(position);
        changeTab(className);

        bottomBar.setOnTabSelectListener(new OnTabSelectListener() {
            @Override
            public void onTabSelected(int tabId) {
                for (int i = 0; i < bottomBar.getTabCount(); i++) {
                    if (tabId == bottomBar.getTabAtPosition(i).getId()) {
                        changeTab(items.get(i));
                        break;
                    }
                }
            }
        });

        // Show image
        String image = setting.getString("image", "img_normal_user.jpg");
        showImageByInt(nav_ivIcon, getResources()
                .getIdentifier(image.substring(0, image.length() - 4), "drawable", getPackageName()));

        Calendar c = Calendar.getInstance();
        SimpleDateFormat sdf;

        sdf = new SimpleDateFormat("dd-MM-yyyy EEEE", Locale.ENGLISH);
        String strDate = sdf.format(c.getTime());

        sdf = new SimpleDateFormat("hh:mm aa", Locale.ENGLISH);
        String strTime = sdf.format(c.getTime());

        // Speak of the welcome message
        message = getString(R.string.main_welcome_message) + nav_tvName.getText().toString() + getString(R.string.main_welcome_end) +
                         getString(R.string.main_date_message) + strDate + getString(R.string.main_date_end) +
                         getString(R.string.main_time_message) + strTime + getString(R.string.main_time_end);
    }

    //get server Path
    public String getPath() {
        return path;
    }

    //change page tab
    private void changeTab(String className) {
        try {
            Fragment fragment = (Fragment) Class.forName(getPackageName() + ".Fragment." + className).newInstance();
            commitFragment(fragment);
        } catch (InstantiationException e) {
            e.printStackTrace();
        } catch (IllegalAccessException e) {
            e.printStackTrace();
        } catch (ClassNotFoundException e) {
            e.printStackTrace();
        }
    }

    //change fragment
    private void commitFragment(Fragment fragment){
        android.support.v4.app.FragmentTransaction fragmentTransaction = getSupportFragmentManager().beginTransaction();
        fragmentTransaction.replace(R.id.fragment_container, fragment);
        fragmentTransaction.commit();
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        // Inflate the menu and adds items to the action bar if it is present.
        getMenuInflater().inflate(R.menu.activity_option, menu);
        return true;
    }

    //menu
    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        int id = item.getItemId();

        switch (id) {
            case android.R.id.home: {
                super.onBackPressed();
                break;
            }
            case R.id.setting: {
                 Intent intent = new Intent(this, SettingsActivity.class);
                 startActivity(intent);
                break;
            }
        }
        return super.onOptionsItemSelected(item);
    }

    //change image method
    private void showImageByInt(final ImageView view, int layout) {
        Glide.with(this)
                .load(layout)
                .dontAnimate()
                .into(new SimpleTarget<GlideDrawable>() {
                    @Override
                    public void onResourceReady(GlideDrawable resource, GlideAnimation<? super GlideDrawable> glideAnimation) {
                        view.setImageDrawable(resource.getCurrent());
                    }
                });
    }

    //change image method
    private void showImageByUri(final ImageView view, Uri layout) {
        Glide.with(this)
                .load(layout)
                .dontAnimate()
                .into(new SimpleTarget<GlideDrawable>() {
                    @Override
                    public void onResourceReady(GlideDrawable resource, GlideAnimation<? super GlideDrawable> glideAnimation) {
                        view.setImageDrawable(resource.getCurrent());
                    }
                });
    }

    //keep fragment index
    public HashMap<Integer, String> getFragment() {
        HashMap<Integer, String> items = new HashMap<>();
        items.put(0, "HomePageFragment");
        items.put(1, "HeartPageFragment");
        items.put(2, "WeatherPageFragment");
        items.put(3, "LocationPageFragment");
        items.put(4, "PersonalPageFragment");
        return items;
    }

    //Navigation
    @SuppressWarnings("StatementWithEmptyBody")
    @Override
    public boolean onNavigationItemSelected(MenuItem item) {
        // Handle navigation view item clicks here.
        int id = item.getItemId();

        switch (id) {
            case R.id.nav_home: {
                changeTab(items.get(0));
                break;
            }
            case R.id.nav_logout: {
                // Reset all data
                SharedPreferences.Editor editor = setting.edit();
                editor.clear();
                editor.commit();

                Intent intent = new Intent(this, LoginActivity.class);
                startActivity(intent);
                break;
            }
        }

        layout_drawer.closeDrawer(GravityCompat.START);
        return true;
    }

    public static class CustomEventListener implements CustomActivityOnCrash.EventListener {
        @Override
        public void onLaunchErrorActivity() {
            Log.i(TAG, "onLaunchErrorActivity()");
        }

        @Override
        public void onRestartAppFromErrorActivity() {
            Log.i(TAG, "onRestartAppFromErrorActivity()");
        }

        @Override
        public void onCloseAppFromErrorActivity() {
            Log.i(TAG, "onCloseAppFromErrorActivity()");
        }
    }

    //detected error and forward to error page
    public void toTimeoutErrorActivity() {
        Intent intent = new Intent(this, TimeoutErrorActivity.class);
        this.finish();
        startActivity(intent);
    }

    //ask question for exit
    @Override
    public void onBackPressed() {
        AlertDialog.Builder builder = new AlertDialog.Builder(this);
        builder.setMessage(getString(R.string.exit));
        builder.setCancelable(false);

        builder.setNeutralButton("No", new DialogInterface.OnClickListener() {
            @Override
            public void onClick(DialogInterface dialog, int which) {

            }
        });

        builder.setPositiveButton("Yes", new DialogInterface.OnClickListener() {
            @Override
            public void onClick(DialogInterface dialog, int which) {
                SharedPreferences.Editor editor = setting.edit();
                editor.remove("weatherSetting");
                editor.commit();
                Intent intent = new Intent(Intent.ACTION_MAIN);
                intent.addCategory(Intent.CATEGORY_HOME);
                intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
                startActivity(intent);
            }
        });
        builder.create();
        builder.show();
    }

    //set up textToSpeech function
    @Override
    public void onInit(int status) {
        // Status can be either TextToSpeech.SUCCESS or TextToSpeech.ERROR
        if (status == TextToSpeech.SUCCESS) {
            // Set preferred language to US english.
            // Note that a language may not be available, and the result will indicate this.
            int result = mTts.setLanguage(Locale.US);

            if (result == TextToSpeech.LANG_MISSING_DATA || result == TextToSpeech.LANG_NOT_SUPPORTED) {
                // Language data is missing or the language is not supported.
                speak("Language is not available.");
            } else {
                if (setting.getInt("weatherNotification", 1) == 1) {
                    speak(message);
                }
            }
        } else {
            // Initialization failed.
            speak("Could not initialize TextToSpeech.");
            // Maybe its not installed so we prompt it to be installed
            Intent installIntent = new Intent();
            installIntent.setAction(
                    TextToSpeech.Engine.ACTION_INSTALL_TTS_DATA);
            startActivity(installIntent);
        }
    }

    //sound messages
    public void speak(String message) {
        if (message.length() > 0) {
            try {
                if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.LOLLIPOP) {
                    mTts.speak(message, TextToSpeech.QUEUE_ADD, null, "toastText");
                } else {
                    //noinspection deprecation
                    mTts.speak(message, TextToSpeech.QUEUE_ADD, null);
                }
            } catch (Exception ignore) {

            }
        }
    }
}
