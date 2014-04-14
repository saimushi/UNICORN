<?php

/**
 * サンプルとして一部だけ公開
 */
class GetInfo extends AppController
{
	public function _execute(){

		$data = array("blacked"=>"0", "questions_id"=>"", "questions"=>array());

		// ブラックリスト端末判定
		if(isset($_POST["device"]) && strlen($_POST["device"]) > 0){
			// 非推奨端末一覧を取得
			$Res = DBO::sharedInstance()->execute("SELECT `device` FROM `tblacklists` WHERE `type`='1' AND `available`=1");
			if($Res && $Res->RecordCount() > 0){
				while($recode = $Res->FetchRow()){
					$deviceBlackList[] = $recode["device"];
					// 前方から一致している
					if(0 === strpos(strtoupper($_POST["device"]), strtoupper($recode["device"]))){
						// 非推奨端末
						$data["blacked"] = "1";
					}
				}
			}
		}

		// 問題データ一覧(端末キャッシュ用圏外時動作用)
		$_POST["questions_id"] = "1";
		if(isset($_POST["questions_id"]) && strlen($_POST["questions_id"]) > 0 && "" != $_POST["questions_id"]){
			// 迷路
			$Question = ORMapper::getModel(DBO::sharedInstance(), "mpuzzles", " WHERE `level` > 0 AND `type` = '1' AND licensed = '0' AND available = '1' ORDER BY `level` ASC");
			for($idx=0, $lidx=count($data["questions"]); $idx < $Question->count; $idx++){
				$gameData = json_decode($Question->data, true);
				$data["questions"][$lidx + (int)$Question->level - 1] = array("game_id"=>(string)$Question->id, "game_level"=>(string)$Question->level, "game_type"=>(string)$Question->type, "mission"=>str_replace("\\n", "\n", $Question->mission_msg), "mission_time"=>(string)$Question->mission_time, "game_data"=>$gameData["game_data"]);
				// 最大数のインデックスをquestions_idにする
				if("" === $data["questions_id"] || (int)$data["questions_id"] < (int)$Question->id){
					$data["questions_id"] = (string)$Question->id;
				}
				if(false === $Question->next()){
					break;
				}
			}
			// ナンプレ
			$Question = ORMapper::getModel(DBO::sharedInstance(), "mpuzzles", " WHERE `level` > 0 AND `type` = '2' AND licensed = '0' AND available = '1' ORDER BY `level` ASC");
			for($idx=0, $lidx=count($data["questions"]); $idx < $Question->count; $idx++){
				$gameData = json_decode($Question->data, true);
				$data["questions"][$lidx + (int)$Question->level - 1] = array("game_id"=>(string)$Question->id, "game_level"=>(string)$Question->level, "game_type"=>(string)$Question->type, "mission"=>str_replace("\\n", "\n", $Question->mission_msg), "mission_time"=>(string)$Question->mission_time, "game_data"=>$gameData["game_data"]);
				// 最大数のインデックスをquestions_idにする
				if("" === $data["questions_id"] || (int)$data["questions_id"] < (int)$Question->id){
					$data["questions_id"] = (string)$Question->id;
				}
				if(false === $Question->next()){
					break;
				}
			}
			// 投影図
			$Question = ORMapper::getModel(DBO::sharedInstance(), "mpuzzles", " WHERE `level` > 0 AND `type` = '3' AND licensed = '0' AND available = '1' ORDER BY `level` ASC");
			for($idx=0, $lidx=count($data["questions"]); $idx < $Question->count; $idx++){
				$gameData = json_decode($Question->data, true);
				$data["questions"][$lidx + (int)$Question->level - 1] = array("game_id"=>(string)$Question->id, "game_level"=>(string)$Question->level, "game_type"=>(string)$Question->type, "mission"=>str_replace("\\n", "\n", $Question->mission_msg), "mission_time"=>(string)$Question->mission_time, "game_data"=>$gameData["game_data"]);
				// 最大数のインデックスをquestions_idにする
				if("" === $data["questions_id"] || (int)$data["questions_id"] < (int)$Question->id){
					$data["questions_id"] = (string)$Question->id;
				}
				if(false === $Question->next()){
					break;
				}
			}
		}
		return $data;
	}
}

?>