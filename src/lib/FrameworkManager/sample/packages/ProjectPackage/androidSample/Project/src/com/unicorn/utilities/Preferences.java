package com.unicorn.utilities;

import java.util.ArrayList;

import org.json.JSONException;
import org.json.JSONObject;

import com.unicorn.project.Constant;

import android.content.Context;
import android.content.SharedPreferences;
import android.content.SharedPreferences.Editor;

public class Preferences {

	public SharedPreferences pref;
	public Context con;

	public Preferences(Context con) {
		this.con = con;
		this.pref = con.getSharedPreferences("Project_Pref", Context.MODE_PRIVATE);
	}

	public void saveStampHistory(String add_history) {

		ArrayList<String> array_history = getStampHistory();

		array_history.remove(add_history);

		String saveText = "";
		saveText += add_history;

		for (int i = 0; i < array_history.size(); i++) {
			if (i < 39) {
				saveText += ",";
				saveText += array_history.get(i);
			} else {
				break;
			}
		}

		Editor editor = pref.edit();
		editor.putString(Constant.PREFERENCES_STAMP_HISTORY, saveText);
		editor.commit();
	}

	public ArrayList<String> getStampHistory() {

		String[] history = pref.getString(Constant.PREFERENCES_STAMP_HISTORY, "").split(",");
		ArrayList<String> array_history = new ArrayList<String>();

		for (int i = 0; i < history.length; i++) {
			if (!"".equals(history[i])) {
				array_history.add(history[i]);
			}
		}

		return array_history;
	}

	public void saveNotificationId(int id) {
		Editor editor = pref.edit();
		editor.putInt(Constant.PREFERENCES_NOTOFOCATION_ID, id);
		editor.commit();
	}

	public int getNotficationId() {
		return pref.getInt(Constant.PREFERENCES_NOTOFOCATION_ID, 0);
	}

	public void setNoticeDisplayed(String albumID, String displayed) {
		String displayeds = pref.getString(Constant.PREFERENCES_NOTICE_DISPLAYEDS, "");
		String saveText = "";
		Editor editor = pref.edit();
		JSONObject rootObject;
		try {
			if (null == displayeds || displayeds.equals("")) {
				rootObject = new JSONObject();
			} else {
				rootObject = new JSONObject(displayeds);
			}
			rootObject.put(albumID, displayed);
			saveText = rootObject.toString();
		} catch (JSONException e) {
			// TODO 自動生成された catch ブロック
			e.printStackTrace();
		}
		editor.putString(Constant.PREFERENCES_NOTICE_DISPLAYEDS, saveText);
		editor.commit();
	}

	public String getNoticeDisplayed(String albumID) {
		String displayeds = pref.getString(Constant.PREFERENCES_NOTICE_DISPLAYEDS, "");
		JSONObject rootObject;
		String displayed = "";
		if (!"".equals(displayeds)) {
			try {
				rootObject = new JSONObject(displayeds);
				displayed = rootObject.getString(albumID);
			} catch (JSONException e) {
				// TODO 自動生成された catch ブロック
				e.printStackTrace();
			}
		}
		// if(){
		// [activityViewDisplayeds setValue:@"1" forKey:[self.albumListData
		// objectForKey:@"album_id"]];
		// }
		return displayed;
	}

	public void clearNoticeDisplayeds() {
		Editor editor = pref.edit();
		editor.putString(Constant.PREFERENCES_NOTICE_DISPLAYEDS, "");
		editor.commit();
	}

	public boolean saveDBinitializedflg(boolean flg) {
		Editor editor = pref.edit();

		editor.putBoolean(Constant.PREFERENCES_DB_INITIALIZED, flg);

		editor.commit();

		return true;
	}

	public boolean getDBinitializedflg() {
		return pref.getBoolean(Constant.PREFERENCES_DB_INITIALIZED, false);
	}

	public boolean saveShowInviteDialog(boolean flg) {
		Editor editor = pref.edit();

		editor.putBoolean(Constant.PREFERENCES_SHOW_INVITE_DIALOG, flg);

		editor.commit();

		return true;
	}

	public boolean isShowInviteDialog() {
		return pref.getBoolean(Constant.PREFERENCES_SHOW_INVITE_DIALOG, false);
	}

	public boolean saveReloadAlbumList(boolean flg) {
		Editor editor = pref.edit();

		editor.putBoolean(Constant.PREFERENCES_RELOAD_ALBUMLIST, flg);

		editor.commit();

		return true;
	}

	public boolean needReloadAlbumList() {
		return pref.getBoolean(Constant.PREFERENCES_RELOAD_ALBUMLIST, false);
	}

	public boolean saveRegisteredFlg(boolean flg) {
		Editor editor = pref.edit();

		editor.putBoolean(Constant.PREFERENCES_IS_REGISTERED, flg);

		editor.commit();

		return true;
	}

	public boolean getRegisteredFlg() {
		return pref.getBoolean(Constant.PREFERENCES_IS_REGISTERED, false);
	}

	public boolean saveUpdateProfileFlg(boolean flg) {
		Editor editor = pref.edit();
		editor.putBoolean(Constant.PREFERENCES_IS_UPDATED_PROFILE, flg);
		editor.commit();
		return true;
	}

	public boolean getUpdateProfileFlg() {
		return pref.getBoolean(Constant.PREFERENCES_IS_UPDATED_PROFILE, false);
	}

