package com.iot.digitalhome.Adapter;

import android.app.Activity;
import android.content.Context;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import com.iot.digitalhome.Model.HeartRecordEntity;
import com.iot.digitalhome.R;

import java.util.ArrayList;
import java.util.List;

public class HeartRecordDetailAdapter extends RecyclerView.Adapter<HeartRecordDetailAdapter.ViewHolder> {

    private Activity activity;
    private List<HeartRecordEntity> heartRecordEntity = new ArrayList<HeartRecordEntity>();

    public HeartRecordDetailAdapter(Activity activity, List<HeartRecordEntity> heartRecordEntity) {
        this.activity = activity;
        this.heartRecordEntity = heartRecordEntity;
    }

    @Override
    public ViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        LayoutInflater inflater = activity.getLayoutInflater();
        View cardView = inflater.inflate(R.layout.fragment_heart_child, null, false);
        ViewHolder viewHolder = new ViewHolder(cardView);
        viewHolder.tvDate = (TextView) cardView.findViewById(R.id.tvDate);
        viewHolder.tvHeartRate = (TextView) cardView.findViewById(R.id.tvHeartRate);
        return viewHolder;
    }

    @Override
    public void onBindViewHolder(ViewHolder holder, final int position) {
        holder.tvDate.setText(heartRecordEntity.get(position).getDate());
        holder.tvHeartRate.setText(heartRecordEntity.get(position).getHeartRate() + "");
    }

    @Override
    public int getItemCount() {
        return heartRecordEntity.size();
    }

    @Override
    public void onAttachedToRecyclerView(RecyclerView recyclerView) {
        super.onAttachedToRecyclerView(recyclerView);
    }

    public static class ViewHolder extends RecyclerView.ViewHolder {

        TextView tvDate;
        TextView tvHeartRate;

        public ViewHolder(View itemView) {
            super(itemView);
            tvDate = (TextView) itemView.findViewById(R.id.tvDate);
            tvHeartRate = (TextView) itemView.findViewById(R.id.tvHeartRate);
        }
    }
}
