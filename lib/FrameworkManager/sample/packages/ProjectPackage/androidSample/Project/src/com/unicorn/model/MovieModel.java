package com.unicorn.model;

import java.util.HashMap;

import android.content.Context;
import android.os.Handler;

/**
* UserModelはUserテーブルのデータを参照、保存するためのクラスです
* @author　c1363
*/
public class MovieModel extends ModelBase {

	public static String TAG = "MovieModel";
	public String thumbnail;
	public String url;
	
	public boolean thumbnail_replaced;
	public boolean url_replaced;

	/**
	 * コンストラクタです
	 * ModelBaseのコンストラクタを呼び出しmodelNameに「movie」をセットします
	 * @param argContext Contextが入っています
	 */
	public MovieModel(Context argContext) {
		super(argContext);
		modelName = "movie";
	}

	/**
	 * オーバーロードされたコンストラクタです
	 * ModelBaseのコンストラクタを呼び出しmodelNameに「movie」をセットします
	 * @param argContext Contextが入っています
	 * @param argProtocol プロトコルが入っています
	 * @param argDomain ドメインが入っています
	 * @param argURLBase ドメイン以下のディレクトリ名が入っています
	 * @param argTokenKeyName Cookieに保存するトークンのkey名が入っています
	 */
	public MovieModel(Context argContext, String argProtocol, String argDomain, String argURLBase,
			String argTokenKeyName) {
		super(argContext,argProtocol,argDomain,argURLBase,argTokenKeyName);
		modelName = "movie";
	}

	/**
	 * オーバーロードされたコンストラクタです
	 * ModelBaseのコンストラクタを呼び出しmodelNameに「movie」をセットします
	 * @param argContext Contextが入っています
	 * @param argProtocol プロトコルが入っています
	 * @param argDomain ドメインが入っています
	 * @param argURLBase ドメイン以下のディレクトリ名が入っています
	 * @param argTokenKeyName Cookieに保存するトークンのkey名が入っています
	 * @param argTimeout Timeoutまでの時間が入っています
	 */
	public MovieModel(Context argContext, String argProtocol, String argDomain, String argURLBase,
			String argTokenKeyName, int argTimeout) {
		super(argContext,argProtocol,argDomain,argURLBase,argTokenKeyName,argTimeout);
		modelName = "movie";
	}

	/**
	 * オーバーロードされたコンストラクタです
	 * ModelBaseのコンストラクタを呼び出しmodelNameに「movie」をセットします
	 * @param argContext Contextが入っています
	 * @param argProtocol プロトコルが入っています
	 * @param argDomain ドメインが入っています
	 * @param argURLBase ドメイン以下のディレクトリ名が入っています
	 * @param argTokenKeyName Cookieに保存するトークンのkey名が入っています
	 * @param argCryptKey トークンの暗号化に使うKEYが入っています
	 * @param argCryptIV トークンの暗号化に使うIVが入っています
	 */
	public MovieModel(Context argContext, String argProtocol, String argDomain, String argURLBase,
			String argTokenKeyName, String argCryptKey, String argCryptIV) {
		super(argContext,argProtocol,argDomain,argURLBase,argTokenKeyName,argCryptKey,argCryptIV);
		modelName = "movie";
	}

	/**
	 * オーバーロードされたコンストラクタです
	 * ModelBaseのコンストラクタを呼び出しmodelNameに「movie」をセットします
	 * @param argContext Contextが入っています
	 * @param argProtocol プロトコルが入っています
	 * @param argDomain ドメインが入っています
	 * @param argURLBase ドメイン以下のディレクトリ名が入っています
	 * @param argTokenKeyName Cookieに保存するトークンのkey名が入っています
	 * @param argCryptKey トークンの暗号化に使うKEYが入っています
	 * @param argCryptIV トークンの暗号化に使うIVが入っています
	 * @param argTimeout Timeoutまでの時間が入っています
	 */
	public MovieModel(Context argContext, String argProtocol, String argDomain, String argURLBase,
			String argTokenKeyName, String argCryptKey, String argCryptIV, int argTimeout) {
		super(argContext,argProtocol,argDomain,argURLBase,argTokenKeyName,argCryptKey,argCryptIV,argTimeout);
		modelName = "movie";
	}

