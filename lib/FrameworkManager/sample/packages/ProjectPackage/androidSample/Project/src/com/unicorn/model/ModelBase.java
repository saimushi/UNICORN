package com.unicorn.model;

import java.lang.reflect.Constructor;
import java.lang.reflect.InvocationTargetException;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.Iterator;
import java.util.Map.Entry;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import com.loopj.android.http.JsonHttpResponseHandler;
import com.loopj.android.http.RequestParams;
import com.unicorn.project.Constant;
import com.unicorn.project.R;
import com.unicorn.utilities.AsyncHttpClientAgent;
import com.unicorn.utilities.Log;
import com.unicorn.utilities.Utilitis;

import android.app.Activity;
import android.content.Context;
import android.os.Handler;
import android.os.Message;

/**
* ModelBaseはUnicornとRest通信を行う為のベースクラスです。
* @author　c1363
*/
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

	/**
	 * コンストラクタです
	 * @param argContext Contextが入っています
	 */
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

	/**
	 * オーバーロードされたコンストラクタです
	 * @param argContext Contextが入っています
	 * @param argProtocol プロトコルが入っています
	 * @param argDomain ドメインが入っています
	 * @param argURLBase ドメイン以下のディレクトリ名が入っています
	 * @param argTokenKeyName Cookieに保存するトークンのkey名が入っています
	 */
	public ModelBase(Context argContext, String argProtocol, String argDomain, String argURLBase,
			String argTokenKeyName) {
		this(argContext);
		protocol = argProtocol;
		domain = argDomain;
		urlbase = argURLBase;
		tokenKeyName = argTokenKeyName;
	}

	/**
	 * オーバーロードされたコンストラクタです
	 * @param argContext Contextが入っています
	 * @param argProtocol プロトコルが入っています
	 * @param argDomain ドメインが入っています
	 * @param argURLBase ドメイン以下のディレクトリ名が入っています
	 * @param argTokenKeyName Cookieに保存するトークンのkey名が入っています
	 * @param argTimeout Timeoutまでの時間が入っています
	 */
	public ModelBase(Context argContext, String argProtocol, String argDomain, String argURLBase,
			String argTokenKeyName, int argTimeout) {
		this(argContext);
		protocol = argProtocol;
		domain = argDomain;
		urlbase = argURLBase;
		tokenKeyName = argTokenKeyName;
		timeout = argTimeout;
	}

	/**
	 * オーバーロードされたコンストラクタです
	 * @param argContext Contextが入っています
	 * @param argProtocol プロトコルが入っています
	 * @param argDomain ドメインが入っています
	 * @param argURLBase ドメイン以下のディレクトリ名が入っています
	 * @param argTokenKeyName Cookieに保存するトークンのkey名が入っています
	 * @param argCryptKey トークンの暗号化に使うKEYが入っています
	 * @param argCryptIV トークンの暗号化に使うIVが入っています
	 */
	public ModelBase(Context argContext, String argProtocol, String argDomain, String argURLBase,
			String argTokenKeyName, String argCryptKey, String argCryptIV) {
		this(argContext, argProtocol, argDomain, argURLBase, argTokenKeyName);
		cryptKey = argCryptKey;
		cryptIV = argCryptIV;
	}

	/**
	 * オーバーロードされたコンストラクタです
	 * @param argContext Contextが入っています
	 * @param argProtocol プロトコルが入っています
	 * @param argDomain ドメインが入っています
	 * @param argURLBase ドメイン以下のディレクトリ名が入っています
	 * @param argTokenKeyName Cookieに保存するトークンのkey名が入っています
	 * @param argCryptKey トークンの暗号化に使うKEYが入っています
	 * @param argCryptIV トークンの暗号化に使うIVが入っています
	 * @param argTimeout Timeoutまでの時間が入っています
	 */
	public ModelBase(Context argContext, String argProtocol, String argDomain, String argURLBase,
			String argTokenKeyName, String argCryptKey, String argCryptIV, int argTimeout) {
		this(argContext, argProtocol, argDomain, argURLBase, argTokenKeyName, argCryptKey,
				argCryptIV);
		timeout = argTimeout;
	}

	/**
	 * REST通信を行う為のURLの生成を行うメソッドです
	 * @param resourceId IDが入っています
	 * @return 生成したURLが入っています。
	 */
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

	/**
	 * モデルを参照するメソッドです
	 * 通信結果を元に処理を行う場合はload(Hanler argCompletionHandler)を使用し、
	 * Hanler内で処理を分岐して下さい。
	 * @return IDが無指定の場合はfalse、それ以外はtrueを返却します。
	 */
	public boolean load() {
		if (null == ID || "".equals(ID)) {
			// ID無指定は単一モデル参照エラー
			return false;
		}
		return load(loadResourceMode.myResource, null);
	}

	/**
	 * モデルを参照するメソッドです
	 * 通信結果は引数として渡されたHandlerに渡されます。
	 * @param argCompletionHandler 通信後に呼び出すhandlerが入っています
	 * @return IDが無指定の場合はfalse、それ以外はtrueを返却します。
	 */
	public boolean load(Handler argCompletionHandler) {
		if (null == ID) {
			// ID無指定は単一モデル参照エラー
			return false;
		}
		completionHandler = argCompletionHandler;
		return load(loadResourceMode.myResource, null);
	}

	/**
	 * モデルをlist参照するメソッドです
	 * 通信結果を元に処理を行う場合はlist(Hanler argCompletionHandler)を使用し、
	 * Hanler内で処理を分岐して下さい。
	 * @return trueを返却します。
	 */
	public boolean list() {
		return load(loadResourceMode.listedResource, null);
	}

	/**
	 * モデルをlist参照するメソッドです
	 * 通信結果は引数として渡されたHandlerに渡されます。
	 * @param argCompletionHandler 通信後に呼び出すhandlerが入っています
	 * @return trueを返却します。
	 */
	public boolean list(Handler argCompletionHandler) {
		completionHandler = argCompletionHandler;
		return load(loadResourceMode.listedResource, null);
	}

	/**
	 * モデルを条件付きで参照するメソッドです
	 * 通信結果を元に処理を行う場合はquery(HashMap<String, Object> argWhereParams, Handler argCompletionHandler)
	 * を使用し、Hanler内で処理を分岐して下さい。
	 * @param argWhereParams 通信時に付与するパラメータがMapで入っています
	 * @return trueを返却します。
	 */
	public boolean query(HashMap<String, Object> argWhereParams) {
		return load(loadResourceMode.automaticResource, argWhereParams);
	}

	/**
	 * モデルを条件付きで参照するメソッドです
	 * 通信結果は引数として渡されたHandlerに渡されます。
	 * @param argWhereParams 通信時に付与するパラメータがMapで入っています
	 * @param argCompletionHandler 通信後に呼び出すhandlerが入っています
	 * @return trueを返却します。
	 */
	public boolean query(HashMap<String, Object> argWhereParams, Handler argCompletionHandler) {
		completionHandler = argCompletionHandler;
		return load(loadResourceMode.automaticResource, argWhereParams);
	}

	/**
	 * モデルを保存するメソッドです
	 * @return trueを返却します。
	 */
	public boolean save() {
		completionHandler = null;
		return true;
	}

	/**
	 * モデルを保存するメソッドです
	 * @param argCompletionHandler 通信後に呼び出すhandlerが入っています
	 * @return trueを返却します。
	 */
	public boolean save(Handler argCompletionHandler) {
		completionHandler = argCompletionHandler;
		return true;
	}

	/**
	 * モデルを保存するメソッドです
	 * @param argSaveParams 通信後に呼び出すhandlerが入っています
	 * @param argUploadData 通信後に呼び出すhandlerが入っています
	 * @param argUploadDataName 通信後に呼び出すhandlerが入っています
	 * @param argUploadDataContentType 通信後に呼び出すhandlerが入っています
	 * @param argUploadDataKey 通信後に呼び出すhandlerが入っています
	 * @return trueを返却します。
	 */
	public boolean save(HashMap<String, Object> argSaveParams, byte[] argUploadData,
			String argUploadDataName, String argUploadDataContentType, String argUploadDataKey) {

		String url = createURLString(ID);

		if (ID != null) {
			// 更新(Put)

			AsyncHttpClientAgent.putBinary(context, url, argSaveParams, argUploadData,
					argUploadDataName, argUploadDataContentType, argUploadDataKey,
					new JsonHttpResponseHandler() {
						@Override
						public void onSuccess(JSONObject response) {
							Log.v(TAG, "post->onSuccessJsonObject");
							try {
								responseList.add(createMapFromJSONObject(response));
								Log.v(TAG, "post->onSuccessJsonObject->pauseSuccess");
								Message msg = new Message();
								msg.arg1 = Constant.RESULT_OK;
								msg.obj = response;
								returnMainTheread(msg);
							} catch (JSONException e) {
								e.printStackTrace();
							}
						}

						@Override
						public void onSuccess(JSONArray response) {
							Log.v(TAG, "post->onSuccessJsonArray");
							try {
								responseList = createArrayFromJSONArray(response);
								Log.v(TAG, "post->onSuccessJsonArray->pauseSuccess");
								Message msg = new Message();
								msg.arg1 = Constant.RESULT_OK;
								msg.obj = response;
								returnMainTheread(msg);
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
							returnMainTheread(msg);
						}

						@Override
						public void onFailure(Throwable e, JSONArray errorResponse) {
							String error = e.toString();
							Log.d(TAG + " error", error);
							Message msg = new Message();
							msg.arg1 = Constant.RESULT_FAILED;
							msg.obj = error;
							returnMainTheread(msg);
						}

						@Override
						public void onFailure(Throwable e, String errorResponse) {
							Log.d(TAG + " error", errorResponse);
							Message msg = new Message();
							msg.arg1 = Constant.RESULT_FAILED;
							msg.obj = errorResponse;
							returnMainTheread(msg);
						}
					});
		} else {
			// 更新(Post)

			AsyncHttpClientAgent.postBinary(context, url, argSaveParams, argUploadData,
					argUploadDataName, argUploadDataContentType, argUploadDataKey,
					new JsonHttpResponseHandler() {
						@Override
						public void onSuccess(JSONObject response) {
							Log.v(TAG, "post->onSuccessJsonObject");
							try {
								responseList.add(createMapFromJSONObject(response));
								Log.v(TAG, "post->onSuccessJsonObject->pauseSuccess");
								Message msg = new Message();
								msg.arg1 = Constant.RESULT_OK;
								msg.obj = response;
								returnMainTheread(msg);
							} catch (JSONException e) {
								e.printStackTrace();
							}
						}

						@Override
						public void onSuccess(JSONArray response) {
							Log.v(TAG, "post->onSuccessJsonArray");
							try {
								responseList = createArrayFromJSONArray(response);
								Log.v(TAG, "post->onSuccessJsonArray->pauseSuccess");
								Message msg = new Message();
								msg.arg1 = Constant.RESULT_OK;
								msg.obj = response;
								returnMainTheread(msg);
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
							returnMainTheread(msg);
						}

						@Override
						public void onFailure(Throwable e, JSONArray errorResponse) {
							String error = e.toString();
							Log.d(TAG + " error", error);
							Message msg = new Message();
							msg.arg1 = Constant.RESULT_FAILED;
							msg.obj = error;
							returnMainTheread(msg);
						}

						@Override
						public void onFailure(Throwable e, String errorResponse) {
							Log.d(TAG + " error", errorResponse);
							Message msg = new Message();
							msg.arg1 = Constant.RESULT_FAILED;
							msg.obj = errorResponse;
							returnMainTheread(msg);
						}
					});
		}
		return true;
	}

	/**
	 * ファイルを一つのモデルリソースと見立ててアップロードする。
	 * ID無しでのアップロードは許可しない為強制PUT
	 * @param argUploadData アップロードデータのbyte[]
	 * @return ID無しの場合はfalse,それ以外はtrueを返却
	 */
	public boolean _save(byte[] argUploadData) {
		String url = createURLString(ID);

		if (null != ID) {
			// 更新(Put)

			AsyncHttpClientAgent.putBinary(context, url, argUploadData,
					new JsonHttpResponseHandler() {
						@Override
						public void onSuccess(JSONObject response) {
							Log.v(TAG, "post->onSuccessJsonObject");
							try {
								responseList.add(createMapFromJSONObject(response));
								Log.v(TAG, "post->onSuccessJsonObject->pauseSuccess");
								Message msg = new Message();
								msg.arg1 = Constant.RESULT_OK;
								msg.obj = response;
								returnMainTheread(msg);
							} catch (JSONException e) {
								e.printStackTrace();
							}
						}

						@Override
						public void onSuccess(JSONArray response) {
							Log.v(TAG, "post->onSuccessJsonArray");
							try {
								responseList = createArrayFromJSONArray(response);
								Log.v(TAG, "post->onSuccessJsonArray->pauseSuccess");
								Message msg = new Message();
								msg.arg1 = Constant.RESULT_OK;
								msg.obj = response;
								returnMainTheread(msg);
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
							returnMainTheread(msg);
						}

						@Override
						public void onFailure(Throwable e, JSONArray errorResponse) {
							String error = e.toString();
							Log.d(TAG + " error", error);
							Message msg = new Message();
							msg.arg1 = Constant.RESULT_FAILED;
							msg.obj = error;
							returnMainTheread(msg);
						}

						@Override
						public void onFailure(Throwable e, String errorResponse) {
							Log.d(TAG + " error", errorResponse);
							Message msg = new Message();
							msg.arg1 = Constant.RESULT_FAILED;
							msg.obj = errorResponse;
							returnMainTheread(msg);
						}
					});
		} else {
			// XXX ID無しのファイルアップロードは出来ない！
			return false;
		}
		return false;
	}

	/**
	 * ファイルを一つのモデルリソースと見立ててアップロードする。
	 * ID無しでのアップロードは許可しない為強制PUT
	 * @param argsaveParams postするデータ
	 * @return trueを返却
	 */
	public boolean save(HashMap<String, Object> argsaveParams) {

		String url = createURLString(ID);

		if (ID != null) {
			// 更新(Put)
			RequestParams requestParam = new RequestParams();

			for (Iterator<Entry<String, Object>> it = argsaveParams.entrySet().iterator(); it
					.hasNext();) {
				HashMap.Entry<String, Object> entry = (HashMap.Entry<String, Object>) it.next();
				Object key = entry.getKey();
				Object value = entry.getValue();
				if (value instanceof String) {
					requestParam.put((String) key, (String) value);
				}
			}

			AsyncHttpClientAgent.post(context, url, requestParam, new JsonHttpResponseHandler() {
				@Override
				public void onSuccess(JSONObject response) {
					Log.v(TAG, "post->onSuccessJsonObject");
					try {
						responseList.add(createMapFromJSONObject(response));
						Log.v(TAG, "post->onSuccessJsonObject->pauseSuccess");
						Message msg = new Message();
						msg.arg1 = Constant.RESULT_OK;
						msg.obj = response;
						returnMainTheread(msg);
					} catch (JSONException e) {
						e.printStackTrace();
					}
				}

				@Override
				public void onSuccess(JSONArray response) {
					Log.v(TAG, "post->onSuccessJsonArray");
					try {
						responseList = createArrayFromJSONArray(response);
						Log.v(TAG, "post->onSuccessJsonArray->pauseSuccess");
						Message msg = new Message();
						msg.arg1 = Constant.RESULT_OK;
						msg.obj = response;
						returnMainTheread(msg);
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
					returnMainTheread(msg);
				}

				@Override
				public void onFailure(Throwable e, JSONArray errorResponse) {
					String error = e.toString();
					Log.d(TAG + " error", error);
					Message msg = new Message();
					msg.arg1 = Constant.RESULT_FAILED;
					msg.obj = error;
					returnMainTheread(msg);
				}

				@Override
				public void onFailure(Throwable e, String errorResponse) {
					Log.d(TAG + " error", errorResponse);
					Message msg = new Message();
					msg.arg1 = Constant.RESULT_FAILED;
					msg.obj = errorResponse;
					returnMainTheread(msg);
				}
			});
		} else {
			// 新規(POST)
			RequestParams requestParam = new RequestParams();

			for (Iterator<Entry<String, Object>> it = argsaveParams.entrySet().iterator(); it
					.hasNext();) {
				HashMap.Entry<String, Object> entry = (HashMap.Entry<String, Object>) it.next();
				Object key = entry.getKey();
				Object value = entry.getValue();
				if (value instanceof String) {
					requestParam.put((String) key, (String) value);
				}
			}

			AsyncHttpClientAgent.post(context, url, requestParam, new JsonHttpResponseHandler() {
				@Override
				public void onSuccess(JSONObject response) {
					Log.v(TAG, "post->onSuccessJsonObject");
					try {
						responseList.add(createMapFromJSONObject(response));
						Log.v(TAG, "post->onSuccessJsonObject->pauseSuccess");
						Message msg = new Message();
						msg.arg1 = Constant.RESULT_OK;
						msg.obj = response;
						returnMainTheread(msg);
					} catch (JSONException e) {
						e.printStackTrace();
					}
				}

				@Override
				public void onSuccess(JSONArray response) {
					Log.v(TAG, "post->onSuccessJsonArray");
					try {
						responseList = createArrayFromJSONArray(response);
						Log.v(TAG, "post->onSuccessJsonArray->pauseSuccess");
						Message msg = new Message();
						msg.arg1 = Constant.RESULT_OK;
						msg.obj = response;
						returnMainTheread(msg);
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
					returnMainTheread(msg);
				}

				@Override
				public void onFailure(Throwable e, JSONArray errorResponse) {
					String error = e.toString();
					Log.d(TAG + " error", error);
					Message msg = new Message();
					msg.arg1 = Constant.RESULT_FAILED;
					msg.obj = error;
					returnMainTheread(msg);
				}

				@Override
				public void onFailure(Throwable e, String errorResponse) {
					Log.d(TAG + " error", errorResponse);
					Message msg = new Message();
					msg.arg1 = Constant.RESULT_FAILED;
					msg.obj = errorResponse;
					returnMainTheread(msg);
				}
			});
		}
		return true;
	}

	/**
	 * GET通信をする際にパラメータをURLにつける
	 * @param url 元のURLが入っています
	 * @param argsaveParams getパラメータのmap
	 * @return 生成されたurlが返却されます
	 */
	public String createGetURl(String url, HashMap<String, Object> argsaveParams) {
		for (Iterator<Entry<String, Object>> it = argsaveParams.entrySet().iterator(); it.hasNext();) {
			HashMap.Entry<String, Object> entry = (HashMap.Entry<String, Object>) it.next();
			Object key = entry.getKey();
			Object value = entry.getValue();
			if (value instanceof String) {
				url = url + " " + (String) key + "=" + (String) value;
			}
		}
		return url;
	}

	/**
	 * IDを元にモデル参照を行います
	 * ただしloadResourceModeがautomaticResourceの場合のみargWhereParamsのデータをもとに
	 * 条件検索を行う事ができます。
	 * @param loadResourceMode モデル参照するタイプ
	 * myResource　自分のデータ
	 * listedResource　リストデータ
	 * automaticResource　自動判別
	 * @param argWhereParams　条件つきで参照する場合のパラメータのmap
	 * @return trueを返却します
	 */
	public boolean load(loadResourceMode argLoadResourceMode, HashMap<String, Object> argWhereParams) {

		switch (argLoadResourceMode) {
		case myResource:
			_load(ID, null);
			break;
		case listedResource:
			_load(null, null);
			break;
		case automaticResource:
			_load(ID, argWhereParams);
			break;
		default:
			break;
		}
		return true;
	}

	/**
	 * 通信レスポンスデータを元にmodelにデータをセットする。
	 * リスト参照など、複数データが返却された場合は先頭のデータがセットされます。
	 */
	public void setModelData() {
		total = responseList.size();
		if (0 < total) {
			_setModelData(responseList.get(0));
		}
	}

	/**
	 * listの0番目のHashMapを元にmodelにデータをセットする。
	 * セット部分は各モデルで_setModelDataをOverrideして実装して下さい
	 * @param list モデルにセットする元データ(jsonのArray) 
	 */
	public void setModelData(ArrayList<HashMap<String, Object>> list) {
		responseList = list;
		total = responseList.size();
		if (0 < total) {
			index = 0;
			_setModelData(responseList.get(0));
		}
	}

	/**
	 * listのargIndex番目のHashMapを元にmodelにデータをセットする。
	 * セット部分は各モデルで_setModelDataをOverrideして実装して下さい
	 * @param list モデルにセットする元データ(jsonのArray) 
	 */
	public void setModelData(ArrayList<HashMap<String, Object>> list, int argIndex) {
		responseList = list;
		total = list.size();
		if (0 < total) {
			index = argIndex;
			_setModelData(list.get(index));
		}
	}

	/**
	 * setModelDataから呼ばれるメソッド
	 * 各モデルでOverrideして実装。モデル毎の専用変数にデータを入れて下さい
	 * @param map モデルにセットする元データ(jsonのMap) 
	 */
	public void _setModelData(HashMap<String, Object> map) {

	}

	/**
	 * モデルからモデル生成の元データとなるMapを生成する
	 * 各モデルでOverrideして実装。
	 * @return trueを返却します
	 */
	public HashMap<String, Object> convertModelData() {
		return null;
	}

	/**
	 * 引数でわたされたresourceIdのモデルを参照する
	 * @param argWhereParams　条件をしてして参照する場合に渡すMap
	 */
	public void _load(String resourceId, HashMap<String, Object> argWhereParams) {

		String url = createURLString(resourceId);
		if (argWhereParams != null) {
			url = createGetURl(url, argWhereParams);
		}
		AsyncHttpClientAgent.get(context, url, null, new JsonHttpResponseHandler() {
			@Override
			public void onSuccess(JSONObject response) {
				Log.v(TAG, "get->onSuccessJsonObject");
				try {
					responseList.add(createMapFromJSONObject(response));
					Log.v(TAG, "get->onSuccessJsonObject->pauseSuccess");
					setModelData();

					Message msg = new Message();
					msg.arg1 = Constant.RESULT_OK;
					msg.obj = response;
					returnMainTheread(msg);
				} catch (JSONException e) {
					e.printStackTrace();
				}
			}

			@Override
			public void onSuccess(JSONArray response) {
				Log.v(TAG, "get->onSuccessJsonArray");
				try {
					responseList = createArrayFromJSONArray(response);
					Log.v(TAG, "get->onSuccessJsonArray->pauseSuccess");
					setModelData();

					Message msg = new Message();
					msg.arg1 = Constant.RESULT_OK;
					msg.obj = response;
					returnMainTheread(msg);
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
				returnMainTheread(msg);
			}

			@Override
			public void onFailure(Throwable e, JSONArray errorResponse) {
				String error = e.toString();
				Log.d(TAG + " error", error);
				Message msg = new Message();
				msg.arg1 = Constant.RESULT_FAILED;
				msg.obj = error;
				returnMainTheread(msg);
			}

			@Override
			public void onFailure(Throwable e, String errorResponse) {
				Log.d(TAG + " error", errorResponse);
				Message msg = new Message();
				msg.arg1 = Constant.RESULT_FAILED;
				msg.obj = errorResponse;
				returnMainTheread(msg);
			}
		});
	}

	/**
	 * handlerがnullで無い場合、通信結果をhandlerに渡す
	 * @param msg　通信結果が格納されています。
	 */
	public void returnMainTheread(Message msg) {
		if (completionHandler != null) {
			completionHandler.sendMessage(msg);
			completionHandler = null;
		}
	}

	/**
	 * JSONArrayをパースしてArrayListに変換します
	 * JSONArray内のJSONObjectはcreateMapFromJSONObjectでHashMap<String,Object>に変換されます
	 * @param data JSONArrayが格納されています
	 * @throws JSONException パースに失敗した場合throwされます
	 * @return JsonArrayをパースした結果
	 */
	public ArrayList<HashMap<String, Object>> createArrayFromJSONArray(JSONArray data)
			throws JSONException {
		ArrayList<HashMap<String, Object>> array = new ArrayList<HashMap<String, Object>>();
		for (int i = 0; i < data.length(); i++) {
			JSONObject jsonObject = data.getJSONObject(i);
			array.add(createMapFromJSONObject(jsonObject));
		}
		return array;
	}

	/**
	 * JSONObjectをパースしてHashMap<String,Object>に変換します
	 * JSONObject内にJSONArrayがあった場合はcreateArrayFromJSONArrayでArrayListへ変換されます
	 * @param data JSONObjectが格納されています
	 * @throws JSONException パースに失敗した場合throwされます
	 * @return JsonObjectをパースした結果
	 */
	public HashMap<String, Object> createMapFromJSONObject(JSONObject data) throws JSONException {
		HashMap<String, Object> map = new HashMap<String, Object>();
		Iterator<?> keys = data.keys();

		while (keys.hasNext()) {
			String key = (String) keys.next();
			if (data.get(key) instanceof JSONObject) {
				map.put(key, createMapFromJSONObject((JSONObject) data.get(key)));
			} else if (data.get(key) instanceof JSONArray) {
				map.put(key, createArrayFromJSONArray((JSONArray) data.get(key)));
			} else if (data.get(key) instanceof String) {
				map.put(key, data.get(key));
			}
		}
		return map;
	}

	/**
	 * model内のあるデータを1加算する場合に使用する目的のメソッド
	 * 各モデルでorrverrideして使用
	 * 各モデルでの実装内容：目的の変数を加算してreplacedをtrueにして_incrementを呼び出します
	 * @return _incrementメソッドをコールした戻り値が返ります
	 */
	public boolean increment() {
		return true;
	}

	/**
	 * modelbaseを継承した子クラスのincrementから呼ばれます。
	 * @return _incrementメソッドをコールした戻り値が返ります
	 */
	public boolean _increment(HashMap<String, Object> argSaveParams) {
		if (null != ID) {
			return save(argSaveParams);
		}
		// インクリメントはID指定ナシはエラー！
		return false;
	}

	/**
	 * model内のあるデータを1減算する場合に使用する目的のメソッド
	 * 各モデルでorrverrideして使用
	 * 各モデルでの実装内容：目的の変数を減算してreplacedをtrueにして_decrementを呼び出します
	 * @return _decrementメソッドをコールした戻り値が返ります
	 */
	public boolean decrement() {
		return true;
	}

	/**
	 * modelbaseを継承した子クラスのdecrementから呼ばれます。
	 * @return _decrementメソッドをコールした戻り値が返ります
	 */
	public boolean _decrement(HashMap<String, Object> argSaveParams) {
		if (null != ID) {
			return save(argSaveParams);
		}
		// インクリメントはID指定ナシはエラー！
		return false;
	}

	/**
	 * total件数が現在のindexより多い場合次のモデルデータに変更する
	 * @return 次のモデルが存在しない場合false、それ以外はtrueを返す
	 */
	public boolean next() {
		if (index < responseList.size() - 1) {
			index++;
			setModelData(responseList, index);
			return true;
		}
		return false;
	}

	/**
	 * argIndex番目のモデルデータを取得します
	 * @param argIndex 何番目のmodelを取得するか
	 * @return argIndex番目のモデル
	 */
	public ModelBase objectAtIndex(int argIndex) {
		ModelBase nextModel = null;
		if (0 < total && argIndex < responseList.size()) {
			Class<?> cls;

			Constructor<?> constructor = null;
			// 引数の型を定義
			Class<Context> contextParam = Context.class;
			Class<String> stringParam = String.class;

			String className = this.getClass().getName();
			try {
				cls = Class.forName(className);
				constructor = cls.getConstructor(contextParam, stringParam, stringParam,
						stringParam, stringParam, stringParam, stringParam, Integer.TYPE);
				// 引数を渡してオブジェクトを生成する
				nextModel = (ModelBase) constructor.newInstance(context, protocol, domain, urlbase,
						tokenKeyName, cryptKey, cryptIV, timeout);
				nextModel.setModelData(responseList, argIndex);
			} catch (ClassNotFoundException e) {
				e.printStackTrace();
			} catch (NoSuchMethodException e) {
				e.printStackTrace();
			} catch (InstantiationException e) {
				e.printStackTrace();
			} catch (IllegalAccessException e) {
				e.printStackTrace();
			} catch (InvocationTargetException e) {
				e.printStackTrace();
			}
		}
		return nextModel;
	}

	/**
	 * argIndex番目にモデルを挿入します
	 * @param model 挿入するモデル
	 * @param argIndex 何番目に挿入するか
	 */
	public void insertObject(ModelBase model, int argIndex) {
		HashMap<String, Object> response = model.convertModelData();
		responseList.add(argIndex, response);
		total = responseList.size();
	}
	
	/**
	 * argIndex番目のモデルを置き換えます
	 * @param model 置き換えるモデル
	 * @param argIndex 何番目に挿入するか
	 */
	public void replaceObject(ModelBase model, int argIndex) {
		HashMap<String, Object> response = model.convertModelData();
		responseList.remove(argIndex);
		responseList.add(argIndex, response);
		total = responseList.size();
	}

	/**
	 * argIndex番目のモデルを削除します
	 * @param argIndex 何番目のモデルを削除するか
	 */
	public void removeObject(int argIndex) {
		responseList.remove(argIndex);
		total = responseList.size();
	}

	/**
	 * モデルデータを全件ArrayListにして返します
	 * @return　全件分のArrayList<ModelBase>
	 */
	public ArrayList<ModelBase> toArray() {
		ArrayList<ModelBase> array = new ArrayList<ModelBase>();
		for (int i = 0; i < total; i++) {
			array.add(objectAtIndex(i));
		}
		return array;
	}

	/**
	 * エラーダイアログを表示します
	 * @param argStatusCode エラーのステータスコード
	 * @param activity　このダイアログを管理するActivity（最前面のactivity)
	 */
	public void showRequestError(int argStatusCode, Activity activity) {
		String errorMsg = context.getString(R.string.errorMsgTimeout);
		if (0 < argStatusCode) {
			errorMsg = context.getString(R.string.errorMsgServerError);
			if (400 == argStatusCode) {
				errorMsg = context.getString(R.string.errorMsg400);
			}
			if (401 == argStatusCode) {
				errorMsg = context.getString(R.string.errorMsg401);
			}
			if (404 == argStatusCode) {
				errorMsg = context.getString(R.string.errorMsg404);
			}
			if (503 == argStatusCode) {
				errorMsg = context.getString(R.string.errorMsg503);
			}
		}

		if (context != null) {
			Utilitis.showAlert(context, errorMsg, activity);
		}
	}
}