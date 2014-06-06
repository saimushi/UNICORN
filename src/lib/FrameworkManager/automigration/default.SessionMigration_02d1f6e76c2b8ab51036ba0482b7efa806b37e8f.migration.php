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
		$alter = array();
		$alter["uid"] = array();
		$alter["uid"]["alter"] = "DROP";
		$alter["data"] = array();
		$alter["data"]["alter"] = "DROP";
		$alter["modified"] = array();
		$alter["modified"]["alter"] = "DROP";
		
		return $this->alter($argDBO, $alter);
	}

	public function down($argDBO){
		$alter = array();
		$alter["uid"] = array();
		$alter["uid"]["type"] = "string";
		$alter["uid"]["null"] = FALSE;
		$alter["uid"]["pkey"] = TRUE;
		$alter["uid"]["length"] = 32;
		$alter["uid"]["autoincrement"] = FALSE;
		$alter["uid"]["alter"] = "ADD";
		$alter["data"] = array();
		$alter["data"]["type"] = "text";
		$alter["data"]["null"] = TRUE;
		$alter["data"]["pkey"] = FALSE;
		$alter["data"]["length"] = 65535;
		$alter["data"]["min-length"] = 1;
		$alter["data"]["autoincrement"] = FALSE;
		$alter["data"]["alter"] = "ADD";
		$alter["modified"] = array();
		$alter["modified"]["type"] = "date";
		$alter["modified"]["null"] = FALSE;
		$alter["modified"]["pkey"] = FALSE;
		$alter["modified"]["min-length"] = 1;
		$alter["modified"]["autoincrement"] = FALSE;
		$alter["modified"]["alter"] = "ADD";
		
		return $this->alter($argDBO, $alter);
	}
}

?>