	/**
	 * setterメソッドです
	 * setterによりフィールドが変更されたことを保持するreplacedフラグをtrueに書き換え
	 * どのフィールドが変更されたかを保持するフィールド名_replacedフラグをtrueに書き換えます
	 * @param argThumbnail サムネイル画像のurlが入っています
	 */
	public void setThumbnail(String argThumbnail) {
		thumbnail = argThumbnail;
		thumbnail_replaced = true;
		replaced = true;
	}

	/**
	 * setterメソッドです
	 * setterによりフィールドが変更されたことを保持するreplacedフラグをtrueに書き換え
	 * どのフィールドが変更されたかを保持するフィールド名_replacedフラグをtrueに書き換えます
	 * @param argUrl 動画のurlが入っています
	 */
	public void setUrl(String argUrl) {
		url = argUrl;
		url_replaced = true;
		replaced = true;
	}

	/**
	 * モデルを参照するメソッドです
	 * 通信結果を元に処理を行う場合はload(Hanler argCompletionHandler)を使用し、
	 * Hanler内で処理を分岐して下さい。
	 * @return IDが無指定の場合はfalse、それ以外はtrueを返却します。
	 */
	public boolean load() {
		_load(null, null);
		return true;
	}

	/**
	 * モデルを参照するメソッドです
	 * 通信結果は引数として渡されたHandlerに渡されます。
	 * @param argCompletionHandler 通信後に呼び出すhandlerが入っています
	 * @return IDが無指定の場合はfalse、それ以外はtrueを返却します。
	 */
	public boolean load(Handler argCompletionHandler) {
		completionHandler = argCompletionHandler;
		_load(null, null);
		return true;
	}

	/**
	 * モデルを保存するメソッドです
	 * @param argCompletionHandler 通信後に呼び出すhandlerが入っています
	 * @return trueを返却します。
	 */
	public boolean save(Handler argCompletionHandler) {
		super.save(argCompletionHandler);
		save();
		return true;
	}

	/**
	 * モデルを保存するメソッドです
	 * @return trueを返却します。
	 */
	public boolean save() {
		HashMap<String, Object> argsaveParams = new HashMap<String, Object>();

		if (replaced) {
			if (thumbnail_replaced) {
				argsaveParams.put("thumbnail", thumbnail);
			}
			if (url_replaced) {
				argsaveParams.put("url", url);
			}
		}

		super.save(argsaveParams);
		return true;
	}

	/**
	 * setModelDataから呼ばれるメソッド
	 * 各モデルでOverrideして実装。モデル毎の専用変数にデータを入れて下さい
	 * @param map モデルにセットする元データ(jsonのMap) 
	 */
	public void _setModelData(HashMap<String, Object> map) {
		ID = (String) map.get("id");
		thumbnail = (String) map.get("thumbnail");
		url = (String) map.get("url");

		resetReplaceFlagment();
	}

	/**
	 * このモデルの専用変数の更新フラグを全てリセットするメソッド
	 */
	public void resetReplaceFlagment() {
		thumbnail_replaced = false;
		url_replaced = false;
	}

	/**
	 * モデルデータからMapを生成するメソッド
	 * @return このモデルを生成するために必要なMap
	 */
	public HashMap<String, Object> convertModelData() {
		HashMap<String, Object> newMap = new HashMap<String, Object>();
		newMap.put("id", ID);
		newMap.put("thumbnail", thumbnail);
		newMap.put("url", url);
		return newMap;
	}

	/**
	 * 動画のサムネイル画像の保存を行うメソッド
	 * @param argUploadData サムネイル画像のbyte[]
	 * @param argTimeLineID タイムラインのID
	 * @param argCompletionHandler 通信後に呼び出すhandlerが入っています
	 * @return IDが無い場合はfalse、それ以外はsaveの戻り値
	 */
	public boolean saveThumbnail(byte[] argUploadData,String argTimeLineID,Handler argCompletionHandler)
	{
	    if(null == ID){
	        ID = argTimeLineID + ".jpg";
	        completionHandler = argCompletionHandler;

	        return super._save(argUploadData);
	    }
	    // 異常終了
	    return false;
	}
}