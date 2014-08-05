package com.unicorn.project;

import android.view.ViewGroup;

public class Constant {

	public static final boolean isDebug = false;
	public static final String NETWORK_CRYPT_KEY = "bdcc45fba7d9865d";
	public static final String NETWORK_CRYPT_IV = "ccfd810a95af4d9051acc0136b331500";

	/**
	 * 本番サーバ
	 */

	public static final String DOMAIN_NAME = "api.ciaopic.com";

	public static final String API_UUID_AUTH_URL = "https://api.ciaopic.com/auth.json";
	public static final String API_GET_ALBUM_LIST_URL = "https://api.ciaopic.com/getAlbumList.json";
	public static final String API_GET_TIME_LINE_URL = "https://api.ciaopic.com/getTimeline.json";
	public static final String API_GET_COMMENTS_URL = "https://api.ciaopic.com/getComments.json";
	public static final String API_PUT_COMMENT_URL = "https://api.ciaopic.com/putComment.json";
	public static final String API_GET_ALBUM_URL = "https://api.ciaopic.com/getAlbum.json";
	public static final String API_PUT_TMP_IMAGE_URL = "https://api.ciaopic.com/putTmpImage.json";
	public static final String API_PUT_IMAGE_URL = "https://api.ciaopic.com/putImage.json";
	public static final String API_DELETE_IMAGE_URL = "https://api.ciaopic.com/DeleteImage.json";
	public static final String API_PUT_INVITED_STATUS_URL = "https://api.ciaopic.com/putInvitedStatus.json";
	public static final String API_AUTH_TELEPHONE_REGISTER_URL = "https://api.ciaopic.com/authTelephone+register.json";
	public static final String API_AUTH_TELEPHONE_AUTH_URL = "https://api.ciaopic.com/authTelephone+auth.json";
	public static final String API_PUT_USER_REGISTER_URL = "https://api.ciaopic.com/putUser+register.json";
	public static final String API_PUT_USER_UNREGISTER_URL = "https://api.ciaopic.com/putUser+unregister.json";
	public static final String API_PUT_USER_UPDATE_URL = "https://api.ciaopic.com/putUser+update.json";
	public static final String API_SEARCH_FRIEND_ID_URL = "https://api.ciaopic.com/searchFriend+ID.json";
	public static final String API_PUT_FRIEND_REQUEST_URL = "https://api.ciaopic.com/putFriendRequest.json";
	public static final String API_PUT_FRIEND_APPROVAL_URL = "https://api.ciaopic.com/putFriendApproval.json";
	public static final String API_GET_FRIEND_APPROVAL_LIST_URL = "https://api.ciaopic.com/getFriendApprovalList.json";
	public static final String API_GET_FRIEND_REQUESTED_LIST_URL = "https://api.ciaopic.com/getFriendRequestedList.json";
	public static final String API_GET_FRIEND_LIST_URL = "https://api.ciaopic.com/getFriendList.json";
	public static final String API_GET_ACTIVITY_LIST_URL = "https://api.ciaopic.com/getActivityList.json";
	public static final String API_GET_BADGES = "https://api.ciaopic.com/getBadges.json";
	public static final String API_RELAYUSER_REGISTER_URL = "https://api.ciaopic.com/relayUser+register.json";
	public static final String API_RELAYUSER_AUTH_URL = "https://api.ciaopic.com/relayUser+auth.json";
	public static final String API_INFO_URL = "https://api.ciaopic.com/info.html";
	public static final String API_RULE_URL = "https://api.ciaopic.com/static/rule.html";
	public static final String API_POLICY_URL = "https://api.ciaopic.com/static/policy.html";
	public static final String API_HELP_URL = "https://api.ciaopic.com/static/help.html";
	public static final String API_ALBUM_ICON_URL = "https://api.ciaopic.com/images/albumicon_";
	public static final String API_USER_ICON_URL = "https://api.ciaopic.com/images/usericon_";
	public static final String API_GET_BLOCKED_STATUS_URL = "https://api.ciaopic.com/getBlockedStatus.json";
	public static final String API_GET_BLOCK_LIST_URL = "https://api.ciaopic.com/getBlockList.json";
	public static final String API_PUT_BLOCKED_STATUS_URL = "https://api.ciaopic.com/putBlockedStatus.json";
	public static final String API_UPDATE_TELEPHONE_REGISTER_URL = "https://api.ciaopic.com/updateTelephone+register.json";
	public static final String API_UPDATE_TELEPHONE_AUTH_URL = "https://api.ciaopic.com/updateTelephone+auth.json";
	public static final String API_GET_GROUP_INFO = "https://api.ciaopic.com/getGroupInfo.json";
	public static final String API_PUT_GROUP_INFO = "https://api.ciaopic.com/putGroupInfo.json";
	public static final String API_PUT_GROUP_INFO_QUIT = "https://api.ciaopic.com/putGroupInfo+quit.json";
	public static final String API_GET_SELL_ITEMS = "https://api.ciaopic.com/getSellItems.json";
	public static final String API_GET_DOWNLOAD = "https://api.ciaopic.com/download.json";
	public static final String API_GET_PURCHASEHISTORY = "https://api.ciaopic.com/getPurchaseHistory.json";
	public static final String API_GET_DETAIL = "https://api.ciaopic.com/getDetail.json";
	public static final String API_GET_INVITE_MESSEAGE_URL = "https://api.ciaopic.com/getInviteMessage.json";

