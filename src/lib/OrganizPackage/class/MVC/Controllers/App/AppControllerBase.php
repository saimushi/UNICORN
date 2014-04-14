<?php

/**
 * APIのベースコントローラクラス
 */
class AppControllerBase extends ControllerBase {

	// XXX orverride
	public $jsonUnescapedUnicode = true;

	/**
	 * メインアクション
	 */
	protected function _execute($argment=null){
		try{
			if(null === $argment){
				$argment="_execute";
			}
			$response = $this->$argment();
			Session::clean();
			$this->_DBO->commit();
			return $response;
		}
		catch (Exception $Exception){
			logging(__CLASS__.PATH_SEPARATOR.__METHOD__.PATH_SEPARATOR.__LINE__.PATH_SEPARATOR.$Exception->getMessage(), "exception");
			$status = API_ERROR_CODE_FATAL;
			$exceptionMsg = $Exception->getMessage();
			if(false !== strpos($exceptionMsg, "Session:")){
				// セッションエラー
				$status = API_ERROR_CODE_NOSESSION;
			}
			$exceptionCode = $Exception->getCode();
			if(0 != $exceptionCode){
				$status = str_pad($exceptionCode, 3, "0", STR_PAD_LEFT);
			}
			return $this->_error($status);
		}
	}

	/**
	 * アプリ認証
	 * @param boolean 認証状態をtrue、falseで返すかどうか
	 * @return boolean
	 */
	public static function auth($argValidateOnly=false){
		$res = true;
		if(null === Core::$CurrentController->loginedUserID){
			// Sessionの初期化
			AppSession::start($_SERVER["SERVER_NAME"], ProjectConfigure::SESSION_EXPIRED_TIME);
			$UIID = AppSession::get("UIID");
			$docomoID = AppSession::get("docomoID");
			debug("UIID=" . $UIID);
			debug("docomoID=" . $docomoID);
			$LoginedUser = ORMapper::getModel(DBO::sharedInstance(), "t_user", " WHERE uiid = :uiid AND available = '1' ", array("uiid" => $UIID));
			if (!$LoginedUser->id) {
				$res = false;
			}
			else {
				Core::$CurrentController->LoginedUser = $LoginedUser;
				Core::$CurrentController->loginedUserID = $LoginedUser->id;
				Core::$CurrentController->loginedUserUIID = $UIID;
				Core::$CurrentController->loginedUserDocomoID = $docomoID;
				AppSession::set("loginedUserID", $LoginedUser->id);
				AppSession::set("loginedFirstName", $LoginedUser->first_name);
				AppSession::set("loginedLastName", $LoginedUser->last_name);
				AppSession::set("loginedCountryCode", $LoginedUser->country_code);
			}
		}
		if(false === $res && false === $argValidateOnly){
			// ログインユーザが取得できない
			throw new Exception(__CLASS__.PATH_SEPARATOR.__METHOD__.PATH_SEPARATOR.__LINE__, API_ERROR_CODE_NOAUTH);
		}
		return $res;
	}
}

?>