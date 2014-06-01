<?php

/**
 * Sessionクラス(DB版)
 * @author saimushi
 */
class SessionDB extends SessionDataDB implements SessionIO {

	private static $_initialized = FALSE;
	private static $_replaced = FALSE;
	private static $_tokenKeyName = 'token';
	private static $_token = NULL;
	private static $_identifier = NULL;
	private static $_domain = NULL;
	private static $_path = '/';
	private static $_expiredtime = 3600;// 60分
	private static $_sessionTblName = 'session_table';
	private static $_sessionTblPkeyName = 'token';
	private static $_sessionDateKeyName = 'created';
	private static $_DBO = NULL;

	/**
	 * Sessionクラスの初期化
	 */
	private static function _init($argDomain=NULL, $argExpiredtime=NULL, $argDSN=NULL){
		if(FALSE === self::$_initialized){

			// セッションの有効ドメインを設定
			self::$_domain = $argDomain;
			if(NULL === $argDomain){
				self::$_domain = $_SERVER['SERVER_NAME'];
			}

			$DSN = NULL;
			$expiredtime = self::$_expiredtime;

			if(class_exists('Configure') && NULL !== Configure::constant('DB_DSN')){
				// 定義からセッションDBの接続情報を特定
				$DSN = Configure::DB_DSN;
			}
			if(class_exists('Configure') && NULL !== Configure::constant('SESSION_DB_DSN')){
				// 定義からセッションDBの接続情報を特定
				$DSN = Configure::DB_DSN;
			}
			if(class_exists('Configure') && NULL !== Configure::constant('SESSION_EXPIRED_TIME')){
				// 定義からセッションの有効期限を設定
				$expiredtime = Configure::SESSION_EXPIRED_TIME;
			}
			if(class_exists('Configure') && NULL !== Configure::constant('SESSION_TBL_NAME')){
				// 定義からセッションテーブル名を特定
				self::$_sessionTblName = Configure::SESSION_TBL_NAME;
			}
			if(class_exists('Configure') && NULL !== Configure::constant('SESSION_TBL_PKEY_NAME')){
				// 定義からセッションテーブルのPkey名を特定
				self::$_sessionTblPkeyName = Configure::SESSION_TBL_PKEY_NAME;
			}
			if(class_exists('Configure') && NULL !== Configure::constant('SESSION_DATE_KEY_NAME')){
				// 定義から日時フィールド名を特定
				self::$_sessionDateKeyName = Configure::SESSION_DATE_KEY_NAME;
			}
			if(defined('PROJECT_NAME') && strlen(PROJECT_NAME) > 0 && class_exists(PROJECT_NAME . 'Configure')){
				$ProjectConfigure = PROJECT_NAME . 'Configure';
				if(NULL !== $ProjectConfigure::constant('DB_DSN')){
					// 定義からセッションDBの接続情報を特定
					$DSN = $ProjectConfigure::DB_DSN;
				}
				if(NULL !== $ProjectConfigure::constant('SESSION_DB_DSN')){
					// 定義からセッションDBの接続情報を特定
					$DSN = $ProjectConfigure::SESSION_DB_DSN;
				}
				if(NULL !== $ProjectConfigure::constant('SESSION_EXPIRED_TIME')){
					// 定義からセッションの有効期限を設定
					$expiredtime = $ProjectConfigure::SESSION_EXPIRED_TIME;
				}
				if(NULL !== $ProjectConfigure::constant('SESSION_TBL_NAME')){
					// 定義からセッションテーブル名を特定
					self::$_sessionTblName = $ProjectConfigure::SESSION_TBL_NAME;
				}
				if(NULL !== $ProjectConfigure::constant('SESSION_TBL_PKEY_NAME')){
					// 定義からセッションテーブルのPkey名を特定
					self::$_sessionTblPkeyName = $ProjectConfigure::SESSION_TBL_PKEY_NAME;
				}
				if(NULL !== $ProjectConfigure::constant('SESSION_DATE_KEY_NAME')){
					// 定義から日時フィールド名を特定
					self::$_sessionDateKeyName = $ProjectConfigure::SESSION_DATE_KEY_NAME;
				}
			}

			// DBOを初期化
			if(NULL === self::$_DBO){
				if(NULL !== $argDSN){
					// セッションDBの接続情報を直指定
					$DSN = $argDSN;
				}
				self::$_DBO = new DBO($DSN);
			}

			// セッションの有効期限を設定
			if(NULL !== $argExpiredtime){
				// セッションの有効期限を直指定
				$expiredtime = $argExpiredtime;
			}
			self::$_expiredtime = $expiredtime;

			// セッションデータクラスの初期化
			parent::_init($expiredtime, $DSN);

			// トークンの初期化
			if(FALSE === self::_initializeToken()){
				// エラー
				throw new Exception(__CLASS__.PATH_SEPARATOR.__METHOD__.PATH_SEPARATOR.__LINE__.PATH_SEPARATOR.Utilities::getBacktraceExceptionLine());
			}

			// 初期化済み
			self::$_initialized = TRUE;
		}
	}