	public static final String WEB_POLICY_URL = "https://api.ciaopic.com/static/policy.html";
	public static final String WEB_INFO_URL = "https://api.ciaopic.com/info.html";
	public static final String WEB_TELEPHONE_HELP_URL = "https://api.ciaopic.com/telephoneHelp.html";
	public static final String WEB_RULE_URL = "https://api.ciaopic.com/static/rule.html";
	public static final String WEB_HELP_URL = "https://api.ciaopic.com/static/help.html";
	public static final String SECRETWORDSETTING_URL = "https://api.ciaopic.com/putAlbum+update.json";
	public static final String API_RELAY_INSENTIV_URL = "https://api.ciaopic.com/putAlbum+relayInsentiv.json";
	public static final String API_AUTH_ALBUM = "https://api.ciaopic.com/authAlbum.json";
	public static final String API_GETBLANKALBUMLIST_URL = "https://api.ciaopic.com/getBlankAlbumList.json";
	public static final String API_PUT_ALBUM_REGISTER = "https://api.ciaopic.com/putAlbum+register.json";
	public static final String API_PUT_USER_UPDATE = "https://api.ciaopic.com/putUser+update.json";
	public static final String API_GET_INVITE = "https://api.ciaopic.com/getInvite.json";
	public static final String GET_INVITE_URL = "http://testweb.ciaopic.com/r";
	public static final String API_GETINSENTIV_ALBUMLIST = "https://api.ciaopic.com/GetInsentivAlbumList.json";
	public static final String API_GET_TIME_LINE_LIGHTLIST_URL = "https://api.ciaopic.com/getTimeline+lightlist.json";
	public static final String API_GET_TIME_LINE_HEAVYLIST_URL = "https://api.ciaopic.com/getTimeline+heavylist.json";
	public static final String API_GET_TIME_LINE_NEWLIST_URL = "https://api.ciaopic.com/getTimeline+newlist.json";
	public static final String SETTING_BASE_BUNNER_URL = "http://api.ciaopic.com/images/banner_stampshop-";
	public static final String API_PUTALBUM_UNREGISTER_URL = "https://api.ciaopic.com/putAlbum+unregister.json";
	public static final String API_PUTALBUM_RENAME_URL = "https://api.ciaopic.com/putAlbum+update.json";
	/**
	 * テストサーバ
	 */
//	public static final String DOMAIN_NAME = "testapi.ciaopic.com";
//
//	public static final String API_UUID_AUTH_URL = "http://testapi.ciaopic.com/auth.json";
//	public static final String API_GET_ALBUM_LIST_URL = "http://testapi.ciaopic.com/getAlbumList.json";
//	public static final String API_GET_TIME_LINE_URL = "http://testapi.ciaopic.com/getTimeline.json";
//	public static final String API_GET_COMMENTS_URL = "http://testapi.ciaopic.com/getComments.json";
//	public static final String API_PUT_COMMENT_URL = "http://testapi.ciaopic.com/putComment.json";
//	public static final String API_GET_ALBUM_URL = "http://testapi.ciaopic.com/getAlbum.json";
//	public static final String API_PUT_TMP_IMAGE_URL = "http://testapi.ciaopic.com/putTmpImage.json";
//	public static final String API_PUT_IMAGE_URL = "http://testapi.ciaopic.com/putImage.json";
//	public static final String API_DELETE_IMAGE_URL = "http://testapi.ciaopic.com/DeleteImage.json";
//	public static final String API_PUT_INVITED_STATUS_URL = "http://testapi.ciaopic.com/putInvitedStatus.json";
//	public static final String API_AUTH_TELEPHONE_REGISTER_URL = "http://testapi.ciaopic.com/authTelephone+register.json";
//	public static final String API_AUTH_TELEPHONE_AUTH_URL = "http://testapi.ciaopic.com/authTelephone+auth.json";
//	public static final String API_PUT_USER_REGISTER_URL = "http://testapi.ciaopic.com/putUser+register.json";
//	public static final String API_PUT_USER_UNREGISTER_URL = "http://testapi.ciaopic.com/putUser+unregister.json";
//	public static final String API_PUT_USER_UPDATE_URL = "http://testapi.ciaopic.com/putUser+update.json";
//	public static final String API_SEARCH_FRIEND_ID_URL = "http://testapi.ciaopic.com/searchFriend+ID.json";
//	public static final String API_PUT_FRIEND_REQUEST_URL = "http://testapi.ciaopic.com/putFriendRequest.json";
//	public static final String API_PUT_FRIEND_APPROVAL_URL = "http://testapi.ciaopic.com/putFriendApproval.json";
//	public static final String API_GET_FRIEND_APPROVAL_LIST_URL = "http://testapi.ciaopic.com/getFriendApprovalList.json";
//	public static final String API_GET_FRIEND_REQUESTED_LIST_URL = "http://testapi.ciaopic.com/getFriendRequestedList.json";
//	public static final String API_GET_FRIEND_LIST_URL = "http://testapi.ciaopic.com/getFriendList.json";
//	public static final String API_GET_ACTIVITY_LIST_URL = "http://testapi.ciaopic.com/getActivityList.json";
//	public static final String API_GET_BADGES = "http://testapi.ciaopic.com/getBadges.json";
//	public static final String API_RELAYUSER_REGISTER_URL = "http://testapi.ciaopic.com/relayUser+register.json";
//	public static final String API_RELAYUSER_AUTH_URL = "http://testapi.ciaopic.com/relayUser+auth.json";
//	public static final String API_GET_BLOCKED_STATUS_URL = "http://testapi.ciaopic.com/getBlockedStatus.json";
//	public static final String API_GET_BLOCK_LIST_URL = "http://testapi.ciaopic.com/getBlockList.json";
//	public static final String API_PUT_BLOCKED_STATUS_URL = "http://testapi.ciaopic.com/putBlockedStatus.json";
//	public static final String API_UPDATE_TELEPHONE_REGISTER_URL = "http://testapi.ciaopic.com/updateTelephone+register.json";
//	public static final String API_UPDATE_TELEPHONE_AUTH_URL = "http://testapi.ciaopic.com/updateTelephone+auth.json";
//	public static final String API_GET_GROUP_INFO = "http://testapi.ciaopic.com/getGroupInfo.json";
//	public static final String API_PUT_GROUP_INFO = "http://testapi.ciaopic.com/putGroupInfo.json";
//	public static final String API_PUT_GROUP_INFO_QUIT = "http://testapi.ciaopic.com/putGroupInfo+quit.json";
//	public static final String API_GET_SELL_ITEMS = "http://testapi.ciaopic.com/getSellItems.json";
//	public static final String API_GET_DOWNLOAD = "http://testapi.ciaopic.com/download.json";
//	public static final String API_GET_PURCHASEHISTORY = "http://testapi.ciaopic.com/getPurchaseHistory.json";
//	public static final String API_GET_DETAIL = "http://testapi.ciaopic.com/getDetail.json";
//	public static final String API_GET_INVITE_MESSEAGE_URL = "http://testapi.ciaopic.com/getInviteMessage.json";
//
//	public static final String API_INFO_URL = "http://testapi.ciaopic.com/info.html";
//	public static final String API_RULE_URL = "http://testapi.ciaopic.com/static/rule.html";
//	public static final String API_POLICY_URL = "http://testapi.ciaopic.com/static/policy.html";
//	public static final String API_HELP_URL = "http://testapi.ciaopic.com/static/help.html";
//	public static final String API_ALBUM_ICON_URL = "http://testapi.ciaopic.com/images/albumicon_";
//	public static final String API_USER_ICON_URL = "http://testapi.ciaopic.com/images/usericon_";
//	public static final String API_PUT_ALBUM_REGISTER = "http://testapi.ciaopic.com/putAlbum+register.json";
//	public static final String API_PUT_USER_UPDATE = "http://testapi.ciaopic.com/putUser+update.json";
//	public static final String API_AUTH_ALBUM = "http://testapi.ciaopic.com/authAlbum.json";
//	public static final String API_GET_INVITE = "http://testapi.ciaopic.com/getInvite.json";
//
//	public static final String WEB_POLICY_URL = "http://testapi.ciaopic.com/static/policy.html";
//	public static final String WEB_INFO_URL = "http://testapi.ciaopic.com/info.html";
//	public static final String WEB_TELEPHONE_HELP_URL = "http://testapi.ciaopic.com/telephoneHelp.html";
//	public static final String WEB_RULE_URL = "http://testapi.ciaopic.com/static/rule.html";
//	public static final String WEB_HELP_URL = "http://testapi.ciaopic.com/static/help.html";
//	public static final String GET_INVITE_URL = "http://testweb.ciaopic.com/r";
//	public static final String SECRETWORDSETTING_URL = "http://testapi.ciaopic.com/putAlbum+update.json";
//	public static final String API_RELAY_INSENTIV_URL = "http://testapi.ciaopic.com/putAlbum+relayInsentiv.json";
//	public static final String API_GETBLANKALBUMLIST_URL = "http://testapi.ciaopic.com/getBlankAlbumList.json";
//	public static final String API_GETINSENTIV_ALBUMLIST = "http://testapi.ciaopic.com/GetInsentivAlbumList.json";
//
//	public static final String API_GET_TIME_LINE_LIGHTLIST_URL = "http://testapi.ciaopic.com/getTimeline+lightlist.json";
//	public static final String API_GET_TIME_LINE_HEAVYLIST_URL = "http://testapi.ciaopic.com/getTimeline+heavylist.json";
//	public static final String API_GET_TIME_LINE_NEWLIST_URL = "http://testapi.ciaopic.com/getTimeline+newlist.json";
//
//	public static final String SETTING_BASE_BUNNER_URL = "http://testapi.ciaopic.com/images/banner_stampshop-";
//	public static final String API_PUTALBUM_UNREGISTER_URL = "http://testapi.ciaopic.com/putAlbum+unregister.json";
//	public static final String API_PUTALBUM_RENAME_URL = "http://testapi.ciaopic.com/putAlbum+update.json";

