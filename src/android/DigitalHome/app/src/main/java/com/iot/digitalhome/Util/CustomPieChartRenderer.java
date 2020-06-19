package com.iot.digitalhome.Util;

import android.content.Context;
import android.graphics.Canvas;
import android.graphics.drawable.Drawable;
import android.support.v4.content.res.ResourcesCompat;

import com.github.mikephil.charting.animation.ChartAnimator;
import com.github.mikephil.charting.charts.PieChart;
import com.github.mikephil.charting.renderer.PieChartRenderer;
import com.github.mikephil.charting.utils.MPPointF;
import com.github.mikephil.charting.utils.ViewPortHandler;

public class CustomPieChartRenderer extends PieChartRenderer {
    private Context context;
    private int image;

    public CustomPieChartRenderer(PieChart chart, ChartAnimator animator,
                                  ViewPortHandler viewPortHandler) {
        super(chart, animator, viewPortHandler);
        context = chart.getContext();
    }

    @Override
    public void drawExtras(Canvas c) {
        super.drawExtras(c);
        drawImage(c);
    }

    public void setImage(int image) {
        this.image = image;
    }

    private void drawImage(Canvas c) {
        MPPointF center = mChart.getCenterCircleBox();

        Drawable d =  ResourcesCompat.getDrawable(context.getResources(), image, null);

        if(d != null) {
            float halfWidth = d.getIntrinsicWidth() / 5;
            float halfHeight = d.getIntrinsicHeight() / 5;

            d.setBounds((int) (center.x - halfWidth), (int) (center.y - halfHeight), (int) (center.x + halfWidth), (int) (center.y + halfHeight) );
            d.draw(c);
        }
    }
}
