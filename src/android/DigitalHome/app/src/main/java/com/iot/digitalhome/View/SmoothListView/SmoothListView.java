package com.iot.digitalhome.View.SmoothListView;

import android.content.Context;
import android.util.AttributeSet;
import android.view.MotionEvent;
import android.view.View;
import android.view.ViewTreeObserver.OnGlobalLayoutListener;
import android.view.animation.DecelerateInterpolator;
import android.widget.AbsListView;
import android.widget.AbsListView.OnScrollListener;
import android.widget.ListAdapter;
import android.widget.ListView;
import android.widget.RelativeLayout;
import android.widget.Scroller;
import android.widget.TextView;

import com.iot.digitalhome.R;

public class SmoothListView extends ListView implements OnScrollListener {

    private float mLastY = -1; // Save event y
    private Scroller mScroller; // Used for scroll back
    private OnScrollListener mScrollListener; // UserEntity's scroll listener

    // The interface to trigger refresh and load more.
    private ISmoothListViewListener mListViewListener;

    // -- Header view
    private SmoothListViewHeader mHeaderView;
    // Header view content, use it to calculate the Header's height. And hide it
    // When disable pull refresh.
    private RelativeLayout mHeaderViewContent;
    private TextView mHeaderTimeView;
    private int mHeaderViewHeight; // Header view's height
    private boolean mEnablePullRefresh = true;
    private boolean mPullRefreshing = false; // Is it refreshing

//    // -- Footer view
//    private SmoothListViewFooter mFooterView;
//    private boolean mEnablePullLoad;
//    private boolean mPullLoading;
//    private boolean mIsFooterReady = false;

    // Total list items, used to detect is at the bottom of list view.
    private int mTotalItemCount;

    // For mScroller, scroll back from header or footer.
    private int mScrollBack;
    private final static int SCROLLBACK_HEADER = 0;
    private final static int SCROLLBACK_FOOTER = 1;

    private final static int SCROLL_DURATION = 400; // Scroll back duration
    private final static int PULL_LOAD_MORE_DELTA = 50; // When pull up >= 50px
    // At bottom, trigger
    // load more.
    private final static float OFFSET_RADIO = 1.8f; // Support iOS like pull
    // Feature.

    // @param context
    public SmoothListView(Context context) {
        super(context);
        initWithContext(context);
    }

    public SmoothListView(Context context, AttributeSet attrs) {
        super(context, attrs);
        initWithContext(context);
    }

    public SmoothListView(Context context, AttributeSet attrs, int defStyle) {
        super(context, attrs, defStyle);
        initWithContext(context);
    }

    private void initWithContext(Context context) {
        mScroller = new Scroller(context, new DecelerateInterpolator());
        // XListView need the scroll event, and it will dispatch the event to user's listener (as a proxy).
        super.setOnScrollListener(this);

        // Init header view
        mHeaderView = new SmoothListViewHeader(context);
        mHeaderViewContent = (RelativeLayout) mHeaderView.findViewById(R.id.smoothlistview_header_content);
        mHeaderTimeView = (TextView) mHeaderView.findViewById(R.id.smoothlistview_header_time);
        addHeaderView(mHeaderView);

//        // Init footer view
//        mFooterView = new SmoothListViewFooter(context);

        // Init header height
        mHeaderView.getViewTreeObserver().addOnGlobalLayoutListener(
                new OnGlobalLayoutListener() {
                    @Override
                    public void onGlobalLayout() {
                        mHeaderViewHeight = mHeaderViewContent.getHeight();
                        getViewTreeObserver().removeGlobalOnLayoutListener(this);
                    }
                });
    }

    @Override
    public void setAdapter(ListAdapter adapter) {
        // Make sure XListViewFooter is the last footer view, and only add once.
//        if (mIsFooterReady == false) {
//            mIsFooterReady = true;
//            addFooterView(mFooterView);
//        }
        super.setAdapter(adapter);
    }

