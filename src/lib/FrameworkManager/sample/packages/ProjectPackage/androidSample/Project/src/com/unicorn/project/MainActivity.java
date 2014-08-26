package com.unicorn.project;

import com.unicorn.model.UserModel;
import com.unicorn.utilities.Log;

import android.os.Bundle;
import android.app.Activity;
import android.view.Menu;

public class MainActivity extends Activity {

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_main);
		
		UserModel userModel = new UserModel(this,Constant.PROTOCOL,Constant.DOMAIN_NAME,Constant.URL_BASE,Constant.COOKIE_TOKEN_NAME,Constant.SESSION_CRYPT_KEY,Constant.SESSION_CRYPT_IV);
		userModel.load();
	}

	@Override
	public boolean onCreateOptionsMenu(Menu menu) {
		// Inflate the menu; this adds items to the action bar if it is present.
		getMenuInflater().inflate(R.menu.main, menu);
		return true;
	}

}
