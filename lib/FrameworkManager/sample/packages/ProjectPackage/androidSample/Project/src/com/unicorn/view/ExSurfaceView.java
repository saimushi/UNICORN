package com.unicorn.view;

import android.content.Context;
import android.content.res.Resources;
import android.view.SurfaceHolder;
import android.view.SurfaceView;
import android.view.SurfaceHolder.Callback;

public class ExSurfaceView extends SurfaceView implements Callback, Runnable {
    private SurfaceHolder holder;
    Resources res = this.getContext().getResources();

    public ExSurfaceView(Context context) {
        super(context);
        holder = getHolder();
        //callbackメソッドを登録
        holder.addCallback(this);
    }

    @Override
    public void surfaceChanged(SurfaceHolder holder, int f, int w, int h) {
    }

    @Override
    public void surfaceCreated(SurfaceHolder holder) {
    }

    @Override
    public void surfaceDestroyed(SurfaceHolder holder) {
    }

    @Override
    public void run() {
    }
}