    /**
         * Enable or disable pull down refresh feature.
         *
         * @param enable
         */
    public void setRefreshEnable(boolean enable) {
        mEnablePullRefresh = enable;
        if (!mEnablePullRefresh) { // disable, hide the content
            mHeaderViewContent.setVisibility(View.INVISIBLE);
        } else {
            mHeaderViewContent.setVisibility(View.VISIBLE);
        }
    }

    /**
         * Enable or disable pull up load more feature.
         *
         * @param enable
         */
//    public void setLoadMoreEnable(boolean enable) {
//        mEnablePullLoad = enable;
//        if (!mEnablePullLoad) {
//            mFooterView.hide();
//            mFooterView.setOnClickListener(null);
//            // Make sure "pull up" don't show a line in bottom when listview with one page
//            setFooterDividersEnabled(false);
//        } else {
//            mPullLoading = false;
//            mFooterView.show();
//            mFooterView.setState(SmoothListViewFooter.STATE_NORMAL);
//            // Make sure "pull up" don't show a line in bottom when listview with one page
//            setFooterDividersEnabled(true);
//            // Both "pull up" and "click" will invoke load more.
//            mFooterView.setOnClickListener(new View.OnClickListener() {
//                @Override
//                public void onClick(View v) {
//                    startLoadMore();
//                }
//            });
//        }
//    }

    /**
         * Stop refresh, reset header view.
         */
    public void stopRefresh() {
        if (mPullRefreshing == true) {
            mPullRefreshing = false;
            resetHeaderHeight();
        }
    }

    /**
         * Stop load more, reset footer view.
         */
//    public void stopLoadMore() {
//        if (mPullLoading == true) {
//            mPullLoading = false;
//            mFooterView.setState(SmoothListViewFooter.STATE_NORMAL);
//        }
//    }

    /**
         * Set last refresh time
         *
         * @param time
         */
    public void setRefreshTime(String time) {
        mHeaderTimeView.setText(time);
    }

    private void invokeOnScrolling() {
        if (mScrollListener instanceof OnSmoothScrollListener) {
            OnSmoothScrollListener l = (OnSmoothScrollListener) mScrollListener;
            l.onSmoothScrolling(this);
        }
    }

    private void updateHeaderHeight(float delta) {
        mHeaderView.setVisiableHeight((int) delta
                + mHeaderView.getVisiableHeight());
        if (mEnablePullRefresh && !mPullRefreshing) { // Not refreshed. Update the arrow
            if (mHeaderView.getVisiableHeight() > mHeaderViewHeight) {
                mHeaderView.setState(SmoothListViewHeader.STATE_READY);
            } else {
                mHeaderView.setState(SmoothListViewHeader.STATE_NORMAL);
            }
        }
        setSelection(0); // Scroll to top each time
    }

    /**
         * Reset header view's height.
         */
    private void resetHeaderHeight() {
        int height = mHeaderView.getVisiableHeight();
        if (height == 0) // not visible.
            return;
        // Refreshing and header isn't shown fully. do nothing.
        if (mPullRefreshing && height <= mHeaderViewHeight) {
            return;
        }
        int finalHeight = 0; // Default: scroll back to dismiss header.
        // Is refreshing, just scroll back to show all the header.
        if (mPullRefreshing && height > mHeaderViewHeight) {
            finalHeight = mHeaderViewHeight;
        }
        mScrollBack = SCROLLBACK_HEADER;
        mScroller.startScroll(0, height, 0, finalHeight - height,
                SCROLL_DURATION);
        // Trigger computeScroll
        invalidate();
    }

//    private void updateFooterHeight(float delta) {
//        int height = mFooterView.getBottomMargin() + (int) delta;
//        if (mEnablePullLoad && !mPullLoading) {
//            if (height > PULL_LOAD_MORE_DELTA) { // Height enough to invoke load more.
//                mFooterView.setState(SmoothListViewFooter.STATE_READY);
//            } else {
//                mFooterView.setState(SmoothListViewFooter.STATE_NORMAL);
//            }
//        }
//        mFooterView.setBottomMargin(height);
//
////		setSelection(mTotalItemCount - 1);  // Scroll to bottom
//    }
//
//    private void resetFooterHeight() {
//        int bottomMargin = mFooterView.getBottomMargin();
//        if (bottomMargin > 0) {
//            mScrollBack = SCROLLBACK_FOOTER;
//            mScroller.startScroll(0, bottomMargin, 0, -bottomMargin, SCROLL_DURATION);
//            invalidate();
//        }
//    }
//
//    private void startLoadMore() {
//        mPullLoading = true;
//        mFooterView.setState(SmoothListViewFooter.STATE_LOADING);
//        if (mListViewListener != null) {
//            mListViewListener.onLoadMore();
//        }
//    }

