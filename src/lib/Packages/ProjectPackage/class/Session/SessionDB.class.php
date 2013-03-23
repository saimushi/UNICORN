<?php

/**
 * 独自Session
 * PHP標準のPHPSESSIDは使わず、tokenをCookieに書き込む方式によるSessionの実現の為のクラス
 * @author saimushi
 */
class SessionDB {

	private static $_initialized = FALSE;
	private static $_sessionData = array();
	private static $_tokenKeyName = 'token';
	private static $_token = NULL;
	private static $_parseToken = array();
	private static $_domain = NULL;
	private static $_path = '/';
	// 60分
	private static $_expiredtime = 3600;

	private static $_tableName = 't_session_tmp';
	private static $_pkeyName = 'token';
	private static $_serializeKeyName = 'data';
	private static $_dateKeyName = 'created';
	private static $_DBO;

	/**
	 * システム毎に書き換え推奨
	 */
	private static function _initializeToken(){
		if(NULL === self::$_token){
			if(isset($_COOKIE[self::$_tokenKeyName])){
				$token = $_COOKIE[self::$_tokenKeyName];
				// tokenをパース
				$encryptedToken = substr($token, 0, 128);
				$tokenExpierd = substr($token, 128, 14);
				$decryptToken = Utilities::doHexDecryptAES($encryptedToken, Config::NETWORK_CRYPT_KEY, Config::NETWORK_CRYPT_IV);
				$UUID = substr($decryptToken, 0, 36);
				$tokenTRUEExpierd = substr($decryptToken, 36, 14);
				debug('$tokenTRUEExpierd=' . $tokenTRUEExpierd . '&$decryptToken=' . $decryptToken);
				// expierdの正当性チェック
				if(strlen($tokenExpierd) == 14 && $tokenExpierd == $tokenTRUEExpierd){
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
					if($tokenexpiredatetime >= $expiredatetime){
						// tokenとして認める
						self::$_token = $token;
						self::$_parseToken['UUID'] = $UUID;
						return TRUE;
					}
				}
				else{
					// XXX ペナルティーレベルのクラッキングアクセス行為に該当
					logging(__CLASS__.PATH_SEPARATOR.__METHOD__.PATH_SEPARATOR.__LINE__, "hack");
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
		if(FALSE === self::$_initialized){
			self::start();
		}
		if(NULL === self::$_token){
			if(FALSE === self::_initializeToken()){
				// エラー
				throw new Exception(__CLASS__.PATH_SEPARATOR.__METHOD__.PATH_SEPARATOR.__LINE__.PATH_SEPARATOR.Utilities::getBacktraceExceptionLine());
			}
			if(FALSE === self::_initializeData()){
				// エラー
				throw new Exception(__CLASS__.PATH_SEPARATOR.__METHOD__.PATH_SEPARATOR.__LINE__.PATH_SEPARATOR.Utilities::getBacktraceExceptionLine());
			}
		}
		// 新しいtokenを発行する
		$UUID = self::$_parseToken['UUID'];
		$newExpiredDatetime = Utilities::modifyDate('+'.(string)self::$_expiredtime . 'sec', 'YmdHis', NULL, NULL, 'GMT');
		self::$_token = Utilities::doHexEncryptAES($UUID.$newExpiredDatetime, Config::NETWORK_CRYPT_KEY, Config::NETWORK_CRYPT_IV).$newExpiredDatetime;
		// クッキーを書き換える
		setcookie(self::$_tokenKeyName, self::$_token, 0, self::$_path, self::$_domain);
	}

	/**
	 * システム毎に書き換え推奨
	 */
	private static function _initializeData(){
		if(is_array(self::$_sessionData) && count(self::$_sessionData) === 0){
			if(FALSE === self::$_initialized){
				self::start();
			}
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
				if(isset(self::$_parseToken['UUID'])){
					self::$_sessionData['UUID'] = self::$_parseToken['UUID'];
					if(FALSE === self::_finalizeData()){
						// エラー
						throw new Exception(__CLASS__.PATH_SEPARATOR.__METHOD__.PATH_SEPARATOR.__LINE__.PATH_SEPARATOR.Utilities::getBacktraceExceptionLine());
					}
					return TRUE;
				}
				return FALSE;
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

	/**
	 * システム毎に書き換え推奨
	 */
	public static function start($argDomain=NULL, $argExpiredtime=NULL, $argDSN=NULL){
		self::$_domain = $_SERVER['SERVER_NAME'];
		if(NULL !== $argDomain){
			self::$_domain = $argDomain;
		}
		if(NULL !== $argExpiredtime){
			self::$_expiredtime = $argExpiredtime;
		}
		// DBOをセットしておく
		if(FALSE === self::$_initialized){
			if(NULL === $argDSN){
				$argDSN = Config::DB_DSN;
			}
			self::$_DBO = new DBO($argDSN);
		}
		self::$_initialized = TRUE;
	}

	public static function count(){
		count(self::$_sessionData);
	}

	public static function keys(){
		return array_keys(self::$_sessionData);
	}

	public static function get($argKey = NULL){
		if(FALSE === self::_initializeToken()){
			// エラー
			debug("issa?");
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
		self::$_sessionData[$argKey] = $argment;
		debug(self::$_token);
		self::_finalizeToken();
		debug(self::$_token);
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
		if(FALSE === self::$_initialized){
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