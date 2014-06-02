<?php

class Auth
{
	protected static $_sessionCryptKey = NULL;
	protected static $_sessionCryptIV = NULL;
	protected static $_authCryptKey = NULL;
	protected static $_authCryptIV = NULL;
	protected static $_DBO = NULL;
	protected static $_initialized = FALSE;
	public static $authTable = 'user_table';
	public static $authPKeyField = 'id';
	public static $authIDField = 'mailaddress';
	public static $authPassField = 'password';
	public static $authIDEncrypted = 'AES128CBC';
	public static $authPassEncrypted = 'SHA256';

	protected static function _init($argDSN=NULL){
		if(FALSE === self::$_initialized){

			$DSN = NULL;

			if(class_exists('Configure') && NULL !== Configure::constant('DB_DSN')){
				// 定義からセッションDBの接続情報を特定
				$DSN = Configure::DB_DSN;
			}
			if(class_exists('Configure') && NULL !== Configure::constant('AUTH_DB_DSN')){
				// 定義からセッションDBの接続情報を特定
				$DSN = Configure::AUTH_DB_DSN;
			}
			if(class_exists('Configure') && NULL !== Configure::constant('AUTH_TBL_NAME')){
				// 定義からuserTable名を特定
				self::$authTable = Configure::AUTH_TBL_NAME;
			}
			if(class_exists('Configure') && NULL !== Configure::constant('AUTH_PKEY_FIELD_NAME')){
				// 定義からuserTable名を特定
				self::$authPKeyField = Configure::AUTH_PKEY_FIELD_NAME;
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
			if(class_exists('Configure') && NULL !== Configure::constant('CRYPT_KEY')){
				// 定義から暗号化キーを設定
				self::$_sessionCryptKey = Configure::CRYPT_KEY;
				self::$_authCryptKey = Configure::CRYPT_KEY;
			}
			if(class_exists('Configure') && NULL !== Configure::constant('NETWORK_CRYPT_KEY')){
				// 定義から暗号化キーを設定
				self::$_sessionCryptKey = Configure::NETWORK_CRYPT_KEY;
			}
			if(class_exists('Configure') && NULL !== Configure::constant('SESSION_CRYPT_KEY')){
				// 定義から暗号化キーを設定
				self::$_sessionCryptKey = Configure::SESSION_CRYPT_KEY;
			}
			if(class_exists('Configure') && NULL !== Configure::constant('DB_CRYPT_KEY')){
				// 定義から暗号化キーを設定
				self::$_authCryptKey = Configure::DB_CRYPT_KEY;
			}
			if(class_exists('Configure') && NULL !== Configure::constant('AUTH_CRYPT_KEY')){
				// 定義から暗号化キーを設定
				self::$_authCryptKey = Configure::AUTH_CRYPT_KEY;
			}
			if(class_exists('Configure') && NULL !== Configure::constant('CRYPT_IV')){
				// 定義から暗号化IVを設定
				self::$_sessionCryptIV = Configure::CRYPT_IV;
				self::$_authCryptIV = Configure::CRYPT_KEY;
			}
			if(class_exists('Configure') && NULL !== Configure::constant('NETWORK_CRYPT_IV')){
				// 定義から暗号化IVを設定
				self::$_sessionCryptIV = Configure::NETWORK_CRYPT_IV;
			}
			if(class_exists('Configure') && NULL !== Configure::constant('SESSION_CRYPT_IV')){
				// 定義から暗号化IVを設定
				self::$_sessionCryptIV = Configure::SESSION_CRYPT_IV;
			}
			if(class_exists('Configure') && NULL !== Configure::constant('DB_CRYPT_IV')){
				// 定義から暗号化キーを設定
				self::$_authCryptKIV = Configure::DB_CRYPT_IV;
			}
			if(class_exists('Configure') && NULL !== Configure::constant('AUTH_CRYPT_IV')){
				// 定義から暗号化キーを設定
				self::$_authCryptIV = Configure::AUTH_CRYPT_IV;
			}
			if(defined('PROJECT_NAME') && strlen(PROJECT_NAME) > 0 && class_exists(PROJECT_NAME . 'Configure')){
				$ProjectConfigure = PROJECT_NAME . 'Configure';
				if(NULL !== $ProjectConfigure::constant('DB_DSN')){
					// 定義からセッションDBの接続情報を特定
					$DSN = $ProjectConfigure::DB_DSN;
				}
				if(NULL !== $ProjectConfigure::constant('AUTH_DB_DSN')){
					// 定義からセッションDBの接続情報を特定
					$DSN = $ProjectConfigure::AUTH_DB_DSN;
				}
				if(NULL !== $ProjectConfigure::constant(PROJECT_NAME . 'AUTH_TBL_NAME')){
					// 定義からuserTable名を特定
					self::$authTable = $ProjectConfigure::AUTH_TBL_NAME;
				}
				if(NULL !== $ProjectConfigure::constant('AUTH_PKEY_FIELD_NAME')){
					// 定義からuserTable名を特定
					self::$authPKeyField = $ProjectConfigure::AUTH_PKEY_FIELD_NAME;
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
				if(NULL !== $ProjectConfigure::constant('CRYPT_KEY')){
					// 定義から暗号化キーを設定
					self::$_sessionCryptKey = $ProjectConfigure::CRYPT_KEY;
				}
				if(NULL !== $ProjectConfigure::constant('NETWORK_CRYPT_KEY')){
					// 定義から暗号化キーを設定
					self::$_sessionCryptKey = $ProjectConfigure::NETWORK_CRYPT_KEY;
				}
				if(NULL !== $ProjectConfigure::constant('SESSION_CRYPT_KEY')){
					// 定義から暗号化キーを設定
					self::$_sessionCryptKey = $ProjectConfigure::SESSION_CRYPT_KEY;
				}
				if(NULL !== $ProjectConfigure::constant('DB_CRYPT_KEY')){
					// 定義から暗号化キーを設定
					self::$_authCryptKey = $ProjectConfigure::DB_CRYPT_KEY;
				}
				if(NULL !== $ProjectConfigure::constant('AUTH_CRYPT_KEY')){
					// 定義から暗号化キーを設定
					self::$_authCryptKey = $ProjectConfigure::AUTH_CRYPT_KEY;
				}
				if(NULL !== $ProjectConfigure::constant('CRYPT_IV')){
					// 定義から暗号化IVを設定
					self::$_sessionCryptIV = $ProjectConfigure::CRYPT_IV;
					self::$_authCryptIV = $ProjectConfigure::CRYPT_KEY;
				}
				if(NULL !== $ProjectConfigure::constant('NETWORK_CRYPT_IV')){
					// 定義から暗号化IVを設定
					self::$_sessionCryptIV = $ProjectConfigure::NETWORK_CRYPT_IV;
				}
				if(NULL !== $ProjectConfigure::constant('SESSION_CRYPT_IV')){
					// 定義から暗号化IVを設定
					self::$_sessionCryptIV = $ProjectConfigure::SESSION_CRYPT_IV;
				}
				if(NULL !== $ProjectConfigure::constant('DB_CRYPT_IV')){
					// 定義から暗号化キーを設定
					self::$_authCryptKIV = $ProjectConfigure::DB_CRYPT_IV;
				}
				if(NULL !== $ProjectConfigure::constant('AUTH_CRYPT_IV')){
					// 定義から暗号化キーを設定
					self::$_authCryptIV = $ProjectConfigure::AUTH_CRYPT_IV;
				}
			}

			// DBOを初期化
			if(NULL === self::$_DBO){
				if(NULL !== $argDSN){
					// セッションDBの接続情報を直指定
					$DSN = $argDSN;
				}
				self::$_DBO = DBO::sharedInstance($DSN);
			}

			// 初期化済み
			self::$_initialized = TRUE;
		}
	}

	/**
	 * セッションが既にあるかどうか
	 * @param string $argDSN
	 */
	public static function isSession($argDSN = NULL){
		if(FALSE === self::$_initialized){
			self::_init($argDSN);
		}
		Session::start();
		debug('is???');
		$sessionIdentifier = Session::sessionID();
		debug( self::$_sessionCryptKey . ':' . self::$_sessionCryptIV);
		debug("session identifier".$sessionIdentifier);
		$userID = Utilities::doHexDecryptAES($sessionIdentifier, self::$_sessionCryptKey, self::$_authCryptIV);
		debug("userID=".$userID);
		if(strlen($userID) > 0){
			$User = ORMapper::getModel(self::$_DBO, self::$authTable, $userID);
			debug("userID=".$User->{self::$authPKey});
			if(isset($User->{self::$authPKey}) && NULL !== $User->{self::$authPKey} && FALSE === is_object($User->{self::$authPKey}) && strlen((string)$User->{self::$authPKey}) > 0){
				// UserIDが特定出来た
				debug("Authlized");
				return TRUE;
			}
		}
		// 認証出来ない！
		debug("Auth failed");
		return FALSE;
	}

	/**
	 * 登録済みかどうか
	 * @param string $argDSN
	 */
	public static function isRegistered($argDSN = NULL){
	}
}
?>