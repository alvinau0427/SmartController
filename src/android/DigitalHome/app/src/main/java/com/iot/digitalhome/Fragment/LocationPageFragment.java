package com.iot.digitalhome.Fragment;

import android.Manifest;
import android.content.SharedPreferences;
import android.content.pm.PackageManager;
import android.location.LocationListener;
import android.location.LocationManager;
import android.location.LocationProvider;
import android.os.Bundle;
import android.support.v4.app.ActivityCompat;
import android.support.v4.app.Fragment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;

import com.google.android.gms.maps.CameraUpdateFactory;
import com.google.android.gms.maps.GoogleMap;
import com.google.android.gms.maps.MapView;
import com.google.android.gms.maps.MapsInitializer;
import com.google.android.gms.maps.OnMapReadyCallback;
import com.google.android.gms.maps.model.BitmapDescriptorFactory;
import com.google.android.gms.maps.model.CameraPosition;
import com.google.android.gms.maps.model.LatLng;
import com.google.android.gms.maps.model.MarkerOptions;
import com.iot.digitalhome.Activity.MainActivity;
import com.iot.digitalhome.Conn.Connection;
import com.iot.digitalhome.Conn.ConnectionFromOkHttp3;
import com.iot.digitalhome.R;
import com.iot.digitalhome.Model.UserEntity;
import com.iot.digitalhome.Util.Location;

import java.util.ArrayList;
import java.util.concurrent.TimeoutException;

public class LocationPageFragment extends Fragment {

    private SharedPreferences setting;
    private LocationManager mLocationManager;
    private LocationProvider provider;

    // Show text content
    private MapView mView;
    private View rootView;
    private ArrayList<Location> locations;

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        setting = getActivity().getSharedPreferences("account", 0);

        // Inflate the layout for this fragment
        rootView = inflater.inflate(R.layout.fragment_location, container, false);
        mView = (MapView) rootView.findViewById(R.id.mView);
        mView.onCreate(savedInstanceState);
        mView.onResume();

        //check user type and follow the type to do different action
        try {
            UserEntity userEntity = (UserEntity) Class.forName(getActivity().getPackageName() + ".Model." + setting.getString("type", "User")).newInstance();
            MainActivity mainActivity = new MainActivity();
            userEntity.setData(mainActivity.getPath(), setting);
            locations = userEntity.getLocation();
        } catch (java.lang.InstantiationException e) {
            e.printStackTrace();
        } catch (IllegalAccessException e) {
            e.printStackTrace();
        } catch (ClassNotFoundException e) {
            e.printStackTrace();
        }

        createLocationService();

        mView = (MapView) rootView.findViewById(R.id.mView);
        mView.onCreate(savedInstanceState);
        mView.onResume();

        showMap();

        return rootView;
    }

//    private String getAddress(float latitude, float longitude) {
//        try {
//            Connection conn = new ConnectionFromOkHttp3("https://maps.googleapis.com/maps/api/geocode/json?latlng=" + latitude + "," + longitude + "&sensor=true&language=en&key=AIzaSyBKjiWG35Fm97wFP_iAaM0enPZJ3d5vgrY");
//            conn.send("GetRequest");
//            String response = conn.getData();
//            JSONObject jsonObject = new JSONObject(response);
//            String name = jsonObject.getJSONArray("results").getJSONObject(0).getString("formatted_address");
//            return name;
//        } catch (Exception e) {
//            return null;
//        }
//    }

    private void showMap() {
        try {
            MapsInitializer.initialize(getActivity().getApplicationContext());
        } catch (Exception e) {
            e.printStackTrace();
        }

        mView.getMapAsync(new OnMapReadyCallback() {
            @Override
            public void onMapReady(GoogleMap googleMap) {
                for (int i = 0; i < locations.size(); i++) {
                    MarkerOptions markerOptions = new MarkerOptions();
                    LatLng latLng = new LatLng(locations.get(i).getLatitude(), locations.get(i).getLongitude());
                    markerOptions.position(latLng);
                    markerOptions.title(locations.get(i).getUserName());
                    if (i == 0)
                        markerOptions.icon(BitmapDescriptorFactory.defaultMarker(BitmapDescriptorFactory.HUE_GREEN));
                    else
                        markerOptions.icon(BitmapDescriptorFactory.defaultMarker(BitmapDescriptorFactory.HUE_ORANGE));
                    googleMap.addMarker(markerOptions);
                }

                CameraPosition cameraPosition = new CameraPosition.Builder().target(new LatLng(locations.get(0).getLatitude(), locations.get(0).getLongitude())).zoom(15).build();
                googleMap.animateCamera(CameraUpdateFactory.newCameraPosition(cameraPosition));
                googleMap.getUiSettings().setZoomControlsEnabled(false);
                googleMap.getUiSettings().setMapToolbarEnabled(true);
            }
        });
    }


    private void createLocationService() {
        if (ActivityCompat.checkSelfPermission(getActivity(), Manifest.permission.ACCESS_FINE_LOCATION) == PackageManager.PERMISSION_GRANTED || ActivityCompat.checkSelfPermission(getActivity(), Manifest.permission.ACCESS_COARSE_LOCATION) == PackageManager.PERMISSION_GRANTED) {
            mLocationManager = (LocationManager) getActivity().getSystemService(getContext().LOCATION_SERVICE);
            android.location.Location location;
            if (mLocationManager.isProviderEnabled(LocationManager.GPS_PROVIDER)) {
                provider = mLocationManager.getProvider(LocationManager.NETWORK_PROVIDER);
                location = mLocationManager.getLastKnownLocation(provider.getName());
                setLocation(location);

                mLocationManager.requestLocationUpdates(provider.getName(), 60000, 100, new LocationListener() {
                    @Override
                    public void onLocationChanged(android.location.Location location) {
                        setLocation(location);
                        updateLocation(location.getLatitude(), location.getLongitude());
                    }

                    @Override
                    public void onStatusChanged(String provider, int status, Bundle extras) {

                    }

                    @Override
                    public void onProviderEnabled(String provider) {

                    }

                    @Override
                    public void onProviderDisabled(String provider) {

                    }
                });
            }
        }
    }

    private void setLocation(android.location.Location location) {
        if (location != null) {
            SharedPreferences.Editor editor = setting.edit();
            editor.putFloat("latitude", (float) location.getLatitude());
            editor.putFloat("longitude", (float) location.getLongitude());
            editor.commit();
        }
    }

    private void updateLocation(double latitude, double longitude) {
        try {
            MainActivity mainActivity = new MainActivity();
            Connection conn = new ConnectionFromOkHttp3(mainActivity.getPath() + "users/locations");
            conn.post("userID", setting.getString("userID", null));
            conn.post("latitude", latitude + "");
            conn.post("longitude", longitude + "");
            conn.send("PutRequest");
        } catch (TimeoutException e) {
            e.printStackTrace();
        }
    }
}
