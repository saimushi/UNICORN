package com.unicorn.project;

import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.os.Handler;
import android.view.Window;

public class SplashActivity extends Activity {

	public Handler hdl;
	public Runnable runnable;

	@Override
	protected void onCreate(Bundle savedInstanceState) {

		super.onCreate(savedInstanceState);
		requestWindowFeature(Window.FEATURE_NO_TITLE);
		setContentView(R.layout.splash);
	}
	
	@Override
	protected void onResume(){
		super.onResume();
		hdl = new Handler();
		runnable = new splashHandler();
		hdl.postDelayed(runnable, 1500);
	}
	
	@Override
	protected void onPause(){
		super.onPause();
		hdl.removeCallbacks(runnable);
	}

	class splashHandler implements Runnable {
		public void run() {
			if (!SplashActivity.this.isFinishing()) {
				Intent i = new Intent(getApplication(), MainActivity.class);
				startActivity(i);
				SplashActivity.this.finish();
			}
		}
	}
}