	/**
	 * トークンを固有識別子まで分解する
	 * 分解したトークンの有効期限チェックを自動で行います
	 * XXX 各システム毎に、Tokenの仕様が違う場合はこのメソッドをオーバーライドして実装を変更して下さい
	 * @param string トークン文字列
	 * @return mixed パースに失敗したらFALSE 成功した場合はstring 固有識別子を返す
	 */
	private static function _tokenToIdentifier($argToken){
		$token = $argToken;
		// 暗号化されたトークンの本体を取得
		$encryptedToken = substr($token, 0, 128);
		// トークンが発行された日時分秒文字列
		$tokenExpierd = substr($token, 128, 14);
		// トークンを複合
		$decryptToken = Utilities::doHexDecryptAES($encryptedToken, Configure::NETWORK_CRYPT_KEY, Configure::NETWORK_CRYPT_IV);
		// XXXデフォルトのUUIDはSHA256
		$identifier = substr($decryptToken, 0, 64);
		// トークンの中に含まれていた、トークンが発行された日時分秒文字列
		$tokenTRUEExpierd = substr($decryptToken, 36, 14);

		debug('$tokenTRUEExpierd=' . $tokenTRUEExpierd . '&$decryptToken=' . $decryptToken);
		// expierdの偽装チェックはココでしておく
		if(strlen($tokenExpierd) == 14 && $tokenExpierd == $tokenTRUEExpierd){
			// $tokenExpierdと$tokenTRUEExpierdが一致しない=$tokenExpierdが偽装されている！？
			// XXX ペナルティーレベルのクラッキングアクセス行為に該当
			logging(__CLASS__.PATH_SEPARATOR.__METHOD__.PATH_SEPARATOR.__LINE__, "hack");
			// パースに失敗したとみなす
			return FALSE;
		}

		// tokenの有効期限のチェック
		$year = substr($tokenTRUEExpierd, 0, 4);
		$month = substr($tokenTRUEExpierd, 4, 2);
		$day = substr($tokenTRUEExpierd, 6, 2);
		$hour = substr($tokenTRUEExpierd, 8, 2);
		$minute = substr($tokenTRUEExpierd, 10, 2);
		$second = substr($tokenTRUEExpierd, 12, 2);
		$tokenexpiredatetime = (int)Utilities::date('U', $year . '-' . $month . '-'. $day . ' ' . $hour . ':' . $minute . ':' . $second, 'GMT');
		$expiredatetime = (int)Utilities::modifyDate("-".(string)self::$_expiredtime . 'sec', 'U', NULL, NULL, 'GMT');
		debug('$tokenTRUEExpierd='.$tokenTRUEExpierd.'&$tokenexpiredatetime=' . $tokenexpiredatetime . '&$expiredatetime=' . $expiredatetime);
		if($tokenexpiredatetime < $expiredatetime){
			return FALSE;
		}

		return $identifier;
	}

	/**
	 * 固有識別子からトークンを生成する
	 * XXX 各システム毎に、Tokenの仕様が違う場合はこのメソッドをオーバーライドして実装を変更して下さい
	 * @param string identifier
	 * @return string token
	 */
	private static function _identifierToToken($argIdentifier){
		$identifier = $argIdentifier;
		$newExpiredDatetime = Utilities::modifyDate('+'.(string)self::$_expiredtime . 'sec', 'YmdHis', NULL, NULL, 'GMT');
		$token = Utilities::doHexEncryptAES($identifier.$newExpiredDatetime, Configure::NETWORK_CRYPT_KEY, Configure::NETWORK_CRYPT_IV).$newExpiredDatetime;
		return $token;
	}