    @Override
    public boolean onTouchEvent(MotionEvent ev) {
        if (mLastY == -1) {
            mLastY = ev.getRawY();
        }

        switch (ev.getAction()) {
            case MotionEvent.ACTION_DOWN:
                mLastY = ev.getRawY();
                break;
            case MotionEvent.ACTION_MOVE:
                final float deltaY = ev.getRawY() - mLastY;
                mLastY = ev.getRawY();
                if (getFirstVisiblePosition() == 0
                        && (mHeaderView.getVisiableHeight() > 0 || deltaY > 0)) {
                    // The first item is showing, header has shown or pull down.
                    updateHeaderHeight(deltaY / OFFSET_RADIO);
                    invokeOnScrolling();
                }
//                } else if (getLastVisiblePosition() == mTotalItemCount - 1
//                        && (mFooterView.getBottomMargin() > 0 || deltaY < 0)) {
//                    // Last item, already pulled up or want to pull up.
//                    updateFooterHeight(-deltaY / OFFSET_RADIO);
//                }
                break;
            default:
                mLastY = -1; // Reset
                if (getFirstVisiblePosition() == 0) {
                    // Invoke refresh
                    if (mEnablePullRefresh
                            && mHeaderView.getVisiableHeight() > mHeaderViewHeight) {
                        mPullRefreshing = true;
                        mHeaderView.setState(SmoothListViewHeader.STATE_REFRESHING);
                        if (mListViewListener != null) {
                            mListViewListener.onRefresh();
                        }
                    }
                    resetHeaderHeight();
                }
//                } else if (getLastVisiblePosition() == mTotalItemCount - 1) {
//                    // Invoke load more.
//                    if (mEnablePullLoad
//                            && mFooterView.getBottomMargin() > PULL_LOAD_MORE_DELTA
//                            && !mPullLoading) {
//                        startLoadMore();
//                    }
//                    resetFooterHeight();
//                }
                break;
        }
        return super.onTouchEvent(ev);
    }

    @Override
    public void computeScroll() {
        if (mScroller.computeScrollOffset()) {
            if (mScrollBack == SCROLLBACK_HEADER) {
                mHeaderView.setVisiableHeight(mScroller.getCurrY());
            }
//            } else {
//                mFooterView.setBottomMargin(mScroller.getCurrY());
//            }
            postInvalidate();
            invokeOnScrolling();
        }
        super.computeScroll();
    }

    @Override
    public void setOnScrollListener(OnScrollListener l) {
        mScrollListener = l;
    }

    @Override
    public void onScrollStateChanged(AbsListView view, int scrollState) {
        if (mScrollListener != null) {
            mScrollListener.onScrollStateChanged(view, scrollState);
        }
    }

    @Override
    public void onScroll(AbsListView view, int firstVisibleItem, int visibleItemCount, int totalItemCount) {
        // Send to user's listener
        mTotalItemCount = totalItemCount;
        if (mScrollListener != null) {
            mScrollListener.onScroll(view, firstVisibleItem, visibleItemCount,
                    totalItemCount);
        }
    }

    public void setSmoothListViewListener(ISmoothListViewListener l) {
        mListViewListener = l;
    }

    /**
         * You can listen ListView.OnScrollListener or this one. it will invoke
         * onSmoothScrolling when header/footer scroll back.
         */
    public interface OnSmoothScrollListener extends OnScrollListener {
        void onSmoothScrolling(View view);
    }

    /**
         * Implements this interface to get refresh / load more event.
         */
    public interface ISmoothListViewListener {
        void onRefresh();
        void onLoadMore();
    }
}
