<?php

class SessionMigration_02d1f6e76c2b8ab51036ba0482b7efa806b37e8f extends MigrationBase {

	public $tableName = "session";

	public static $migrationHash = "02d1f6e76c2b8ab51036ba0482b7efa806b37e8f";

	public function __construct(){
		$this->describes = array();
		$this->describes["token"] = array();
		$this->describes["token"]["type"] = "string";
		$this->describes["token"]["null"] = FALSE;
		$this->describes["token"]["pkey"] = TRUE;
		$this->describes["token"]["length"] = 255;
		$this->describes["token"]["min-length"] = 1;
		$this->describes["token"]["autoincrement"] = FALSE;
		$this->describes["created"] = array();
		$this->describes["created"]["type"] = "date";
		$this->describes["created"]["null"] = FALSE;
		$this->describes["created"]["pkey"] = FALSE;
		$this->describes["created"]["min-length"] = 1;
		$this->describes["created"]["autoincrement"] = FALSE;
		return;
	}

	public function up($argDBO){
		return $this->create($argDBO);
	}

	public function down($argDBO){
		return $this->drop($argDBO);
	}
}

?>