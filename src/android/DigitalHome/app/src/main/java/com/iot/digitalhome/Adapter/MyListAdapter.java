package com.iot.digitalhome.Adapter;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.ListAdapter;

import com.iot.digitalhome.Activity.MainActivity;
import com.iot.digitalhome.Util.Holder;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

public class MyListAdapter extends BaseAdapter implements ListAdapter {

    private MainActivity mainActivity;
    private LayoutInflater inflater;
    private JSONArray jsonArray;
    private Context mContext;
    private String holderName;
    private int layout;

    public MyListAdapter(MainActivity mainActivity, Context mContext, JSONArray jsonArray, String holderName, int layout) {
        this.mainActivity = mainActivity;
        this.mContext = mContext;
        this.jsonArray = jsonArray;
        this.holderName = holderName;
        this.layout = layout;
        inflater = LayoutInflater.from(mContext);
    }

    @Override
    public int getCount() {
        return jsonArray.length();
    }

    @Override
    public JSONObject getItem(int i) {
        JSONObject jsonObject = null;
        try {
            jsonObject = jsonArray.getJSONObject(i);
        } catch (JSONException e) {
            e.printStackTrace();
        }
        return jsonObject;
    }

    @Override
    public long getItemId(int i) {
        return i;
    }

    @Override
    public View getView(int position, View convertView, ViewGroup viewGroup) {
        Holder holder = null;
        if (convertView == null) {
            convertView = inflater.inflate(layout, viewGroup, false);
            try {
                holder = (Holder) Class.forName(mContext.getPackageName() + ".Util." + holderName).newInstance();
                convertView.setTag(holder);
                holder.setContext(mContext);
                holder.setMainActivity(mainActivity);
                holder.setValue(getItem(position), convertView);
                holder.show();
            } catch (InstantiationException e) {
                e.printStackTrace();
            } catch (IllegalAccessException e) {
                e.printStackTrace();
            } catch (ClassNotFoundException e) {
                e.printStackTrace();
            }
        } else {
            holder = (Holder) convertView.getTag();
            holder.show();
        }
        return convertView;
    }
}
