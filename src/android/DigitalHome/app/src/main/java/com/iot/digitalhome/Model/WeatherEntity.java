package com.iot.digitalhome.Model;

import java.io.Serializable;

public class WeatherEntity implements Serializable {

    private String title;
    private String tips;
    private String message;
    private String image_url;

    public WeatherEntity() {
    }

    public WeatherEntity(String title, String tips, String message, String image_url) {
        this.title = title;
        this.tips = tips;
        this.message = message;
        this.image_url = image_url;
    }

    public String getTitle() {
        return title;
    }

    public void setTitle(String title) {
        this.title = title;
    }

    public String getTips() {
        return tips;
    }

    public void setTips(String tips) {
        this.tips = tips;
    }

    public void setMessage(String message) {
        this.message = message;
    }

    public String getMessage() {
        return message;
    }

    public String getImage_url() {
        return image_url;
    }

    public void setImage_url(String image_url) {
        this.image_url = image_url;
    }
}
