package com.iot.digitalhome.Adapter;

import android.app.Activity;
import android.content.Context;
import android.database.DataSetObserver;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseExpandableListAdapter;
import android.widget.ImageView;
import android.widget.TextView;

import com.iot.digitalhome.Manager.ImageManager;
import com.iot.digitalhome.Model.HeartRecordEntity;
import com.iot.digitalhome.R;

import java.util.ArrayList;
import java.util.List;

import me.gujun.android.taggroup.TagGroup;

public class HeartRecordAdapter extends BaseExpandableListAdapter {

    private Activity activity;
    private List<List<HeartRecordEntity>> heartRecordEntities = new ArrayList<List<HeartRecordEntity>>();
    private ParentHolder parentHolder = null;
    private ChildHolder childHolder = null;
    private HeartRecordDetailAdapter heartRecordDetailAdapter;

    public HeartRecordAdapter(Activity activity, List<List<HeartRecordEntity>> heartRecordEntities) {
        this.activity = activity;
        this.heartRecordEntities = heartRecordEntities;
    }

    public void refresh() {
        notifyDataSetChanged();
    }

    public void setHeartRecord(List<List<HeartRecordEntity>> heartRecordEntities) {
        this.heartRecordEntities = heartRecordEntities;
    }

    @Override
    public void registerDataSetObserver(DataSetObserver observer) {

    }

    @Override
    public void unregisterDataSetObserver(DataSetObserver observer) {

    }

    @Override
    public int getGroupCount() {
        return heartRecordEntities.size();
    }

    @Override
    public int getChildrenCount(int groupPosition) {
        return 1;
    }

    @Override
    public Object getGroup(int groupPosition) {
        return heartRecordEntities.get(groupPosition);
    }

    @Override
    public Object getChild(int groupPosition, int childPosition) {
        return heartRecordEntities.get(groupPosition);
    }

    @Override
    public long getGroupId(int groupPosition) {
        return groupPosition;
    }

    @Override
    public long getChildId(int groupPosition, int childPosition) {
        return childPosition;
    }

    @Override
    public boolean hasStableIds() {
        return true;
    }

    @Override
    public View getGroupView(int groupPosition, boolean isExpanded, View convertView, ViewGroup parent) {

        List<HeartRecordEntity> entity = (List<HeartRecordEntity>) getGroup(groupPosition);

        if (convertView == null) {
            LayoutInflater mInflater = activity.getLayoutInflater();
            convertView = mInflater.inflate(R.layout.fragment_heart_parent, null);
            convertView.setHorizontalScrollBarEnabled(true);

            parentHolder = new ParentHolder();
            convertView.setTag(parentHolder);
        } else {
            parentHolder = (ParentHolder) convertView.getTag();
        }

        parentHolder.tag_group = (TagGroup) convertView.findViewById(R.id.tag_group);
        parentHolder.tag_group.setTags(entity.get(0).getDate().substring(0, 7));

        ImageManager mImageManager = new ImageManager(activity);
        parentHolder.image_indicator = (ImageView) convertView.findViewById(R.id.image_indicator);
        if (isExpanded) {
            mImageManager.loadResImage(R.drawable.ic_keyboard_arrow_up_black_18dp, parentHolder.image_indicator);
        } else {
            mImageManager.loadResImage(R.drawable.ic_keyboard_arrow_down_black_18dp, parentHolder.image_indicator);
        }

        return convertView;
    }

    @Override
    public View getChildView(int groupPosition, int childPosition, boolean isLastChild, View convertView, ViewGroup parent) {

        if (convertView == null) {
            LayoutInflater inflater = activity.getLayoutInflater();
            convertView = inflater.inflate(R.layout.fragment_heart_group_child, parent, false);
            childHolder = new ChildHolder();
            convertView.setTag(childHolder);
        } else {
            childHolder = (ChildHolder) convertView.getTag();
        }

        childHolder.heartDetails = (RecyclerView) convertView.findViewById(R.id.heartDetails);
        LinearLayoutManager layoutManager = new LinearLayoutManager(activity, LinearLayoutManager.VERTICAL, false);
        childHolder.heartDetails.setLayoutManager(layoutManager);

        heartRecordDetailAdapter = new HeartRecordDetailAdapter(activity, heartRecordEntities.get(groupPosition));
        childHolder.heartDetails.setAdapter(heartRecordDetailAdapter);

        return convertView;
    }

    @Override
    public boolean isChildSelectable(int groupPosition, int childPosition) {
        return false;
    }

    @Override
    public boolean areAllItemsEnabled() {
        return false;
    }

    @Override
    public boolean isEmpty() {
        return false;
    }

    @Override
    public void onGroupExpanded(int groupPosition) {

    }

    @Override
    public void onGroupCollapsed(int groupPosition) {

    }

    @Override
    public long getCombinedChildId(long groupId, long childId) {
        return 0;
    }

    @Override
    public long getCombinedGroupId(long groupId) {
        return 0;
    }

    public static class ChildHolder {
        static RecyclerView heartDetails;
    }

    public static class ParentHolder {
        TagGroup tag_group;
        ImageView image_indicator;
    }
}