	public static final int WC = ViewGroup.LayoutParams.WRAP_CONTENT;
	public static final int MP = ViewGroup.LayoutParams.MATCH_PARENT;

	public static final int RESULT_OK = 1000;
	public static final int RESULT_FAILED = 1001;
	public static final int RESULT_CANCELED = 1002;
	public static final int RESULT_BLOCKED_USER = 1003;
	public static final int RESULT_EXIST_CONTACT_ADDRESS = 1004;
	public static final int RESULT_EXIST_PLURAL_CONTACT_ADDRESS = 1005;
	public static final int RESULT_NOTFOUND = 1006;

	public static final int RESULT_GET_ALBUM_LIST = 1999;
	public static final int RESULT_GET_COMMENTS = 2000;
	public static final int RESULT_PUT_COMMENTS = 2001;
	public static final int RESULT_GET_BADGES = 2002;
	public static final int RESULT_PUT_USER_REGISTER = 2003;
	public static final int RESULT_AUTH_TELEPHONEAUTH = 2004;
	public static final int RESULT_GET_TIMELINE_LIST = 2005;
	public static final int RESULT_RELAY_USER_REGISTER = 2006;
	public static final int RESULT_GET_FRIENDLIST = 2007;
	public static final int RESULT_GET_SEARCH_FRIENDLIST = 2008;
	public static final int RESULT_GET_REFRESH_FRIENDLIST = 2009;
	public static final int RESULT_GET_BLOCKLIST = 2010;
	public static final int RESULT_PUT_BLOCKED_STATUS = 2011;
	public static final int RESULT_UPDATE_TELEPHONE_REGISTER = 2012;
	public static final int RESULT_GET_EMAIL_WITH_PHONENUMBER = 2013;
	public static final int RESULT_GET_GROUP_INFO = 2014;
	public static final int RESULT_PUT_GROUP_INFO = 2015;
	public static final int RESULT_PUT_GROUP_INFO_QUIT = 2016;
	public static final int RESULT_GET_SELL_ITEMS = 2017;
	public static final int RESULT_GET_DOWNLOAD = 2018;
	public static final int RESULT_GET_PURCHASEHISTORY = 2019;
	public static final int RESULT_CREATE_ALBUM = 2020;
	public static final int RESULT_WATCH_ALBUM = 2021;
	public static final int RESULT_SETTINGSECRETWORD = 2022;
	public static final int RESULT_PUTALBUM_UNREGISTER = 2023;
	public static final int RESULT_PUTALBUM_RENAME = 2024;

