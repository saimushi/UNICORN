<?php

abstract class GenericMigrationBase {

	public static $migrationHash = "";
	public $tableName = "";
	public $describes = array();

	/**
	 * 適用されていないマイグレーションを探して、あれば実行する。なければそのまま終了する
	 * @param instance $argDBO
	 * @return boolean
	 */
	public function up($argDBO){
		//$argDBO->execute($this->upSQL);
		return TRUE;
	}

	/**
	 * DBインスタンス上の全てのテーブルマイグレートを自動解決する
	 * @param unknown $argDBO
	 * @param unknown $argTable
	 * @return boolean
	 */
	public function down($argDBO){
		//$argDBO->execute($this->downSQL);
		return TRUE;
	}
}

?>