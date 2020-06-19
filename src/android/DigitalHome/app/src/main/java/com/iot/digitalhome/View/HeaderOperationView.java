package com.iot.digitalhome.View;

import android.app.Activity;
import android.content.Intent;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ListView;

import com.google.android.youtube.player.YouTubeBaseActivity;
import com.iot.digitalhome.R;
import com.iot.digitalhome.Adapter.HeaderOperationAdapter;
import com.iot.digitalhome.Model.OperationEntity;

import java.util.List;

import butterknife.BindView;
import butterknife.ButterKnife;

public class HeaderOperationView extends HeaderViewInterface<List<OperationEntity>> {

    @BindView(R.id.gv_operation)
    FixedGridView gvOperation;

    public HeaderOperationView(Activity context) {
        super(context);
    }

    @Override
    protected void getView(List<OperationEntity> list, ListView listView) {
        View view = mInflate.inflate(R.layout.header_operation_layout, listView, false);
        ButterKnife.bind(this, view);

        dealWithTheView(list);
        listView.addHeaderView(view);
    }

    private void dealWithTheView(List<OperationEntity> list) {
        if (list == null || list.size() < 2 || list.size() > 6) return;
        if (list.size() % 2 != 0) return;

        final HeaderOperationAdapter adapter = new HeaderOperationAdapter(mActivity, list);
        gvOperation.setAdapter(adapter);

        gvOperation.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
                try {
                    YouTubeBaseActivity activity = (YouTubeBaseActivity) Class.forName(mActivity.getPackageName() + ".Activity." + adapter.getItem(position).getClassName()).newInstance();
                    Intent intent = new Intent(mActivity, activity.getClass());
                    mActivity.startActivity(intent);
                } catch (InstantiationException e) {
                    e.printStackTrace();
                } catch (IllegalAccessException e) {
                    e.printStackTrace();
                } catch (ClassNotFoundException e) {
                    e.printStackTrace();
                }
            }
        });
    }
}
