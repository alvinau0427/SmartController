package com.iot.digitalhome.Manager;

import android.content.Context;
import android.net.Uri;
import android.widget.ImageView;

import com.bumptech.glide.Glide;
import com.iot.digitalhome.R;
import com.iot.digitalhome.View.GlideCircleTransform;

public class ImageManager {

    private Context mContext;
    public static final String ANDROID_RESOURCE = "android.resource://";
    public static final String SEPARATOR = "/";

    public ImageManager(Context context) {
        this.mContext = context;
    }

    // Change the resource ID to Uri
    public Uri resourceIdToUri(int resourceId) {
        return Uri.parse(ANDROID_RESOURCE + mContext.getPackageName() + SEPARATOR + resourceId);
    }

    // Load network pictures
    public void loadUrlImage(String url, ImageView imageView) {
        Glide.with(mContext)
                .load(url)
                .placeholder(R.color.font_black_6)
                .error(R.color.font_black_6)
                .crossFade()
                .into(imageView);
    }

    // Load the drawable image
    public void loadResImage(int resId, ImageView imageView) {
        Glide.with(mContext)
                .load(resId)
                .placeholder(R.color.font_black_6)
                .fitCenter()
                .error(R.color.font_black_6)
                .into(imageView);
    }

    // Load local image
    public void loadLocalImage(String path, ImageView imageView) {
        Glide.with(mContext)
                .load("file://" + path)
                .placeholder(R.color.font_black_6)
                .error(R.color.font_black_6)
                .crossFade()
                .into(imageView);
    }

    // Loads a network circle image
    public void loadCircleImage(String url, ImageView imageView) {
        Glide.with(mContext)
                .load(url)
                .placeholder(R.color.font_black_6)
                .error(R.color.font_black_6)
                .crossFade()
                .transform(new GlideCircleTransform(mContext))
                .into(imageView);
    }

    // Loads a drawable circular picture
    public void loadCircleResImage(int resId, ImageView imageView) {
        Glide.with(mContext)
                .load(resourceIdToUri(resId))
                .placeholder(R.color.font_black_6)
                .error(R.color.font_black_6)
                .crossFade()
                .transform(new GlideCircleTransform(mContext))
                .into(imageView);
    }

    // Load local circle image
    public void loadCircleLocalImage(String path, ImageView imageView) {
        Glide.with(mContext)
                .load("file://" + path)
                .placeholder(R.color.font_black_6)
                .error(R.color.font_black_6)
                .crossFade()
                .transform(new GlideCircleTransform(mContext))
                .into(imageView);
    }

    public void loadDrawableImage(String image, ImageView imageView) {
        Glide.with(mContext)
                .load(mContext.getResources().getIdentifier(
                        image.substring(0, image.length() - 4), "drawable", "com.iot.digitalhome"))
                .placeholder(R.color.font_black_6)
                .error(R.color.font_black_6)
                .crossFade()
                .into(imageView);
    }
}
