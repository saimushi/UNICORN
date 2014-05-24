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
class AppController extends ControllerBase {

	public $loginedUserIdentifier = null;
	public $loginedUserDocomoID = null;
	public $loginedUserKidsID = null;
	public $nowGMT = null;

	// orverride
	public $jsonUnescapedUnicode = true;

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
			// Auth認証
			if(false === self::auth()){
				// 自動会員登録
				$UIID = AppSession::get("UIID");
				$docomoID = AppSession::get("docomoID");
				$kidsID = AppSession::get("kidsID");
				debug($UIID);
				debug($docomoID);
				debug($kidsID);
				$identifier = $docomoID;
				if(null !== $kidsID && "" !== $kidsID){
					$identifier .= ":" . $kidsID;
				}
				// ユーザー登録
				$LoginedUser = ORMapper::getModel(DBO::sharedInstance(), "tusers", " WHERE identifier = :identifier AND available = '1' ", array("identifier" => $identifier));
				$LoginedUser->setIdentifier($identifier);
				$LoginedUser->setDocomoId($docomoID);
				$LoginedUser->setKidsId($kidsID);
				$LoginedUser->setCreated($this->nowGMT);
				$LoginedUser->setModified($this->nowGMT);
				$LoginedUser->save();
				if(!$LoginedUser->id){
					// ログインユーザが取得できない
					throw new Exception(__CLASS__.PATH_SEPARATOR.__METHOD__.PATH_SEPARATOR.__LINE__, API_ERROR_CODE_NOAUTH);
				}
				// デバイス登録
				$LoginedDevice = ORMapper::getModel(DBO::sharedInstance(), "tdevices", " WHERE uiid = :uiid AND available = '1' ", array("uiid" => $UIID));
				$LoginedDevice->setUserId($LoginedUser->id);
				$LoginedDevice->setVersion($this->appVersion);
				$LoginedDevice->setModified($this->nowGMT);
				if(!$LoginedDevice->id){
					$LoginedDevice->setUiid($UIID);
					$LoginedDevice->setOs($this->deviceType);
					$LoginedDevice->setCreated($this->nowGMT);
				}
				$LoginedDevice->save();
				if(!$LoginedDevice->id){
					// ログインユーザが取得できない
					throw new Exception(__CLASS__.PATH_SEPARATOR.__METHOD__.PATH_SEPARATOR.__LINE__, API_ERROR_CODE_NOAUTH);
				}
				// 再度authする
				if(false === self::auth(true)){
					// ログインユーザが取得できない
					throw new Exception(__CLASS__.PATH_SEPARATOR.__METHOD__.PATH_SEPARATOR.__LINE__, API_ERROR_CODE_NOAUTH);
				}
			}
			// ゲーム結果データ取得
			$result = $this->putResult();

			// メイン処理の実行
			$response = true;
			$response = $this->$argment();

			if(is_array($response)){
				$responseTmp = $response;
				$response = array_merge($responseTmp, $result);
			}
			else if(true === $response){
				$response = $result;
			}

			// DBを一度閉じる
			DBO::sharedInstance()->commit();

			// お知らせデータ取得
			$info = $this->getInfo();
			if(is_array($response)){
				$responseTmp = $response;
				$response = array_merge($responseTmp, $info);
			}
			else if(true === $response){
				$response = $info;
			}

			// 不要なセッションレコードの削除
			AppSession::clean();

			// 成功ステータスをセット
			if(is_array($response)){
				$response["status"] = API_SUCCESS_CODE;
			}
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
			if(isset($_POST["uiid"]) && isset($_POST["docomo_id"]) && isset($_POST["kid"])){
				try {
					$UIID = AppSession::get("UIID");
				}
				catch (Exception $Exception){
					$exceptionMsg = $Exception->getMessage();
					if(false !== strpos($exceptionMsg, "Session:")){
						// POSTデータからAuthする
						debug("post-auth");
						AppSession::setCookeToken($_POST["uiid"], $_POST["docomo_id"], $_POST["kid"]);
					}
					else {
						// そのままエラー
						throw $Exception;
					}
				}
			}
			$UIID = AppSession::get("UIID");
			$docomoID = AppSession::get("docomoID");
			$kidsID = AppSession::get("kidsID");
			$identifier = $docomoID;
			if(null !== $kidsID && "" !== $kidsID){
				$identifier .= ":" . $kidsID;
			}
			debug("identifier=".$identifier);
			$LoginedUser = ORMapper::getModel(DBO::sharedInstance(), "tusers", " WHERE identifier = :identifier AND available = '1' ", array("identifier" => $identifier));
			if (!$LoginedUser->id) {
				return false;
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
		return true;
	}

