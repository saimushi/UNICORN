package com.unicorn.model;

import java.util.HashMap;

import android.content.Context;
import android.os.Handler;

/**
* UserModelはUserテーブルのデータを参照、保存するためのクラスです
* @author　c1363
*/
public class UserModel extends ModelBase {

	public String name;
	public String uniq_name;
	public String profile_image_url;
	public String created;
	public String modified;
	public String available;

	public boolean name_replaced;
	public boolean uniq_name_replaced;
	public boolean profile_image_url_replaced;
	public boolean created_replaced;
	public boolean modified_replaced;
	public boolean available_replaced;

	/**
	 * コンストラクタです
	 * ModelBaseのコンストラクタを呼び出しmodelNameに「user」をセットします
	 * @param argContext Contextが入っています
	 */
	public UserModel(Context argContext) {
		super(argContext);
		modelName = "user";
	}

	/**
	 * オーバーロードされたコンストラクタです
	 * ModelBaseのコンストラクタを呼び出しmodelNameに「user」をセットします
	 * @param argContext Contextが入っています
	 * @param argProtocol プロトコルが入っています
	 * @param argDomain ドメインが入っています
	 * @param argURLBase ドメイン以下のディレクトリ名が入っています
	 * @param argTokenKeyName Cookieに保存するトークンのkey名が入っています
	 */
	public UserModel(Context argContext, String argProtocol, String argDomain, String argURLBase,
			String argTokenKeyName) {
		super(argContext,argProtocol,argDomain,argURLBase,argTokenKeyName);
		modelName = "user";
	}

	/**
	 * オーバーロードされたコンストラクタです
	 * ModelBaseのコンストラクタを呼び出しmodelNameに「user」をセットします
	 * @param argContext Contextが入っています
	 * @param argProtocol プロトコルが入っています
	 * @param argDomain ドメインが入っています
	 * @param argURLBase ドメイン以下のディレクトリ名が入っています
	 * @param argTokenKeyName Cookieに保存するトークンのkey名が入っています
	 * @param argTimeout Timeoutまでの時間が入っています
	 */
	public UserModel(Context argContext, String argProtocol, String argDomain, String argURLBase,
			String argTokenKeyName, int argTimeout) {
		super(argContext,argProtocol,argDomain,argURLBase,argTokenKeyName,argTimeout);
		modelName = "user";
	}

	/**
	 * オーバーロードされたコンストラクタです
	 * ModelBaseのコンストラクタを呼び出しmodelNameに「user」をセットします
	 * @param argContext Contextが入っています
	 * @param argProtocol プロトコルが入っています
	 * @param argDomain ドメインが入っています
	 * @param argURLBase ドメイン以下のディレクトリ名が入っています
	 * @param argTokenKeyName Cookieに保存するトークンのkey名が入っています
	 * @param argCryptKey トークンの暗号化に使うKEYが入っています
	 * @param argCryptIV トークンの暗号化に使うIVが入っています
	 */
	public UserModel(Context argContext, String argProtocol, String argDomain, String argURLBase,
			String argTokenKeyName, String argCryptKey, String argCryptIV) {
		super(argContext, argProtocol, argDomain, argURLBase, argTokenKeyName,argCryptKey,argCryptIV);
		modelName = "user";
	}

	/**
	 * オーバーロードされたコンストラクタです
	 * ModelBaseのコンストラクタを呼び出しmodelNameに「user」をセットします
	 * @param argContext Contextが入っています
	 * @param argProtocol プロトコルが入っています
	 * @param argDomain ドメインが入っています
	 * @param argURLBase ドメイン以下のディレクトリ名が入っています
	 * @param argTokenKeyName Cookieに保存するトークンのkey名が入っています
	 * @param argCryptKey トークンの暗号化に使うKEYが入っています
	 * @param argCryptIV トークンの暗号化に使うIVが入っています
	 * @param argTimeout Timeoutまでの時間が入っています
	 */
	public UserModel(Context argContext, String argProtocol, String argDomain, String argURLBase,
			String argTokenKeyName, String argCryptKey, String argCryptIV, int argTimeout) {
		super(argContext, argProtocol, argDomain, argURLBase, argTokenKeyName, argCryptKey,
				argCryptIV,argTimeout);
		modelName = "user";
	}

	/**
	 * setterメソッドです
	 * setterによりフィールドが変更されたことを保持するreplacedフラグをtrueに書き換え
	 * どのフィールドが変更されたかを保持するフィールド名_replacedフラグをtrueに書き換えます
	 * @param argName 名前が入っています
	 */
	public void setName(String argName) {
		name = argName;
		name_replaced = true;
		replaced = true;
	}

	/**
	 * setterメソッドです
	 * setterによりフィールドが変更されたことを保持するreplacedフラグをtrueに書き換え
	 * どのフィールドが変更されたかを保持するフィールド名_replacedフラグをtrueに書き換えます
	 * @param argUniq_name ユニーク名が入っています
	 */
	public void setUniq_name(String argUniq_name) {
		uniq_name = argUniq_name;
		uniq_name_replaced = true;
		replaced = true;
	}

