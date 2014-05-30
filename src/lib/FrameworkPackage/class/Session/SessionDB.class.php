<?php

/**
 * Sessionクラス(DB版)
 * @author saimushi
 */
class SessionDB extends SessionData implements SessionIO {

	private static $_started = FALSE;
	private static $_sessionData = array();
	private static $_tokenKeyName = 'token';
	private static $_token = NULL;
	private static $_UUID = NULL;
	private static $_domain = NULL;
	private static $_path = '/';
	private static $_expiredtime = 3600;// 60分
	private static $_sessionTblName = 'session_table';
	private static $_sessionDataTblName = 'session_table';
	private static $_sessionTblPkeyName = 'token';
	private static $_sessionDataTblPkeyName = 'token';
	private static $_serializeKeyName = 'data';
	private static $_dateKeyName = 'created';
	private static $_DBO;

	/**
	 * Sessionクラスの初期化
	 */
	private static function _init(){
		if(class_exists('Configure') && NULL !== Configure::constant('SESSION_TBL_NAME')){
			// 定義からセッションテーブル名を特定
			self::$_sessionTblName = Configure::SESSION_TBL_NAME;
		}
		if(class_exists('Configure') && NULL !== Configure::constant('SESSION_TBL_NAME')){
			// 定義からセッションデータテーブル名を特定
			self::$_sessionDataTblName = Configure::SESSION_DATA_TBL_NAME;
		}
		if(class_exists('Configure') && NULL !== Configure::constant('SESSION_TBL_PKEY_NAME')){
			// 定義からセッションテーブルのPkey名を特定
			self::$_sessionTblPkeyName = Configure::SESSION_TBL_PKEY_NAME;
		}
		if(class_exists('Configure') && NULL !== Configure::constant('SESSION_DATA_TBL_PKEY_NAME')){
			// 定義からセッションデータテーブルのPkey名を特定
			self::$_sessionDataTblPkeyName = Configure::SESSION_DATA_TBL_PKEY_NAME;
		}
		if(class_exists('Configure') && NULL !== Configure::constant('SERIALIZE_KEY_NAME')){
			// 定義からuserTable名を特定
			self::$_serializeKeyName = Configure::SERIALIZE_KEY_NAME;
		}
		if(class_exists('Configure') && NULL !== Configure::constant('DATE_KEY_NAME')){
			// 定義からuserTable名を特定
			self::$_dateKeyName = Configure::DATE_KEY_NAME;
		}
		if(defined('PROJECT_NAME') && strlen(PROJECT_NAME) > 0 && class_exists(PROJECT_NAME . 'Configure')){
			$ProjectConfigure = PROJECT_NAME . 'Configure';
			if(NULL !== $ProjectConfigure::constant('SESSION_TBL_NAME')){
				// 定義からセッションテーブル名を特定
				self::$_sessionTblName = $ProjectConfigure::SESSION_TBL_NAME;
			}
			if(NULL !== $ProjectConfigure::constant('SESSION_TBL_NAME')){
				// 定義からセッションデータテーブル名を特定
				self::$_sessionDataTblName = $ProjectConfigure::SESSION_DATA_TBL_NAME;
			}
			if(NULL !== $ProjectConfigure::constant('SESSION_TBL_PKEY_NAME')){
				// 定義からセッションテーブルのPkey名を特定
				self::$_sessionTblPkeyName = $ProjectConfigure::SESSION_TBL_PKEY_NAME;
			}
			if(NULL !== $ProjectConfigure::constant('SESSION_DATA_TBL_PKEY_NAME')){
				// 定義からセッションデータテーブルのPkey名を特定
				self::$_sessionDataTblPkeyName = $ProjectConfigure::SESSION_DATA_TBL_PKEY_NAME;
			}
			if(NULL !== $ProjectConfigure::constant('SERIALIZE_KEY_NAME')){
				// 定義からuserTable名を特定
				self::$_serializeKeyName = $ProjectConfigure::SERIALIZE_KEY_NAME;
			}
			if(NULL !== $ProjectConfigure::constant('DATE_KEY_NAME')){
				// 定義からuserTable名を特定
				self::$_dateKeyName = $ProjectConfigure::DATE_KEY_NAME;
			}
		}
	}

	/**
	 * トークンをUUIDまで分解する
	 * 分解したトークンの有効期限チェックを自動で行います
	 * XXX 各システム毎に、Tokenの仕様が違う場合はこのメソッドをオーバーライドして実装を変更して下さい
	 * @param string トークン文字列
	 * @return mixed パースに失敗したらFALSE 成功した場合はstring UUIDを返す
	 */
	private static function _tokenToUUID($argToken){
		$token = $argToken;
		// 暗号化されたトークンの本体を取得
		$encryptedToken = substr($token, 0, 128);
		// トークンが発行された日時分秒文字列
		$tokenExpierd = substr($token, 128, 14);
		// トークンを複合
		$decryptToken = Utilities::doHexDecryptAES($encryptedToken, Configure::NETWORK_CRYPT_KEY, Configure::NETWORK_CRYPT_IV);
		// XXXデフォルトのUUIDはSHA256
		$UUID = substr($decryptToken, 0, 64);
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

		return $UUID;
	}

