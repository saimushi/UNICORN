<?php

class GenericMigrationManager {

	private static $_lastMigrationHash;

	/**
	 * 適用されていないマイグレーションを探して、あれば実行する。なければそのまま終了する
	 * @param instance $argDBO
	 * @return boolean
	 */
	public static function dispatchAll($argDBO, $argTblName=NULL){
		// 適用差分を見つける
		self::$_lastMigrationHash = NULL;
		$diff = self::_getDiff($argDBO, $argTblName);
		if(count($diff) > 0){
			// 差分の数だけマイグレーションを適用
			for($diffIdx=0; $diffIdx < count($diff); $diffIdx++){
				$migrationFilePath = getAutoMigrationPath().$argDBO->dbidentifykey.'.'.$diff[$diffIdx].'.migration.php';
				if(TRUE === file_exists($migrationFilePath) && TRUE === is_file($migrationFilePath)){
					@include_once $migrationFilePath;
					// migrationの実行
					$migration = $diff[$diffIdx]();
					$migration->up($argDBO);
				}
			}
		}
		if(NULL !== self::$_lastMigrationHash){
			return self::$_lastMigrationHash;
		}
		return TRUE;
	}

	/**
	 * テーブルマイグレートを自動解決する
	 * @param unknown $argDBO
	 * @param unknown $argTable
	 * @return boolean
	 */
	public static function resolve($argDBO, $argTblName, $argLastMigrationHash=NULL){
		$firstMigration = TRUE;
		if(!isset(ORMapper::$modelHashs[$argTblName])){
			// コンソールから強制マイグレーションされる時に恐らくココを通る
			$nowModel = ORMapper::getModel($argDBO, $argTblName);
		}
		// XXX ORMapperとMigrationManagerは循環しているのでいじる時は気をつけて！
		$modelHash = ORMapper::$modelHashs[$argTblName];
		$migrationHash = $argLastMigrationHash;
		if(NULL === $migrationHash){
			// 既に見つけているマイグレーションハッシュから定義を取得する
			$diff = self::_getDiff($argDBO, $argTblName);
			if(NULL !== self::$_lastMigrationHash){
				$migrationHash = self::$_lastMigrationHash;
			}
		}

		if(NULL !== $migrationHash){
			$migrationFilePath = getAutoMigrationPath().$argDBO->dbidentifykey.'.'.$migrationHash.'.migration.php';
			if(TRUE === file_exists($migrationFilePath) && TRUE === is_file($migrationFilePath)){
				// 既にテーブルはあるとココで断定
				$firstMigration = FALSE;
				// 直前のマイグレーションファイルを取得する
				@include_once $migrationFilePath;
				debug($migrationHash::$migrationHash);
				if($modelHash == $migrationHash::$migrationHash){
					// 現在のテーブル定義と最新のマイグレーションファイル上のテーブルハッシュに差分が無いので何もしない
					debug('hash match');
					return TRUE;
				}

			}
		}

		debug('hash not match');

		// テーブル定義を取得
		$tableDefs = ORMapper::getModelPropertyDefs($argDBO, $argTblName);
		$describeDef = $tableDefs['describeDef'];

		$migrationClassDef = PHP_EOL;
		$migrationClassDef .= PHP_EOL . PHP_TAB . 'public function __construct(){' . PHP_EOL . PHP_TAB . PHP_TAB . str_replace('; ', ';' . PHP_EOL . PHP_TAB . PHP_TAB, $describeDef) . 'return;' . PHP_EOL . PHP_TAB . '}'. PHP_EOL;
		if(TRUE === $firstMigration){
			// create指示を生成
			$migrationClassDef .= PHP_EOL . PHP_TAB . 'public function up($argDBO){' . PHP_EOL . PHP_TAB . PHP_TAB . 'return $this->create($argDBO);' . PHP_EOL . PHP_TAB . '}'. PHP_EOL;
			// drop指示を生成
			$migrationClassDef .= PHP_EOL . PHP_TAB . 'public function down($argDBO){' . PHP_EOL . PHP_TAB . PHP_TAB . 'return $this->drop($argDBO);' . PHP_EOL . PHP_TAB . '}'. PHP_EOL;
		}
		else {
			// ALTERかDROP指示を生成
			$upAlterDef = '$alter = array();';
			$downAlterDef = '$alter = array();';
			// 差分をフィールドを走査して特定する
			$lastModel = new $migrationHash();
			$beforeDescribes = $migrationHash->describes;
			$describes = array();
			eval(str_replace('$this->', '$', $describeDef));
			// 増えてる減ってるでループの起点を切り替え
			if(count($describes) >= count($beforeDescribes)) {
				// フィールドが増えている もしくは数は変わらない
				foreach($describes as $feldKey => $propary){
					// 最新のテーブル定義に合わせて
					if($describes){
						// 増えてるフィールドを単純に増やす
						$feldKey;
						// 処理をスキップして次のループへ
						continue;
					}
					// 新旧フィールドのハッシュ値比較
					if($describes){
						// ハッシュ値が違うので新しいフィールド情報でAlterする
					}
				}
			}
			else{
				// フィールドが減っている
				foreach($beforeDescribes as $feldKey => $propary){
					// 前のテーブル定義に合わせて
					if($beforeDescribes){
						// 減ってるフィールドを単純にARTER DROPする
						$feldKey;
						// 処理をスキップして次のループへ
						continue;
					}
					// 新旧フィールドのハッシュ値比較
					if($describes){
						// ハッシュ値が違うので新しいフィールド情報でAlterする
					}
				}
			}
			// alter指示を生成
			$migrationClassDef .= PHP_EOL . PHP_TAB . 'public function up($argDBO){' . PHP_EOL . PHP_TAB . PHP_TAB . $upAlterDef . PHP_EOL . PHP_TAB . PHP_TAB . 'return $this->alter($argDBO, $alter);' . PHP_EOL . PHP_TAB . '}'. PHP_EOL;
			$migrationClassDef .= PHP_EOL . PHP_TAB . 'public function down($argDBO){' . PHP_EOL . PHP_TAB . PHP_TAB . $downAlterDef . PHP_EOL . PHP_TAB . PHP_TAB . 'return $this->alter($argDBO, $alter);' . PHP_EOL . PHP_TAB . '}'. PHP_EOL;
		}

		// 現在の定義でマイグレーションファイルを生成する
		$migrationClassName = self::_createMigrationClassName($argTblName).'_'.$modelHash;
		$migrationClassDef = 'class '.$migrationClassName.' extends MigrationBase {' . PHP_EOL . PHP_EOL . PHP_TAB . 'public $tableName = "' . $argTblName . '";' . PHP_EOL . PHP_EOL . PHP_TAB . 'public static $migrationHash = "' . $modelHash . '";' . $migrationClassDef . '}';
		$path = getAutoMigrationPath().$argDBO->dbidentifykey.'.'.$migrationClassName.'.migration.php';
		@file_put_contents($path, '<?php' . PHP_EOL . PHP_EOL . $migrationClassDef . PHP_EOL . PHP_EOL . '?>');
		@chmod($path, 0777);

		// 生成した場合は、生成環境のマイグレーションが最新で、適用済みと言う事になるので

		// マイグレーション済みファイルを生成し、新たにマイグレーション一覧に追記する
		@file_put_contents_e(getAutoMigrationPath().$argDBO->dbidentifykey.'.all.migrations', $migrationClassName.PHP_EOL, FILE_APPEND);
		@file_put_contents_e(getAutoMigrationPath().$argDBO->dbidentifykey.'.dispatched.migrations', $migrationClassName.PHP_EOL, FILE_APPEND);
		return TRUE;
	}