	public function putResult(){
		$data = array("result_id"=>"");
		if(isset($_POST["results"]) && strlen($_POST["results"]) > 0){
			// resultsがあればあるだけ処理をする
			$resultsArr = json_decode($_POST["results"], true);
			if(isset($resultsArr["results"]) && is_array($resultsArr["results"]) && count($resultsArr["results"]) > 0){
				$results = $resultsArr["results"];
				debug($results);
				for($ridx=0; $ridx < count($results); $ridx++){
					$result = $results[$ridx];
					$time = $result["time"];
					$gameID = $result["game_id"];
					$gameMode = $result["game_mode"];
					$charaed = $result["charaed"];
					$cleared = $result["cleared"];

					// 終了したゲームデータ
					$question = ORMapper::getModel(DBO::sharedInstance(), "mpuzzles", $gameID);

					// 標準で貰えるドロップ数
					$drop = 1;

					// 検定じゃない場合はドロップ付与
					if("0" != $gameMode){
						// ボーナスドロップがあるかどうかの計算
						$beforeHistory = ORMapper::getModel(DBO::sharedInstance(), "thistories", " WHERE user_id = :user_id AND puzzle_id = :game_id AND cleared != '9' ORDER BY `id` DESC LIMIT 1", array("user_id" => $this->loginedUserID, "game_id" => $gameID));
						// 2度目以降のチャレンジの場合はボーナスドロップを計算する
						if((int)$beforeHistory->id > 0){
							// 基準タイムを超えたかどうか、自己記録を更新したかどうか
							if((int)$question->mission_time > (int)$time && (int)$beforeHistory->time > (int)$time){
								// 自己記録を更新したらボーナスドロップ
								$drop = ceil((float)$question->scale * ((int)$beforeHistory->time - (int)$time));
							}
						}
						unset($beforeHistory);
					}

					// パラメータのadd処理
					if((int)$question->mission_time > (int)$time){
						// 基準タイムを超えている
						$beforeHistory = ORMapper::getModel(DBO::sharedInstance(), "thistories", " WHERE user_id = :user_id AND puzzle_id = :game_id AND cleared != '9' AND time < :time ORDER BY `id` DESC LIMIT 1", array("user_id" => $this->loginedUserID, "game_id" => $gameID, "time" => $question->mission_time));
						if(!((int)$beforeHistory->id > 0)){
							// 今までaddした事が無い問題ならパラメータをadd
							$this->LoginedUser->setParam1((int)$this->LoginedUser->param1 + (int)$question->param1);
							$this->LoginedUser->setParam2((int)$this->LoginedUser->param2 + (int)$question->param2);
							$this->LoginedUser->setParam3((int)$this->LoginedUser->param3 + (int)$question->param3);
							$this->LoginedUser->setParam4((int)$this->LoginedUser->param4 + (int)$question->param4);
							$this->LoginedUser->setParam5((int)$this->LoginedUser->param5 + (int)$question->param5);
							$this->LoginedUser->setParam6((int)$this->LoginedUser->param6 + (int)$question->param6);
							$this->LoginedUser->setParam7((int)$this->LoginedUser->param7 + (int)$question->param7);
							$this->LoginedUser->setParam8((int)$this->LoginedUser->param8 + (int)$question->param8);
						}
					}

					// 今回の結果を記録する
					$history = ORMapper::getModel(DBO::sharedInstance(), "thistories");
					$history->setUserId($this->loginedUserID);
					$history->setPuzzleId($gameID);
					$history->setGameMode($gameMode);
					$history->setCharaed($charaed);
					$history->setCleared($cleared);
					$history->setTime($time);
					// XXX
					//$history->setLocation($location);
					$history->setCreated($this->nowGMT);
					$history->setModified($this->nowGMT);
					$history->save();

					// クリアしたデータに応じてレベルとドロップを更新
					if("0" == $gameMode){
						debug("gameMode=".$gameMode);
						// 検定終了時
						if("1" === $cleared){
							// レベルアップ処理
							// 迷路のレベルアップ
							if(1 === (int)$question->type){
								// レベルアーップ！(順番大事)
								$this->LoginedUser->setMazeLevel((int)$this->LoginedUser->maze_level + 1);
							}
							// ナンプレのレベルアップ
							elseif(2 === (int)$question->type){
								// レベルアーップ！(順番大事)
								$this->LoginedUser->setFourplaceLevel((int)$this->LoginedUser->fourplace_level + 1);
							}
							// 投影図のレベルアップ
							elseif(3 === (int)$question->type){
								// レベルアーップ！(順番大事)
								$this->LoginedUser->setProjectionLevel((int)$this->LoginedUser->projection_level + 1);
							}
						}
					}
					else {
						if("2" == $gameMode){
							// 今日の探検の履歴を保存
							$DaylyHistory = ORMapper::getModel(DBO::sharedInstance(), "tdaylyhistories");
							$DaylyHistory->setUserId($this->loginedUserID);
							// XXX ホントはタイムゾーンを見ないとならん！
							$DaylyHistory->setDay(Utilities::date("Ymd"));
							$DaylyHistory->setCharaed($charaed);
							$DaylyHistory->setCreated($this->nowGMT);
							$DaylyHistory->setModified($this->nowGMT);
							$DaylyHistory->save();
						}
						// 今日の探検と練習問題終了時
						$this->LoginedUser->setDrop((int)$this->LoginedUser->drop + $drop);
						$this->LoginedUser->setDropSum((int)$this->LoginedUser->drop_sum + $drop);
					}

					$this->LoginedUser->modified($this->nowGMT);
					$this->LoginedUser->save();
				}

				// 結果IDを返す
				$data["result_id"] = (string)$history->id;
			}
		}

		return $data;
	}