	/**
	 * setterメソッドです
	 * setterによりフィールドが変更されたことを保持するreplacedフラグをtrueに書き換え
	 * どのフィールドが変更されたかを保持するフィールド名_replacedフラグをtrueに書き換えます
	 * @param argProfile_image_url ユニーク名が入っています
	 */
	public void setProfile_image_url(String argProfile_image_url) {
		profile_image_url = argProfile_image_url;
		profile_image_url_replaced = true;
		replaced = true;
	}

	/**
	 * setterメソッドです
	 * setterによりフィールドが変更されたことを保持するreplacedフラグをtrueに書き換え
	 * どのフィールドが変更されたかを保持するフィールド名_replacedフラグをtrueに書き換えます
	 * @param argCreated create日付名が入っています
	 */
	public void setCreated(String argCreated) {
		created = argCreated;
		created_replaced = true;
		replaced = true;
	}

	/**
	 * setterメソッドです
	 * setterによりフィールドが変更されたことを保持するreplacedフラグをtrueに書き換え
	 * どのフィールドが変更されたかを保持するフィールド名_replacedフラグをtrueに書き換えます
	 * @param argModified 更新日付名が入っています
	 */
	public void setModified(String argModified) {
		modified = argModified;
		modified_replaced = true;
		replaced = true;
	}

	/**
	 * setterメソッドです
	 * setterによりフィールドが変更されたことを保持するreplacedフラグをtrueに書き換え
	 * どのフィールドが変更されたかを保持するフィールド名_replacedフラグをtrueに書き換えます
	 * @param argAvailable レコードの有効無効フラグが入っています
	 */
	public void setAvailable(String argAvailable) {
		available = argAvailable;
		available_replaced = true;
		replaced = true;
	}

	/**
	 * モデルを参照するメソッドです
	 * 通信結果を元に処理を行う場合はload(Hanler argCompletionHandler)を使用し、
	 * Hanler内で処理を分岐して下さい。
	 * @return IDが無指定の場合はfalse、それ以外はtrueを返却します。
	 */
	@Override
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
	@Override
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
	@Override
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
			if (name_replaced) {
				argsaveParams.put("name", name);
			}
			if (uniq_name_replaced) {
				argsaveParams.put("uniq_name", uniq_name);
			}
			if (profile_image_url_replaced) {
				argsaveParams.put("profile_image_url", profile_image_url);
			}
			if (created_replaced) {
				argsaveParams.put("created", created);
			}
			if (modified_replaced) {
				argsaveParams.put("modified", modified);
			}
			if (available_replaced) {
				argsaveParams.put("available", available);
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
	@Override
	public void _setModelData(HashMap<String, Object> map) {
		ID = (String) map.get("id");
		name = (String) map.get("name");
		uniq_name = (String) map.get("uniq_name");
		profile_image_url = (String) map.get("profile_image_url");
		created = (String) map.get("created");
		modified = (String) map.get("modified");
		available = (String) map.get("available");

		resetReplaceFlagment();
	}

	/**
	 * このモデルの専用変数の更新フラグを全てリセットするメソッド
	 */
	public void resetReplaceFlagment() {
		name_replaced = false;
		uniq_name_replaced = false;
		profile_image_url_replaced = false;
		created_replaced = false;
		modified_replaced = false;
		available_replaced = false;
	}

	/**
	 * モデルデータからMapを生成するメソッド
	 * @return このモデルを生成するために必要なMap
	 */
	@Override
	public HashMap<String, Object> convertModelData() {
		HashMap<String, Object> newMap = new HashMap<String, Object>();
		newMap.put("id", ID);
		newMap.put("uniq_name", uniq_name);
		newMap.put("profile_image_url", profile_image_url);
		newMap.put("created", created);
		newMap.put("modified", modified);
		newMap.put("available", available);
		return newMap;
	}

	/**
	 * プロフィール画像の保存を行うメソッド
	 * @param imageData プロフィール画像のbyte[]
	 * @param argCompletionHandler 通信後に呼び出すhandlerが入っています
	 * @return saveの戻り値
	 */
	public boolean saveWithProfileImage(byte[] imageData, Handler argCompletionHandler) {
		
		HashMap<String, Object> argsaveParams = new HashMap<String, Object>();
		
		if (replaced) {
			if (name_replaced) {
				argsaveParams.put("name", name);
			}
			if (uniq_name_replaced) {
				argsaveParams.put("uniq_name", uniq_name);
			}
			if (profile_image_url_replaced) {
				argsaveParams.put("profile_image_url", profile_image_url);
			}
			if (created_replaced) {
				argsaveParams.put("created", created);
			}
			if (modified_replaced) {
				argsaveParams.put("modified", modified);
			}
			if (available_replaced) {
				argsaveParams.put("available", available);
			}
		}

		super.save(argsaveParams,imageData,"tmp.jpg","image/jpeg","image");
		return true;
	}

}