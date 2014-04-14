<?php

/**
 * サンプルとして一部だけ公開
 */
class Result extends WebViewController
{
	public function _execute(){
		// XXX 本当はlocal見ないとダメ！
		$nowMonth = (int)Utilities::date("n");
		$seirekiY = (int)Utilities::date("Y");
		$warekiY = $seirekiY - 1988;

		$baseYear = $seirekiY;
		$baseMonth = $nowMonth;
		if(isset($_GET["date"]) && is_numeric($_GET["date"])){
			$baseYear = substr($_GET["date"], 0, 4);
			$baseMonth = (int)substr($_GET["date"], 4, 2);
		}
		$beforeDate = Utilities::modifyDate("-1 month", "Ym", $baseYear . "-" . $baseMonth. "-1");
		debug($beforeDate);
		$nextDate = Utilities::modifyDate("+1 month", "Ym", $baseYear . "-" . $baseMonth. "-1");
		debug($nextDate);

		// 基準月の1日が何曜日なのか
		$baseDOW = (int)Utilities::date("w", $seirekiY."/".$baseMonth."/1");
		$baseDay = (int)Utilities::date("j");

		// カレンダー
		// XXX 画像のパスベタでゴメン・・・
		$daylyhistories = array();
		// 1日までをブランク画像で埋めるループ
		for($idx=0; $idx < $baseDOW; $idx++){
			$daylyhistories[$idx] = array(HtmlViewAssignor::REPLACE_ATTR_KEY => array("src"=>"./img/masu_non.png"));
		}
		// 1日から基準日の今日までを履歴で埋めるループ
		for($idx=$baseDOW; $idx < $baseDOW+$baseDay; $idx++){
			$gmtCreated = Utilities::date("Ymd", $seirekiY . "/" . $baseMonth. "/" . $idx, null, "GMT");
			$history = ORMapper::getModel(DBO::sharedInstance(), "thistories", " WHERE user_id = :user_id AND cleared != '9' AND DATE_FORMAT(created, '%Y%m%d') = :created AND available = '1' LIMIT 1", array("user_id" => $this->loginedUserID, "created" => $gmtCreated));
			// デフォルトは日付画像表示
			$daylyhistories[$idx] = array(HtmlViewAssignor::REPLACE_ATTR_KEY => array("src"=>"./img/n".($idx-$baseDOW+1).".png"));
			if((int)$history->id > 0){
				$daylyhistories[$idx] = array(HtmlViewAssignor::REPLACE_ATTR_KEY => array("src"=>"./img/na.png"));
			}
		}
		// 未来の日付では日付画像で埋めるループ
		for($idx; $idx < $baseDOW+(int)Utilities::date("t", $seirekiY."/".$baseMonth."/1"); $idx++){
			$daylyhistories[$idx] = array(HtmlViewAssignor::REPLACE_ATTR_KEY => array("src"=>"./img/n".($idx-$baseDOW+1).".png"));
		}
		// 42セルあるので42セルまでブランク画像で埋めるループ
		for($idx; $idx < 42; $idx++){
			$daylyhistories[$idx] = array(HtmlViewAssignor::REPLACE_ATTR_KEY => array("src"=>"./img/masu_non.png"));
		}

		// 出力データ群
		$data = array(".hesei" => $warekiY,
				".seireki" => $seirekiY,
				".info-seireki" => $baseYear,
				".month" => $nowMonth,
				".month_img" => array(HtmlViewAssignor::REPLACE_ATTR_KEY => array("src"=>"./img/mon".$baseMonth.".png")),
				"#drop" => $this->LoginedUser->drop_sum,
				".tree_level" => $this->getTreeLevel(),
				//"#tree_level_img" => array(HtmlViewAssignor::REPLACE_ATTR_KEY => array("src"=>"./img/levelTree".$this->getTreeLevel().".png")),
				"#tree_level_img" => array(HtmlViewAssignor::REPLACE_ATTR_KEY => array("src"=>"./img/levelTree10.png")),
				".maze_level" => $this->LoginedUser->maze_level,
				".fourplace_level" => $this->LoginedUser->fourplace_level,
				".projection_level" => $this->LoginedUser->projection_level,
				"#beforeMonthButton" => array(HtmlViewAssignor::REPLACE_ATTR_KEY => array("href"=>"./result.html?date=".$beforeDate."#daylyinfo")),
				"#nextMonthButton" => array(HtmlViewAssignor::REPLACE_ATTR_KEY => array("href"=>"./result.html?date=".$nextDate."#daylyinfo")),
				"#histories" =>$daylyhistories,
		);

		return $data;
	}
}

?>