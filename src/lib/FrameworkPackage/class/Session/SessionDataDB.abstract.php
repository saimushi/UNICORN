<?php

/**
 * Sessionデータクラス(DB版)
 * @author saimushi
 */
abstract class SessionDBData {

	private static $_initialized = FALSE;
	private static $_expiredtime = 3600;// 60分
	private static $_sessionDataTblName = 'session_table';
	private static $_sessionDataTblPkeyName = 'uid';
	private static $_serializeKeyName = 'data';
	private static $_sessionDataDateKeyName = 'created';
	private static $_sessionData = NULL;
	private static $_DBO = NULL;

	/**
	 * Sessionクラスの初期化
	 */
	private static function _init($argExpiredtime=NULL, $argDSN=NULL){
		if(FALSE === self::$_initialized){

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
			if(class_exists('Configure') && NULL !== Configure::constant('SESSION_DATA_TBL_NAME')){
				// 定義からセッションデータテーブル名を特定
				self::$_sessionDataTblName = $ProjectConfigure::SESSION_DATA_TBL_NAME;
			}
			if(class_exists('Configure') && NULL !== Configure::constant('SESSION_DATA_TBL_PKEY_NAME')){
				// 定義からセッションデータテーブルのPkey名を特定
				self::$_sessionDataTblPkeyName = Configure::SESSION_DATA_TBL_PKEY_NAME;
			}
			if(class_exists('Configure') && NULL !== Configure::constant('SERIALIZE_KEY_NAME')){
				// 定義からシリアライズデータのフィールド名を特定
				self::$_serializeKeyName = Configure::SERIALIZE_KEY_NAME;
			}
			if(class_exists('Configure') && NULL !== Configure::constant('SESSION_DATA_DATE_KEY_NAME')){
				// 定義から日時フィールド名を特定
				self::$_sessionDataDateKeyName = Configure::DATE_KEY_NAME;
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
				if(NULL !== $ProjectConfigure::constant('SESSION_DATA_TBL_NAME')){
					// 定義からセッションデータテーブル名を特定
					self::$_sessionDataTblName = $ProjectConfigure::SESSION_DATA_TBL_NAME;
				}
				if(NULL !== $ProjectConfigure::constant('SESSION_DATA_TBL_PKEY_NAME')){
					// 定義からセッションデータテーブルのPkey名を特定
					self::$_sessionDataTblPkeyName = $ProjectConfigure::SESSION_DATA_TBL_PKEY_NAME;
				}
				if(NULL !== $ProjectConfigure::constant('SERIALIZE_KEY_NAME')){
					// 定義からuserTable名を特定
					self::$_serializeKeyName = $ProjectConfigure::SERIALIZE_KEY_NAME;
				}
				if(NULL !== $ProjectConfigure::constant('SESSION_DATA_DATE_KEY_NAME')){
					// 定義から日時フィールド名を特定
					self::$_sessionDataDateKeyName = $ProjectConfigure::DATE_KEY_NAME;
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

			// 初期化済み
			self::$_initialized = TRUE;
		}
	}

	/**
	 * セッションデータデーブルからデータを取得し復元する
	 */
	private static function _initializeData($argPkey){
		if(NULL === self::$_sessionData){
			$query = 'SELECT `' . self::$_serializeKeyName . '` FROM `' . self::$_sessionDataTblName . '` WHERE `' . self::$_sessionDataTblPkeyName . '` = :' . self::$_sessionDataTblPkeyName . ' AND `' . self::$_sessionDataDateKeyName . '` >= :expierddate ORDER BY `' . self::$_sessionDataDateKeyName . '` DESC limit 1';
			$date = Utilities::modifyDate('-' . (string)self::$_expiredtime . 'sec', 'Y-m-d H:i:s', NULL, NULL, 'GMT');
			$binds = array(self::$_sessionDataTblPkeyName => $argPkey, 'expierddate' => $date);
			$response = self::$_DBO->execute($query, $binds);
			if(is_object($response)){
				if(0 < $response->RecordCount()){
					$tmp = $response->GetArray();
					self::$_sessionData = json_decode($tmp[0][self::$_serializeKeyName], TRUE);
				}
				else{
					// 配列に初期化
					self::$_sessionData = array();
				}
			}
		}
		return TRUE;
	}

	/**
	 * システム毎に書き換え推奨
	 */
	private static function _finalizeData($argPkey){
		if(is_array(self::$_sessionData) && count(self::$_sessionData) > 0){
			// XXX identifierが変えられたかもしれないので、もう一度セレクトから
			$query = 'SELECT `' . self::$_serializeKeyName . '` FROM `' . self::$_sessionDataTblName . '` WHERE `' . self::$_sessionDataTblPkeyName . '` = :' . self::$_sessionDataTblPkeyName . ' ORDER BY `' . self::$_sessionDataDateKeyName . '` DESC limit 1';
			$date = Utilities::modifyDate('+' . (string)self::$_expiredtime . 'sec', 'Y-m-d H:i:s', NULL, NULL, 'GMT');
			$data = json_encode(self::$_sessionData);
			$binds = array(self::$_serializeKeyName => $data, self::$_sessionDataTblPkeyName => $argPkey, self::$_sessionDataDateKeyName => $date);
			$response = self::$_DBO->execute($query, array(self::$_sessionDataTblPkeyName => $argPkey));
			if(is_object($response) && 0 < $response->RecordCount()){
				$res = $response->GetAll();
				if($data !== $res[0][self::$_serializeKeyName]){
					// update
					$query = 'UPDATE `' . self::$_sessionDataTblName . '` SET `' . self::$_serializeKeyName . '` = :' . self::$_serializeKeyName . ' , `' . self::$_sessionDataDateKeyName . '` = :' . self::$_sessionDataDateKeyName . ' WHERE `' . self::$_sessionDataTblPkeyName . '` = :' . self::$_sessionDataTblPkeyName . ' ';
					$response = self::$_DBO->execute($query, $binds);
					if ($response) {
						return TRUE;
					}
				}
				return TRUE;
			}
			else{
				// insert
				$query = 'INSERT INTO `' . self::$_sessionDataTblName . '` (`' . self::$_serializeKeyName . '`, `' . self::$_sessionDataTblPkeyName . '`, `' . self::$_sessionDataDateKeyName . '`) VALUES ( :' . self::$_serializeKeyName . ' , :' . self::$_sessionDataTblPkeyName . ' , :' . self::$_sessionDataDateKeyName . ' )';
				try{
					$response = self::$_DBO->execute($query, $binds);
					if ($response) {
						return TRUE;
					}
				}
				catch (exception $Exception){
					// update
					// XXX この場合は、並列プロセス(Ajaxの非同期プロセス等)が先にinsertを走らせた場合に発生する
					$query = 'UPDATE `' . self::$_sessionDataTblName . '` SET `' . self::$_serializeKeyName . '` = :' . self::$_serializeKeyName . ' , `' . self::$_sessionDataDateKeyName . '` = :' . self::$_sessionDataDateKeyName . ' WHERE `' . self::$_sessionDataTblPkeyName . '` = :' . self::$_sessionDataTblPkeyName . ' ';
					$response = self::$_DBO->execute($query, $binds);
					if ($response) {
						return TRUE;
					}
				}
			}
			// XXX SESSIONExceptionクラスを実装予定
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

	public static function get($argPkey, $argKey = NULL, $argExpiredtime=NULL, $argDSN=NULL){
		if(FALSE === self::$_initialized){
			self::_init($argExpiredtime, $argDSN);
		}
		// データに実際にアクセスする時に、データの初期化は実行される
		if(NULL === self::$_sessionData){
			self::_initializeData($argPkey);
		}
		if(isset(self::$_sessionData[$argKey])){
			return self::$_sessionData[$argKey];
		}
		return NULL;
	}

	public static function set($argPkey, $argKey, $argment, $argExpiredtime=NULL, $argDSN=NULL){
		if(FALSE === self::$_initialized){
			self::_init($argExpiredtime, $argDSN);
		}
		// データに実際にアクセスする時に、データの初期化は実行される
		if(NULL === self::$_sessionData){
			self::_initializeData($argPkey);
		}
		// 配列にデータを追加
		self::$_sessionData[$argKey] = $argment;
		if(FALSE === self::_finalizeData($argPkey)){
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
			self::_init($argExpiredtime, $argDSN);
		}
		$query = 'DELETE FROM `' . self::$_sessionDataTblName . '` WHERE `' . self::$_sessionDataDateKeyName . '` <= :' . self::$_sessionDataDateKeyName . ' ';
		$date = Utilities::modifyDate('-' . (string)self::$_expiredtime . 'sec', 'Y-m-d H:i:s', NULL, NULL, 'GMT');
		$response = self::$_DBO->execute($query, array(self::$_sessionDataDateKeyName => $date));
		if (!$response) {
			// XXX
			logging(__CLASS__.PATH_SEPARATOR.__METHOD__.PATH_SEPARATOR.__LINE__.PATH_SEPARATOR.self::$_DBO->getLastErrorMessage(), 'exception');
		}
		return TRUE;
	}
}

?>