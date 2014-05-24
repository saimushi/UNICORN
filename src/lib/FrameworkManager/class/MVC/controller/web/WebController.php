<?php

// 000番台(正常終了)
define("API_SUCCESS_CODE", "000");

// 000番台(セッションの異常)
define("API_ERROR_CODE_NOSESSION", "001");
define("API_ERROR_CODE_NOAUTH", "002");
define("API_ERROR_CODE_NOUUID", "003");
define("API_ERROR_CODE_APP_REVIEWER", "004");

// 100番台(POSTパラメータの異常)
define("API_ERROR_CODE_MISSMATCH_AUTHCODE", "100");
define("API_ERROR_CODE_OVER_EXPIRED_AUTHCODE", "101");
define("API_ERROR_CODE_EXISTS_TELEPHONE", "102");
define("API_ERROR_CODE_NOT_ASSIGN_USERID", "103");
define("API_ERROR_CODE_TYPE_MISSMATCH_USERID", "104");
define("API_ERROR_CODE_MISSMATCH_LOGINEDUSER", "105");
define("API_ERROR_CODE_NOT_ASSIGN_ALBUMID", "106");
define("API_ERROR_CODE_TYPE_MISSMATCH_ALBUMID", "107");
define("API_ERROR_CODE_NOTFOUND_ALBUM", "108");
define("API_ERROR_CODE_MISSMATCH_ENTERED_ALBUM", "109");
define("API_ERROR_CODE_NOT_ASSIGN_NECESSARY_PARAM", "110");
define("API_ERROR_CODE_TYPE_MISSMATCH_NECESSARY_PARAM", "111");
define("API_ERROR_CODE_NOTFOUND_NECESSARY_RECORD", "112");
define("API_ERROR_CODE_EXISTS_UNIQID", "113");
define("API_ERROR_CODE_EXISTS_MAILADDRESS", "114");
define("API_ERROR_CODE_DIFFERENT_FORMAT_MAILADDRESS", "115");
define("API_ERROR_CODE_NOT_EXISTS_USER_DONT_ADD_GROUP", "116");
define("API_ERROR_CODE_TYPE_MISSMATCH_ALBUMNAME", "117");
define("API_ERROR_CODE_NOT_EXISTS_SELLITEMID", "118");
define("API_ERROR_CODE_NOT_EXISTS_RECEIPT", "119");
define("API_ERROR_CODE_NOT_EXISTS_SIGNATURE", "120");
define("API_ERROR_CODE_NOTFOUND_SELLITEM_RECORD", "121");
define("API_ERROR_CODE_NOT_EXISTS_PLATFORM", "122");
define("API_ERROR_CODE_NOT_EXISTS_SERIAL_CODE", "123");
define("API_ERROR_CODE_NOTFOUND_SERIAL_CODE", "124");
define("API_ERROR_CODE_EXPIRATION_STAMP", "125");

// 200番台(ファイル操作エラー)
define("API_ERROR_CODE_NOTFOUND_TMPIMAGE", "200");
define("API_ERROR_CODE_FAILED_FILEUPLOAD", "201");
define("API_ERROR_CODE_USERICON_MOVE_FAILED", "202");

// 300番台(特殊以上)
define("API_ERROR_CODE_NOT_EXISTS_IMAGE", "300");
define("API_ERROR_CODE_BLOCKED", "301");
define("API_ERROR_CODE_NOT_CREATE_MYSELF_ALBUM", "302");
define("API_ERROR_CODE_INCORRECT_RECEIPT", "303");
define("API_ERROR_CODE_NOT_CREATE_PUBLIC_KEY", "304");
define("API_ERROR_CODE_SECRET_MISSMATCH", "305");
define("API_ERROR_CODE_INSENTIVMODE_EXISTS", "306");
define("API_ERROR_CODE_NOQUESTION", "307");

// 900番台(異常系)
define("API_ERROR_CODE_MAINTENANCE", "900");
define("API_ERROR_CODE_DBCONNECT", "901");
define("API_ERROR_CODE_EXCEPTION", "902");
define("API_ERROR_CODE_NOTMUST_APPVERSION", "903");
define("API_ERROR_CODE_FATAL", "999");

/**
 * APIのベースコントローラクラス
*/
class WebViewController extends ControllerBase {

