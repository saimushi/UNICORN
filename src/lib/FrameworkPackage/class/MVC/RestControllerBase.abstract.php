<?php

abstract class RestControllerBase extends APIControllerBase implements RestControllerIO {

	protected $_initialized = FALSE;
	public $requestMethod = 'GET';
	public $restResource = '';
	public $restResourceModel = '';
	public $restResourceCreateDateKeyName = '';
	public $restResourceModifyDateKeyName = '';
	public $restResourceAvailableKeyName = '';
	public $AuthUser = NULL;
	public $authUserID = NULL;
	public $authUserIDFieldName = NULL;
	public $authUserQuery = NULL;
	public $deepRESTMode = TRUE;

	protected function _init(){
		if(FALSE === $this->_initialized){
			if(class_exists('Configure') && NULL !== Configure::constant('REST_RESOURCE_OWNER_PKEY_NAME')){
				$this->authUserIDFieldName = Configure::REST_RESOURCE_OWNER_PKEY_NAME;
			}
			if(class_exists('Configure') && NULL !== Configure::constant('REST_RESOURCE_CREATE_DATE_KEY_NAME')){
				$this->restResourceCreateDateKeyName = Configure::REST_RESOURCE_CREATE_DATE_KEY_NAME;
			}
			if(class_exists('Configure') && NULL !== Configure::constant('REST_RESOURCE_MODIFY_DATE_KEY_NAME')){
				$this->restResourceModifyDateKeyName = Configure::REST_RESOURCE_MODIFY_DATE_KEY_NAME;
			}
			if(class_exists('Configure') && NULL !== Configure::constant('REST_RESOURCE_AVAILABLE_KEY_NAME')){
				$this->restResourceAvailableKeyName = Configure::REST_RESOURCE_AVAILABLE_KEY_NAME;
			}
			elseif(defined('PROJECT_NAME') && strlen(PROJECT_NAME) > 0 && class_exists(PROJECT_NAME . 'Configure')){
				$ProjectConfigure = PROJECT_NAME . 'Configure';
				if(NULL !== $ProjectConfigure::constant('REST_RESOURCE_OWNER_PKEY_NAME')){
					$this->authUserIDFieldName = $ProjectConfigure::REST_RESOURCE_OWNER_PKEY_NAME;
				}
				if(NULL !== $ProjectConfigure::constant('REST_RESOURCE_CREATE_DATE_KEY_NAME')){
					$this->restResourceCreateDateKeyName = $ProjectConfigure::REST_RESOURCE_CREATE_DATE_KEY_NAME;
				}
				if(NULL !== $ProjectConfigure::constant('REST_RESOURCE_MODIFY_DATE_KEY_NAME')){
					$this->restResourceModifyDateKeyName = $ProjectConfigure::REST_RESOURCE_MODIFY_DATE_KEY_NAME;
				}
				if(NULL !== $ProjectConfigure::constant('REST_RESOURCE_AVAILABLE_KEY_NAME')){
					$this->restResourceAvailableKeyName = $ProjectConfigure::REST_RESOURCE_AVAILABLE_KEY_NAME;
				}
			}
			debug('restResourceCreateDateKeyName='.$this->restResourceCreateDateKeyName);
			debug('restResourceModifyDateKeyName='.$this->restResourceModifyDateKeyName);
			$this->_initialized = TRUE;
		}
	}

	protected static function _getDBO($argDSN=NULL){
		// DBOを初期化
		static $defaultDSN = NULL;
		static $DBO = array();
		// DSNの自動判別
		$DSN = $defaultDSN;
		if(NULL === $argDSN && NULL === $defaultDSN){
			$DSN = NULL;
			if(class_exists('Configure') && NULL !== Configure::constant('DB_DSN')){
				// 定義からセッションDBの接続情報を特定
				$DSN = Configure::DB_DSN;
			}
			if(class_exists('Configure') && NULL !== Configure::constant('REST_DB_DSN')){
				// 定義からセッションDBの接続情報を特定
				$DSN = Configure::REST_DB_DSN;
			}
			if(defined('PROJECT_NAME') && strlen(PROJECT_NAME) > 0 && class_exists(PROJECT_NAME . 'Configure')){
				$ProjectConfigure = PROJECT_NAME . 'Configure';
				if(NULL !== $ProjectConfigure::constant('DB_DSN')){
					// 定義からセッションDBの接続情報を特定
					$DSN = $ProjectConfigure::DB_DSN;
				}
				if(NULL !== $ProjectConfigure::constant('REST_DB_DSN')){
					// 定義からセッションDBの接続情報を特定
					$DSN = $ProjectConfigure::REST_DB_DSN;
				}
			}
			$defaultDSN = $DSN;
		}
		// DSN指定があった場合はそれに従う
		elseif(NULL !== $argDSN){
			$DSN = $argDSN;
		}
		if(!isset($DBO[$DSN])){
			$DBO[$DSN] = DBO::sharedInstance($DSN);
		}
		return $DBO[$DSN];
	}

	protected function _getModel($argModel, $argIdentifierORQuery=NULL, $argBinds=NULL, $argDSN=NULL){
		if(NULL !== $argIdentifierORQuery){
			return ORMapper::getModel(self::_getDBO($argDSN), $argModel, $argIdentifierORQuery, $argBinds);
		}
		else{
			return ORMapper::getModel(self::_getDBO($argDSN), $argModel);
		}
	}

	protected function _convertArrayFromModel($ArgModel, $argFields=NULL){
		if(is_object($ArgModel)){
			$arrayModel = array();
			$fields = $ArgModel->getFieldKeys();
			if(NULL !== $argFields){
				for($fieldsIdx=0; $fieldsIdx < count($argFields); $fieldsIdx++){
					if(isset($ArgModel->{$argFields[$fieldsIdx]})){
						$arrayModel[$argFields[$fieldsIdx]] = (string)$ArgModel->{$argFields[$fieldsIdx]};
					}
				}
			}
			else{
				for($fieldsIdx=0; $fieldsIdx < count($fields); $fieldsIdx++){
					$arrayModel[$fields[$fieldsIdx]] = (string)$ArgModel->{$fields[$fieldsIdx]};
				}
			}
			return $arrayModel;
		}
		return FALSE;
	}

