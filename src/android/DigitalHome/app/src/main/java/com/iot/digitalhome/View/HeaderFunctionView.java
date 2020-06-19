package com.iot.digitalhome.View;

import android.app.Activity;
import android.view.View;
import android.widget.ExpandableListView;
import android.widget.ListView;

import com.iot.digitalhome.Adapter.HeaderFunctionAdapter;
import com.iot.digitalhome.Model.FunctionEntity;
import com.iot.digitalhome.R;
import com.iot.digitalhome.Util.MyExpandableListView;

import java.util.List;

import butterknife.BindView;
import butterknife.ButterKnife;

public class HeaderFunctionView extends HeaderViewInterface<List<FunctionEntity>> {

    @BindView(R.id.expandableListView)
    ExpandableListView expandableListView;

    private String userID;

    private HeaderFunctionAdapter headerFunctionAdapter;

    public HeaderFunctionView(Activity context, String userID) {
        super(context);
        this.userID = userID;
    }

    private int previousGroup = -1;

    @Override
    protected void getView(List<FunctionEntity> functions, ListView listView) {
        View view = mInflate.inflate(R.layout.header_function_layout, listView, false);
        ButterKnife.bind(this, view);

        dealWithTheView(functions);
        listView.addHeaderView(view);
    }

    private void dealWithTheView(List<FunctionEntity> list) {
        headerFunctionAdapter = new HeaderFunctionAdapter(mActivity, list, userID);
        expandableListView.setAdapter(headerFunctionAdapter);

        expandableListView.setOnGroupExpandListener(new MyExpandableListView.OnGroupExpandListener() {
            @Override
            public void onGroupExpand(int groupPosition) {
                if ((previousGroup != -1) && (groupPosition != previousGroup)) {
                    expandableListView.collapseGroup(previousGroup);
                }
                previousGroup = groupPosition;
            }
        });
    }

    public void refresh() {
        headerFunctionAdapter.refresh();

        if (previousGroup != -1) {
            expandableListView.collapseGroup(previousGroup);
            expandableListView.expandGroup(previousGroup);
        }
    }

    public HeaderFunctionAdapter getHeaderFunctionAdapter() {
        return headerFunctionAdapter;
    }
}
