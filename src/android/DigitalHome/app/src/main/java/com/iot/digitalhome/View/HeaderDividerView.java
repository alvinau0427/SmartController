package com.iot.digitalhome.View;

import android.app.Activity;
import android.view.View;
import android.widget.ListView;

import com.iot.digitalhome.R;

import butterknife.BindView;
import butterknife.ButterKnife;
import me.gujun.android.taggroup.TagGroup;

public class HeaderDividerView extends HeaderViewInterface<String> {

    @BindView(R.id.tag_group)
    TagGroup tag_group;

    public HeaderDividerView(Activity context) {
        super(context);
    }

    @Override
    protected void getView(String s, ListView listView) {
        View view = mInflate.inflate(R.layout.header_divider_layout, listView, false);
        ButterKnife.bind(this, view);

        dealWithTheView(s);
        listView.addHeaderView(view);
    }

    private void dealWithTheView(String s) {
        tag_group.setTags(s);
    }
}
