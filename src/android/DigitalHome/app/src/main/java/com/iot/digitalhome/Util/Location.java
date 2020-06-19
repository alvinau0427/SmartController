package com.iot.digitalhome.Util;

import android.os.Parcel;
import android.os.Parcelable;

public class Location implements Parcelable {
    private String userName;
    private float latitude;
    private float longitude;

    public static final Creator<Location> CREATOR = new Creator<Location>() {
        @Override
        public Location createFromParcel(Parcel in) {
            Location location = new Location();
            location.setUserName(in.readString());
            location.setLatitude(in.readFloat());
            location.setLongitude(in.readFloat());
            return location;
        }

        @Override
        public Location[] newArray(int size) {
            return new Location[size];
        }
    };

    public String getUserName() {
        return userName;
    }

    public float getLatitude() {
        return latitude;
    }

    public float getLongitude() {
        return longitude;
    }

    public void setUserName(String userName) {
        this.userName = userName;
    }

    public void setLatitude(float latitude) {
        this.latitude = latitude;
    }

    public void setLongitude(float longitude) {
        this.longitude = longitude;
    }

    @Override
    public int describeContents() {
        return 0;
    }

    @Override
    public void writeToParcel(Parcel dest, int flags) {
        dest.writeString(userName);
        dest.writeFloat(latitude);
        dest.writeFloat(longitude);
    }
}