	/**
	 * トークンの初期化
	 */
	private static function _initializeToken(){
		if(NULL === self::$_token){
			if(isset($_COOKIE[self::$_tokenKeyName])){
				// Cookieが在る場合はCookieからトークンと固有識別子を初期化する
				$token = $_COOKIE[self::$_tokenKeyName];
				$identifier = self::_tokenToIdentifier($token);
				if(FALSE !== $identifier){
					// XXX SESSIONレコードを走査
					// tokenとして認める
					self::$_token = $token;
					self::$_identifier = $identifier;
					return TRUE;
				}
			}
			else{
				// 固有識別子をセットする
				self::sessionID(self::$_identifier);
				// トークンがまだ無いので、最初のトークンとして空文字を入れておく
				self::$_token = "";
			}
			// セッションは存在したが、一致特定出来なかった時はエラー終了
			return FALSE;
		}
		return TRUE;
	}

	/**
	 * Cookieからトークンを出し入れする時のキー名を変えられるようにする為のアクセサ
	 * @param string トークンキー名
	 */
	public static function setTokenKey($argTokenKey){
		self::$_tokenKeyName = $argTokenKey;
	}

	/**
	 * 新しいトークンを指定のトークンキー名で払い出しcookieにセットする
	 * @param string トークンキー名
	 */
	public static function setTokenToCookie($argTokenKey){
		// 新しいtokenを発行する
		self::$_token = self::_identifierToToken(self::$_identifier);
		// クッキーを書き換える
		setcookie($argTokenKey, self::$_token, 0, self::$_path, self::$_domain);
		// XXX SESSHONレコードを更新
	}

	/**
	 * セッションIDを明示的に指定する
	 */
	public static function sessionID($argIdentifier=NULL){
		if(NULL === self::$_identifier && NULL === $argIdentifier){
			// idetifierが未セットの場合は自動生成
			self::$_identifier = sha256(uniqid());
		}
		if(NULL === $argIdentifier){
			// 現在設定されている固有識別子をセッションIDとして返却
			return self::$_identifier;
		}
		else{
			// 渡された値を固有識別子に書き換える
			self::$_identifier = $argIdentifier;
		}
	}

	/**
	 * セッションの開始する(_initのアクセサ)
	 * @param string セッションの範囲となるドメイン
	 * @param string セッションの有効期限
	 * @param string DBDSN情報
	 * @throws Exception
	 */
	public static function start($argDomain=NULL, $argExpiredtime=NULL, $argDSN=NULL){
		if(FALSE === self::$_initialized){
			self::_init($argDomain, $argExpiredtime, $argDSN);
		}
	}

	/**
	 * セッションの指定のキー名で保存されたデータを返す
	 * セッションが初期化されていなければ初期化する
	 * @param string キー名
	 * @param mixed 変数全て
	 */
	public static function get($argKey = NULL){
		if(FALSE === self::$_initialized){
			// 自動セッションスタート
			self::_init();
		}
		// XXX 標準ではセッションデータのPkeyはセッションの固有識別子
		return parent::get(self::$_identifier);
	}

	/**
	 * セッションに指定のキー名で指定のデータをしまう
	 * セッションが初期化されていなければ初期化する
	 * @param string キー名
	 * @param mixed 変数全て(PHPオブジェクトは保存出来ない！)
	 */
	public static function set($argKey, $argment){
		static $replaced = FALSE;
		if(FALSE === self::$_initialized){
			// 自動セッションスタート
			self::_init();
		}
		if(FALSE === $replaced){
			// Cookieの書き換えがまだなら書き換える
			self::setTokenToCookie(self::$_tokenKeyName);
			// 2度はヘッダ出力しない為に処理終了を取っておく
			$replaced = TRUE;
		}
		// XXX 標準ではセッションデータのPkeyはセッションの固有識別子
		return parent::set(self::$_identifier, $argKey, $argment);
	}
}

?>