<?php

class HtmlViewAssignor {

	const REMOVE_NODE_KEY = 'remove-node';
	const REPLACE_ATTR_KEY = 'replace-attribute';
	const PART_REPLACE_ATTR_KEY = 'part-replace-attribute';
	const LOOP_NODE_KEY = 'loop-node';
	const PART_REPLACE__NODE_KEY = 'part-replace-node';

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

	public static function assign($argTemplateHint, $argParams=NULL, $argKey=NULL, $argDepth = 0){
		static $Template = array();
		if (null !== $argTemplateHint && !isset($Template[$argDepth])){
			if(is_object($argTemplateHint)){
				// テンプレートエンジンインスタンスが渡ってきていると判定
				$Template[$argDepth] = $argTemplateHint;
			}
			else {
				// テンプレートファイルパスないし、html文字列が渡ってきていると判定し
				// テンプレートエンジンインスタンス生成
				$Template[$argDepth] = new HtmlTemplate($argTemplateHint);
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
					$dom = $Template[$argDepth]->find($argKey);
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
				// 属性の部分置換を処理
				elseif(NULL !== $argKey && self::PART_REPLACE_ATTR_KEY === $key){
					$dom = $Template[$argDepth]->find($argKey);
					if(isset($dom) && is_array($dom) && isset($dom[0])){
						for ($domIdx = 0; count($dom) > $domIdx; $domIdx++) {
							foreach($val as $attrKey => $part){
								// 部分置換
								$attrVal = $dom[$domIdx]->getAttribute($attrKey);
								foreach($part as $partKey => $partVal){
									$attrVal = str_replace($partKey, $partVal, $attrVal);
								}
								// 置き換え
								$dom[$domIdx]->setAttribute($attrKey, $attrVal);
							}
						}
					}
					unset($dom);
				}
				// NODEの部分置換を処理
				elseif(NULL !== $argKey && self::PART_REPLACE__NODE_KEY === $key){
					$dom = $Template[$argDepth]->find($argKey);
					if(isset($dom) && is_array($dom) && isset($dom[0])){
						for ($domIdx = 0; count($dom) > $domIdx; $domIdx++) {
							foreach($val as $attrKey => $part){
								// 部分置換
								$nodeVal = $dom[$domIdx]->text();
								foreach($part as $partKey => $partVal){
									$nodeVal = str_replace($partKey, $partVal, $nodeVal);
								}
								// 置き換え
								$dom[$domIdx]->text($nodeVal);
							}
						}
					}
					unset($dom);
				}
				// 同じタグを繰り返し処理して描画する
				elseif(NULL !== $argKey && self::LOOP_NODE_KEY === $key){
					if(is_array($val)){
						$newDomHtml = "";
						$dom = $Template[$argDepth]->find($argKey);
						$outerhtml = $dom[0]->outertext();
						foreach($val as $lKey => $lval){
							if(is_numeric($lKey)){
								$lKey = NULL;
							}
							$newDomHtml .= self::assign($outerhtml, $lval, $lKey, $argDepth+1);
						}
						$dom[0]->setAttribute('outertext', $newDomHtml);
					}
				}
				// 再帰処理
				elseif(is_array($val)){
					if(NULL !== $argKey){
						$key = $argKey . "-" . $key;
					}
					self::assign(null, $val, $key, $argDepth);
				}
				// ノード内のテキスト(html)の単純置換
				else {
					// ただのキーに紐づく値(innerHTML)の置換
					if(NULL !== $argKey){
						$key = $argKey . "-" . $key;
					}
					// ループの時用のkey自動走査対象の追加処理
					if(0 < $argDepth && FALSE === strpos($key, "#") && FALSE === strpos($key, ".") && !is_object($Template[$argDepth]->find($key))){
						// 対応のキーに値が無い時、自動でclass扱いしてみる
						// XXX class以外は対象外！理由は書くのが面倒くさい
						$key = "." . $key;
					}
					$Template[$argDepth]->assignHtml($key, $val);
				}
			}
		}
		if (null !== $argTemplateHint){
			// 処理結果html文字列を返却
			return $Template[$argDepth]->flush();
		}
	}
}

?>