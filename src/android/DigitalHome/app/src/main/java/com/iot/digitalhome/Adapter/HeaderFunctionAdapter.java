package com.iot.digitalhome.Adapter;

import android.content.Context;
import android.database.DataSetObserver;
import android.os.Handler;
import android.os.Message;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseExpandableListAdapter;
import android.widget.ImageView;
import android.widget.TextView;

import com.iot.digitalhome.Manager.ImageManager;
import com.iot.digitalhome.R;
import com.iot.digitalhome.Model.FunctionEntity;

import java.util.ArrayList;
import java.util.List;

public class HeaderFunctionAdapter extends BaseExpandableListAdapter {

    private Context context;
    private String userID;
    private List<FunctionEntity> functions = new ArrayList<FunctionEntity>();
    private ParentHolder parentHolder = null;
    private ChildHolder childHolder = null;
    private HeaderModuleAdapter headerModuleAdapter;

    public HeaderFunctionAdapter(Context context, List<FunctionEntity> functions, String userID) {
        this.context = context;
        this.functions = functions;
        this.userID = userID;
    }

    public void refresh() {
        notifyDataSetChanged();
    }

    public void setFunctions(List<FunctionEntity> functions) {
        this.functions = functions;
    }

    @Override
    public void registerDataSetObserver(DataSetObserver observer) {

    }

    @Override
    public void unregisterDataSetObserver(DataSetObserver observer) {

    }

    @Override
    public int getGroupCount() {
        return functions.size();
    }

    @Override
    public int getChildrenCount(int groupPosition) {
        return 1;
    }

    @Override
    public Object getGroup(int groupPosition) {
        return functions.get(groupPosition);
    }

    @Override
    public Object getChild(int groupPosition, int childPosition) {
        return functions.get(groupPosition);
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

        FunctionEntity entity = (FunctionEntity) getGroup(groupPosition);

        if (convertView == null) {
            LayoutInflater mInflater = (LayoutInflater) context.getSystemService(context.LAYOUT_INFLATER_SERVICE);
            convertView = mInflater.inflate(R.layout.fragment_home_item_parent, null);
            convertView.setHorizontalScrollBarEnabled(true);

            parentHolder = new ParentHolder();
            convertView.setTag(parentHolder);
        } else {
            parentHolder = (ParentHolder) convertView.getTag();
        }

        parentHolder.iv_image = (ImageView) convertView.findViewById(R.id.iv_image);
        ImageManager mImageManager = new ImageManager(context);
        mImageManager.loadDrawableImage(entity.getImage_url(), parentHolder.iv_image);

        parentHolder.tv_title = (TextView) convertView.findViewById(R.id.tv_title);
        parentHolder.tv_title.setText(entity.getTitle());

        parentHolder.tv_functionID = (TextView) convertView.findViewById(R.id.tv_functionID);
        parentHolder.tv_functionID.setText("Function ID：" + entity.getFunctionID());

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
            LayoutInflater inflater = (LayoutInflater) context.getSystemService(context.LAYOUT_INFLATER_SERVICE);
            convertView = inflater.inflate(R.layout.fragment_home_item_group_child, parent, false);
            childHolder = new ChildHolder();
            convertView.setTag(childHolder);
        } else {
            childHolder = (ChildHolder) convertView.getTag();
        }

        childHolder.modules = (RecyclerView) convertView.findViewById(R.id.modules);
        LinearLayoutManager layoutManager = new LinearLayoutManager(context, LinearLayoutManager.HORIZONTAL, false);
        childHolder.modules.setLayoutManager(layoutManager);

        headerModuleAdapter = new HeaderModuleAdapter(context, functions.get(groupPosition).modules, userID);
        childHolder.modules.setAdapter(headerModuleAdapter);

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
        static RecyclerView modules;
    }

    public static class ParentHolder {
        ImageView iv_image;
        TextView tv_title;
        TextView tv_functionID;
        ImageView image_indicator;
    }}
