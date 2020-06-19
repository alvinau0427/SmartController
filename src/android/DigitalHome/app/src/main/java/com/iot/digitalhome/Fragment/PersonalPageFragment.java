package com.iot.digitalhome.Fragment;

import android.content.SharedPreferences;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import com.bumptech.glide.Glide;
import com.bumptech.glide.load.resource.drawable.GlideDrawable;
import com.bumptech.glide.request.animation.GlideAnimation;
import com.bumptech.glide.request.target.SimpleTarget;
import com.iot.digitalhome.R;

import de.hdodenhof.circleimageview.CircleImageView;

public class PersonalPageFragment extends Fragment {

    private SharedPreferences setting;
    private View rootView;
    private TextView tvName, tvType, tvEmail;
    private CircleImageView cvIcon;

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {

        setting = getActivity().getSharedPreferences("account", 0);

        // Inflate the layout for this fragment
        rootView = inflater.inflate(R.layout.fragment_personal, container, false);

        // TextView
        tvName = (TextView) rootView.findViewById(R.id.tvName);
        tvName.setText(setting.getString("name", null));
        tvType = (TextView) rootView.findViewById(R.id.tvType);
        tvType.setText(setting.getString("type", null));
        tvEmail = (TextView) rootView.findViewById(R.id.tvEmail);
        tvEmail.setText(setting.getString("email", null));

        // CircleImageView
        cvIcon = (CircleImageView) rootView.findViewById(R.id.cvIcon);

        // Show user icon
        String image = setting.getString("image", getString(R.string.personalfragment_default_image));

        Glide.with(this)
            .load(getResources().getIdentifier(
                    image.substring(0, image.length() - 4), "drawable", getActivity().getPackageName()))
            .dontAnimate()
            .into(new SimpleTarget<GlideDrawable>() {
                @Override
                public void onResourceReady(GlideDrawable resource, GlideAnimation<? super GlideDrawable> glideAnimation) {
                    cvIcon.setImageDrawable(resource.getCurrent());
                }
            });

        return rootView;
    }
}