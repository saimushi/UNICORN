<?php

/**
 * MVCモデルをフレームワークとして提供するクラス
 */
class MVCCore {

	public static $appVersion = NULL;
	public static $deviceType = NULL;
	public static $appleReviewd = FALSE;
	public static $mustAppVersioned = TRUE;
	public static $CurrentController;

	/**
	 * WebインターフェースでのMVCのメイン処理
	 * DIコンテナで実行するかどうか
	 *
	 * @param boolean DIコンテナで実行するかどうか
	 * @throws Exception
	 */
	public static function webmain($argContainerMode=false){

		logging($_REQUEST, 'post');
		logging($_COOKIE, 'cookie');
		logging($_SERVER, 'server');
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
		// アプリケーション情報の取得
		$serverUserAgent = $_SERVER['HTTP_USER_AGENT'];
		$appleReviewd = FALSE;
		$deviceType = 'PC';
		if(false != strpos(strtolower($serverUserAgent), 'iphone')){
			$deviceType = 'iPhone';
		}elseif(false != strpos(strtolower($serverUserAgent), 'ipad')){
			$deviceType = 'iPad';
		}elseif(false != strpos(strtolower($serverUserAgent), 'ipod')){
			$deviceType = 'iPod';
		}elseif(false != strpos(strtolower($serverUserAgent), 'android')){
			$deviceType = 'Android';
		}
		debug('deviceType='.$deviceType);

		// アプリの必須バージョンチェック
		if(isset($_GET['_v_'])){
			if(TRUE === Configure::constant('MUST_IOSAPP_VERSION_FLAG_FILE') && ('iPhone' === $deviceType || 'iPad' === $deviceType || 'iPod' === $deviceType)){
				if(TRUE === is_file(Configure::MUST_IOSAPP_VERSION_FLAG_FILE)){
					debug(Configure::MUST_IOSAPP_VERSION_FLAG_FILE);
					$mustVirsionStr = @file_get_contents(Configure::MUST_IOSAPP_VERSION_FLAG_FILE);
					$matches = NULL;
					if(preg_match("/([0-9\.]+)/", $mustVirsionStr, $matches)){
						$mustVirsionNum = (int)str_replace(".","", $matches[1]);
						debug("mustVirsionNum=". $mustVirsionNum);
						debug("nowversion=" . (int)str_replace(".", "", $_GET['_v_']));
						if($mustVirsionNum > (int)str_replace(".", "", $_GET['_v_'])){
							self::$mustAppVersioned = FALSE;
						}
					}
				}
			}
			else if(TRUE === Configure::constant('MUST_ANDROIDAPP_VERSION_FLAG_FILE') && ('android' === $deviceType || 'Android' === $deviceType)){
				if(TRUE === is_file(Configure::MUST_ANDROIDAPP_VERSION_FLAG_FILE)){
					debug(Configure::MUST_ANDROIDAPP_VERSION_FLAG_FILE);
					$mustVirsionStr = @file_get_contents(Configure::MUST_ANDROIDAPP_VERSION_FLAG_FILE);
					$matches = null;
					if(preg_match("/([0-9\.]+)/", $mustVirsionStr, $matches)){
						$mustVirsionNum = (int)str_replace(".","", $matches[1]);
						if($mustVirsionNum > (int)str_replace(".", "", $_GET['_v_'])){
							self::$mustAppVersioned = FALSE;
						}
					}
				}
			}
		}

		// アップルレビューバージョンの存在チェック
		if('iPhone' === $deviceType || 'iPad' === $deviceType || 'iPod' === $deviceType){
			if(TRUE === Configure::constant('APPLE_REVIEW_FLAG_FILE') && isset($_GET['_v_'])){
				if(TRUE === is_file(Configure::APPLE_REVIEW_FLAG_FILE.$_GET['_v_'])){
					debug(Configure::APPLE_REVIEW_FLAG_FILE.$_GET['_v_']);
					$appleReviewd = TRUE;
					debug('isAppleReview');
				}
			}
		}

		// アプリバージョン
		$version = NULL;
		if(isset($_GET['_v_']) && strlen($_GET['_v_']) > 0){
			$version = $_GET['_v_'];
			debug('version=' . $version);
		}

		self::$appVersion = $version;
		self::$deviceType = $deviceType;
		self::$appleReviewd = $appleReviewd;

		$res = FALSE;

		// 実行
		try{
			// コントロール対象を取得
			$controlerClassName = self::loadMVCModule();
			self::$CurrentController = new $controlerClassName();
			$httpStatus = 200;
			self::$CurrentController->controlerClassName = $controlerClassName;
			self::$CurrentController->outputType = $outputType;
			self::$CurrentController->deviceType = self::$deviceType;
			self::$CurrentController->appVersion = self::$appVersion;
			self::$CurrentController->appleReviewd = self::$appleReviewd;
			self::$CurrentController->mustAppVersioned = self::$mustAppVersioned;
			$res = self::$CurrentController->$actionMethodName();
			if(FALSE === $res){
				throw new Exception();
			}
		}
		catch (Exception $Exception){
			// statusコードがアレバそれを使う
			if(isset(self::$CurrentController->httpStatus) && $httpStatus != self::$CurrentController->httpStatus){
				$httpStatus = self::$CurrentController->httpStatus;
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
				if(FALSE === $res && isset($Exception)){
					_systemError('Exception :' . $Exception->getMessage());
				}
			}
			else{
				$isBinary = FALSE;
				$outputType = self::$CurrentController->outputType;
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
					if(TRUE == self::$CurrentController->jsonUnescapedUnicode){
						$res = unicode_encode($res);
						// スラッシュのエスケープをアンエスケープする
						$res = preg_replace('/\\\\\//', '/', $res);
					}
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

	/**
	 * MVCクラスモジュールの読み込み処理
	 * @param string クラス名
	 * @param string クラスの読み故事にエラーが在る場合にbooleanを返すかどうか
	 * @return boolean
	 */
	public static function loadMVCModule($argClassName = NULL, $argClassExistsCalled = FALSE){

		$targetPath = '';
		if(NULL !== $argClassName){
			$controlerClassName = $argClassName;
		}
		else {
			// コントロール対象を自動特定
			$controlerClassName = 'Index';
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
		}

		$version = NULL;
		if(isset($_GET['_v_']) && strlen($_GET['_v_']) > 0){
			$version = $_GET['_v_'];
		}

		// コントローラを読み込み
		if(NULL !== $version){
			// バージョン一致のファイルを先ず走査する
			loadModule('default.controlmain.' . $targetPath . $version . '/' . $controlerClassName, TRUE);
		}
		if(!class_exists($controlerClassName, FALSE)){
			loadModule('default.controlmain.' . $targetPath . $controlerClassName, $argClassExistsCalled);
		}
		if(!class_exists($controlerClassName, FALSE)){
			// エラー終了
			return FALSE;
		}

		return $controlerClassName;
	}

	/**
	 * MVCクラスモジュールの読み込み処理
	 * @param string クラス名
	 * @param string クラスの読み故事にエラーが在る場合にbooleanを返すかどうか
	 * @return boolean
	 */
	public static function loadView($argClassName = NULL){

		$targetPath = '';
		if(NULL !== $argClassName){
			$controlerClassName = $argClassName;
		}
		else {
			// コントロール対象を自動特定
			$controlerClassName = 'Index';
			debug($controlerClassName);
			if(isset($_GET['_c_']) && strlen($_GET['_c_']) > 0){
				$controlerClassName = ucfirst($_GET['_c_']);
				if(FALSE !== strpos($_GET['_c_'], '/') && strlen($_GET['_c_']) > 1){
					$matches = NULL;
					if(preg_match('/(.*)\/([^\/]*)$/', $_GET['_c_'], $matches) && is_array($matches) && isset($matches[2])){
						$controlerClassName = ucfirst($matches[2]);
						if(isset($matches[1]) && strlen($matches[1]) > 0){
							$targetPath = $matches[1].'/';
							debug('view targetPath = ' . $targetPath);
						}
					}
				}
			}
		}

		$version = NULL;
		if(isset($_GET['_v_']) && strlen($_GET['_v_']) > 0){
			$version = $_GET['_v_'];
		}

		$HtmlView = NULL;

		// コントローラを読み込み
		if(NULL !== $version){
			debug($targetPath . $version . '/' . $controlerClassName . '.html');
			if(TRUE === file_exists_ip($targetPath . $version . '/' . $controlerClassName . '.html')){
				// Viewインスタンスの生成
				$HtmlView = new HtmlViewAssignor($targetPath . $version . '/' . $controlerClassName . '.html');
			}
		}

		if(NULL === $HtmlView){
			// バージョンを抜いてインクルード
			debug(get_include_path());
			debug($targetPath . '/' . $controlerClassName . '.html');
			if(TRUE === file_exists_ip($targetPath . '/' . $controlerClassName . '.html')){
				// Viewインスタンスの生成
				$HtmlView = new HtmlViewAssignor($targetPath . '/' . $controlerClassName . '.html');
			}
			else {
				// エラー終了
				return FALSE;
			}
		}

		return $HtmlView;
	}
}

?>