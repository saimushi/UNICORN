package com.unicorn.model;

import java.util.HashMap;

import com.unicorn.model.ModelBase.loadResourceMode;
import com.unicorn.project.Constant;

import android.content.Context;
import android.os.Handler;
import android.os.Message;

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

	public UserModel(Context argContext) {
		super(argContext);
		modelName = "user";
	}

	public UserModel(Context argContext, String argProtocol, String argDomain, String argURLBase,
			String argTokenKeyName) {
		this(argContext);
		protocol = argProtocol;
		domain = argDomain;
		urlbase = argURLBase;
		tokenKeyName = argTokenKeyName;
	}

	public UserModel(Context argContext, String argProtocol, String argDomain, String argURLBase,
			String argTokenKeyName, int argTimeout) {
		this(argContext);
		protocol = argProtocol;
		domain = argDomain;
		urlbase = argURLBase;
		tokenKeyName = argTokenKeyName;
		timeout = argTimeout;
	}

	public UserModel(Context argContext, String argProtocol, String argDomain, String argURLBase,
			String argTokenKeyName, String argCryptKey, String argCryptIV) {
		this(argContext, argProtocol, argDomain, argURLBase, argTokenKeyName);
		cryptKey = argCryptKey;
		cryptIV = argCryptIV;
	}

	public UserModel(Context argContext, String argProtocol, String argDomain, String argURLBase,
			String argTokenKeyName, String argCryptKey, String argCryptIV, int argTimeout) {
		this(argContext, argProtocol, argDomain, argURLBase, argTokenKeyName, argCryptKey,
				argCryptIV);
		timeout = argTimeout;
	}

	public void setName(String argName) {
		name = argName;
		name_replaced = true;
		replaced = true;
	}

	public void setUniq_name(String argUniq_name) {
		uniq_name = argUniq_name;
		uniq_name_replaced = true;
		replaced = true;
	}

	public void setProfile_image_url(String argProfile_image_url) {
		profile_image_url = argProfile_image_url;
		profile_image_url_replaced = true;
		replaced = true;
	}

	public void setCreated(String argCreated) {
		created = argCreated;
		created_replaced = true;
		replaced = true;
	}

	public void setModified(String argModified) {
		modified = argModified;
		modified_replaced = true;
		replaced = true;
	}

	public void setAvailable(String argAvailable) {
		available = argAvailable;
		available_replaced = true;
		replaced = true;
	}

	public boolean load() {
		return load(loadResourceMode.myResource);
	}

	public boolean load(Handler argCompletionHandler) {
		completionHandler = argCompletionHandler;
		return load(loadResourceMode.myResource);
	}

	public void setModelData(Message msg) {
		if (msg.arg1 == Constant.RESULT_OK) {
			HashMap<String, Object> map = responseList.get(0);
			ID = (String) map.get("id");
			name = (String) map.get("name");
			uniq_name = (String) map.get("uniq_name");
			profile_image_url = (String) map.get("profile_image_url");
			created = (String) map.get("created");
			modified = (String) map.get("modified");
			available = (String) map.get("available");
		}
	}

}