package com.iot.digitalhome.Model;

public class ModuleEntity {
    private String image;
    private int actuatorID;
    private String deviceName;
    private boolean status;
    private int permissionID;
    private String permissionDescription;

    public ModuleEntity(String image, int actuatorID, String deviceName, boolean status, int permissionID, String permissionDescription) {
        this.image = image;
        this.actuatorID = actuatorID;
        this.deviceName = deviceName;
        this.status = status;
        this.permissionID = permissionID;
        this.permissionDescription = permissionDescription;
    }

    public String getImage() {
        return image;
    }

    public int getActuatorID() {
        return actuatorID;
    }

    public String getDeviceName() {
        return deviceName;
    }

    public boolean isStatus() {
        return status;
    }

    public void setPermissionID(int permissionID) {
        this.permissionID = permissionID;
    }

    public String getPermissionDescription() {
        return permissionDescription;
    }

    public void setImage(String image) {
        this.image = image;
    }

    public void setActuatorID(int actuatorID) {
        this.actuatorID = actuatorID;
    }

    public void setDeviceName(String deviceName) {
        this.deviceName = deviceName;
    }

    public void setStatus(boolean status) {
        this.status = status;
    }

    public int getPermissionID() {
        return permissionID;
    }

    public void setPermissionDescription(String permissionDescription) {
        this.permissionDescription = permissionDescription;
    }
}
