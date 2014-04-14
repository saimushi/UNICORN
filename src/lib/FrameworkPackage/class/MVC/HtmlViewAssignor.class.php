<?php

class HtmlViewAssignor {

	const REMOVE_NODE_KEY = 'remove-node';
	const REPLACE_ATTR_KEY = 'replace-attribute';

	protected $_orgHtmlHint;
	public $Templates = array();

	public function __construct($argHtmlHint=NULL){
		// コンストラクタですっ飛ばさない為に一旦しまってそれでコンストラクタは終わり
		$this->_orgHtmlHint = $argHtmlHint;
	}

	public function addTemplate($argHtmlHinst, $argKey="base"){
		if(is_object($argHtmlHinst)){
			// テンプレートエンジンインスタンスが渡ってきていると判定
			$this->Templates[$argKey] = $argHtmlHinst;
		}
		else {
			// テンプレートファイルパスないし、html文字列が渡ってきていると判定し
			// テンプレートエンジンインスタンス生成
			$this->Templates[$argKey] = new HtmlTemplate($argHtmlHinst);
		}
	}

	public function addTemplateAndAssign($argHtmlHinst, $argKey, $argParams){
		// assignの実行
		$html = HtmlViewAssignor::assign($Template, $argParams);
		// assignを実行した結果のhtmlをaddTemplateする
		$this->addTemplate($html, $argKey);
	}

	public function execute($argHtmlHint=NULL, $argParams=NULL){

		// ベースとなるテンプレートエンジンインスタンスを生成
		if(NULL !== $this->_orgHtmlHint){
			$this->addTemplate($this->_orgHtmlHint);
			// 一度使ったら不要
			$this->_orgHtmlHint = NULL;
		}
		if(NULL !== $argHtmlHint){
			$this->addTemplate($argHtmlHint);
		}

		// assignの実行
		$html = "";
		$htmls = array();
		foreach($this->Templates as $key => $val){
			$tmpHtml = HtmlViewAssignor::assign($val, $argParams);
			if("base" === $key){
				$html = $tmpHtml;
			}
			else {
				$htmls[$key] = $tmpHtml;
			}
		}

		// 複数のテンプレートhtmlをガッチャンコ
		if(count($htmls) >0){
			$BaseTemplate = new HtmlTemplate($html);
			foreach($htmls as $key => $val){
				$BaseTemplate->addSource($key, $val);
			}
			// 書き戻し
			$html = $BaseTemplate->flush();
			unset($htmls);
		}

		return $html;
	}

	public static function assign($argTemplateHint, $argParams=NULL, $argKey=NULL, $argRoot = TRUE){
		static $Template;
		if (TRUE === $argRoot){
			if(is_object($argTemplateHint)){
				// テンプレートエンジンインスタンスが渡ってきていると判定
				$Template = $argTemplateHint;
			}
			else {
				// テンプレートファイルパスないし、html文字列が渡ってきていると判定し
				// テンプレートエンジンインスタンス生成
				$Template = new HtmlTemplate($argTemplateHint);
			}
		}
		// アサイン処理の実行
		if(null !== $argParams && is_array($argParams)){
			foreach($argParams as $key => $val){
				// ノードの削除を処理
				if(NULL !== $argKey && self::REMOVE_NODE_KEY === $key){
					$dom = $tpl->find($argKey);
					if(isset($dom) && is_array($dom) && isset($dom[0])){
						for ($domIdx = 0; count($dom) > $domIdx; $domIdx++) {
							// 削除
							$dom[$domIdx]->remove();
						}
					}
					unset($dom);
				}
				// 属性の置換を処理
				elseif(NULL !== $argKey && self::REPLACE_ATTR_KEY === $key){
					debug($argKey);
					$dom = $Template->find($argKey);
					if(isset($dom) && is_array($dom) && isset($dom[0])){
						for ($domIdx = 0; count($dom) > $domIdx; $domIdx++) {
							foreach($val as $attrKey => $attrVal){
								// 置き換え
								$dom[$domIdx]->setAttribute($attrKey, $attrVal);
							}
						}
					}
					unset($dom);
				}
				// 再帰処理
				elseif(is_array($val)){
					if(NULL !== $argKey){
						$key = $argKey . "-" . $key;
					}
					self::assign(null, $val, $key, FALSE);
				}
				// ノード内のテキスト(html)の単純置換
				else {
					// ただのキーに紐づく値(innerHTML)の置換
					if(NULL !== $argKey){
						$key = $argKey . "-" . $key;
					}
					$Template->assignHtml($key, $val);
				}
			}
		}
		if (TRUE === $argRoot){
			// 処理結果html文字列を返却
			return $Template->flush();
		}
	}
}

?>