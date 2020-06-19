package com.iot.digitalhome.Model;

import java.io.Serializable;
import java.util.ArrayList;
import java.util.List;

public class FunctionEntity implements Serializable, Comparable<FunctionEntity> {

    private String title;
    private int functionID;
    private String image_url;

    public List<ModuleEntity> modules = new ArrayList<ModuleEntity>();

    // No data attributes
    private boolean isNoData = false;
    private int height;

    public FunctionEntity() {
    }

    public FunctionEntity(String title, int functionID, String image_url, List<ModuleEntity> modules) {
        this.title = title;
        this.functionID = functionID;
        this.image_url = image_url;
        this.modules = modules;
    }

    public int getHeight() {
        return height;
    }

    public void setHeight(int height) {
        this.height = height;
    }

    public boolean isNoData() {
        return isNoData;
    }

    public void setNoData(boolean noData) {
        isNoData = noData;
    }

    public String getTitle() {
        return title;
    }

    public void setTitle(String title) {
        this.title = title;
    }

    public int getFunctionID() {
        return functionID;
    }

    public void setFunctionID(int functionID) {
        this.functionID = functionID;
    }

    public String getImage_url() {
        return image_url;
    }

    public void setImage_url(String image_url) {
        this.image_url = image_url;
    }

    @Override
    public int compareTo(FunctionEntity another) {
        return this.getFunctionID() - another.getFunctionID();
    }
}