	public static final int REQUEST_GALLERY = 1;
	public static final int REQUEST_CAMERA = 2;
	public static final int REQUEST_FRIENDLIST = 3;
	public static final int REQUEST_IMAGEEDIT = 4;
	public static final int REQUEST_POSTEDIT = 5;
	public static final int REQUEST_CAMERAALBUMLIST = 6;
	public static final int REQUEST_ALBUMDETAIL = 7;
	public static final int REQUEST_EMAIL = 8;
	public static final int REQUEST_INVITE = 8;
	public static final int REQUEST_WEBVIEW_FROM_SETTINGS = 9;
	public static final int REQUEST_WEBVIEW_FROM_REGISTER = 10;
	public static final int REQUEST_PROFILEEDIT = 11;
	public static final int REQUEST_NAMESETTING = 12;
	public static final int REQUEST_FRIENDIDSEARCH = 13;
	public static final int REQUEST_UNIQUENAMESETTING = 14;
	public static final int REQUEST_UNIQUENAMEALLOWSETTING = 15;
	public static final int REQUEST_FRIENDREQUESTLIST = 16;
	public static final int REQUEST_FRIENDREAPPROVALLIST = 17;
	public static final int REQUEST_NOTICEPOPUP = 18;
	public static final int REQUEST_SET_NOTIFICATION = 19;
	public static final int REQUEST_RELAYEDIT = 20;
	public static final int REQUEST_UPDATETELEPHONE = 21;
	public static final int REQUEST_GROUP_DETAIL = 22;
	public static final int REQUEST_GROUP_EDIT = 23;
	public static final int REQUEST_STAMP_SHOP = 24;
	public static final int REQUEST_STAMP_SHOP_DETAIL = 25;
	public static final int REQUEST_FROM_GALLERY = 26;
	public static final int REQUEST_STAMP_SHOP_EVENT_DETAIL = 27;
	public static final int REQUEST_FRIENDSEARCHLIST = 28;
	public static final int REQUEST_MULTI_GALLERY = 29;
	public static final int REQUEST_FRIENDPROFIELE = 30;
	public static final int REQUEST_PASSCODE = 31;
	public static final int REQUEST_PASSCODESETTING = 32;
	public static final int REQUEST_IMAGEWATCH = 33;
	public static final int REQUEST_STAMPMANAGER = 34;
	public static final int REQUEST_ALBUM_CHOOSE = 35;
	public static final int REQUEST_GROUP_SECRET = 36;

