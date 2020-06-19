package com.iot.digitalhome.Adapter;

import android.view.View;
import android.view.ViewGroup;
import android.widget.AbsListView;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.iot.digitalhome.Activity.MainActivity;
import com.iot.digitalhome.Conn.Connection;
import com.iot.digitalhome.Conn.ConnectionFromOkHttp3;
import com.iot.digitalhome.R;
import com.iot.digitalhome.Model.FunctionEntity;

import org.json.JSONArray;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.List;

import butterknife.BindView;
import butterknife.ButterKnife;

//for 3-party template debug only --- no use but necessary
public class FunctionAdapter extends BaseListAdapter<FunctionEntity> {

    private boolean isNoData;
    private int mHeight;
    public static final int ONE_SCREEN_COUNT = 8; // Display the layout in full screen
    public static final int ONE_REQUEST_COUNT = 10; // Request the value of one
    private MainActivity mainActivity;

    public FunctionAdapter(MainActivity mainActivity) {
        super(mainActivity);
        this.mainActivity = mainActivity;
    }

    public FunctionAdapter(MainActivity mainActivity, List<FunctionEntity> list) {
        super(mainActivity, list);
        this.mainActivity = mainActivity;
    }

    // Data Setting
    public void setData(List<FunctionEntity> list) {
        clearAll();
        addALL(list);

        isNoData = false;
        if (list.size() == 1 && list.get(0).isNoData()) {
            // Layout with none data
            isNoData = list.get(0).isNoData();
            mHeight = list.get(0).getHeight();
        } else {
            // Add a null data
            if (list.size() < ONE_SCREEN_COUNT) {
                addALL(createEmptyList(ONE_SCREEN_COUNT - list.size()));
            }
        }
        notifyDataSetChanged();
    }

    // Creates empty data that is less than one screen
    public List<FunctionEntity> createEmptyList(int size) {
        List<FunctionEntity> emptyList = new ArrayList<>();
        if (size <= 0) return emptyList;
        for (int i=0; i<size; i++) {
            emptyList.add(new FunctionEntity());
        }
        return emptyList;
    }

    @Override
    public View getView(int position, View convertView, ViewGroup parent) {

        // Normal data
        final ViewHolder holder;
        if (convertView != null && convertView instanceof LinearLayout) {
            holder = (ViewHolder) convertView.getTag();
        } else {
            convertView = mInflater.inflate(R.layout.item_travel, null);
            holder = new ViewHolder(convertView);
            convertView.setTag(holder);
        }

        final FunctionEntity entity = getItem(position);

        final String title = entity.getTitle();
        holder.tvTitle.setText(title);
        holder.tvRank.setText("Function ID：" + entity.getFunctionID());
        mImageManager.loadDrawableImage(entity.getImage_url(), holder.ivImage);

        return convertView;
    }

    static class ViewHolder {
        @BindView(R.id.ll_root_view)
        LinearLayout llRootView;
        @BindView(R.id.iv_image)
        ImageView ivImage;
        @BindView(R.id.tv_title)
        TextView tvTitle;
        @BindView(R.id.tv_rank)
        TextView tvRank;

        ViewHolder(View view) {
            ButterKnife.bind(this, view);
        }
    }
}
