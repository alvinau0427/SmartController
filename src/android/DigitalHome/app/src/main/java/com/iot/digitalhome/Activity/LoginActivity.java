package com.iot.digitalhome.Activity;

import android.annotation.TargetApi;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.SharedPreferences;
import android.graphics.Typeface;
import android.os.Build;
import android.os.Bundle;
import android.support.v7.app.AlertDialog;
import android.support.v7.app.AppCompatActivity;
import android.util.Log;
import android.view.View;
import android.widget.CheckBox;
import android.widget.EditText;
import android.widget.RelativeLayout;

import com.bumptech.glide.Glide;
import com.bumptech.glide.load.resource.drawable.GlideDrawable;
import com.bumptech.glide.request.animation.GlideAnimation;
import com.bumptech.glide.request.target.SimpleTarget;
import com.dd.CircularProgressButton;
import com.google.firebase.iid.FirebaseInstanceId;
import com.iot.digitalhome.R;
import com.iot.digitalhome.Util.AsteriskPasswordTransformationMethod;
import com.iot.digitalhome.Conn.Connection;
import com.iot.digitalhome.Conn.ConnectionFromOkHttp3;
import com.iot.digitalhome.Util.ToastUtil;

import org.json.JSONException;
import org.json.JSONObject;

import java.security.MessageDigest;
import java.security.NoSuchAlgorithmException;
import java.util.concurrent.TimeoutException;

import cat.ereza.customactivityoncrash.CustomActivityOnCrash;
import de.hdodenhof.circleimageview.CircleImageView;

public class LoginActivity extends AppCompatActivity implements View.OnClickListener, View.OnFocusChangeListener {

    SharedPreferences setting;
    private EditText etUserName, etPassword;
    private CheckBox cboKeep;
    private CircleImageView ivIcon;
    private CircularProgressButton btnOK;
    private RelativeLayout activity_login;
    private Typeface fontBD, fontRG;