	public function getInfo(){
		// 端末解像度を取る
		// XXX ここは共通化出来そうだぉ
		$resolution = "images/xdpi/";
		if(isset($_POST["resolution"])){
			$resolution = $_POST["resolution"];
		}
		elseif(isset($_SERVER["HTTP_USER_AGENT"]) && false !== strpos($_SERVER["HTTP_USER_AGENT"], "; Resolution:")) {
			$uaparams = explode(";", $_SERVER["HTTP_USER_AGENT"]);
			$resolparam = explode(":", $uaparams[1]);
			$resolution = trim($resolparam[1])."/";
		}
		debug("resolution='".$resolution."'");

		$data = array();
		// 返却データのデフォルト値
		$data["info_data"] = array("id"=>"0", "date"=>"", "message"=>"");
		$data["chara_tanken"] = "0";
		// XXX れんちゃんしかいない時は0に
		$data["chara_cnt"] = "0";
		$data["download_id"] = "";
		$data["assets"] = array();

		// お知らせの取得
		// XXX ホントはココはユーザー毎のタイムゾーンを見る！
		// TODO tusersにtimezoneとcountry_codeを追加する事！
		$nowlocalDate = Utilities::date("Y-m-d H:i:s", $this->nowGMT, "GMT", "Asia/Tokyo");
		$nowlocalDay = Utilities::date("m/d", $this->nowGMT, "GMT", "Asia/Tokyo");
		debug("nowlocalDate=".$nowlocalDate);
		$Info = ORMapper::getModel(DBO::sharedInstance(), "tinfos", " WHERE startdate <= :startdate AND enddate > :enddate AND (os = :device OR os = 'ALL') AND available = '1'", array("startdate"=>$nowlocalDate, "enddate"=>$nowlocalDate, "device"=>$this->deviceType));
		if((int)$Info->id > 0){
			// 表示すべきお知らせが存在する
			debug("is info!");
			$data["info_data"] = array("id"=>(string)$Info->id, "date"=>$nowlocalDay, "message"=>str_replace("\\n", "\n", $Info->msg));
			// ゆるキャラ表示の有無
			if((int)$Info->chara_cnt > 0){
				// ゆるキャラ探検を有効状態に
				$data["chara_tanken"] = "1";
				// 2重ダウンロードさせないようにダウンロードIDとして、ダウンロードのある時のinfoIDを返す
				$data["download_id"] = (string)$Info->id;
				// ダウンロードアセット
				// XXX chara_cntが在る時、jsonは必須である事！
				$assets = json_decode(str_replace("%base_asset_url%", "http://" . $_SERVER["SERVER_NAME"] . dirname(dirname($_SERVER["REQUEST_URI"])) . "/static/assets/" . $resolution, $Info->assets), true);
				$data["assets"] = $assets["assets"];
				// ゆるキャラ探検が済んでいるかどうかのチェック
				$DaylyHistory = ORMapper::getModel(DBO::sharedInstance(), "tdaylyhistories", " WHERE `day` = :day AND user_id = :user_id AND charaed = '1' AND available = '1'", array("day"=>Utilities::date("Ymd"), "user_id"=>$this->loginedUserID));
				debug("DaylyHistory=".$DaylyHistory->id . "&charaed=".$DaylyHistory->charaed);
				if((int)$DaylyHistory->id > 0 && (string)$DaylyHistory->charaed === "1"){
					// キャラカウントを入れる
					$data["chara_cnt"] = (string)$Info->chara_cnt;
					debug("display uruchara!");
				}
			}
		}

		// 子供の現在のデータ
		$data["tutorial1"] = (string)$this->LoginedUser->tutorial1;
		$data["tutorial2"] = (string)$this->LoginedUser->tutorial2;
		$data["tutorial3"] = (string)$this->LoginedUser->tutorial3;
		$data["drop"] = (string)$this->LoginedUser->drop;

		// 成長の木のレベル計算
		$data["tree_level"] = (string)$this->getTreeLevel();

		// パズルの個別レベル
		$mazeLev = (int)$this->LoginedUser->maze_level;
		$fourLev = (int)$this->LoginedUser->fourplace_level;
		$projLev = (int)$this->LoginedUser->projection_level;
		$data["maze_level"] = (string)$mazeLev;
		$data["fourplace_level"] = (string)$fourLev;
		$data["projection_level"] = (string)$projLev;

		// 次の検定に必要なドロップ数
		$mazeDrop = "0";
		$fourDrop = "0";
		$projDrop = "0";
		// 迷路
		$maze = ORMapper::getModel(DBO::sharedInstance(), "mpuzzles", " WHERE `level` = :level AND `type` = '1' AND licensed = '1' AND available = '1' LIMIT 1", array("level" => (int)$this->LoginedUser->maze_level));
		if((int)$maze->id > 0){
			$mazeDrop = (string)$maze->drop;
		}
		// ナンプレ
		$fourplace = ORMapper::getModel(DBO::sharedInstance(), "mpuzzles", " WHERE `level` = :level AND `type` = '2' AND licensed = '1' AND available = '1' LIMIT 1", array("level" => (int)$this->LoginedUser->fourplace_level));
		if((int)$fourplace->id > 0){
			$fourDrop = (string)$fourplace->drop;
		}
		// 投影図
		$proj = ORMapper::getModel(DBO::sharedInstance(), "mpuzzles", " WHERE `level` = :level AND `type` = '3' AND licensed = '1' AND available = '1' LIMIT 1", array("level" => (int)$this->LoginedUser->projection_level));
		if((int)$proj->id > 0){
			$projDrop = (string)$proj->drop;
		}
		$data["necessary_drop_for_maze"] = $mazeDrop;
		$data["necessary_drop_for_fourplace"] = $fourDrop;
		$data["necessary_drop_for_projection"] = $projDrop;

		return $data;
	}
}

?>