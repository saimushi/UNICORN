package com.unicorn.model;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.Iterator;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import com.loopj.android.http.JsonHttpResponseHandler;
import com.loopj.android.http.RequestParams;
import com.unicorn.project.Constant;
import com.unicorn.utilities.AsyncHttpClientAgent;
import com.unicorn.utilities.Log;
import com.unicorn.utilities.PublicFunction;

import android.content.Context;
import android.os.Handler;
import android.os.Message;

public class ModelBase {

	public enum loadResourceMode {
		myResource, listedResource, automaticResource,
	};

	public static String TAG = "ModelBase";
	public Context context;

	public String protocol;
	public String domain;
	public String urlbase;
	public String cryptKey;
	public String cryptIV;
	public int timeout;
	public String tokenKeyName;
	public String modelName;
	public String ID;
	public String myResourcePrefix;
	public int index;
	public int total;
	public ArrayList<HashMap<String, Object>> responseList;
	// 通信に関する変数
	public boolean replaced;
	public boolean requested;
	public int statusCode;
	// Blockでハンドラを受け取るバージョンの為に用意
	public Handler completionHandler;
	public Handler modelBaseHandler;

	public ModelBase(Context argContext) {
		context = argContext;
		protocol = "";
		domain = "";
		urlbase = "";
		cryptKey = "";
		cryptIV = "";
		timeout = 10;
		tokenKeyName = "";
		modelName = "";
		ID = null;
		myResourcePrefix = "me/";
		index = 0;
		total = 0;
		responseList = new ArrayList<HashMap<String, Object>>();
		replaced = false;
		requested = false;
		statusCode = 0;
		// Blockでハンドラを受け取るバージョンの為に用意
		completionHandler = null;
	}

	public ModelBase(Context argContext, String argProtocol, String argDomain, String argURLBase,
			String argTokenKeyName) {
		this(argContext);
		protocol = argProtocol;
		domain = argDomain;
		urlbase = argURLBase;
		tokenKeyName = argTokenKeyName;
	}

	public ModelBase(Context argContext, String argProtocol, String argDomain, String argURLBase,
			String argTokenKeyName, int argTimeout) {
		this(argContext);
		protocol = argProtocol;
		domain = argDomain;
		urlbase = argURLBase;
		tokenKeyName = argTokenKeyName;
		timeout = argTimeout;
	}

	public ModelBase(Context argContext, String argProtocol, String argDomain, String argURLBase,
			String argTokenKeyName, String argCryptKey, String argCryptIV) {
		this(argContext, argProtocol, argDomain, argURLBase, argTokenKeyName);
		cryptKey = argCryptKey;
		cryptIV = argCryptIV;
	}

	public ModelBase(Context argContext, String argProtocol, String argDomain, String argURLBase,
			String argTokenKeyName, String argCryptKey, String argCryptIV, int argTimeout) {
		this(argContext, argProtocol, argDomain, argURLBase, argTokenKeyName, argCryptKey,
				argCryptIV);
		timeout = argTimeout;
	}

	/* RESTfulURLの生成 */
	public String createURLString(String resourceId) {
		String url = "";
		if (null != resourceId) {
			// 更新(Put)
			url = String.format("%s://%s%s%s%s/%s.json", protocol, domain, urlbase,
					myResourcePrefix, modelName, resourceId);
		} else {
			// 新規(POST)
			url = String.format("%s://%s%s%s%s.json", protocol, domain, urlbase, myResourcePrefix,
					modelName);
		}
		return url;
	}

	/* モデルを参照する */
	public boolean load() {
		if (null == ID || "".equals(ID)) {
			// ID無指定は単一モデル参照エラー
			return false;
		}
		return load(loadResourceMode.myResource);
	}

	public boolean load(Handler argCompletionHandler) {
		if (null == ID) {
			// ID無指定は単一モデル参照エラー
			return false;
		}
		completionHandler = argCompletionHandler;
		return load(loadResourceMode.myResource);
	}

	public boolean load(loadResourceMode argLoadResourceMode) {

		switch (argLoadResourceMode) {
		case myResource:
			get(ID);
			break;
		case listedResource:
			get(null);
			break;
		case automaticResource:
			get(ID);
			break;
		default:
			break;
		}
		return true;
	}
	
	public boolean save(Handler argCompletionHandler) {
		if (null == ID) {
			// ID無指定は単一モデル参照エラー
			return false;
		}
		completionHandler = argCompletionHandler;
		return load(loadResourceMode.myResource);
	}