	public static final int REQUEST_ALBUMLIST = 0;

	public static final int REQUEST_TOPSPLASH = 35;
	public static final int REQUEST_PROFILE_FACEBOOK = 36;
	public static final int REQUEST_FRIEND_FACEBOOK = 37;
	public static final int REQUEST_ALBUM_REGISTER = 38;
	public static final int REQUEST_ALBUM_SECRETWORDSETTING = 39;
	public static final int REQUEST_ALBUM_FACEBOOK_FRIEND = 40;
	public static final int REQUEST_CONTACT_LIST = 41;
	public static final int REQUEST_CONTACT_DETAIL = 42;
	public static final int REQUEST_PROFILE_SETTING_SEARCH_ID = 43;
	public static final int REQUEST_RELEASE_BLOCK_FRIEND = 44;
	public static final int REQUEST_RELAY = 45;
	public static final int REQUEST_TEACH_ID = 46;
	public static final int REQUEST_QR_CODE = 47;
	public static final int REQUEST_CIAO_LIST = 48;
	public static final int REQUEST_ALBUM_CREATE = 49;
	public static final int REQUEST_GROUP_CREATE = 50;
	public static final int REQUEST_QR_CODE_INVITE = 51;
	public static final int REQUEST_TUTORIAL = 52;
	public static final int REQUEST_FRIEND_SETTING = 53;
	public static final int REQUEST_OTHERS_SETTING = 54;
	public static final int REQUEST_MY_DRESS_UP = 55;
	public static final int REQUEST_DRESS_UP = 56;
	public static final int REQUEST_DRESS_UP_DETAIL = 57;