	public $loginedUserIdentifier = null;
	public $loginedUserDocomoID = null;
	public $loginedUserKidsID = null;
	public $nowGMT = null;

	protected $_action = "execute";

	/**
	 * メインアクション
	 * MVCCoreからトライキャッチを奪う為、executeは常にココで処理される
	 */
	public function execute($argment=null){
		$this->nowGMT = Utilities::date("Y-m-d H:i:s", null, null, "GMT");
		try{
			if(null === $argment){
				$argment="_execute";
			}

			// ベースのhtml
			$html = "";

			// Auth認証
			self::auth();

			// メイン処理の実行
			$response = $this->$argment();

			// DBを一度閉じる
			DBO::sharedInstance()->commit();

			// 不要なセッションレコードの削除
			AppSession::clean();

			$params = null;
			// 成功ステータスをセット
			if(is_array($response)){
				$params = $response;
			}

			// Viewの処理
			$html = Core::loadView()->execute(null, $params);

			// htmlの返却
			return $html;
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

	protected function _error($argStatus = "999"){
		debug("rollback");
		@DBO::sharedInstance()->rollback();

		$outputArr = array();
		$outputArr["status"] = $argStatus;
		$outputArr["error"] = API_ERROR_MSG_EXCEPTION;

		// システムエラー
		if(API_ERROR_CODE_FATAL === $argStatus){
			$outputArr["error"] = API_ERROR_MSG_FATAL;
		}
		// 必須バージョンエラー
		elseif(API_ERROR_CODE_NOTMUST_APPVERSION === $argStatus){
			$outputArr["error"] = API_ERROR_MSG_NOTMUST_APPVERSION;
		}
		// Sessionの不整合
		elseif(API_ERROR_CODE_NOSESSION === $argStatus){
			$outputArr["error"] = API_ERROR_MSG_NOAUTH;
		}
		elseif(API_ERROR_CODE_NOAUTH === $argStatus){
			$outputArr["error"] = API_ERROR_MSG_NOAUTH;
		}
		elseif(API_ERROR_CODE_NOUUID === $argStatus){
			$outputArr["error"] = sprintf(API_ERROR_MSG_NOUUID, API_ERROR_CODE_NOUUID);
		}
		// 電話番号認証エラー
		elseif(API_ERROR_CODE_MISSMATCH_AUTHCODE === $argStatus || API_ERROR_CODE_OVER_EXPIRED_AUTHCODE === $argStatus){
			$outputArr["error"] = API_ERROR_MSG_MISSMATCH_AUTHCODE;
		}
		// ファイル操作のエラー
		elseif(API_ERROR_CODE_NOTFOUND_TMPIMAGE === $argStatus || API_ERROR_CODE_FAILED_FILEUPLOAD === $argStatus || API_ERROR_CODE_USERICON_MOVE_FAILED === $argStatus){
			$outputArr["error"] = API_ERROR_MSG_MISS_UPLOAD;
		}
		// ユニークID設定エラー
		elseif(API_ERROR_CODE_EXISTS_UNIQID === $argStatus){
			$outputArr["error"] = API_ERROR_MSG_EXISTS_UNIQID;
		}
		// ブロックのための参照無効エラー
		elseif(API_ERROR_CODE_BLOCKED === $argStatus){
			$outputArr["error"] = API_ERROR_MSG_BLOCKED;
		}
		// 自分で自分のアルバムを作るのは無効エラー
		elseif(API_ERROR_CODE_NOT_CREATE_MYSELF_ALBUM === $argStatus){
			$outputArr["error"] = API_ERROR_MSG_NOT_CREATE_MYSELF_ALBUM;
		}
		// メールアドレス形式エラー
		elseif(API_ERROR_CODE_DIFFERENT_FORMAT_MAILADDRESS === $argStatus){
			$outputArr["error"] = API_ERROR_MSG_DIFFERENT_FORMAT_MAILADDRESS;
		}
		// メールアドレス重複エラー
		elseif(API_ERROR_CODE_EXISTS_MAILADDRESS === $argStatus){
			$outputArr["error"] = API_ERROR_MSG_EXISTS_MAILADDRESS;
		}
		// 電話番号重複エラー
		elseif(API_ERROR_CODE_EXISTS_TELEPHONE === $argStatus){
			$outputArr["error"] = API_ERROR_MSG_EXISTS_TELEPHONE;
		}
		// グループADDエラー
		elseif(API_ERROR_CODE_NOT_EXISTS_USER_DONT_ADD_GROUP === $argStatus){
			$outputArr["error"] = API_ERROR_MSG_NOT_EXISTS_USER_DONT_ADD_GROUP;
		}
		// グループADDエラー
		elseif(API_ERROR_CODE_TYPE_MISSMATCH_ALBUMNAME === $argStatus){
			$outputArr["error"] = API_ERROR_MSG_TYPE_MISSMATCH_ALBUMNAME;
		}
		// シリアルコード該当無しエラー
		elseif(API_ERROR_CODE_NOTFOUND_SERIAL_CODE === $argStatus){
			$outputArr["error"] = API_ERROR_MSG_NOTFOUND_SERIAL_CODE;
		}
		// スタンプ期限切れ
		elseif(API_ERROR_CODE_EXPIRATION_STAMP === $argStatus){
			$outputArr["error"] = API_ERROR_MSG_EXPIRATION_STAMP;
		}
		// 秘密の質問の解答間違い
		elseif(API_ERROR_CODE_SECRET_MISSMATCH === $argStatus){
			$outputArr["error"] = API_ERROR_MSG_SECRET_MISSMATCH;
		}
		// 既にインセンティブに紐付け済みのアルバムがある場合
		elseif(API_ERROR_CODE_INSENTIVMODE_EXISTS === $argStatus){
			$outputArr["error"] = API_ERROR_MSG_INSENTIVMODE_EXISTS;
		}
		// 問題がまだ無い
		elseif(API_ERROR_CODE_NOQUESTION === $argStatus){
			$outputArr["error"] = API_ERROR_MSG_NOQUESTION;
		}

		// エラーメッセージ表示文字の整形
		if(API_ERROR_CODE_NOQUESTION !== $argStatus){
			$outputArr["error"] = "ERROR CODE:".$outputArr["status"]."\n".$outputArr["error"];
		}

		return json_encode($outputArr);
	}

	/**
	 * アプリ認証
	 * @param boolean 認証状態をtrue、falseで返すかどうか
	 * @return boolean
	 */
	public static function auth(){
		if(null === Core::$CurrentController->loginedUserID){
			// Sessionの初期化
			AppSession::start($_SERVER["SERVER_NAME"], ProjectConfigure::SESSION_EXPIRED_TIME);
			if(!isset($_COOKIE["token"]) && isset($_GET["token"])){
				if(true !== AppSession::checkToken($_GET["token"])){
					// tokenエラー
					throw new Exception(__CLASS__.PATH_SEPARATOR.__METHOD__.PATH_SEPARATOR.__LINE__.PATH_SEPARATOR."Session:parse error");
				}
				// getのトークンで認証(初回のみ)
				$parsedTokens = AppSession::getParseToken();
				// CookieにTokenをしまう
				AppSession::setCookeToken($parsedTokens["UIID"], $parsedTokens["docomoID"], $parsedTokens["kidsID"]);
			}
			// 常時認証
			$UIID = AppSession::get("UIID");
			$docomoID = AppSession::get("docomoID");
			$kidsID = AppSession::get("kidsID");
			$identifier = $docomoID;
			if(null !== $kidsID && "" !== $kidsID){
				$identifier .= ":" . $kidsID;
			}
			// 認証ユーザーの特定
			debug("identifier=".$identifier);
			$LoginedUser = ORMapper::getModel(DBO::sharedInstance(), "tusers", " WHERE identifier = :identifier AND available = '1' ", array("identifier" => $identifier));
			if (!$LoginedUser->id) {
				// 認証エラー
				throw new Exception(__CLASS__.PATH_SEPARATOR.__METHOD__.PATH_SEPARATOR.__LINE__.PATH_SEPARATOR."Session:not found user");
			}
			else {
				Core::$CurrentController->LoginedUser = $LoginedUser;
				Core::$CurrentController->loginedUserID = $LoginedUser->id;
				Core::$CurrentController->loginedUserIdentifier = $identifier;
				Core::$CurrentController->loginedUserDocomoID = $docomoID;
				Core::$CurrentController->loginedUserKidsID = $kidsID;
				AppSession::set("loginedUserID", $LoginedUser->id);
			}
		}
	}
}

?>