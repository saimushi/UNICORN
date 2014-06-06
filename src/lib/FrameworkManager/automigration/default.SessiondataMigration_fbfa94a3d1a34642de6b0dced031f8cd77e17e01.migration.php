<?php

class SessiondataMigration_fbfa94a3d1a34642de6b0dced031f8cd77e17e01 extends MigrationBase {

	public $tableName = "sessiondata";

	public static $migrationHash = "fbfa94a3d1a34642de6b0dced031f8cd77e17e01";

	public function __construct(){
		$this->describes = array();
		$this->describes["uid"] = array();
		$this->describes["uid"]["type"] = "string";
		$this->describes["uid"]["null"] = FALSE;
		$this->describes["uid"]["pkey"] = TRUE;
		$this->describes["uid"]["length"] = 32;
		$this->describes["uid"]["autoincrement"] = FALSE;
		$this->describes["data"] = array();
		$this->describes["data"]["type"] = "text";
		$this->describes["data"]["null"] = TRUE;
		$this->describes["data"]["pkey"] = FALSE;
		$this->describes["data"]["length"] = 65535;
		$this->describes["data"]["min-length"] = 1;
		$this->describes["data"]["autoincrement"] = FALSE;
		$this->describes["modified"] = array();
		$this->describes["modified"]["type"] = "date";
		$this->describes["modified"]["null"] = FALSE;
		$this->describes["modified"]["pkey"] = FALSE;
		$this->describes["modified"]["min-length"] = 1;
		$this->describes["modified"]["autoincrement"] = FALSE;
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