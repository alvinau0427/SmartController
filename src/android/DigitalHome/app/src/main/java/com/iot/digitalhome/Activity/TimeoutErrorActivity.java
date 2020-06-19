package com.iot.digitalhome.Activity;

import android.content.DialogInterface;
import android.content.Intent;
import android.graphics.Typeface;
import android.os.Bundle;
import android.support.v7.app.AlertDialog;
import android.support.v7.app.AppCompatActivity;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;

import com.iot.digitalhome.R;

import cat.ereza.customactivityoncrash.CustomActivityOnCrash;

public class TimeoutErrorActivity extends AppCompatActivity {

    private TextView tvErrorMessage;
    private Button btnReconnect;
    private Typeface fontBD, fontRG;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_timeout_error);

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

        // TextView
        tvErrorMessage = (TextView) findViewById(R.id.tvErrorMessage);
        tvErrorMessage.setTypeface(fontRG);

        // Button
        btnReconnect = (Button) findViewById(R.id.btnReconnect);
        btnReconnect.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                toLoginActivity();
            }
        });
        btnReconnect.setTypeface(fontRG);
    }

    private void toLoginActivity() {
        Intent intent = new Intent(this, LoginActivity.class);
        this.finish();
        startActivity(intent);
    }

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
                finish();
            }
        });
        builder.create();
        builder.show();
    }
}