	protected function _getRequestParams(){
		$requestParams = array();
		if('PUT' === $_SERVER['REQUEST_METHOD'] || 'DELETE' === $_SERVER['REQUEST_METHOD']){
			parse_str(file_get_contents('php://input', FALSE , NULL, -1 , $_SERVER['CONTENT_LENGTH'] ), $requestParams);
		}
		else if('POST' === $_SERVER['REQUEST_METHOD']){
			// XXX multipart/form-dataもPOSTなので、PHPに任せます
			$requestParams = $_POST;
		}
		else if('GET' === $_SERVER['REQUEST_METHOD']){
			$requestParams = $_GET;
		}
		else {
			// 未知のメソッド
		}
		return $requestParams;
	}

	/**
	 * フレームワーク標準のAuth機能を利用した認証と登録を行って、RESTする(スマフォAPI向け)
	 * @return array 配列構造のリソースデータ
	 */
	public function UIDAuthAndExecute(){
		$this->_init();
		// UIDAuthREST用変数初期化
		$DBO = NULL;
		$User = FALSE;
		$userModelName = NULL;
		$UserModelCreatedFieldName = NULL;
		$UserModelModifiedFieldName = NULL;
		$deviceTypeFieldName = NULL;
		if(class_exists('Configure') && NULL !== Configure::constant('REST_UIDAUTH_USER_TBL_NAME')){
			$userModelName = Configure::REST_UIDAUTH_USER_TBL_NAME;
		}
		if(class_exists('Configure') && NULL !== Configure::constant('REST_UIDAUTH_USER_CREATE_DATE_KEY_NAME')){
			$UserModelCreatedFieldName = Configure::REST_UIDAUTH_USER_CREATE_DATE_KEY_NAME;
		}
		if(class_exists('Configure') && NULL !== Configure::constant('REST_UIDAUTH_USER_MODIFY_DATE_KEY_NAME')){
			$UserModelModifiedFieldName = Configure::REST_UIDAUTH_USER_MODIFY_DATE_KEY_NAME;
		}
		if(class_exists('Configure') && NULL !== Configure::constant('REST_UIDAUTH_DEVICE_TYPE_FIELD_NAME')){
			$deviceTypeFieldName = Configure::REST_UIDAUTH_DEVICE_TYPE_FIELD_NAME;
		}
		if(defined('PROJECT_NAME') && strlen(PROJECT_NAME) > 0 && class_exists(PROJECT_NAME . 'Configure')){
			$ProjectConfigure = PROJECT_NAME . 'Configure';
			if(NULL !== $ProjectConfigure::constant('REST_UIDAUTH_USER_TBL_NAME')){
				$userModelName = $ProjectConfigure::REST_UIDAUTH_USER_TBL_NAME;
			}
			if(NULL !== $ProjectConfigure::constant('REST_UIDAUTH_USER_CREATE_DATE_KEY_NAME')){
				$UserModelCreatedFieldName = $ProjectConfigure::REST_UIDAUTH_USER_CREATE_DATE_KEY_NAME;
			}
			if(NULL !== $ProjectConfigure::constant('REST_UIDAUTH_USER_MODIFY_DATE_KEY_NAME')){
				$UserModelModifiedFieldName = $ProjectConfigure::REST_UIDAUTH_USER_MODIFY_DATE_KEY_NAME;
			}
			if(NULL !== $ProjectConfigure::constant('REST_UIDAUTH_DEVICE_TYPE_FIELD_NAME')){
				$deviceTypeFieldName = $ProjectConfigure::REST_UIDAUTH_DEVICE_TYPE_FIELD_NAME;
			}
		}
		try{
			$gmtDate = Utilities::date('Y-m-d H:i:s', NULL, NULL, 'GMT');
			$DBO = self::_getDBO();
			// UIDAuth
			$Device = Auth::getCertifiedUser();
			if(FALSE === $Device){
				// 登録処理
				// SessionID=端末固有IDと言う決めに沿って登録を行う
				$Device = Auth::registration(Auth::getDecryptedAuthIdentifier(), Auth::getDecryptedAuthIdentifier());
				// 強制認証で証明を得る
				if(TRUE !== Auth::certify($Device->{Auth::$authIDField}, $Device->{Auth::$authPassField}, NULL, TRUE)){
					// 認証NG(401)
					throw new RESTException(__CLASS__.PATH_SEPARATOR.__METHOD__.PATH_SEPARATOR.__LINE__, 401);
				}
				// user情報の更新
				$userAdded = FALSE;
				$deviceModified = FALSE;
				if(NULL !== $userModelName && $userModelName != $Device->tableName){
					$ownerIDField = $userModelName . '_id';
					if(isset($Device->{$this->authUserIDFieldName})){
						$ownerIDField = $this->authUserIDFieldName;
					}
					if(!(0 < strlen($Device->{$ownerIDField}) && '0' != $Device->{$ownerIDField})){
						// userテーブルとdeviceテーブルのテーブル名が違うので、userテーブルの保存を行う
						$User = self::_getModel($userModelName);
						if(NULL !== $UserModelCreatedFieldName && in_array($UserModelCreatedFieldName, $User->getFieldKeys())){
							$User->{'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $UserModelCreatedFieldName)))}($gmtDate);
						}
						if(NULL !== $UserModelModifiedFieldName && in_array($UserModelModifiedFieldName, $User->getFieldKeys())){
							$User->{'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $UserModelModifiedFieldName)))}($gmtDate);
						}
						$User->save();
						// deviceテーブルにユーザーIDのセット(強制)
						$Device->{'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $ownerIDField)))}($User->pkey);
						$deviceModified = TRUE;
						$userAdded = TRUE;
					}
					else {
						$User = self::_getModel($userModelName, $Device->{$ownerIDField});
						if(!(0 < (int)$User->pkey)){
							// デバイスの持ち主が変わった可能性
							$gmtDate = Utilities::date('Y-m-d H:i:s', NULL, NULL, 'GMT');
							// userテーブルとdeviceテーブルのテーブル名が違うので、userテーブルの保存を行う
							if(NULL !== $UserModelCreatedFieldName && in_array($UserModelCreatedFieldName, $User->getFieldKeys())){
								$User->{'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $UserModelCreatedFieldName)))}($gmtDate);
							}
							if(NULL !== $UserModelModifiedFieldName && in_array($UserModelModifiedFieldName, $User->getFieldKeys())){
								$User->{'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $UserModelModifiedFieldName)))}($gmtDate);
							}
							$User->save();
							// deviceテーブルにユーザーIDのセット(強制)
							$Device->{'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $ownerIDField)))}($User->pkey);
							$deviceModified = TRUE;
							$userAdded = TRUE;
						}
					}
				}
				else {
					$User = $Device;
				}
				// device情報の更新
				if(NULL !== $deviceTypeFieldName && in_array($deviceTypeFieldName, $Device->getFieldKeys()) && isset($this->deviceType) && 0 < strlen($this->deviceType)){
					$Device->{'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $deviceTypeFieldName)))}($this->deviceType);
					$deviceModified = TRUE;
				}
				if(TRUE === $deviceModified){
					if(in_array(Auth::$authModifiedField, $Device->getFieldKeys())){
						$Device->{'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', Auth::$authModifiedField)))}($gmtDate);
					}
					$Device->save();
				}
				if(TRUE === $deviceModified || TRUE === $userAdded){
					// 一旦コミット
					$DBO->commit();
				}
			}
			elseif(NULL !== $userModelName && $userModelName != $Device->tableName){
				$ownerIDField = $userModelName . '_id';
				if(isset($Device->{$this->authUserIDFieldName})){
					$ownerIDField = $this->authUserIDFieldName;
				}
				debug('is owner?'.$Device->{$ownerIDField});
				if(!(0 < strlen($Device->{$ownerIDField}) && '0' != $Device->{$ownerIDField})){
					// user情報の更新
					// userテーブルとdeviceテーブルのテーブル名が違うので、userテーブルの保存を行う
					$User = self::_getModel($userModelName);
					if(NULL !== $UserModelCreatedFieldName && in_array($UserModelCreatedFieldName, $User->getFieldKeys())){
						$User->{'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $UserModelCreatedFieldName)))}($gmtDate);
					}
					if(NULL !== $UserModelModifiedFieldName && in_array($UserModelModifiedFieldName, $User->getFieldKeys())){
						$User->{'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $UserModelModifiedFieldName)))}($gmtDate);
					}
					$User->save();
					// deviceテーブルにユーザーIDのセット(強制)
					$Device->{'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $ownerIDField)))}($User->pkey);
					if(NULL !== $deviceTypeFieldName && in_array($deviceTypeFieldName, $Device->getFieldKeys()) && isset($this->deviceType) && 0 < strlen($this->deviceType)){
						$Device->{'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $deviceTypeFieldName)))}($this->deviceType);
					}
					if(in_array(Auth::$authModifiedField, $Device->getFieldKeys())){
						$Device->{'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', Auth::$authModifiedField)))}($gmtDate);
					}
					$Device->save();
					// 一旦コミット
					$DBO->commit();
				}
				else {
					$User = self::_getModel($userModelName, $Device->{$ownerIDField});
					if(!(0 < (int)$User->pkey)){
						// デバイスの持ち主が変わった可能性
						$gmtDate = Utilities::date('Y-m-d H:i:s', NULL, NULL, 'GMT');
						// userテーブルとdeviceテーブルのテーブル名が違うので、userテーブルの保存を行う
						if(NULL !== $UserModelCreatedFieldName && in_array($UserModelCreatedFieldName, $User->getFieldKeys())){
							$User->{'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $UserModelCreatedFieldName)))}($gmtDate);
						}
						if(NULL !== $UserModelModifiedFieldName && in_array($UserModelModifiedFieldName, $User->getFieldKeys())){
							$User->{'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $UserModelModifiedFieldName)))}($gmtDate);
						}
						$User->save();
						// deviceテーブルにユーザーIDのセット(強制)
						$Device->{'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $ownerIDField)))}($User->pkey);
						if(NULL !== $deviceTypeFieldName && in_array($deviceTypeFieldName, $Device->getFieldKeys()) && isset($this->deviceType) && 0 < strlen($this->deviceType)){
							$Device->{'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $deviceTypeFieldName)))}($this->deviceType);
						}
						if(in_array(Auth::$authModifiedField, $Device->getFieldKeys())){
							$Device->{'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', Auth::$authModifiedField)))}($gmtDate);
						}
						$Device->save();
						// 一旦コミット
						$DBO->commit();
					}
				}
			}
			else{
				$User = $Device;
			}
		}
		catch (Exception $Exception){
			if(NULL !== $DBO && is_object($DBO)){
				// トランザクションを異常終了する
				$DBO->rollback();
			}
			// 実装の問題によるエラー
			$this->httpStatus = 500;
			if(400 === $Exception->getCode() || 401 === $Exception->getCode() || 404 === $Exception->getCode() || 405 === $Exception->getCode() || 503 === $Exception->getCode()){
				$this->httpStatus = $Exception->getCode();
			}
			throw new RESTException($Exception->getMessage(), $this->httpStatus);
		}

		if(FALSE !== $User){
			// 認証OK
			$this->AuthUser = $User;
			$this->authUserID = $User->pkey;
			if(NULL === $this->authUserIDFieldName){
				// XXX xxx_xxと言うAuthユーザー判定法は固定です！使用は任意になります。
				$this->authUserIDFieldName = strtolower($User->tableName) . '_' . $User->pkeyName;
			}
			$this->authUserQuery = ' `' . $this->authUserIDFieldName . '` = \'' . $User->pkey . '\'';
			return $this->execute();
		}

		// XXX ココを通るのは相当なイレギュラー！
		$this->httpStatus = 500;
		throw new RESTException(__CLASS__.PATH_SEPARATOR.__METHOD__.PATH_SEPARATOR.__LINE__, 500);
	}

	/**
	 * フレームワーク標準のAuth機能を利用した認証を行って、RESTする
	 * @return array 配列構造のリソースデータ
	 */
	public function authAndExecute(){
		$this->_init();
		$DBO = NULL;
		try{
			// Auth
			$DBO = self::_getDBO();
			$User = Auth::getCertifiedUser();
			if(FALSE === $User){
				// 認証NG(401)
				throw new RESTException(__CLASS__.PATH_SEPARATOR.__METHOD__.PATH_SEPARATOR.__LINE__, 401);
			}
		}
		catch (Exception $Exception){
			if(NULL !== $DBO && is_object($DBO)){
				// トランザクションを異常終了する
				$DBO->rollback();
			}
			// 実装の問題によるエラー
			$this->httpStatus = 500;
			if(400 === $Exception->getCode() || 401 === $Exception->getCode() || 404 === $Exception->getCode() || 405 === $Exception->getCode() || 503 === $Exception->getCode()){
				$this->httpStatus = $Exception->getCode();
			}
			throw new RESTException($Exception->getMessage(), $this->httpStatus);
		}

		if(FALSE !== $User){
			// 認証OK
			$this->AuthUser = $User;
			$this->authUserID = $User->pkey;
			// XXX xxx_xxと言うAuthユーザー判定法は固定です！使用は任意になります。
			$this->authUserIDFieldName = strtolower($User->tableName) . '_' . $User->pkeyName;
			$this->authUserQuery = ' ' . $this->authUserIDFieldName . ' = \'' . $User->pkey . '\'';
			return $this->execute();
		}

		// XXX ココを通るのは相当なイレギュラー！
		// 恐らく実装の問題
		$this->httpStatus = 500;
		throw new RESTException(__CLASS__.PATH_SEPARATOR.__METHOD__.PATH_SEPARATOR.__LINE__, 500);
	}

	/**
	 * RESTする
	 * @return array 配列構造のリソースデータ
	 */
	public function execute($argResourceHint=NULL){
		$this->_init();
		debug($this->restResource);
		// RESTアクセスされるリソースの特定
		$this->restResource = $argResourceHint;
		if(NULL === $argResourceHint){
			$this->restResource = $_GET['_r_'];
		}
		if(isset($_GET['_deep_']) && TRUE == ('0' === $_GET['_deep_'] || 'false' === strtolower($_GET['_deep_']))){
			// RESTのDEEPモードを無効にする
			// XXX DEEPモードがTRUEの場合、[model名]_idのフィールドがリソースに合った場合、そのリソースまで自動で参照・更新・作成を試みます
			$this->deepRESTMode = FALSE;
		}
		if(isset($_SERVER['REQUEST_METHOD'])){
			$this->requestMethod = strtoupper($_SERVER['REQUEST_METHOD']);
		}
		debug($this->restResource);
		debug(strtolower($this->requestMethod));
		$resource = self::resolveRESTResource($this->restResource);
		debug($resource);
		if(NULL === $resource){
			// リソースの指定が無かったのでエラー終了
			$this->httpStatus = 400;
			throw new RESTException(__CLASS__.PATH_SEPARATOR.__METHOD__.PATH_SEPARATOR.__LINE__, 400);
		}
		if('GET' !== $this->requestMethod && 'POST' !== $this->requestMethod && NULL === $resource['ids']){
			// GET,POST以外のメソッドの場合はリソースID指定は必須
			$this->httpStatus = 400;
			throw new RESTException(__CLASS__.PATH_SEPARATOR.__METHOD__.PATH_SEPARATOR.__LINE__, 400);
		}

		$res = FALSE;
		$DBO = NULL;
		try{
			$DBO = self::_getDBO();
			// RESTの実行
			$this->restResourceModel = $resource['model'];
			$this->restResource = $resource;
			$classHint = str_replace(' ', '', ucwords(str_replace(' ', '', $this->restResourceModel)));
			debug('$classHint='.$classHint);
			if(FALSE !== MVCCore::loadMVCModule($classHint, TRUE)){
				debug('RestClassLoaded');
				// オーバーライドされたModelへのリソース操作クラスが在る場合は、それをnewして実行する
				$className = MVCCore::loadMVCModule($classHint);
				$RestController = new $className();
				// 自分自身の持っているパブリックパラメータをブリッジ先のRestControllerに引き渡す
				$RestController->controlerClassName = $this->restResourceModel;
				$RestController->httpStatus = $this->httpStatus;
				$RestController->outputType = $this->outputType;
				$RestController->requestMethod = $this->requestMethod;
				$RestController->restResource = $this->restResource;
				$RestController->jsonUnescapedUnicode = $this->jsonUnescapedUnicode;
				$RestController->deviceType = $this->deviceType;
				$RestController->appVersion = $this->appVersion;
				$RestController->appleReviewd = $this->appleReviewd;
				$RestController->mustAppVersioned = $this->mustAppVersioned;
				$RestController->deepRESTMode = $this->deepRESTMode;
				$RestController->restResource = $this->restResource;
				$RestController->restResourceModel = $this->restResourceModel;
				$RestController->restResourceCreateDateKeyName = $this->restResourceCreateDateKeyName;
				$RestController->restResourceModifyDateKeyName = $this->restResourceModifyDateKeyName;
				$RestController->AuthUser = $this->AuthUser;
				$RestController->authUserID = $this->authUserID;
				$RestController->authUserIDFieldName = $this->authUserIDFieldName;
				$RestController->authUserQuery = $this->authUserQuery;
				// リクエストメソッドで分岐する
				$res = $RestController->{strtolower($this->requestMethod)}();
				// 結果のパラメータを受け取り直す
				$this->httpStatus = $RestController->httpStatus;
				$this->outputType = $RestController->outputType;
				$this->requestMethod = $RestController->requestMethod;
				$this->restResource = $RestController->restResource;
				$this->jsonUnescapedUnicode = $RestController->jsonUnescapedUnicode;
				$this->deviceType = $RestController->deviceType;
				$this->appVersion = $RestController->appVersion;
				$this->appleReviewd = $RestController->appleReviewd;
				$this->mustAppVersioned = $RestController->mustAppVersioned;
				$this->deepRESTMode = $RestController->deepRESTMode;
				$this->restResource = $RestController->restResource;
				$this->restResourceModel = $RestController->restResourceModel;
				$this->restResourceCreateDateKeyName = $RestController->restResourceCreateDateKeyName;
				$this->restResourceModifyDateKeyName = $RestController->restResourceModifyDateKeyName;
				$this->AuthUser = $RestController->AuthUser;
				$this->authUserID = $RestController->authUserID;
				$this->authUserIDFieldName = $RestController->authUserIDFieldName;
				$this->authUserQuery = $RestController->authUserQuery;
			}
			else {
				// リクエストメソッドで分岐する
				$res = $this->{strtolower($this->requestMethod)}();
			}
			// トランザクションを正常終了する
			$DBO->commit();
		}
		catch (Exception $Exception){
			if(NULL !== $DBO && is_object($DBO)){
				// トランザクションを異常終了する
				$DBO->rollback();
			}
			// 実装の問題によるエラー
			$this->httpStatus = 500;
			if(400 === $Exception->getCode() || 401 === $Exception->getCode() || 404 === $Exception->getCode() || 405 === $Exception->getCode() || 503 === $Exception->getCode()){
				$this->httpStatus = $Exception->getCode();
			}
			throw new RESTException($Exception->getMessage(), $this->httpStatus);
		}

		debug('res=');
		debug($res);

		if(FALSE === $res || TRUE !== is_array($res)){
			// XXX ココを通るのは相当なイレギュラー！
			// 恐らく実装の問題
			$this->httpStatus = 500;
			throw new RESTException(__CLASS__.PATH_SEPARATOR.__METHOD__.PATH_SEPARATOR.__LINE__, 400);
		}

		// 正常終了(のハズ！)
		return $res;
	}

	/**
	 * RESTAPIでリソースにアクセスする際の指定リソース情報を分解してパラメータ化します
	 * [me指定(任意)/][model指定(必須)/][pkey指定(,pkey指定,...)(GET以外は必須)/]
	 * 例)
	 * 自分の"model"の一覧を取得
	 * /me/model.json
	 * XXX システム毎にRESTのURIパラメータ分解法が違う場合はこのメソッドをオーバーライドして下さい
	 * @param string $argRESTResourceHint
	 * @return array model,listed,fields,idsの配列
	 */
	public static function resolveRESTResource($argRESTResourceHint){
		$resource = NULL;
		if(strlen($argRESTResourceHint) > 0){
			// /区切りのリソースヒントを分解する
			$hints = explode('/', $argRESTResourceHint);
			$hintIdx=0;
			// 少なくとも一つ以上のリソース指定条件を必要とする！
			if(count($hints) >= 1){
				$me = FALSE;
				// me指定があるかどうか
				if('me' === $hints[$hintIdx]){
					$me = TRUE;
					$hintIdx++;
					$model = $hints[$hintIdx];
				}
				else{
					$model = $hints[$hintIdx];
				}
				$hintIdx++;
				$ids = NULL;
				$listed = TRUE;
				// pkey指定があるかどうか
				if(isset($hints[$hintIdx])){
					$ids = array($hints[$hintIdx]);
					if(',' === $hints[$hintIdx]){
						$ids = explode(',', $hints[$hintIdx]);
					}
					if(count($ids) <= 1){
						// リース１件に対してのアクセスなのでlistでは無い
						$listed = FALSE;
					}
				}
				$resource = array('me' => $me, 'model' => $model, 'listed' => $listed, 'ids' => $ids);
			}
		}
		return $resource;
	}

	/**
	 * GETメソッド リソースの参照(冪等性を持ちます)
	 * XXX モデルの位置付けが、テーブルリソースで無い場合は、継承して、RESTの”冪等性”に従って実装して下さい
	 * @return mixed 成功時は最新のリソース配列 失敗時はFALSE
	 */
	public function get(){
		$resources = array();
		$requestParams = array();
		$baseQuery = ' 1=1 ';
		$baseBinds = NULL;
		$isDeepModel = FALSE;
		$deepModels = array();
		if(TRUE === $this->restResource['me']){
			// 認証ユーザーのリソース指定
			// bind使うので自力で組み立てる
			$baseQuery = ' `' . $this->authUserIDFieldName . '` = :' . $this->authUserIDFieldName. ' ';
			$baseBinds = array($this->authUserIDFieldName => $this->authUserID);
		}
		try{
			$requestParams = $this->_getRequestParams();
			$Model = $this->_getModel($this->restResourceModel);
			$fields = $Model->getFieldKeys();
			// REQUESTされているパラメータは条件分に利用する
			for($fieldIdx = 0; $fieldIdx < count($fields); $fieldIdx++){
				if(isset($requestParams[$fields[$fieldIdx]])){
					// GETパラメータでbaseクエリを書き換える
					if(is_array($requestParams[$fields[$fieldIdx]]) && isset($requestParams[$fields[$fieldIdx]]['mark']) && isset($requestParams[$fields[$fieldIdx]]['value'])){
						// =以外の条件を指定したい場合の特殊処理
						$baseQuery .= ' AND `' . $fields[$fieldIdx] . '` ' . $requestParams[$fields[$fieldIdx]]['mark'] . ' :' . $fields[$fieldIdx] . ' ';
						$bindValue = $requestParams[$fields[$fieldIdx]]['value'];
					}
					else{
						$baseQuery .= ' AND `' . $fields[$fieldIdx] . '` = :' . $fields[$fieldIdx] . ' ';
						$bindValue = $requestParams[$fields[$fieldIdx]];
					}
					if(NULL === $baseBinds){
						$baseBinds = array();
					}
					$baseBinds[$fields[$fieldIdx]] = $bindValue;
				}
				// 有効フラグの自動参照制御
				if($this->restResourceAvailableKeyName == $fields[$fieldIdx]){
					$baseQuery .= ' AND `' . $this->restResourceAvailableKeyName . '` = :' . $fields[$fieldIdx] . ' ';
					$baseBinds[$fields[$fieldIdx]] = 1;
				}
				// DEEP-REST用のテーブルとフィールドの一覧をストックしておく
				if(TRUE === $this->deepRESTMode && (strlen($fields[$fieldIdx]) -3) === strpos($fields[$fieldIdx], '_id') && $this->authUserIDFieldName != $fields[$fieldIdx]){
					$deepBaseResource = substr($fields[$fieldIdx], 0, -3);
					debug('deep??'.$deepBaseResource.'&'.$this->authUserIDFieldName.'&'.$fields[$fieldIdx].'&'.(strlen($fields[$fieldIdx]) -3).'&'.strpos($fields[$fieldIdx], '_id'));
					$isDeepModel = TRUE;
					$deepModels[$fields[$fieldIdx]] = $deepBaseResource;
				}
			}
			// 配列の参照
			if(TRUE === $this->restResource['listed']){
				$query = $baseQuery;
				$binds = $baseBinds;
				// ORDER句指定があれば付け足す
				if(isset($requestParams['ORDER'])){
					$query .= ' ORDER BY ' . $requestParams['ORDER'] . ' ';
				}
				elseif(in_array($this->restResourceModifyDateKeyName, $Model->getFieldKeys())) {
					$query .= ' ORDER BY `' . $this->restResourceModifyDateKeyName . '` DESC ';
				}
				elseif(in_array($this->restResourceCreateDateKeyName, $Model->getFieldKeys())) {
					$query .= ' ORDER BY `' . $this->restResourceCreateDateKeyName . '` DESC ';
				}
				else {
					$query .= ' ORDER BY `' . $Model->pkeyName . '` DESC ';
				}
				// LIMIT句指定があれば付け足す
				if(isset($requestParams['LIMIT'])){
					$query .= ' LIMIT ' . $requestParams['LIMIT'] . ' ';
				}

				// 読み込み
				$Model->load($query, $binds);
				if($Model->count > 0){
					do {
						$resources[] = $this->_convertArrayFromModel($Model);
						// DEEP-REST IDに紐づく関連テーブルのレコード参照
						if(TRUE === $isDeepModel){
							foreach($deepModels as $key => $val){
								$id = $resources[count($resources)-1][$key];
								if(0 < (int)$id && 0 < strlen($id)){
									// DEEPは有効なIDの値の時だけ
									$deepResource = $val.'/'.$id;
									debug('deep??'.$deepResource);
									// deepRESTを実行し、IDの取得をする
									$DeepREST = new REST();
									$DeepREST->AuthUser = $this->AuthUser;
									$DeepREST->authUserID = $this->authUserID;
									$DeepREST->authUserIDFieldName = $this->authUserIDFieldName;
									$DeepREST->authUserQuery = $this->authUserQuery;
									$resources[count($resources)-1][$val] = $DeepREST->execute($deepResource);
								}
							}
						}
					} while (false !== $Model->next());
				}
			}
			// ID指定による参照(単一参照含む)
			elseif(NULL !== $this->restResource['ids'] && count($this->restResource['ids']) >= 1){
				// id指定でループする
				for($IDIdx = 0; $IDIdx < count($this->restResource['ids']); $IDIdx++){
					$query = $baseQuery . ' AND `' . $Model->pkeyName . '` = :' . $Model->pkeyName . ' ';
					$binds = $baseBinds;
					if(NULL === $binds){
						$binds = array();
					}
					$binds[$Model->pkeyName] = $this->restResource['ids'][$IDIdx];
					// ORDER句指定があれば付け足す
					if(isset($requestParams['ORDER'])){
						$query .= ' ORDER BY ' . $requestParams['ORDER'] . ' ';
					}
					elseif(in_array($this->restResourceModifyDateKeyName, $Model->getFieldKeys())) {
						$query .= ' ORDER BY `' . $this->restResourceModifyDateKeyName . '` DESC ';
					}
					elseif(in_array($this->restResourceCreateDateKeyName, $Model->getFieldKeys())) {
						$query .= ' ORDER BY `' . $this->restResourceCreateDateKeyName . '` DESC ';
					}
					else {
						$query .= ' ORDER BY `' . $Model->pkeyName . '` DESC ';
					}
					// LIMIT句指定があれば付け足す
					if(isset($requestParams['LIMIT'])){
						$query .= ' LIMIT ' . $requestParams['LIMIT'] . ' ';
					}
					// 読み込み
					$Model->load($query, $binds);
					if($Model->count > 0){
						$resources[] = $this->_convertArrayFromModel($Model);
						// DEEP-REST IDに紐づく関連テーブルのレコード参照
						if(TRUE === $isDeepModel){
							foreach($deepModels as $key => $val){
								$id = $resources[count($resources)-1][$key];
								if(0 < (int)$id && 0 < strlen($id)){
									$deepResource = $val.'/'.$id;
									debug('deep???'.$deepResource);
									// deepRESTを実行し、IDの取得をする
									$DeepREST = new REST();
									$DeepREST->AuthUser = $this->AuthUser;
									$DeepREST->authUserID = $this->authUserID;
									$DeepREST->authUserIDFieldName = $this->authUserIDFieldName;
									$DeepREST->authUserQuery = $this->authUserQuery;
									$resources[count($resources)-1][$val] = $DeepREST->execute($deepResource);
								}
							}
						}
					}
					else if(!isset($Model->pkey) || $Model->pkey != $this->restResource['ids'][$IDIdx]){
						// リソースが存在しない
						debug($Model->pkeyName . '=' . $Model->pkey);
						throw new Exception(__CLASS__.PATH_SEPARATOR.__METHOD__.PATH_SEPARATOR.__LINE__);
					}
				}
			}
		}
		catch (Exception $Exception){
			// リソースが存在しない
			$this->httpStatus = 404;
			throw new RESTException($Exception->getMessage(), $this->httpStatus);
		}
		return $resources;
	}

	/**
	 * POSTメソッド リソースの新規作成、更新、インクリメント、デクリメント(冪等性を強制しません)
	 * XXX モデルの位置付けが、テーブルリソースで無い場合は、継承して、RESTの”冪等性”に従って実装して下さい
	 * @return mixed 成功時は最新のリソース配列 失敗時はFALSE
	 */
	public function post(){
		return $this->put();
		// XXX インクリメント、デクリメントの実装を追加予定
	}

	/**
	 * PUTメソッド リソースの新規作成、更新(冪等性を持ちます)
	 * XXX モデルの位置付けが、テーブルリソースで無い場合は、継承して、RESTの”冪等性”に従って実装して下さい
	 * @return mixed 成功時は最新のリソース配列 失敗時はFALSE
	 */
	public function put(){
		$this->_init();
		$gmtDate = Utilities::date('Y-m-d H:i:s', NULL, NULL, 'GMT');
		$requestParams = array();
		if('PUT' === $_SERVER['REQUEST_METHOD']){
			$requestParams = $this->_getRequestParams();
		}
		else{
			$requestParams = $_POST;
		}
		$resources = FALSE;
		// 更新を行うリソースを特定する
		$baseQuery = ' 1=1 ';
		$baseBinds = NULL;
		if($this->restResource['me'] && NULL !== $this->AuthUser && is_object($this->AuthUser)){
			// 認証ユーザーのリソース指定
			// bind使うので自力で組み立てる
			$baseQuery = ' `' . $this->authUserIDFieldName . '` = :' . $this->authUserIDFieldName. ' ';
			$baseBinds = array($this->authUserIDFieldName => $this->authUserID);
		}
		// リソースの更新
		// XXX 因みに更新はDEEP指定されていてもDEEPしない！
		if(NULL !== $this->restResource['ids'] && count($this->restResource['ids']) >= 1){
			// id指定でループする
			for($IDIdx = 0; $IDIdx < count($this->restResource['ids']); $IDIdx++){
				// 空のモデルを先ず作る
				try{
					$Model = $this->_getModel($this->restResourceModel);
					$query = $baseQuery . ' AND `' . $Model->pkeyName . '` = :' . $Model->pkeyName . ' ';
					$binds = $baseBinds;
					if(NULL === $binds){
						$binds = array();
					}
					$binds[$Model->pkeyName] = $this->restResource['ids'][$IDIdx];
					// 読み込み
					$Model->load($query, $binds);
				}
				catch (Exception $Exception){
					// リソースが存在しない
					$this->httpStatus = 404;
					throw new RESTException($Exception->getMessage(), $this->httpStatus);
					break;
				}
				// 最初の一回目はバリデーションを必ず実行
				if(0 === $IDIdx){
					$datas = array();
					$fields = $Model->getFieldKeys();
					// オートバリデート
					try{
						for($fieldIdx = 0; $fieldIdx < count($fields); $fieldIdx++){
							if(isset($requestParams[$fields[$fieldIdx]])){
								// XXX intのincrementとdecrimentは許可する
								if(FALSE === ('int' === $Model->describes[$fields[$fieldIdx]]['type'] && TRUE === ('increment' === strtolower($requestParams[$fields[$fieldIdx]]) || 'decrement' === strtolower($requestParams[$fields[$fieldIdx]])))){
									// exec系以外はオートバリデート
									$Model->validate($fields[$fieldIdx], $requestParams[$fields[$fieldIdx]]);
								}
								// バリデートに成功したので更新値として認める
								$datas[$fields[$fieldIdx]] = $requestParams[$fields[$fieldIdx]];
							}
							elseif($fields[$fieldIdx] == $this->restResourceCreateDateKeyName && !(0 < strlen($Model->{$this->restResourceCreateDateKeyName}))){
								// データ作成日付の自動補完
								$datas[$fields[$fieldIdx]] = $gmtDate;
							}
							elseif($fields[$fieldIdx] == $this->restResourceModifyDateKeyName){
								// データ更新日付の自動補完
								$datas[$fields[$fieldIdx]] = $gmtDate;
							}
						}
					}
					catch (Exception $Exception){
						// バリデーションエラー(必須パラメータチェックエラー)
						$this->httpStatus = 400;
						throw new RESTException($Exception->getMessage(), $this->httpStatus);
						break;
					}
				}
				// POSTに従ってModelを更新する
				$Model->save($datas);
				// 更新の完了した新しいモデルのデータをレスポンスにセット
				$resources[] = $this->_convertArrayFromModel($Model);
			}
		}
		// リソースの新規作成
		else{
			try{
				$Model = $this->_getModel($this->restResourceModel);
				$datas = array();
				$isDeepModel = FALSE;
				$deepDatas = array();
				$fields = $Model->getFieldKeys();
			}
			catch (Exception $Exception){
				// リソースが存在しない
				$this->httpStatus = 404;
				throw new RESTException($Exception->getMessage(), $this->httpStatus);
			}
			// オートバリデート
			for($fieldIdx = 0; $fieldIdx < count($fields); $fieldIdx++){
				if(isset($requestParams[$fields[$fieldIdx]])){
					try{
						// XXX intのincrementとdecrimentは許可する
						if(FALSE === ('int' === $Model->describes[$fields[$fieldIdx]]['type'] && TRUE === ('increment' === strtolower($requestParams[$fields[$fieldIdx]]) || 'decrement' === strtolower($requestParams[$fields[$fieldIdx]])))){
							// exec系以外はオートバリデート
							$Model->validate($fields[$fieldIdx], $requestParams[$fields[$fieldIdx]]);
						}
						// バリデートに成功したので更新値として認める
						$datas[$fields[$fieldIdx]] = $requestParams[$fields[$fieldIdx]];
					}
					catch (Exception $Exception){
						// バリデーションエラー(必須パラメータチェックエラー)
						$this->httpStatus = 400;
						throw new RESTException($Exception->getMessage(), $this->httpStatus);
						break;
					}
				}
				// DEEP-REST IDに紐づく関連テーブルのレコード作成・更新
				elseif(TRUE === $this->deepRESTMode && (strlen($fields[$fieldIdx]) -3) === strpos($fields[$fieldIdx], '_id') && $this->authUserIDFieldName != $fields[$fieldIdx]){
					$deepResource = substr($fields[$fieldIdx], 0, -3);
					$deepResourcePath = $deepResource;
					if(TRUE === $this->restResource['me']){
						$deepResourcePath = 'me/'.$deepResource;
					}
					debug('deep??'.$deepResourcePath.' & '.$this->authUserIDFieldName.' & '.$fields[$fieldIdx].' & '.(strlen($fields[$fieldIdx]) -3).' & '.strpos($fields[$fieldIdx], '_id'));
					$isDeepModel = TRUE;
					try{
						$deepModel = $this->_getModel($deepResource);
					}
					catch(Exception $Exception){
						$isDeepModel = FALSE;
					}
					if(TRUE === $isDeepModel){
						// deepRESTを実行し、IDの取得をする
						$DeepREST = new REST();
						$DeepREST->AuthUser = $this->AuthUser;
						$DeepREST->authUserID = $this->authUserID;
						$DeepREST->authUserIDFieldName = $this->authUserIDFieldName;
						$DeepREST->authUserQuery = $this->authUserQuery;
						$res = $DeepREST->execute($deepResourcePath);
						$datas[$fields[$fieldIdx]] = $res[0]['id'];
						$deepDatas[$deepResource] = $res;
					}
				}
				// DEEP-REST 自身のIDの自動補完
				elseif(TRUE === $this->deepRESTMode && $this->authUserIDFieldName == $fields[$fieldIdx]) {
					// ログインIDの自動補完
					$datas[$fields[$fieldIdx]] = $this->authUserID;
				}
				if($fields[$fieldIdx] == $this->restResourceCreateDateKeyName || $fields[$fieldIdx] == $this->restResourceModifyDateKeyName){
					// データ作成日付の自動補完
					$datas[$fields[$fieldIdx]] = $gmtDate;
				}
			}
			// POSTに従ってModelを作成する
			$Model->save($datas);
			// 更新の完了した新しいモデルのデータをレスポンスにセット
			$resources[] = $this->_convertArrayFromModel($Model);
			if(TRUE === $isDeepModel && 0 < count($deepDatas)){
				foreach($deepDatas as $key => $val){
					$resources[count($resources)-1][$key] = $val;
				}
			}
		}
		return $resources;
	}

	/**
	 * DELETEメソッド
	 * XXX モデルの位置付けが、テーブルリソースで無い場合は、継承してRESTの”冪等性”に従って実装して下さい(冪等性を持ちます)
	 * @return boolean
	 */
	public function delete(){
		$requestParams = $this->_getRequestParams();
		$baseQuery = ' 1=1 ';
		$baseBinds = NULL;
		$isDeepModel = FALSE;
		$deepModels = array();
		if($this->restResource['me'] && NULL !== $this->AuthUser && is_object($this->AuthUser)){
			// 認証ユーザーのリソース指定
			// bind使うので自力で組み立てる
			$baseQuery = ' `' . $this->authUserIDFieldName . '` = :' . $this->authUserIDFieldName. ' ';
			$baseBinds = array($this->authUserIDFieldName => $this->authUserID);
		}
		// モデルリソースと特定条件を決める
		try{
			// 空のモデルを先ず作る
			$Model = $this->_getModel($this->restResourceModel);
			$fields = $Model->getFieldKeys();
			// REQUESTされているパラメータは条件分に利用する
			for($fieldIdx = 0; $fieldIdx < count($fields); $fieldIdx++){
				if(isset($requestParams[$fields[$fieldIdx]])){
					// GETパラメータでbaseクエリを書き換える
					if(is_array($requestParams[$fields[$fieldIdx]]) && isset($requestParams[$fields[$fieldIdx]]['mark']) && isset($requestParams[$fields[$fieldIdx]]['value'])){
						// =以外の条件を指定したい場合の特殊処理
						$baseQuery .= ' AND `' . $fields[$fieldIdx] . '` ' . $requestParams[$fields[$fieldIdx]]['mark'] . ' :' . $fields[$fieldIdx] . ' ';
						$bindValue = $requestParams[$fields[$fieldIdx]]['value'];
					}
					else{
						$baseQuery .= ' AND `' . $fields[$fieldIdx] . '` = :' . $fields[$fieldIdx] . ' ';
						$bindValue = $requestParams[$fields[$fieldIdx]];
					}
					if(NULL === $baseBinds){
						$baseBinds = array();
					}
					$baseBinds[$fields[$fieldIdx]] = $bindValue;
				}
				// DEEP-REST用のテーブルとフィールドの一覧をストックしておく
				if(TRUE === $this->deepRESTMode && (strlen($fields[$fieldIdx]) -3) === strpos($fields[$fieldIdx], '_id') && $this->authUserIDFieldName != $fields[$fieldIdx]){
					$deepBaseResource = substr($fields[$fieldIdx], 0, -3);
					debug('deep??'.$deepBaseResource.'&'.$this->authUserIDFieldName.'&'.$fields[$fieldIdx].'&'.(strlen($fields[$fieldIdx]) -3).'&'.strpos($fields[$fieldIdx], '_id'));
					$isDeepModel = TRUE;
					$deepModels[$fields[$fieldIdx]] = $deepBaseResource;
				}
			}
			// GETパラメータでbaseクエリを書き換える
			if(is_array($requestParams[$fields[$fieldIdx]]) && isset($requestParams[$fields[$fieldIdx]]['mark']) && isset($requestParams[$fields[$fieldIdx]]['value'])){
				// =以外の条件を指定したい場合の特殊処理
				$baseQuery .= ' AND `' . $fields[$fieldIdx] . '` ' . $requestParams[$fields[$fieldIdx]]['mark'] . ' :' . $fields[$fieldIdx] . ' ';
				$bindValue = $requestParams[$fields[$fieldIdx]]['value'];
			}
			else{
				$baseQuery .= ' AND `' . $fields[$fieldIdx] . '` = :' . $fields[$fieldIdx] . ' ';
				$bindValue = $requestParams[$fields[$fieldIdx]];
			}
			if(NULL === $baseBinds){
				$baseBinds = array();
			}
			$baseBinds[$fields[$fieldIdx]] = $bindValue;
		}
		catch (Exception $Exception){
			// リソースが存在しない
			$this->httpStatus = 404;
			throw new RESTException($Exception->getMessage(), $this->httpStatus);
		}
		// リソースの削除
		if(NULL !== $this->restResource['ids'] && count($this->restResource['ids']) >= 1){
			// id指定でループする
			for($IDIdx = 0; $IDIdx < count($this->restResource['ids']); $IDIdx++){
				$query = $baseQuery . ' AND `' . $Model->pkeyName . '` = :' . $Model->pkeyName . ' ';
				$binds = $baseBinds;
				$binds[$Model->pkeyName] = $this->restResource['ids'][$IDIdx];
				// 読み込み
				$Model->load($query, $binds);
				if((int)$Model->id > 0){
					// リソースの削除を実行
					$Model->remove();
				}
			}
		}
		else{
			// 条件一致した全てのリソースを削除する
			$query = $baseQuery;
			$binds = $baseBinds;
			// 読み込み
			$Model->load($query, $binds);
			if($Model->count > 0){
				// リソースの削除を実行
				do {
					$Model->remove();
				} while (false !== $Model->next());
			}
		}
		return TRUE;
	}
}

?>