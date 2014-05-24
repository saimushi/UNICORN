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
}

?>