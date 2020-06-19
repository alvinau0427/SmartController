package com.iot.digitalhome.View;

import android.app.Activity;
import android.view.View;
import android.widget.ListView;

import com.iot.digitalhome.R;

import butterknife.BindView;
import butterknife.ButterKnife;

public class HeaderFilterView extends HeaderViewInterface<Object> implements FilterView.OnFilterClickListener {

    @BindView(R.id.fv_filter)
    FilterView fvFilter;

    public HeaderFilterView(Activity context) {
        super(context);
    }

    @Override
    protected void getView(Object obj, ListView listView) {
        View view = mInflate.inflate(R.layout.header_filter_layout, listView, false);
        ButterKnife.bind(this, view);

        dealWithTheView(obj);
        listView.addHeaderView(view);
    }

    // Get Filter View
    public FilterView getFilterView() {
        return fvFilter;
    }

    private void dealWithTheView(Object obj) {
        fvFilter.setOnFilterClickListener(this);
    }

    @Override
    public void onFilterClick(int position) {
        if (onFilterClickListener != null) {
            onFilterClickListener.onFilterClick(position);
        }
    }

    private OnFilterClickListener onFilterClickListener;
    public void setOnFilterClickListener(OnFilterClickListener onFilterClickListener) {
        this.onFilterClickListener = onFilterClickListener;
    }
    public interface OnFilterClickListener {
        void onFilterClick(int position);
    }

}