	public static final String RESPONSE_KEY = "response";

	public static final String SET_RESULT_NOTICE_TIMELINE_ID = "set_result_notice_timeline_id";
	public static final String SET_RESULT_NOTICE_TIMELINE = "selection_timeline_position";
	public static final String SET_RESULT_NOTICE_DETAIL = "selection_detail_position";

	public static final String SHAREPREF_KEY = "cp_utility";
	public static final String PREFERENCES_STAMP_HISTORY = "preferences_stamp_history";
	public static final String PREFERENCES_NOTICE_DISPLAYEDS = "preferences_notice_displayeds";
	public static final String PREFERENCES_NOTOFOCATION_ID = "preferences_notofocation_id";
	public static final String PREFERENCES_DB_INIT_FLG = "preferences_db_init_flg";
	public static final String PREFERENCES_IS_REGISTERED = "preferences_is_registered";
	public static final String PREFERENCES_PASSCODE = "preferences_passcode";
	public static final String PREFERENCES_INVITEAUTHCODE = "preferences_inviteauthcode";
	public static final String PREFERENCES_REGISTER_ALBUM_ID = "preferences_register_album_id";
	public static final String PREFERENCES_IS_ALBUMCREATE = "preferences_is_albumcreate";
	public static final String PREFERENCES_FACEBOOK_USER_ID = "preferences_facebook_user_id";
	public static final String PREFERENCES_FACEBOOK_USER_INDEX = "preferences_facebook_user_index";
	public static final String PREFERENCES_CONTACT_ID = "preferences_contact_id";
	public static final String PREFERENCES_CONTACT_INDEX = "preferences_contact_index";
	public static final String PREFERENCES_SMS_MESSAGE = "preferences_sms_message";
	public static final String PREFERENCES_EMAIL_MESSAGE = "preferences_email_message";
	public static final String PREFERENCES_LINE_MESSAGE = "preferences_line_message";
	public static final String PREFERENCES_QR_MESSAGE = "preferences_qr_message";
	public static final String PREFERENCES_FACEBOOK_MESSAGE = "preferences_facebook_message";
	public static final String PREFERENCES_BASIC_MESSAGE = "preferences_basic_message";
	public static final String PREFERENCES_IS_UPDATED_PROFILE = "preferences_is_updated_profile";
	public static final String PREFERENCES_TO_TIMELINE_ID = "preferences_to_timeline_id";
	public static final String PREFERENCES_CREATE_GROUP = "preferences_create_group";
	public static final String PREFERENCES_INVITED = "preferences_invited";
	public static final String PREFERENCES_INVITED_GROUP = "preferences_invited_group";
	public static final String PREFERENCES_SETTINGS_FIRST_RULE = "preferences_settings_first_rule";
	public static final String PREFERENCES_TUTORIAL_MODE = "preferences_tutorial_mode";
	public static final String PREFERENCES_DRESS_UP_ID = "preferences_dress_up_id";