	private static function _getDiff($argDBO, $argTblName){
		// 実行可能なmigrationの一覧を取得
		$migrationes = array();
		$migrationesFilePath = getAutoMigrationPath().$argDBO->dbidentifykey.'.all.migrations';
		if(TRUE === file_exists($migrationesFilePath) && TRUE === is_file($migrationesFilePath)){
			// 適用済みのmigratione一覧を取得
			$handle = fopen($migrationesFilePath, 'r');
			while(($line = fgets($handle, 4096)) !== false){
				$migrationes[] = trim($line);
			}
		}
		debug($migrationes);
		$dispatchedMigrationesFilePath = getAutoMigrationPath().$argDBO->dbidentifykey.'.dispatched.migrations';
		$dispatchedMigrationes = array();
		if(TRUE === file_exists($dispatchedMigrationesFilePath) && TRUE === is_file($dispatchedMigrationesFilePath)){
			// 適用済みのmigratione一覧を取得
			$handle = fopen($dispatchedMigrationesFilePath, 'r');
			while(($line = fgets($handle, 4096)) !== false){
				$dispatchedMigrationes[] = trim($line);
			}
		}
		self::$_lastMigrationHash = NULL;
		$diff = array();
		// 未適用の差分を探す
		for($migIdx=0; $migIdx < count($migrationes); $migIdx++){
			if(FALSE === in_array($migrationes[$migIdx], $dispatchedMigrationes)){
				// 数が足りていないので、実行対象
				$diff[] = $migrationes[$migIdx];
			}
			// テーブル指定があった場合は、最後の該当テーブルに対するマイグレーションファイルを特定しておく
			if(NULL !== $argTblName){
				if(FALSE !== strpos(strtolower($migrationes[$migIdx]), strtolower($argTblName))){
					self::$_lastMigrationHash = $migrationes[$migIdx];
				}
			}
		}
		return $diff;
	}

