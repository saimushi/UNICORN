<?php

class GenericDBMigrator {

	/**
	 * DBインスタンス上の全てのテーブルマイグレートを自動解決する
	 * @param unknown $argDBO
	 * @param unknown $argTable
	 * @return boolean
	 */
	public static function resolveAll($argDBO){
		// テーブル一覧を取得
		$tables = $argDBO->getTables();
		// すべてのテーブルでマイグレート判定を実行
		for ($idx=0; $idx < count($tables); $idx++) {
			if (FALSE === self::resolve($argDBO, $tables[$idx])) {
				// 処理を強制終了
				BREAK;
			}
		}
		return FALSE;
	}

	/**
	 * テーブルマイグレートを自動解決する
	 * @param unknown $argDBO
	 * @param unknown $argTable
	 * @return boolean
	 */
	public static function resolve($argDBO, $argTable){

		$automigrate = FALSE;
		$newVersion = 1;

		// 存在チェック
		$version = self::is($argDBO, $argTable);
		if (FALSE === $version) {
			// 問答無用で新規作成
			// 自動適用しようも無いのでこのまま終了
			return self::create($argDBO, $argTable);
		}
		else {
			// 更新
			$newVersion = self::modify($argDBO, $argTable, $version);
		}

		if(class_exists('Configure') && NULL !== Configure::constant('AUTO_MIGRATE_FLAG')){
			$automigrate = Configure::AUTO_MIGRATE_FLAG;
		}

		// 自動適用を判定
		if ($automigrate && FALSE === $version && $version < $newVersion){
			// 自動適用
			return self::apply($argDBO, $argTable, $newVersion);
		}

		return FALSE;
	}

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