	public void get(String resourceId) {

		String url = createURLString(resourceId);
		AsyncHttpClientAgent.get(context, url, null,
				new JsonHttpResponseHandler() {
					@Override
					public void onSuccess(JSONObject response) {
						Log.v(TAG,"get->onSuccessJsonObject");
						try {
							responseList.add(createMapFromJSONObject(response));
							Log.v(TAG,"get->onSuccessJsonObject->pauseSuccess");
							Message msg = new Message();
							msg.arg1 = Constant.RESULT_OK;
							msg.obj = response;
							setModelData(msg);
						} catch (JSONException e) {
							e.printStackTrace();
						}
					}
					
					@Override
					public void onSuccess(JSONArray response) {
						Log.v(TAG,"get->onSuccessJsonArray");
						try {
							responseList = createArrayFromJSONArray(response);
							Log.v(TAG,"get->onSuccessJsonArray->pauseSuccess");
							Message msg = new Message();
							msg.arg1 = Constant.RESULT_OK;
							msg.obj = response;
							setModelData(msg);
						} catch (JSONException e) {
							e.printStackTrace();
						}			
					}

					@Override
					public void onFailure(Throwable e, JSONObject errorResponse) {
						String error = e.toString();
						Log.d(TAG + " error", error);
						Message msg = new Message();
						msg.arg1 = Constant.RESULT_FAILED;
						msg.obj = error;
						setModelData(msg);
					}

					@Override
					public void onFailure(Throwable e, JSONArray errorResponse) {
						String error = e.toString();
						Log.d(TAG + " error", error);
						Message msg = new Message();
						msg.arg1 = Constant.RESULT_FAILED;
						msg.obj = error;
						setModelData(msg);
					}

					@Override
					public void onFailure(Throwable e, String errorResponse) {
						Log.d(TAG + " error", errorResponse);
						Message msg = new Message();
						msg.arg1 = Constant.RESULT_FAILED;
						msg.obj = errorResponse;
						setModelData(msg);
					}
				});
	}
	
	public void setModelData(Message msg){
		
	}

	public ArrayList<HashMap<String,Object>> createArrayFromJSONArray(JSONArray data) throws JSONException {
		ArrayList<HashMap<String,Object>> array = new ArrayList<HashMap<String,Object>>();
		for(int i=0;i<data.length();i++){
			JSONObject jsonObject = data.getJSONObject(i);
			array.add(createMapFromJSONObject(jsonObject));
		}
		return array;
	}
	
	public HashMap<String,Object> createMapFromJSONObject(JSONObject data) throws JSONException {
		HashMap<String,Object> map = new HashMap<String,Object>();
		Iterator<?> keys = data.keys();

		while (keys.hasNext()) {
			String key = (String) keys.next();
			if (data.get(key) instanceof JSONObject) {
				map.put(key, createMapFromJSONObject((JSONObject)data.get(key)));
			}else if(data.get(key) instanceof JSONArray){
				map.put(key, createArrayFromJSONArray((JSONArray)data.get(key)));
			}else if(data.get(key) instanceof String){
				map.put(key, data.get(key));
			}
		}
		return map;
	}

	public void showRequestError(int argStatusCode) {
		String errorMsg = "通信がタイムアウトしました。\n\n電波状況の良い所で再度実行してみて下さい。";
		if (0 < argStatusCode) {
			errorMsg = "ご迷惑をお掛けします。\n\nサーバーが致命的なエラーを発生させました。\n最初からやり直すか、それでも改善しない場合はシステム管理会社に問い合わせをして下さい。";
			if (400 == argStatusCode) {
				errorMsg = "エラーコード400\n\nデータの入力にあやまりがあるか\nサーバー側の問題により、処理を正常に受付出来ませんでした。\n最初からやり直すか、それでも改善しない場合はシステム管理会社に問い合わせをして下さい。";
			}
			if (401 == argStatusCode) {
				errorMsg = "エラーコード401\n\n何らかの理由により、認証に失敗しました。\n最初からやり直すか、それでも改善しない場合はシステム管理会社に問い合わせをして下さい。";
			}
			if (404 == argStatusCode) {
				errorMsg = "エラーコード404\n\n要求したデータが既に存在しませんでした。\n最初からやり直すか、それでも改善しない場合はシステム管理会社に問い合わせをして下さい。";
			}
			if (503 == argStatusCode) {
				errorMsg = "エラーコード503\n\nご迷惑をお掛けします。\nサーバーが現在メンテナンス中です。\nしばらく経ってから再度実行して下さい。";
			}
		}

		if (context != null) {
			PublicFunction.showAlert(context, errorMsg);
		}
	}
}