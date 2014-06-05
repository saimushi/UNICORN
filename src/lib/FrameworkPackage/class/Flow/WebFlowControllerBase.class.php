<?php

class WebFlowControllerBase extends WebControllerBase {

	protected function _initWebFlow(){
		// Flowパラムの初期化
		if(NULL === Flow::$params){
			Flow::$params = array();
		}
		// flowFormでPOSTされていたら自動的にバリデートする
		if(isset($_GET['flowpostformsection']) && $_GET['_c_'] === $_GET['flowpostformsection'] && count($_POST) > 0){
			Flow::$params['post'] = array();
			foreach($_POST as $key => $val){
				Flow::$params['post'][$key] = $val;
				if(0 !== strpos($key, 'pass')){
					if(NULL === Flow::$params['view']){
						Flow::$params['view'] = array();
					}
					// パスワード以外はREPLACE ATTRIBUTEを自動でして上げる
					Flow::$params['view']['input[name=' . $key . ']'] = array(HtmlViewAssignor::REPLACE_ATTR_KEY => array('value'=>$val));
				}
				// auto validate
				try{
					if(FALSE !== strpos($key, 'mail')){
						// メールアドレスのオートバリデート
						Validations::isEmail($val);
					}
				}
				catch (Exception $Exception){
					// 最後のエラーメッセージを取っておく
					$validateError = Validations::getMessage();
					if(NULL === Flow::$params['view']){
						Flow::$params['view'] = array();
					}
					Flow::$params['view']['div[flowpostformsectionerror=' . $_GET['flowpostformsection'] . ']'] = 'メールアドレスの形式が違います';
				}
			}
			if(isset($validateError)){
				// オートバリデートでエラー
				return FALSE;
			}
		}
		return TRUE;
	}
}

?>