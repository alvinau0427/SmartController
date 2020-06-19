package com.iot.digitalhome.Adapter;

import android.content.Context;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.CompoundButton;
import android.widget.ImageView;
import android.widget.Switch;
import android.widget.TextView;

import com.iot.digitalhome.Activity.MainActivity;
import com.iot.digitalhome.Conn.Connection;
import com.iot.digitalhome.Conn.ConnectionFromOkHttp3;
import com.iot.digitalhome.Manager.ImageManager;
import com.iot.digitalhome.Model.ModuleEntity;
import com.iot.digitalhome.R;

import java.util.ArrayList;
import java.util.List;
import java.util.concurrent.TimeoutException;

import me.gujun.android.taggroup.TagGroup;

public class HeaderModuleAdapter extends RecyclerView.Adapter<HeaderModuleAdapter.ViewHolder> {

    private Context context;
    private List<ModuleEntity> modules = new ArrayList<ModuleEntity>();
    private ImageManager mImageManager;
    private String userID;

    public HeaderModuleAdapter(Context context, List<ModuleEntity> modules, String userID) {
        this.context = context;
        this.modules = modules;
        this.userID = userID;
        mImageManager = new ImageManager(context);
    }

    @Override
    public ViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        LayoutInflater inflater = (LayoutInflater) context.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
        View cardView = inflater.inflate(R.layout.fragment_home_item_child, null, false);
        ViewHolder viewHolder = new ViewHolder(cardView);
        viewHolder.iv_image = (ImageView) cardView.findViewById(R.id.iv_image);
        viewHolder.tvActuatorID = (TextView) cardView.findViewById(R.id.tvActuatorID);
        viewHolder.tvDeviceName = (TextView) cardView.findViewById(R.id.tvDeviceName);
        viewHolder.sw = (Switch) cardView.findViewById(R.id.sw);
        viewHolder.tag_group = (TagGroup) cardView.findViewById(R.id.tag_group);
        return viewHolder;
    }

    @Override
    public void onBindViewHolder(final ViewHolder holder, final int position) {
        mImageManager.loadDrawableImage(modules.get(position).getImage(), holder.iv_image);
        holder.tvActuatorID.setText(modules.get(position).getActuatorID() + "");
        holder.tvDeviceName.setText(modules.get(position).getDeviceName() + "");

        Switch sw = holder.sw;
        sw.setChecked(modules.get(position).isStatus());

        sw.setOnCheckedChangeListener(new CompoundButton.OnCheckedChangeListener() {
            @Override
            public void onCheckedChanged(CompoundButton compoundButton, boolean status) {
                int iStatus = (status)? 1:2;

                //check permission
                if ((iStatus == 1 && ( modules.get(position).getPermissionID() == 1 || modules.get(position).getPermissionID() == 3))) {
                    holder.sw.setChecked(false);
                } else if (iStatus == 2 && ( modules.get(position).getPermissionID() == 1 || modules.get(position).getPermissionID() == 2)) {
                    holder.sw.setChecked(true);
                } else {
                    //update actuator's status
                    try {
                        MainActivity mainActivity = new MainActivity();
                        Connection conn = new ConnectionFromOkHttp3(mainActivity.getPath() + "actuators");
                        conn.post("userID", userID);
                        conn.post("actuatorID", modules.get(position).getActuatorID() + "");
                        conn.post("status", iStatus + "");
                        conn.send("PutRequest");
                    } catch (TimeoutException e) {
                        e.printStackTrace();
                    }
                }
            }
        });

        holder.tag_group.setTags(modules.get(position).getPermissionDescription());
    }

    public void setModules(List<ModuleEntity> modules) {
        this.modules = modules;
        notifyDataSetChanged();
    }

    @Override
    public int getItemCount() {
        return modules.size();
    }

    @Override
    public void onAttachedToRecyclerView(RecyclerView recyclerView) {
        super.onAttachedToRecyclerView(recyclerView);
    }

    public static class ViewHolder extends RecyclerView.ViewHolder {

        ImageView iv_image;
        TextView tvActuatorID;
        TextView tvDeviceName;
        Switch sw;
        TagGroup tag_group;

        public ViewHolder(View itemView) {
            super(itemView);
            iv_image = (ImageView) itemView.findViewById(R.id.iv_image);
            tvActuatorID = (TextView) itemView.findViewById(R.id.tvActuatorID);
            tvDeviceName = (TextView) itemView.findViewById(R.id.tvDeviceName);
            sw = (Switch) itemView.findViewById(R.id.sw);
            tag_group = (TagGroup) itemView.findViewById(R.id.tag_group);
        }
    }
}
