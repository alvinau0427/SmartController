package com.iot.digitalhome.Util.ListViewItems;

import java.util.ArrayList;

public class ChartItemList {

    private ArrayList arrayList;
    private String name;
    private float lineWidth;
    private float circleRadius;
    private int highLightColor;
    private int color;
    private int circleColor;

    public ChartItemList(ArrayList arrayList, String name, Float lineWidth, Float circleRadius, int highLightColor) {
        this.arrayList = arrayList;
        this.name = name;
        this.lineWidth = lineWidth;
        this.circleRadius = circleRadius;
        this.highLightColor = highLightColor;
        this.color = 0;
        this.circleColor = 0;
    }

    public ArrayList getItemList() {
        return arrayList;
    }

    public String getName() {
        return name;
    }

    public float getLineWidth() {
        return lineWidth;
    }

    public void setLineWidth(float lineWidth) {
        this.lineWidth = lineWidth;
    }

    public float getCircleRadius() {
        return circleRadius;
    }

    public void setCircleRadius(float circleRadius) {
        this.circleRadius = circleRadius;
    }

    public int getHighLightColor() {
        return highLightColor;
    }

    public void setHighLightColor(int highLightColor) {
        this.highLightColor = highLightColor;
    }

    public void setColor(int color) {
        this.color = color;
    }

    public int getColor() {
        return color;
    }

    public void setCircleColor(int circleColor) {
        this.circleColor = circleColor;
    }

    public int getCircleColor() {
        return circleColor;
    }
}
