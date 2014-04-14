<?php

/*
 -- --------------------------------------------------------

--
-- テーブルの構造 `t_session_tmp`
--

CREATE TABLE `t_session_tmp` (
		`token` varchar(256) NOT NULL COMMENT 'プライマリーキー',
		`data` longtext COMMENT 'シリアライズデータ',
		`created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'レコード作成日',
		PRIMARY KEY (`token`),
		KEY `t_session_tmp_idx1` (`token`,`created`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 comment='セッションDBテーブル';
*/

/**
 * アプリ用の独自Sessionクラス
 * アプリでの自動認証機構自動ログイン機構に対応させている
 * PHP標準のPHPSESSIDは使わず、tokenをCookieに書き込む方式によるSessionの実現の為のクラス
 * @author saimushi
 */
class AppSession {

	private static $_initialized = FALSE;
	private static $_sessionData = array();
	private static $_tokenKeyName = 'token';
	private static $_token = NULL;
	private static $_parseToken = array();
	private static $_domain = NULL;
	private static $_path = '/';
	// 60分
	private static $_expiredtime = 3600;

	private static $_tableName = 'tsessions';
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
				return self::checkToken($_COOKIE[self::$_tokenKeyName]);
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
		$UIID = self::$_parseToken['UIID'];
		// XXX 拡張
		$docomoID = self::$_parseToken['docomoID'];
		$kID = self::$_parseToken['kidsID'];
		self::$_token = self::resolveToken($UIID, $docomoID, $kID);
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
			// まだUIIDがsessionテーブルに入っていなければ追加する
			if(!isset(self::$_sessionData['UIID'])){
				if(isset(self::$_parseToken['UIID'])){
					self::$_sessionData['UIID'] = self::$_parseToken['UIID'];
					// XXX 拡張
					self::$_sessionData['docomoID'] = self::$_parseToken['docomoID'];
					self::$_sessionData['kidsID'] = self::$_parseToken['kidsID'];
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
				$argDSN = Configure::DB_DSN;
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
		debug("oldtoken=".self::$_token);
		self::_finalizeToken();
		debug("newtoken=".self::$_token);
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

	public static function getParseToken(){
		return self::$_parseToken;
	}
	
	public static function checkToken($token){
		if(strlen($token) > 64){
			// tokenをパース
			$encryptedToken = substr($token, 0, strlen($token)-14);
			debug('len=' . strlen($encryptedToken).' & $encryptedToken=' . $encryptedToken);
			$tokenExpierd = substr($token, strlen($token)-14, 14);
			$decryptToken = Utilities::doHexDecryptAES($encryptedToken, ProjectConfigure::NETWORK_CRYPT_KEY, ProjectConfigure::NETWORK_CRYPT_IV);
			$UIID = substr($decryptToken, 0, 36);
			$tokenTRUEExpierd = substr($decryptToken, 36, 14);
			// XXX 拡張
			$docomoID = substr($decryptToken, 50, strlen($decryptToken) - 50);
			debug('$tokenExpierd=' . $tokenExpierd . '&$tokenTRUEExpierd=' . $tokenTRUEExpierd . '&$decryptToken=' . $decryptToken);
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
					self::$_parseToken['UIID'] = $UIID;
					$kidsID = "";
					debug("iskids?=".$docomoID);
					if(false !== strpos($docomoID,":")){
						$docomoIDS = explode(":", $docomoID);
						$docomoID = $docomoIDS[0];
						$kidsID = $docomoIDS[1];
						debug("iskids!=".$kidsID);
					}
					self::$_parseToken['docomoID'] = $docomoID;
					self::$_parseToken['kidsID'] = $kidsID;
					return TRUE;
				}
			}
			else{
				// XXX ペナルティーレベルのクラッキングアクセス行為に該当
				logging(__CLASS__.PATH_SEPARATOR.__METHOD__.PATH_SEPARATOR.__LINE__.$decryptToken, "hack");
			}
		}
		return FALSE;
	}

	public static function resolveToken($UIID, $docomoID, $kidsID=""){
		$newExpiredDatetime = Utilities::modifyDate('+'.(string)self::$_expiredtime . 'sec', 'YmdHis', NULL, NULL, 'GMT');
		// XXX 拡張
		$token = Utilities::doHexEncryptAES($UIID.$newExpiredDatetime.$docomoID.":".$kidsID, ProjectConfigure::NETWORK_CRYPT_KEY, ProjectConfigure::NETWORK_CRYPT_IV).$newExpiredDatetime;
		// クッキーを書き換える
		setcookie(self::$_tokenKeyName, $token, 0, self::$_path, self::$_domain);
		return $token;
	}

	public static function setCookeToken($UIID, $docomoID, $kidsID=""){
		$_COOKIE[self::$_tokenKeyName] = self::resolveToken($UIID, $docomoID, $kidsID);
	}
}

?>