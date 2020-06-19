package com.iot.digitalhome.Model;

import java.io.Serializable;

public class OperationEntity implements Serializable {

    private String title;
    private String className;
    private int image_id;

    public OperationEntity() {
    }

    public String getClassName() {
        return className;
    }

    public void setClassName(String className) {
        this.className = className;
    }

    public OperationEntity(String title, String className, int image_id) {
        this.title = title;
        this.className = className;
        this.image_id = image_id;
    }

    public String getTitle() {
        return title;
    }

    public void setTitle(String title) {
        this.title = title;
    }

    public int getImage_id() {
        return image_id;
    }

    public void setImage_id(int image_id) {
        this.image_id = image_id;
    }
}
