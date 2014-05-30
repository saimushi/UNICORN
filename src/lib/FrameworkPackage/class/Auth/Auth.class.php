<?php

class Auth
{
	public static $authTable = 'user_table';
	public static $authIDField = 'mailaddress';
	public static $authPassField = 'password';
	public static $authIDEncrypted = 'AES128CBC';
	public static $authPassEncrypted = 'SHA256';

	private static function _init(){
		if(class_exists('Configure') && NULL !== Configure::constant('AUTH_TBL_NAME')){
			// 定義からuserTable名を特定
			self::$authTable = Configure::AUTH_TBL_NAME;
		}
		if(class_exists('Configure') && NULL !== Configure::constant('AUTH_ID_FIELD_NAME')){
			// 定義からuserTable名を特定
			self::$authIDField = Configure::AUTH_ID_FIELD_NAME;
		}
		if(class_exists('Configure') && NULL !== Configure::constant('AUTH_PASS_FIELD_NAME')){
			// 定義からuserTable名を特定
			self::$authPassField = Configure::AUTH_PASS_FIELD_NAME;
		}
		if(class_exists('Configure') && NULL !== Configure::constant('AUTH_ID_ENCRYPTED')){
			// 定義からuserTable名を特定
			self::$authIDEncrypted = Configure::AUTH_ID_ENCRYPTED;
		}
		if(class_exists('Configure') && NULL !== Configure::constant('AUTH_PASS_ENCRYPTED')){
			// 定義からuserTable名を特定
			self::$authPassEncrypted = Configure::AUTH_PASS_ENCRYPTED;
		}
		if(defined('PROJECT_NAME') && strlen(PROJECT_NAME) > 0 && class_exists(PROJECT_NAME . 'Configure')){
			$ProjectConfigure = PROJECT_NAME . 'Configure';
			if(NULL !== $ProjectConfigure::constant(PROJECT_NAME . 'AUTH_TBL_NAME')){
				// 定義からuserTable名を特定
				self::$authTable = $ProjectConfigure::AUTH_TBL_NAME;
			}
			if(NULL !== $ProjectConfigure::constant('AUTH_ID_FIELD_NAME')){
				// 定義からuserTable名を特定
				self::$authIDField = $ProjectConfigure::AUTH_ID_FIELD_NAME;
			}
			if(NULL !== $ProjectConfigure::constant('AUTH_PASS_FIELD_NAME')){
				// 定義からuserTable名を特定
				self::$authPassField = $ProjectConfigure::AUTH_PASS_FIELD_NAME;
			}
			if(NULL !== $ProjectConfigure::constant('AUTH_ID_ENCRYPTED')){
				// 定義からuserTable名を特定
				self::$authIDEncrypted = $ProjectConfigure::AUTH_ID_ENCRYPTED;
			}
			if(NULL !== $ProjectConfigure::constant('AUTH_PASS_ENCRYPTED')){
				// 定義からuserTable名を特定
				self::$authPassEncrypted = $ProjectConfigure::AUTH_PASS_ENCRYPTED;
			}
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