    com.iot.digitalhome.Activity.MainActivity mainPage = new com.iot.digitalhome.Activity.MainActivity();

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login);

        // Enable the crash activity
        CustomActivityOnCrash.setLaunchErrorActivityWhenInBackground(false);
        CustomActivityOnCrash.setRestartActivityClass(LoginActivity.class);
        CustomActivityOnCrash.setShowErrorDetails(false);
        CustomActivityOnCrash.setEnableAppRestart(true);
        CustomActivityOnCrash.setDefaultErrorActivityDrawable(R.drawable.img_digitalhome_crash);
        CustomActivityOnCrash.setErrorActivityClass(CustomErrorActivity.class);
        CustomActivityOnCrash.setEventListener(new MainActivity.CustomEventListener());
        CustomActivityOnCrash.install(this);

        // Font
        fontBD = Typeface.createFromAsset(getAssets(),"fonts/Aller_Bd.ttf");
        fontRG = Typeface.createFromAsset(getAssets(),"fonts/Aller_Rg.ttf");

        // EditText
        etUserName = (EditText) findViewById(R.id.etUserAccount);
        etUserName.setOnClickListener(this);
        etUserName.setOnFocusChangeListener(this);
        etUserName.setTypeface(fontRG);
        etPassword = (EditText) findViewById(R.id.etPassword);
        etPassword.setOnClickListener(this);
        etPassword.setTransformationMethod(new AsteriskPasswordTransformationMethod());
        etPassword.setTypeface(fontRG);

        // CheckBox
        cboKeep = (CheckBox) findViewById(R.id.cboKeep);
        cboKeep.setTypeface(fontBD);

        // CircleImageView
        ivIcon = (CircleImageView) findViewById(R.id.ivIcon);

        // CircularProgressButton
        btnOK = (CircularProgressButton) findViewById(R.id.btnOK);
        btnOK.setOnClickListener(this);
        btnOK.setIndeterminateProgressMode(true);
        btnOK.setTypeface(fontBD);

        // RelativeLayout
        activity_login = (RelativeLayout) findViewById(R.id.activity_login);

        // Show background
        Glide.with(this)
                .load(R.drawable.img_login_background)
                .fitCenter()
                .dontAnimate()
                .into(new SimpleTarget<GlideDrawable>() {
                    @TargetApi(Build.VERSION_CODES.JELLY_BEAN)
                    @Override
                    public void onResourceReady(GlideDrawable resource, GlideAnimation<? super GlideDrawable> glideAnimation) {
                        activity_login.setBackground(resource.getCurrent());
                    }
                });

        // Check SharedPreferences
        setting = getSharedPreferences("account", 0);
        if (setting.getString("account", null) != null && setting.getString("password", null) != null) {
            etUserName.setText(setting.getString("account", null));
            etPassword.setText(setting.getString("password", null));
            btnOK.callOnClick();
        }
    }

    @Override
    public void onClick(View view) {
        switch(view.getId()) {
            //reset progress to 0 if the user does not click the OK button
            case R.id.etUserAccount:
            case R.id.etPassword:
                btnOK.setProgress(0);
                break;

            //the user clicks the OK button
            case R.id.btnOK:
                btnOK.setProgress(50);
                String account = etUserName.getText().toString();
                String password = getHash(etPassword.getText().toString());
                //check empty
                if (account.length() != 0 && password.length() != 0) {
                    try {
                        //validate account
                        Connection conn = new ConnectionFromOkHttp3(mainPage.getPath() + "users/authentication");
                        conn.post("account", account);
                        conn.post("password", password);
                        conn.send("PostRequest");
                        String response = conn.getData();
                        JSONObject jsonObject = new JSONObject(response);
                        //success
                        if (jsonObject.getString("status").equals("true")) {
                            btnOK.setProgress(100);
                            String userID = jsonObject.getJSONObject("data").getString("UserID");
                            updateToken(userID);
                            //keep account to application for automatic login
                            setData(cboKeep.isChecked(), jsonObject);
                            toMainActivity();
                        //failed
                        } else {
                            throw new Exception();
                        }
                    //server connect failed
                    } catch (TimeoutException e) {
                        ToastUtil.show(this, getString(R.string.timeout_network));
                        etPassword.setText("");
                        etPassword.setFocusable(true);
                        btnOK.setProgress(0);
                    //wrong account or password
                    } catch (Exception e) {
                        ToastUtil.show(this, getString(R.string.login_check));
                        btnOK.setProgress(0);
                    }
                //empty input
                } else {
                    ToastUtil.show(this, getString(R.string.login_checkInput));
                    btnOK.setProgress(0);
                }
                break;
        }
    }

    //login successfully and forward to main activity
    private void toMainActivity() {
        Intent intent = new Intent(this, MainActivity.class);
        this.finish();
        startActivity(intent);
    }

    //update token to server for receiving notifications
    private void updateToken(String userID) {
        String token = FirebaseInstanceId.getInstance().getToken();
        try {
            Connection conn = new ConnectionFromOkHttp3(mainPage.getPath() + "users/token");
            conn.post("userID", userID);
            conn.post("token", token);
            conn.send("PutRequest");
            String response = conn.getData();
            JSONObject jsonObject = new JSONObject(response);
            if (jsonObject.getString("status").equals("false")) {
                throw new Exception();
            }
        } catch (Exception e) {
            toTimeoutErrorActivity();
        }
    }

    //keep account to application for automatic login
    private void setData(Boolean isCheck, JSONObject jsonObject) {
        try {
            String userID = jsonObject.getJSONObject("data").getString("UserID");
            String name = jsonObject.getJSONObject("data").getString("UserName");
            String type = jsonObject.getJSONObject("data").getString("UserTypeDescription");
            String account = etUserName.getText().toString();
            String password = etPassword.getText().toString();
            String email = jsonObject.getJSONObject("data").getString("Email");
            String image = jsonObject.getJSONObject("data").getString("Image");
            String receiveNotification = jsonObject.getJSONObject("data").getString("ReceiveNotification");
            String receiveEmail = jsonObject.getJSONObject("data").getString("ReceiveEmail");
            String locationDisplay = jsonObject.getJSONObject("data").getString("LocationDisplay");

            setUserLocation(userID);

            if (isCheck) {
                SharedPreferences.Editor editor = setting.edit();
                editor.putString("userID", userID);
                editor.putString("name", name);
                editor.putString("type", type);
                editor.putString("account", account);
                editor.putString("password", password);
                editor.putString("email", email);
                editor.putString("image", image);
                editor.putString("receiveNotification", receiveNotification);
                editor.putString("receiveEmail", receiveEmail);
                editor.putString("locationDisplay", locationDisplay);
                editor.commit();
            }

        } catch (JSONException e) {
            e.printStackTrace();
        }
    }

    //get user location for no GPS detected
    private void setUserLocation(String userID) {
        try {
            Connection conn = new ConnectionFromOkHttp3(mainPage.getPath() + "users/locations/" + userID);
            conn.send("GetRequest");
            String response = conn.getData();
            JSONObject jsonObject = new JSONObject(response);
            double latitude = jsonObject.getJSONObject("data").getDouble("Latitude");
            double longitude = jsonObject.getJSONObject("data").getDouble("Longitude");

            SharedPreferences.Editor editor = setting.edit();
            editor.putFloat("latitude", (float) latitude);
            editor.putFloat("longitude", (float) longitude);
            editor.commit();

        } catch (TimeoutException e) {
            e.printStackTrace();
        } catch (JSONException e) {
            e.printStackTrace();
        }
    }

    // Generate a hash
    private static String getHash(String value) {
        MessageDigest digest;
        String hash = "";
        try {
            digest = MessageDigest.getInstance("SHA-256");
            digest.update(value.getBytes());
            hash = bytesToHexString(digest.digest());
        } catch (NoSuchAlgorithmException e1) {
            // TODO Auto-generated catch block
            e1.printStackTrace();
        }
        return hash;
    }

    // Utility function
    private static String bytesToHexString(byte[] bytes) {
        // Reference -> http://stackoverflow.com/questions/332079
        StringBuffer sb = new StringBuffer();
        for (int i = 0; i < bytes.length; i++) {
            String hex = Integer.toHexString(0xFF & bytes[i]);
            if (hex.length() == 1) {
                sb.append('0');
            }
            sb.append(hex);
        }
        String h = sb.toString();
        h = h.substring(0, 10) + "a" + h.substring(11, 20) + "a" + h.substring(21);
        return h;
    }

    //change the user's icon
    @Override
    public void onFocusChange(View view, boolean isFocus) {
        if (!isFocus) {
            String account = etUserName.getText().toString();
            try {
                Connection conn = new ConnectionFromOkHttp3(mainPage.getPath() + "users/image/" + account);
                conn.send("GetRequest");
                String response = conn.getData();
                JSONObject jsonObject = new JSONObject(response);
                String image = jsonObject.getJSONObject("data").getString("Image");
                if (!(image.equals(""))) {
                    Glide.with(this)
                            .load(getResources().getIdentifier(
                                    image.substring(0, image.length() - 4), "drawable", getPackageName()))
                            .dontAnimate()
                            .into(new SimpleTarget<GlideDrawable>() {
                                @Override
                                public void onResourceReady(GlideDrawable resource, GlideAnimation<? super GlideDrawable> glideAnimation) {
                                    ivIcon.setImageDrawable(resource.getCurrent());
                                }
                            });
                }
            } catch (TimeoutException e) {
                ToastUtil.show(this, getString(R.string.timeout_network));
            } catch (JSONException e) {
                Glide.with(this)
                        .load(R.drawable.img_normal_user)
                        .dontAnimate()
                        .into(new SimpleTarget<GlideDrawable>() {
                            @Override
                            public void onResourceReady(GlideDrawable resource, GlideAnimation<? super GlideDrawable> glideAnimation) {
                                ivIcon.setImageDrawable(resource.getCurrent());
                            }
                        });
            }
        }
    }

    public static class CustomEventListener implements CustomActivityOnCrash.EventListener {
        private static final String TAG = "";

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

    //detect error and forward to error page
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
}