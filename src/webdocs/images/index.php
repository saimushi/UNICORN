<?php

// 許可しないURIは404エラーにする
$paths = pathinfo($_SERVER["REQUEST_URI"]);
if(!isset($paths["extension"]) && ("png" === $paths["extension"] || "jpg" === $paths["extension"])){
	// 404 Not Found
	header("HTTP", TRUE, 404);
}

require_once dirname(dirname(dirname(__FILE__)))."/projectcore/webcore.php";

?>