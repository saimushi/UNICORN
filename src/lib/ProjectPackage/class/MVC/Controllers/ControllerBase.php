<?php

/**
 * ベースコントローラクラス
 */
abstract class ControllerBase extends MVCControllerBase {

	public $LoginedUser = null;
	public $loginedUserID = null;

	/**
	 * try〜catchをMVCCoreから奪う為にactionをMap
	 */
	public function execute($argment=null){
		$this->_execute($argment);
	}

	public function getTreeLevel(){
		// 成長の木のレベル計算
		$mazeLev = (int)$this->LoginedUser->maze_level;
		$fourLev = (int)$this->LoginedUser->fourplace_level;
		$projLev = (int)$this->LoginedUser->projection_level;
		$treeLev = 0;
		if($mazeLev === 1 && $fourLev === 1 && $projLev === 1){
			$treeLev = 1;
		}
		elseif ($mazeLev < 6 && $fourLev < 6 && $projLev < 6){
			$treeLev = 2;
		}
		elseif ($mazeLev < 10 && $fourLev < 10 && $projLev < 10){
			$treeLev = 3;
		}
		elseif ($mazeLev >= 100 && $fourLev >= 100 && $projLev >= 100){
			$treeLev = 10;
		}
		else {
			// 10〜100までは計算で処理
			$baseTreeLev = floor(($mazeLev + $fourLev + $projLev) / 3 / 10 + 2);
			$treeLev = ($baseTreeLev > 4)? (($baseTreeLev < 9)? $baseTreeLev : 9) : 4;
		}
		return $treeLev;
	}
}

?>