	/**
	 * UUIDからトークンを生成する
	 * XXX 各システム毎に、Tokenの仕様が違う場合はこのメソッドをオーバーライドして実装を変更して下さい
	 * @param string UUID
	 * @return string token
	 */
	private static function _UUIDToToken($argUUID){
		$UUID = $argUUID;
		$newExpiredDatetime = Utilities::modifyDate('+'.(string)self::$_expiredtime . 'sec', 'YmdHis', NULL, NULL, 'GMT');
		$token = Utilities::doHexEncryptAES($UUID.$newExpiredDatetime, Configure::NETWORK_CRYPT_KEY, Configure::NETWORK_CRYPT_IV).$newExpiredDatetime;
		return $token;
	}

	/**
	 * システム毎に書き換え推奨
	 */
	private static function _initializeToken(){
		if(NULL === self::$_token){
			if(isset($_COOKIE[self::$_tokenKeyName])){
				$token = $_COOKIE[self::$_tokenKeyName];
				$UUID = self::_parseToken($token);
				if(FALSE !== $UUID){
					// tokenとして認める
					self::$_token = $token;
					self::$_UUID = $UUID;
					return TRUE;
				}
			}
			return FALSE;
		}
		return TRUE;
	}

	/**
	 * システム毎に書き換え推奨
	 */
	private static function _finalizeToken(){
		// 新しいtokenを発行する
		self::$_token = self::_UUIDToToken(self::$_UUID);
		// クッキーを書き換える
		setcookie(self::$_tokenKeyName, self::$_token, 0, self::$_path, self::$_domain);
	}

	/**
	 * システム毎に書き換え推奨
	 */
	private static function _initializeData(){
		if(is_array(self::$_sessionData) && count(self::$_sessionData) === 0){
			$query = 'SELECT `' . self::$_serializeKeyName . '` FROM `' . self::$_tableName . '` WHERE `' . self::$_pkeyName . '` = :' . self::$_pkeyName . ' AND `' . self::$_dateKeyName . '` >= :expierddate ORDER BY `' . self::$_dateKeyName . '` DESC limit 1';
			$date = Utilities::modifyDate('-' . (string)self::$_expiredtime . 'sec', 'Y-m-d H:i:s', NULL, NULL, 'GMT');
			$binds = array(self::$_pkeyName => self::$_token, 'expierddate' => $date);
			$response = self::$_DBO->execute($query, $binds);
			if(is_object($response)){
				if(0 < $response->RecordCount()){
					$tmp = $response->GetArray();
					self::$_sessionData = json_decode($tmp[0][self::$_serializeKeyName], TRUE);
				}
			}
			// まだUUIDがsessionテーブルに入っていなければ追加する
			if(!isset(self::$_sessionData['UUID'])){
				self::$_sessionData['UUID'] = self::$_UUID;
				if(FALSE === self::_finalizeData()){
					// エラー
					throw new Exception(__CLASS__.PATH_SEPARATOR.__METHOD__.PATH_SEPARATOR.__LINE__.PATH_SEPARATOR.Utilities::getBacktraceExceptionLine());
				}
				return TRUE;
			}
		}
		return TRUE;
	}

