<?php

/**
 * 独自Session
 * PHP標準のPHPSESSIDは使わず、tokenをCookieに書き込む方式によるSessionの実現の為のクラス
 * @author saimushi
 */
abstract class SessionData {

	private static $_initialized = FALSE;
	private static $_sessionData = array();
	private static $_tokenKeyName = 'token';
	private static $_token = NULL;
	private static $_parseToken = array();
	private static $_domain = NULL;
	private static $_path = '/';
	// 60分
	private static $_expiredtime = 3600;

	private static $_tableName = 'session_data';
	private static $_pkeyName = 'uid';
	private static $_serializeKeyName = 'data';
	private static $_dateKeyName = 'created';
	private static $_DBO;

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