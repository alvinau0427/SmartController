package com.iot.digitalhome.View;

import android.app.Activity;
import android.graphics.drawable.Drawable;
import android.util.DisplayMetrics;
import android.view.MotionEvent;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;

import com.iot.digitalhome.Util.ToastUtil;

public class FloorPlanView implements View.OnTouchListener {

    private ViewGroup viewGroup;
    private Activity activity;
    private Drawable drawable;
    private float x1, x2, y1, y2;

    public FloorPlanView(ViewGroup viewGroup, Activity activity) {
        this.viewGroup = viewGroup;
        this.activity = activity;
    }

    public void fillView(ImageView view, Drawable drawable, float x1, float x2, float y1, float y2) {
        DisplayMetrics metrics = new DisplayMetrics();
        activity.getWindowManager().getDefaultDisplay().getMetrics(metrics);
        this.x1 = metrics.widthPixels * x1;
        this.x2 = metrics.widthPixels * x2;
        this.y1 = metrics.heightPixels * y1;
        this.y2 = metrics.heightPixels * y2;
        this.drawable = drawable;

        view.setLayoutParams(new ViewGroup.LayoutParams((int)(this.x2 - this.x1), (int)(this.y2 - this.y1)));
        view.setX(this.x2);
        view.setY(this.y1);
        view.setImageDrawable(drawable);
        view.setOnTouchListener(this);
        viewGroup.addView(view);
    }

    @Override
    public boolean onTouch(View v, MotionEvent event) {
        switch (event.getAction()) {
            case MotionEvent.ACTION_DOWN:
                if (((ImageView)v).getDrawable() == drawable) {
                    ((ImageView)v).setImageDrawable(null);
                    ToastUtil.show(activity, "Closed");
                } else {
                    ((ImageView)v).setImageDrawable(drawable);
                    ToastUtil.show(activity, "Started");
                }
                break;
        }
        return false;
    }
}