	/**
	 * システム毎に書き換え推奨
	 */
	private static function _finalizeData(){
		if(is_array(self::$_sessionData) && count(self::$_sessionData) > 0){
			$query = 'SELECT `' . self::$_serializeKeyName . '` FROM `' . self::$_tableName . '` WHERE `' . self::$_pkeyName . '` = :' . self::$_pkeyName . ' ORDER BY `' . self::$_dateKeyName . '` DESC limit 1';
			$date = Utilities::modifyDate('+' . (string)self::$_expiredtime . 'sec', 'Y-m-d H:i:s', NULL, NULL, 'GMT');
			$data = json_encode(self::$_sessionData);
			$binds = array(self::$_serializeKeyName => $data, self::$_pkeyName => self::$_token, self::$_dateKeyName => $date);
			$response = self::$_DBO->execute($query, array(self::$_pkeyName => self::$_token));
			if(is_object($response) && 0 < $response->RecordCount()){
				$res = $response->GetAll();
				if($data !== $res[0][self::$_serializeKeyName]){
					// update
					$query = 'UPDATE `' . self::$_tableName . '` SET `' . self::$_serializeKeyName . '` = :' . self::$_serializeKeyName . ' , `' . self::$_dateKeyName . '` = :' . self::$_dateKeyName . ' WHERE `' . self::$_pkeyName . '` = :' . self::$_pkeyName . ' ';
					$response = self::$_DBO->execute($query, $binds);
					if ($response) {
						return TRUE;
					}
				}
				return TRUE;
			}
			else{
				// insert
				$query = 'INSERT INTO `' . self::$_tableName . '` (`' . self::$_serializeKeyName . '`, `' . self::$_pkeyName . '`, `' . self::$_dateKeyName . '`) VALUES ( :' . self::$_serializeKeyName . ' , :' . self::$_pkeyName . ' , :' . self::$_dateKeyName . ' )';
				try{
					$response = self::$_DBO->execute($query, $binds);
					if ($response) {
						return TRUE;
					}
				}
				catch (exception $Exception){
					// update
					$query = 'UPDATE `' . self::$_tableName . '` SET `' . self::$_serializeKeyName . '` = :' . self::$_serializeKeyName . ' , `' . self::$_dateKeyName . '` = :' . self::$_dateKeyName . ' WHERE `' . self::$_pkeyName . '` = :' . self::$_pkeyName . ' ';
					$response = self::$_DBO->execute($query, $binds);
					if ($response) {
						return TRUE;
					}
				}
			}
			// XXX
			logging(__CLASS__.PATH_SEPARATOR.__METHOD__.PATH_SEPARATOR.__LINE__.PATH_SEPARATOR.self::$_DBO->getLastErrorMessage(), 'exception');
			return FALSE;
		}
		return TRUE;
	}

	public static function start($argDomain=NULL, $argExpiredtime=NULL, $argDSN=NULL){
		self::$_domain = $_SERVER['SERVER_NAME'];
		if(NULL !== $argDomain){
			self::$_domain = $argDomain;
		}
		if(NULL !== $argExpiredtime){
			self::$_expiredtime = $argExpiredtime;
		}
		// DBOをセットしておく
		if(FALSE === self::$_started){
			if(NULL === $argDSN){
				$argDSN = Configure::DB_DSN;
			}
			self::$_DBO = new DBO($argDSN);
		}
		self::$_started = TRUE;
	}

	public static function count(){
		count(self::$_sessionData);
	}

	public static function keys(){
		return array_keys(self::$_sessionData);
	}

	public static function get($argKey = NULL){
		if(FALSE === self::$_started){
			self::start();
		}
		if(FALSE === self::_initializeToken()){
			// エラー
			throw new Exception(__CLASS__.PATH_SEPARATOR.__METHOD__.PATH_SEPARATOR.__LINE__.PATH_SEPARATOR.Utilities::getBacktraceExceptionLine());
		}
		if(FALSE === self::_initializeData()){
			// エラー
			throw new Exception(__CLASS__.PATH_SEPARATOR.__METHOD__.PATH_SEPARATOR.__LINE__.PATH_SEPARATOR.Utilities::getBacktraceExceptionLine());
		}
		if(isset(self::$_sessionData[$argKey])){
			return self::$_sessionData[$argKey];
		}
		return NULL;
	}

	public static function set($argKey, $argment){
		if(FALSE === self::$_started){
			self::start();
		}
		if(FALSE === self::_initializeToken()){
			// エラー
			throw new Exception(__CLASS__.PATH_SEPARATOR.__METHOD__.PATH_SEPARATOR.__LINE__.PATH_SEPARATOR.Utilities::getBacktraceExceptionLine());
		}
		if(FALSE === self::_initializeData()){
			// エラー
			throw new Exception(__CLASS__.PATH_SEPARATOR.__METHOD__.PATH_SEPARATOR.__LINE__.PATH_SEPARATOR.Utilities::getBacktraceExceptionLine());
		}
		self::$_sessionData[$argKey] = $argment;
		self::_finalizeToken();
		if(FALSE === self::_finalizeData()){
			// エラー
			throw new Exception(__CLASS__.PATH_SEPARATOR.__METHOD__.PATH_SEPARATOR.__LINE__.PATH_SEPARATOR.Utilities::getBacktraceExceptionLine());
		}
		return TRUE;
	}

	/**
	 * Expiredの切れたSessionレコードをDeleteする
	 */
	public static function clean(){
		if(FALSE === self::$_started){
			self::start();
		}
		$query = 'DELETE FROM `' . self::$_tableName . '` WHERE `' . self::$_dateKeyName . '` <= :' . self::$_dateKeyName . ' ';
		$date = Utilities::modifyDate('-' . (string)self::$_expiredtime . 'sec', 'Y-m-d H:i:s', NULL, NULL, 'GMT');
		$response = self::$_DBO->execute($query, array(self::$_dateKeyName => $date));
		if (!$response) {
			// XXX
			logging(__CLASS__.PATH_SEPARATOR.__METHOD__.PATH_SEPARATOR.__LINE__.PATH_SEPARATOR.self::$_DBO->getLastErrorMessage(), 'exception');
		}
		return TRUE;
	}
}

?>