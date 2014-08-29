package com.unicorn.project;

import java.io.ByteArrayOutputStream;

import com.unicorn.model.MovieModel;
import com.unicorn.model.UserModel;
import com.unicorn.utilities.Log;

import android.os.Bundle;
import android.os.Handler;
import android.os.Message;
import android.app.Activity;
import android.graphics.Bitmap;
import android.graphics.Bitmap.CompressFormat;
import android.graphics.BitmapFactory;
import android.view.Menu;

public class MainActivity extends Activity {

	UserModel userModel;
	MovieModel movieModel;
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_main);
		
		Handler handler = new Handler(){
			public void handleMessage(Message msg) {
				Log.d("handler");
			}
		};
		
//		userModel = new UserModel(this,Constant.PROTOCOL,Constant.DOMAIN_NAME,Constant.URL_BASE,Constant.COOKIE_TOKEN_NAME,Constant.SESSION_CRYPT_KEY,Constant.SESSION_CRYPT_IV);
//		Bitmap testBitmap = BitmapFactory.decodeResource(getResources(), R.drawable.ic_launcher);
//		ByteArrayOutputStream baos = new ByteArrayOutputStream();
//		testBitmap.compress(CompressFormat.JPEG, 100, baos);
//		byte[] imageData = baos.toByteArray();
//		userModel.setName("siosiosio");
//		userModel.saveWithProfileImage(imageData, handler);
//		Log.d("test","test");
		
		movieModel = new MovieModel(this,Constant.PROTOCOL,Constant.DOMAIN_NAME,Constant.URL_BASE,Constant.COOKIE_TOKEN_NAME,Constant.SESSION_CRYPT_KEY,Constant.SESSION_CRYPT_IV);
		Bitmap testBitmap = BitmapFactory.decodeResource(getResources(), R.drawable.ic_launcher);
		ByteArrayOutputStream baos = new ByteArrayOutputStream();
		testBitmap.compress(CompressFormat.JPEG, 100, baos);
		byte[] imageData = baos.toByteArray();
		movieModel.saveThumbnail(imageData, "1", handler);
	}

	@Override
	public boolean onCreateOptionsMenu(Menu menu) {
		// Inflate the menu; this adds items to the action bar if it is present.
		getMenuInflater().inflate(R.menu.main, menu);
		return true;
	}

}
