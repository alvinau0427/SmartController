package com.iot.digitalhome.Model;

public class HeartRecordEntity {
    private int heartRateID;
    private String date;
    private int heartRate;
    private int userID;

    public HeartRecordEntity(int heartRateID, String date, int heartRate, int userID) {
        this.heartRateID = heartRateID;
        this.date = date;
        this.heartRate = heartRate;
        this.userID = userID;
    }

    public int getHeartRate() {
        return heartRate;
    }

    public int getHeartRateID() {
        return heartRateID;
    }

    public int getUserID() {
        return userID;
    }

    public String getDate() {
        return date;
    }

    public void setDate(String date) {
        this.date = date;
    }

    public void setHeartRate(int heartRate) {
        this.heartRate = heartRate;
    }

    public void setHeartRateID(int heartRateID) {
        this.heartRateID = heartRateID;
    }

    public void setUserID(int userID) {
        this.userID = userID;
    }
}
