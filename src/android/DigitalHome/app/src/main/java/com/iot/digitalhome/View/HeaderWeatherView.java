package com.iot.digitalhome.View;

import android.app.Activity;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ListView;

import com.iot.digitalhome.Activity.MainActivity;
import com.iot.digitalhome.Adapter.HeaderWeatherAdapter;
import com.iot.digitalhome.R;
import com.iot.digitalhome.Model.WeatherEntity;

import java.util.List;

import butterknife.BindView;
import butterknife.ButterKnife;

public class HeaderWeatherView extends HeaderViewInterface<List<WeatherEntity>> {

    @BindView(R.id.gv_weather)
    FixedGridView gvWeather;

    public HeaderWeatherView(Activity context) {
        super(context);
    }

    @Override
    protected void getView(List<WeatherEntity> list, ListView listView) {
        View view = mInflate.inflate(R.layout.header_weather_layout, listView, false);
        ButterKnife.bind(this, view);

        dealWithTheView(list);
        listView.addHeaderView(view);
    }

    private void dealWithTheView(final List<WeatherEntity> list) {
        if (list == null || list.size() < 2) return;
        int size = list.size();
        if (size <= 4) {
            gvWeather.setNumColumns(size);
        } else if (size == 6) {
            gvWeather.setNumColumns(3);
        } else if (size == 8) {
            gvWeather.setNumColumns(4);
        } else {
            return;
        }

        final HeaderWeatherAdapter adapter = new HeaderWeatherAdapter(mActivity, list);
        gvWeather.setAdapter(adapter);

        gvWeather.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
                ((MainActivity) mActivity).speak("The" + adapter.getItem(position).getMessage() + " is " + adapter.getItem(position).getTitle());
            }
        });
    }

}
