<?php

class Auth
{
	public static $authTable = 'users';
	public static $authIDField = 'mail';
	public static $authPassField = 'pass';
	public static $authIDEncrypted = 'AES128CBC';
	public static $authPassEncrypted = 'SHA256';

	private static function _init(){
		if(class_exists("Configure") && NULL !== Configure::constant('AUTH_TBL_NAME')){
			// 定義からuserTable名を特定
			self::$authTable = Configure::AUTH_TBL_NAME;
		}
		if(class_exists("Configure") && NULL !== Configure::constant('AUTH_ID_FIELD_NAME')){
			// 定義からuserTable名を特定
			self::$authIDField = Configure::AUTH_ID_FIELD_NAME;
		}
		if(class_exists("Configure") && NULL !== Configure::constant('AUTH_PASS_FIELD_NAME')){
			// 定義からuserTable名を特定
			self::$authPassField = Configure::AUTH_PASS_FIELD_NAME;
		}
		if(class_exists("Configure") && NULL !== Configure::constant('AUTH_ID_ENCRYPTED')){
			// 定義からuserTable名を特定
			self::$authIDEncrypted = Configure::AUTH_ID_ENCRYPTED;
		}
		if(class_exists("Configure") && NULL !== Configure::constant('AUTH_PASS_ENCRYPTED')){
			// 定義からuserTable名を特定
			self::$authPassEncrypted = Configure::AUTH_PASS_ENCRYPTED;
		}
	}

	public static function isSession($argDSN = NULL){
		self::_init();
		$Users = ORMapper::getModel(DBO::sharedInstance($argDSN), self::$authTable);
		if(!(isset($Users->id) && NULL !== $Users->id && FALSE === is_object($Users->id) && strlen((string)$Users->id) > 0)){
			// 認証出来ない！
			return FALSE;
		}
		return TRUE;
	}
}
?>