<?php

abstract class GenericMigrationBase {

	public static $migrationHash = '';
	public $tableName = '';
	public $describes = array();

	/**
	 * createのマイグレーションを適用する
	 * @param instance $argDBO
	 * @return boolean
	*/
	public function create($argDBO){
		$sql = '';
		// create文を生成する
		$fieldDef = '';
		$pkeyDef = '';
		// XXX まだMySQLにしか対応してません！ゴメンナサイ！！
		foreach($this->describes as $field => $propaty){
			if(strlen($fieldDef) > 0){
				// 2行目以降は頭に「,」付ける
				$fieldDef .= ', ';
			}
			$fieldDef .= '`' . $field . '`';
			if('string' === $propaty['type'] && isset($propaty['min-length'])){
				$fieldDef .= ' VARCHAR(' . $propaty['length'] . ')';
			}
			elseif('string' === $propaty['type']){
				$fieldDef .= ' CHAR(' . $propaty['length'] . ')';
			}
			elseif('int' === $propaty['type']){
				$fieldDef .= ' int(' . $propaty['length'] . ')';
			}
			elseif('date' === $propaty['type']){
				$fieldDef .= ' DATETIME';
			}
			else {
				$fieldDef .= ' '.$propaty['type'];
			}
			if(FALSE === $propaty['null']){
				$fieldDef .= ' NOT NULL';
			}
			if(isset($propaty['default'])){
				$default = '\'' . $propaty['default'] . '\'';
				if('FALSE' === $default){
					$default = '\'0\'';
				}
				elseif('TRUE' === $default) {
					$default = '\'1\'';
				}
				elseif('NULL' === $default) {
					$default = 'NULL';
				}
				$fieldDef .= ' DEFAULT ' . $default;
			}
			if(isset($propaty['autoincrement']) && TRUE === $propaty['autoincrement']){
				$fieldDef .= ' AUTO_INCREMENT';
			}
			if(isset($propaty['pkey']) && TRUE === $propaty['pkey']){
				$pkeyDef .= ', PRIMARY KEY(`' . $field . '`)';
			}
		}
		if(strlen($fieldDef) > 0){
			$sql = 'CREATE TABLE IF NOT EXISTS `' . $this->tableName . '` (' . $fieldDef . $pkeyDef . ')';
			$argDBO->execute($sql);
		}
		return TRUE;
	}

	/**
	 * dropのマイグレーションを適用する
	 * @param instance $argDBO
	 * @return boolean
	 */
	public function down($argDBO){
		$sql = 'DROP TABLE `' . $this->tableName . '`';
		$argDBO->execute($sql);
		return TRUE;
	}
}

?>