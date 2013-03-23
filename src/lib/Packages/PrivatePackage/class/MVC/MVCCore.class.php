<?php

/**
 * MVCモデルをHBOPとして提供するクラス
 */
class GenericMVCCore {

	/**
	 *
	 */
	public static function webmain(){

		logging($_REQUEST, 'post');
		logging($_COOKIE, 'cookie');
		debug('requestParam='.var_export($_REQUEST, TRUE));
		debug('cookie='.var_export($_COOKIE, TRUE));

		$actionMethodName = 'execute';
		if(isset($_GET['_a_']) && strlen($_GET['_a_']) > 0){
			$actionMethodName = $_GET['_a_'];
		}
		// $_GET['_o_']がコントローラで消されてしまうかも知れないので一回取っておく
		// 正式なOutputType定義はコントローラ処理終了後
		$outputType = 'html';
		if(isset($_GET['_o_']) && strlen($_GET['_o_']) > 0){
			$outputType = $_GET['_o_'];
		}

		// コントロール対象を取得
		$controlerClassName = 'Root';
		$targetPath = '';
		debug($controlerClassName);
		if(isset($_GET['_c_']) && strlen($_GET['_c_']) > 0){
			$controlerClassName = ucfirst($_GET['_c_']);
			if(FALSE !== strpos($_GET['_c_'], '/') && strlen($_GET['_c_']) > 1){
				$matches = NULL;
				if(preg_match('/(.*)\/([^\/]*)$/', $_GET['_c_'], $matches) && is_array($matches) && isset($matches[2])){
					$controlerClassName = ucfirst($matches[2]);
					if(isset($matches[1]) && strlen($matches[1]) > 0){
						$targetPath = $matches[1].'/';
						debug('targetPath = ' . $targetPath);
					}
				}
			}
		}

		$controlerClassName = self::loadWebModule();
		$cntrlr = new $controlerClassName();

		// 実行
		try{
			$httpStatus = 200;
			$cntrlr->controlerClassName = $controlerClassName;
			$cntrlr->outputType = $outputType;
			$res = $cntrlr->$actionMethodName();
			if(FALSE === $res){
				throw new Exception();
			}
		}
		catch (Exception $Exception){
			// statusコードがアレバそれを使う
			if(isset($cntrlr->httpStatus) && $httpStatus != $cntrlr->httpStatus){
				$httpStatus = $cntrlr->httpStatus;
			}
			else{
				// インターナルサーバエラー
				$httpStatus = 500;
			}
		}

		// Output
		try{
			if(200 !== $httpStatus){
				// 200版以外のステータスコードの場合の出力処理
				header('HTTP', TRUE, $httpStatus);
			}
			else{
				$isBinary = FALSE;
				$outputType = $cntrlr->outputType;
				if('html' === $outputType){
					// htmlヘッダー出力
					header('Content-type: text/html; charset=UTF-8');
				}
				elseif('txt' === $outputType){
					// textヘッダー出力
					header('Content-type: text/plain; charset=UTF-8');
				}
				elseif('json' === $outputType){
					// jsonヘッダー出力
					header('Content-type: text/javascript; charset=UTF-8');
					if(is_array($res)){
						$res = json_encode($res);
					}
					debug($res);
				}
				elseif('jpg' === $outputType || 'jpeg' === $outputType){
					// jpgヘッダー出力
					header('Content-type: image/jpeg');
					$isBinary = TRUE;
				}
				elseif('png' === $outputType){
					// pngヘッダー出力
					header('Content-type: image/png');
					$isBinary = TRUE;
				}
				elseif('gif' === strtolower($outputType)){
					// gifヘッダー出力
					header('Content-type: image/gif');
					$isBinary = TRUE;
				}
				elseif('bmp' === strtolower($outputType)){
					// bmpヘッダー出力
					header('Content-type: image/bmp');
					$isBinary = TRUE;
				}
				// 描画処理
				if(TRUE === $isBinary && is_string($res)){
					header('Content-length: ' . strlen($res));
				}
				echo $res;
			}
		}
		catch (Exception $Exception){
			//
		}

		// 明示的終了
		exit;

	}

	public static function batch(){
	}

	public static function loadWebModule($argClassName = NULL){

		// コントロール対象を取得
		$controlerClassName = 'Root';
		$targetPath = '';
		debug($controlerClassName);
		if(isset($_GET['_c_']) && strlen($_GET['_c_']) > 0){
			$controlerClassName = ucfirst($_GET['_c_']);
			if(FALSE !== strpos($_GET['_c_'], '/') && strlen($_GET['_c_']) > 1){
				$matches = NULL;
				if(preg_match('/(.*)\/([^\/]*)$/', $_GET['_c_'], $matches) && is_array($matches) && isset($matches[2])){
					$controlerClassName = ucfirst($matches[2]);
					if(isset($matches[1]) && strlen($matches[1]) > 0){
						$targetPath = $matches[1].'/';
						debug('targetPath = ' . $targetPath);
					}
				}
			}
		}

		if(NULL !== $argClassName){
			$controlerClassName = $argClassName;
		}

		$version = NULL;
		if(isset($_GET['_v_']) && strlen($_GET['_v_']) > 0){
			$version = $_GET['_v_'];
			debug('version=' . $version);
		}

		// コントローラをnew
		if(NULL !== $version){
			// バージョン一致のファイルを先ず走査する
			loadModule('default.webmain.' . $targetPath . $version . '/' . $controlerClassName, NULL, TRUE);
		}
		if(!class_exists($controlerClassName, FALSE)){
			loadModule('default.webmain.'.$targetPath . $controlerClassName);
		}

		return $controlerClassName;
	}

}

?>