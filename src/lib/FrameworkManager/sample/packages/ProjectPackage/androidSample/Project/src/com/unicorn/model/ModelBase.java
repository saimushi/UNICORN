package com.unicorn.model;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.Iterator;

import org.json.JSONException;
import org.json.JSONObject;

import com.unicorn.utilities.Log;
import com.unicorn.utilities.PublicFunction;

import android.content.Context;
import android.os.Handler;

public class ModelBase{
	
	public enum loadResourceMode{
		myResource,
		listedResource,
		automaticResource,
	};
	
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
    public ArrayList<HashMap<String,String>> list;
    // 通信に関する変数
    public boolean replaced;
    public boolean requested;
    public HashMap<String,String> response;
    public int statusCode;
    // Blockでハンドラを受け取るバージョンの為に用意
    public Handler completionHandler;

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
	    ID = "";
	    myResourcePrefix = "me/";
	    index = 0;
	    total = 0;
	    ArrayList<HashMap<String,String>> list = new ArrayList<HashMap<String,String>>();
	    replaced = false;
	    requested = false;
	    HashMap<String,String> response = new HashMap<String,String>();
	    statusCode = 0;
	    // Blockでハンドラを受け取るバージョンの為に用意
	    completionHandler = null;
	}

	public ModelBase(Context argContext,String argProtocol,String argDomain,String argURLBase,String argTokenKeyName) {
		this(argContext);
		protocol = argProtocol;
	    domain = argDomain;
	    urlbase = argURLBase;
	    tokenKeyName = argTokenKeyName;
	}
	
	public ModelBase(Context argContext,String argProtocol,String argDomain,String argURLBase,String argTokenKeyName,int argTimeout) {
		this(argContext);
		protocol = argProtocol;
	    domain = argDomain;
	    urlbase = argURLBase;
	    tokenKeyName = argTokenKeyName;
	    timeout = argTimeout;
	}
	
	public ModelBase(Context argContext,String argProtocol,String argDomain,String argURLBase,String argTokenKeyName,String argCryptKey,String argCryptIV) {
		this(argContext,argProtocol,argDomain,argURLBase,argTokenKeyName);
		cryptKey = argCryptKey;
		cryptIV = argCryptIV;
	}
	
	public ModelBase(Context argContext,String argProtocol,String argDomain,String argURLBase,String argTokenKeyName,String argCryptKey,String argCryptIV,int argTimeout) {
		this(argContext,argProtocol,argDomain,argURLBase,argTokenKeyName,argCryptKey,argCryptIV);
		timeout = argTimeout;
	}
	
	/* RESTfulURLの生成*/
	public String createURLString(String argProtocol,String argDomain,String argURLBase,String argMyResourcePrefix,String argModelName,String argResourceID){
	    String url = "";
	    if(null != argResourceID){
	        // 更新(Put)
	        url = String.format("%s://%s%s%s%s/%s.json", argProtocol, argDomain, argURLBase, argMyResourcePrefix, argModelName, argResourceID);
	    }
	    else{
	        // 新規(POST)
	        url = String.format("%s://%s%s%s%s.json", argProtocol, argDomain, argURLBase, argMyResourcePrefix, argModelName);
	    }
	    return url;
	}
	
	/* モデルを参照する */
	public boolean load(){
	    if(null == ID || "".equals(ID)){
	        // ID無指定は単一モデル参照エラー
	        return false;
	    }
	    return load(loadResourceMode.myResource);
	}
	
	public boolean load(Handler argCompletionHandler){
	    if(null == ID){
	        // ID無指定は単一モデル参照エラー
	        return false;
	    }
	    completionHandler = argCompletionHandler;
	    return load(loadResourceMode.myResource);
	}
	
	public boolean load(loadResourceMode argLoadResourceMode){
		return true;
	}

	
	public ModelBase createData(JSONObject data) throws JSONException {
		 Iterator<?> keys = data.keys();

	        while( keys.hasNext() ){
	            String key = (String)keys.next();
	            if(data.get(key) instanceof JSONObject ){

	            }
	        }
		return this;
	}
	
	public void showRequestError(int argStatusCode){
	    String errorMsg = "通信がタイムアウトしました。\n\n電波状況の良い所で再度実行してみて下さい。";
	    if(0 < argStatusCode){
	        errorMsg = "ご迷惑をお掛けします。\n\nサーバーが致命的なエラーを発生させました。\n最初からやり直すか、それでも改善しない場合はシステム管理会社に問い合わせをして下さい。";
	        if(400 == argStatusCode){
	            errorMsg = "エラーコード400\n\nデータの入力にあやまりがあるか\nサーバー側の問題により、処理を正常に受付出来ませんでした。\n最初からやり直すか、それでも改善しない場合はシステム管理会社に問い合わせをして下さい。";
	        }
	        if(401 == argStatusCode){
	            errorMsg = "エラーコード401\n\n何らかの理由により、認証に失敗しました。\n最初からやり直すか、それでも改善しない場合はシステム管理会社に問い合わせをして下さい。";
	        }
	        if(404 == argStatusCode){
	            errorMsg = "エラーコード404\n\n要求したデータが既に存在しませんでした。\n最初からやり直すか、それでも改善しない場合はシステム管理会社に問い合わせをして下さい。";
	        }
	        if(503 == argStatusCode){
	            errorMsg = "エラーコード503\n\nご迷惑をお掛けします。\nサーバーが現在メンテナンス中です。\nしばらく経ってから再度実行して下さい。";
	        }
	    }
	    
	    if(context != null){
	    	PublicFunction.showAlert(context,errorMsg);
	    }
	}
}