	private static function _createMigrationClassName($argTblName){
		$tableName = $argTblName;
		$migrationName = ucfirst($tableName);
		$migrationName = str_replace('_', ' ', $migrationName);
		$migrationName = ucwords($migrationName);
		$migrationName = str_replace(' ', '', $migrationName);
		if((strlen($migrationName) - (strlen('migration'))) === strpos(strtolower($migrationName), 'migration')){
			// 何もしない
		}
		else{
			$migrationName = $migrationName."Migration";
		}
		return $migrationName;
	}

	// 	/**
	// 	 * DBインスタンス上の全てのテーブルマイグレートを自動解決する
	// 	 * @param unknown $argDBO
	// 	 * @param unknown $argTable
	// 	 * @return boolean
	// 	 */
	// 	public static function resolveAll($argDBO){
	// 		// テーブル一覧を取得
	// 		$tables = $argDBO->getTables();
	// 		// すべてのテーブルでマイグレート判定を実行
	// 		for ($idx=0; $idx < count($tables); $idx++) {
	// 			if (FALSE === self::resolve($argDBO, $tables[$idx])) {
	// 				// 処理を強制終了
	// 				BREAK;
	// 			}
	// 		}
	// 		return FALSE;
	// 	}

	// 	/**
	// 	 * テーブルマイグレートを自動解決する
	// 	 * @param unknown $argDBO
	// 	 * @param unknown $argTable
	// 	 * @return boolean
	// 	 */
	// 	public static function resolve($argDBO, $argModel){

	// 		$automigrate = FALSE;
	// 		$newVersion = 1;

	// 		// 存在チェック
	// 		$version = self::is($argDBO, $argTable);
	// 		if (FALSE === $version) {
	// 			// 問答無用で新規作成
	// 			// 自動適用しようも無いのでこのまま終了
	// 			return self::create($argDBO, $argTable);
	// 		}
	// 		else {
	// 			// 更新
	// 			$newVersion = self::modify($argDBO, $argTable, $version);
	// 		}

	// 		if(class_exists('Configure') && NULL !== Configure::constant('AUTO_MIGRATE_FLAG')){
	// 			$automigrate = Configure::AUTO_MIGRATE_FLAG;
	// 		}

	// 		// 自動適用を判定
	// 		if ($automigrate && FALSE === $version && $version < $newVersion){
	// 			// 自動適用
	// 			return self::apply($argDBO, $argTable, $newVersion);
	// 		}

	// 		return FALSE;
	// 	}

	/**
	 * 定義の存在チェック
	 * 現存する、最新のバージョン番号を返却する
	 * @param unknown $argDBO
	 * @param unknown $argTable
	 * @return mixied 正常終了時はint、以上の場合はFALSEを返す
	 */
	public static function is($argDBO, $argTable){
		if(class_exists('Configure') && NULL !== Configure::constant('LIB_DIR')){
			$dirPath = Configure::LIB_DIR . 'automigrate/' . $argTable;
			$isdir = file_exists($dirPath);
			if(TRUE === $isdir && $handle = opendir($dirPath)) {
				/* ディレクトリをループする際の正しい方法です */
				$version = 1;
				while(false !== ($entry = readdir($handle))) {
					if(0 < strpos($entry, $argTable)){
						$nowVersion = (int)substr($entry, 0, strpos($entry, $argTable) - 1);
						if($version < $nowVersion){
							$version = $nowVersion;
						}
					}
				}
				closedir($handle);
				return $version;
			}
		}
		return FALSE;
	}

	/**
	 * 定義の新規作成
	 * @param unknown $argDBO
	 * @param unknown $argTable
	 * @return boolean
	 */
	public static function create($argDBO, $argTable){
		$describes = $argDBO->getTableDescribes($argTable);
		if(is_array($describes) && count($describes) > 0){
			foreach($describes as $colName => $describe){
				$describe;
				// ちょっとこの続きは今度に・・・
				// $describeをそのまま書き出してしまう。後で差分チェックに配列をそのまま流用する為
				// create文と、$describeを同時に補完するクラスファイルを自動生成して終わらせる
			}
		}
		return FALSE;
	}

	/**
	 * 定義の更新
	 * @param unknown $argDBO
	 * @param unknown $argTable
	 * @param unknown $argVersion
	 */
	public static function modify($argDBO, $argTable, $argVersion){
		// 差分を作成し、バージョン番号をインクリメントして行く
		return FALSE;
	}

	/**
	 * 定義の破棄
	 * @param unknown $argDBO
	 * @param unknown $argTable
	 * @return boolean
	 */
	public static function remove($argDBO, $argTable){
		return FALSE;
	}

	/**
	 * 定義の適用
	 * @param unknown $argDBO
	 * @param unknown $argTable
	 * @return boolean
	 */
	public static function apply($argDBO, $argTable, $version){
		return FALSE;
	}
}

?>