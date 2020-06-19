package com.iot.digitalhome.Activity;

import android.app.Activity;
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

public class CustomErrorActivity extends AppCompatActivity {

    private TextView tvError, tvTitle, tvDetail;
    private Button btnReconnect;
    private Typeface fontBD, fontRG;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_custom_error);

        // CustomActivityOnCrash.getStackTraceFromIntent(getIntent()): gets the stack trace as a string
        // CustomActivityOnCrash.getAllErrorDetailsFromIntent(context, getIntent()): returns all error details including stacktrace as a string
        // CustomActivityOnCrash.getRestartActivityClassFromIntent(getIntent()): returns the class of the restart activity to launch, or null if none
        // CustomActivityOnCrash.getEventListenerFromIntent(getIntent()): returns the event listener that must be passed to restartApplicationWithIntent or closeApplication

        // Font
        fontBD = Typeface.createFromAsset(getAssets(),"fonts/Aller_Bd.ttf");
        fontRG = Typeface.createFromAsset(getAssets(),"fonts/Aller_Rg.ttf");

        // TextView
        tvError = (TextView) findViewById(R.id.tvError);
        tvError.setTypeface(fontRG);
        // ----- Hide for the complementary -----
//        tvTitle = (TextView) findViewById(R.id.tvTitle);
//        tvTitle.setTypeface(fontBD);
//        tvDetail = (TextView) findViewById(R.id.tvDetail);
//        tvDetail.setTypeface(fontRG);
//        tvDetail.setText(CustomActivityOnCrash.getStackTraceFromIntent(getIntent()));
        // -----Hide for the complementary -----

        // Button
        btnReconnect = (Button) findViewById(R.id.btnReconnect);
        btnReconnect.setTypeface(fontRG);

        final Class<? extends Activity> restartActivityClass = CustomActivityOnCrash.getRestartActivityClassFromIntent(getIntent());
        final CustomActivityOnCrash.EventListener eventListener = CustomActivityOnCrash.getEventListenerFromIntent(getIntent());

        if (restartActivityClass != null) {
            btnReconnect.setText(R.string.customError_restart);
            btnReconnect.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    Intent intent = new Intent(CustomErrorActivity.this, restartActivityClass);
                    CustomActivityOnCrash.restartApplicationWithIntent(CustomErrorActivity.this, intent, eventListener);
                }
            });
        } else {
            btnReconnect.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    CustomActivityOnCrash.closeApplication(CustomErrorActivity.this, eventListener);
                }
            });
        }
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