	public void savePassCode(String passCode) {
		Editor editor = pref.edit();
		editor.putString(Constant.PREFERENCES_PASSCODE, passCode);
		editor.commit();
	}

	public String getPassCode() {
		return pref.getString(Constant.PREFERENCES_PASSCODE, "");
	}

	public void saveInviteAuthCode(String inviteAuthCode) {
		Editor editor = pref.edit();
		editor.putString(Constant.PREFERENCES_INVITEAUTHCODE, inviteAuthCode);
		editor.commit();
	}

	public String getInviteAuthCode() {
		return pref.getString(Constant.PREFERENCES_INVITEAUTHCODE, "");
	}

	public boolean getAlbumCreateFlg() {
		return pref.getBoolean(Constant.PREFERENCES_IS_ALBUMCREATE, false);
	}

	public boolean saveAlbumCreateFlg(boolean flg) {
		Editor editor = pref.edit();

		editor.putBoolean(Constant.PREFERENCES_IS_ALBUMCREATE, flg);

		editor.commit();

		return true;
	}

	public void saveFacebookUserInfo(String userFbId, int index) {
		Editor editor = pref.edit();
		editor.putString(Constant.PREFERENCES_FACEBOOK_USER_ID, userFbId);
		editor.putInt(Constant.PREFERENCES_FACEBOOK_USER_INDEX, index);
		editor.commit();
	}

	public String getFacebookUserId() {
		return pref.getString(Constant.PREFERENCES_FACEBOOK_USER_ID, "");
	}

	public int getFacebookUserIndex() {
		return pref.getInt(Constant.PREFERENCES_FACEBOOK_USER_INDEX, 0);
	}

	public void clearFacebookUserInfo() {
		Editor editor = pref.edit();
		editor.putString(Constant.PREFERENCES_FACEBOOK_USER_ID, "");
		editor.putInt(Constant.PREFERENCES_FACEBOOK_USER_INDEX, 0);
		editor.commit();
	}

	public void saveContactInfo(Long userFbId, int index) {
		Editor editor = pref.edit();
		editor.putLong(Constant.PREFERENCES_CONTACT_ID, userFbId);
		editor.putInt(Constant.PREFERENCES_CONTACT_INDEX, index);
		editor.commit();
	}

	public Long getContactId() {
		return pref.getLong(Constant.PREFERENCES_CONTACT_ID, 0);
	}

	public int getContactIndex() {
		return pref.getInt(Constant.PREFERENCES_CONTACT_INDEX, 0);
	}

	public void clearContactInfo() {
		Editor editor = pref.edit();
		editor.putLong(Constant.PREFERENCES_CONTACT_ID, 0);
		editor.putInt(Constant.PREFERENCES_CONTACT_INDEX, 0);
		editor.commit();
	}

	public void saveTotimeLineId(String album_id) {
		Editor editor = pref.edit();
		editor.putString(Constant.PREFERENCES_TO_TIMELINE_ID, album_id);
		editor.commit();
	}

	public String getTotimeLineId() {
		return pref.getString(Constant.PREFERENCES_TO_TIMELINE_ID, "");
	}

	public boolean getGroupCreateFlg() {
		return pref.getBoolean(Constant.PREFERENCES_CREATE_GROUP, false);
	}

	public void saveGroupCreateFlg(boolean flg) {
		Editor editor = pref.edit();

		editor.putBoolean(Constant.PREFERENCES_CREATE_GROUP, flg);

		editor.commit();
	}

	public String getBeforeInvitedId() {
		return pref.getString(Constant.PREFERENCES_INVITED, "");
	}

	public void saveBeforeInvitedId(String album_id) {
		Editor editor = pref.edit();

		editor.putString(Constant.PREFERENCES_INVITED, album_id);

		editor.commit();
	}

	public void clear() {
		Editor editor = pref.edit();
		editor.clear();
		editor.commit();

	}

	public boolean getGroupInviteFlg() {
		return pref.getBoolean(Constant.PREFERENCES_INVITED_GROUP, false);
	}

	public void saveGroupInviteFlg(boolean flg) {
		Editor editor = pref.edit();

		editor.putBoolean(Constant.PREFERENCES_INVITED_GROUP, flg);

		editor.commit();
	}

	public boolean getFirstRuleFlg() {
		return pref.getBoolean(Constant.PREFERENCES_SETTINGS_FIRST_RULE, false);
	}

	public void saveFirstRuleFlg(boolean flg) {
		Editor editor = pref.edit();

		editor.putBoolean(Constant.PREFERENCES_SETTINGS_FIRST_RULE, flg);

		editor.commit();
	}

	public String getTutorialMode() {
		return pref.getString(Constant.PREFERENCES_TUTORIAL_MODE, "");
	}

	public void saveTutorialMode(String tutorialmode) {
		Editor editor = pref.edit();

		editor.putString(Constant.PREFERENCES_TUTORIAL_MODE, tutorialmode);

		editor.commit();
	}

	public int getIntValue(String key) {
		return pref.getInt(key, 0);
	}

	public void setIntValue(String key, int value) {
		Editor editor = pref.edit();

		editor.putInt(key, value);

		editor.commit();
	}

	public void setPurchaseItem(String purchaseitem, boolean value) {
		Editor editor = pref.edit();

		editor.putBoolean(purchaseitem, value);

		editor.commit();
	}

	public boolean getPurchaseItem(String purchaseitem) {
		return pref.getBoolean(purchaseitem, false);
	}
}
