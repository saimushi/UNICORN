<?php

abstract class GenericMigrationBase {

	public static $migrationHash = '';
	public $tableName = '';
	public $describes = array();

	private function _getFieldPropatyQuery($argDescribe){
		// create文を生成する
		$fieldDef = '';
		$pkeyDef = '';
		// XXX まだMySQLにしか対応してません！ゴメンナサイ！！
		foreach($argDescribe as $field => $propaty){
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
		return array('fieldDef'=>$fieldDef, 'pkeyDef'=>$pkeyDef);
	}

	/**
	 * createのマイグレーションを適用する
	 * @param instance $argDBO
	 * @return boolean
	 */
	public function create($argDBO){
		$sql = '';
		$fielPropatyQuerys = $this->_getFieldPropatyQuery($this->describes);
		$pkeyDef = $fielPropatyQuerys['pkeyDef'];
		$fieldDef = $fielPropatyQuerys['fieldDef'];
		if(strlen($fieldDef) > 0){
			$sql = 'CREATE TABLE IF NOT EXISTS `' . $this->tableName . '` (' . $fieldDef . $pkeyDef . ')';
			$argDBO->execute($sql);
			$argDBO->commit();
		}
		return TRUE;
	}

	/**
	 * dropのマイグレーションを適用する
	 * @param instance $argDBO
	 * @return boolean
	 */
	public function drop($argDBO){
		$sql = 'DROP TABLE `' . $this->tableName . '`';
		$argDBO->execute($sql);
		$argDBO->commit();
		return TRUE;
	}

	/**
	 * alterのマイグレーションを適用する
	 * @param instance $argDBO
	 * @return boolean
	 */
	public function alter($argDBO, $argDescribes){
		$executed = FALSE;
		// ALTERは一行づつ処理
		foreach($argDescribes as $field => $propaty){
			$sql = '';
			if('DROP' === $propaty['alter']){
				$sql = 'ALTER TABLE `' . $this->tableName . '` DROP COLUMN `' . $field . '`';
			}
			else{
				$fielPropatyQuerys = $this->_getFieldPropatyQuery(array($field => $propaty));
				$fieldDef = $fielPropatyQuerys['fieldDef'];
				if(strlen($fieldDef) > 0){
					$sql = 'ALTER TABLE `' . $this->tableName . '` ' . $propaty['alter'] . ' COLUMN ' . $fieldDef;
				}
			}
			if(strlen($sql) > 0){
				$executed = TRUE;
				$argDBO->execute($sql);
			}
		}
		if(TRUE === $executed){
			$argDBO->commit();
		}
		return TRUE;
	}
}

?>