	public static final String INTENT_FRIENDLIST_SELECTED_DATA = "FRIENDLIST_SELECTED_DATA";

	// 課金関連
	// The response codes for a request, defined by Android Market.
	public enum ResponseCode {
		RESULT_OK, RESULT_USER_CANCELED, RESULT_SERVICE_UNAVAILABLE, RESULT_BILLING_UNAVAILABLE, RESULT_ITEM_UNAVAILABLE, RESULT_DEVELOPER_ERROR, RESULT_ERROR;

		// Converts from an ordinal value to the ResponseCode
		public static ResponseCode valueOf(int index) {
			ResponseCode[] values = ResponseCode.values();
			if (index < 0 || index >= values.length) {
				return RESULT_ERROR;
			}
			return values[index];
		}
	}

	// The possible states of an in-app purchase, as defined by Android Market.
	public enum PurchaseState {
		// Responses to requestPurchase or restoreTransactions.
		PURCHASED, // User was charged for the order.
		CANCELED, // The charge failed on the server.
		REFUNDED; // User received a refund for the order.

		// Converts from an ordinal value to the PurchaseState
		public static PurchaseState valueOf(int index) {
			PurchaseState[] values = PurchaseState.values();
			if (index < 0 || index >= values.length) {
				return CANCELED;
			}
			return values[index];
		}
	}

	/** This is the action we use to bind to the MarketBillingService. */
	public static final String MARKET_BILLING_SERVICE_ACTION = "com.android.vending.billing.MarketBillingService.BIND";

	// Intent actions that we send from the BillingReceiver to the
	// BillingService. Defined by this application.
	public static final String ACTION_CONFIRM_NOTIFICATION = "net.andromusic.material01.CONFIRM_NOTIFICATION";
	public static final String ACTION_GET_PURCHASE_INFORMATION = "net.andromusic.material01.GET_PURCHASE_INFORMATION";
	public static final String ACTION_RESTORE_TRANSACTIONS = "net.andromusic.material01.RESTORE_TRANSACTIONS";

	// Intent actions that we receive in the BillingReceiver from Market.
	// These are defined by Market and cannot be changed.
	public static final String ACTION_NOTIFY = "com.android.vending.billing.IN_APP_NOTIFY";
	public static final String ACTION_RESPONSE_CODE = "com.android.vending.billing.RESPONSE_CODE";
	public static final String ACTION_PURCHASE_STATE_CHANGED = "com.android.vending.billing.PURCHASE_STATE_CHANGED";

