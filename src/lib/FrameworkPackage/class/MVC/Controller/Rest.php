<?php

class Rest extends RestControllerBase {

	/**
	 * リソースの参照
	 * @return mixed 成功時は最新のリソース配列 失敗時はFALSE
	 */
	public function get(){
		return parent::get();
	}

	/**
	 * リソースの作成・更新・インクリメント・デクリメント
	 * @return mixed 成功時は最新のリソース配列 失敗時はFALSE
	 */
	public function post(){
		return parent::post();
	}

	/**
	 * リソースの作成・更新
	 * @return mixed 成功時は最新のリソース配列 失敗時はFALSE
	 */
	public function put(){
		return parent::put();
	}

	/**
	 * リソースの削除
	 * @return boolean
	 */
	public function delete(){
		return parent::delete();
	}
}

?>