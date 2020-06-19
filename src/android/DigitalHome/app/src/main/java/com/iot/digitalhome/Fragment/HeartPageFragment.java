package com.iot.digitalhome.Fragment;

import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.support.v4.view.ViewPager;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;

import com.iot.digitalhome.R;
import com.ogaclejapan.smarttablayout.SmartTabLayout;
import com.ogaclejapan.smarttablayout.utils.v4.FragmentPagerItemAdapter;
import com.ogaclejapan.smarttablayout.utils.v4.FragmentPagerItems;

public class HeartPageFragment extends Fragment implements ViewPager.OnPageChangeListener {

    private View rootView;
    private SmartTabLayout viewpagertab;
    private ViewPager viewPager;
    private FragmentPagerItemAdapter adapter;

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {

        // Inflate the layout for this fragment
        rootView = inflater.inflate(R.layout.fragment_heart, container, false);

        viewpagertab = (SmartTabLayout) rootView.findViewById(R.id.viewpagertab);

        //set tab
        adapter = new FragmentPagerItemAdapter(
                getChildFragmentManager(), FragmentPagerItems.with(getActivity())
                .add(getString(R.string.fragmentHeartRate_measure), (new HeartPageMeasureFragment()).getClass())
                .add(getString(R.string.fragmentHeartRate_history), (new HeartPageHistoryFragment()).getClass())
                .create());

        viewPager = (ViewPager) rootView.findViewById(R.id.viewpager);
        viewPager.setAdapter(adapter);
        viewpagertab.setViewPager(viewPager);
        viewpagertab.setOnPageChangeListener(this);

        return rootView;
    }

    @Override
    public void onPageScrolled(int position, float positionOffset, int positionOffsetPixels) {}

    @Override
    public void onPageSelected(int position) {}

    @Override
    public void onPageScrollStateChanged(int state) {}
}