	// These are the names of the extras that are passed in an intent from
	// Market to this application and cannot be changed.
	public static final String NOTIFICATION_ID = "notification_id";
	public static final String INAPP_SIGNED_DATA = "inapp_signed_data";
	public static final String INAPP_SIGNATURE = "inapp_signature";
	public static final String INAPP_REQUEST_ID = "request_id";
	public static final String INAPP_RESPONSE_CODE = "response_code";

	// These are the names of the fields in the request bundle.
	public static final String BILLING_REQUEST_METHOD = "BILLING_REQUEST";
	public static final String BILLING_REQUEST_API_VERSION = "API_VERSION";
	public static final String BILLING_REQUEST_PACKAGE_NAME = "PACKAGE_NAME";
	public static final String BILLING_REQUEST_ITEM_ID = "ITEM_ID";
	public static final String BILLING_REQUEST_DEVELOPER_PAYLOAD = "DEVELOPER_PAYLOAD";
	public static final String BILLING_REQUEST_NOTIFY_IDS = "NOTIFY_IDS";
	public static final String BILLING_REQUEST_NONCE = "NONCE";

	public static final String BILLING_RESPONSE_RESPONSE_CODE = "RESPONSE_CODE";
	public static final String BILLING_RESPONSE_PURCHASE_INTENT = "PURCHASE_INTENT";
	public static final String BILLING_RESPONSE_REQUEST_ID = "REQUEST_ID";
	public static long BILLING_RESPONSE_INVALID_REQUEST_ID = -1;

	public static final String PREFERENCES_DB_INITIALIZED = "preferences_db_initialized";
	public static final String PREFERENCES_SHOW_INVITE_DIALOG = "preferences_show_invite_dialog";
	public static final String PREFERENCES_RELOAD_ALBUMLIST = "preferences_reload_albumlist";

	// ダウンロードに必要かも
	public static final String HTTP_HEADER_TIMEOUT = "http.connection.timeout";
	public static final String HTTP_HEADER_USERAGENT = "http.useragent";
	public static final String CHAR_CODE_UPPER_CASE = "UTF-8";
	public static final String CHAR_CODE_LOW_CASE = "utf-8";
	public static final String HTTP_RES_CONTENT_DISP = "Content-Disposition";
	public static final String HTTP_RES_ATTACHMENT = "attachment; filename=";
	public static final String HTTP_RES_MP3 = "mp3";
	public static final String HTTP_RES_M4A = "m4a";
	public static final String SDCARD_PATH = "/CiaoPic/";

	public static final int CALLING_ACTIVITY_PURCHASEHISTORY = 10;
	public static final int CALLING_ACTIVITY_ALBUMDETAIL = 20;
	public static final int CALLING_ACTIVITY_ALBUMTIMELINE = 30;

	public static final int DELETE_FLAG_OFF = 0;
	public static final int DELETE_FLAG_ON = 1;

	// 詳細取得用 リクエストキー
	public static final String DETAIL_GET_TYPE_USERID = "user_id";
	public static final String DETAIL_GET_TYPE_ALBUMID = "album_id";
	public static final String DETAIL_GET_TYPE_TEL = "telephone";

	// LocNTV - Static define
	public static final String ALBUM_NAME_RETURN = "album_name_return";
	public static final String FACEBOOK_NAME_RETURN = "facebook_name_return";
	public static final String SECRET_WORD_SETTING_NAME = "secret_word_setting_name";
	public static final String SCREEN_KEY = "screen_key";
	public static final String CONTACT_ID_KEY = "contact_id_key";
	// LINE package
	public static final String PACKAGE_LINE = "jp.naver.line.android";
	public static final String TYPE_BUTTON_LINE = "LINE";
	public static final String TYPE_BUTTON_FB = "FB";
	public static final String TYPE_BUTTON_TEL = "TEL";
	public static final String TYPE_BUTTON_ID = "ID";
	public static final String TYPE_BUTTON_QR = "QR";
}
