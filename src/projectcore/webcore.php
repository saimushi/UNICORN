<?php

// 内部文字エンコードの明示指定
mb_internal_encoding("UTF-8");

require_once dirname(dirname(__FILE__))."/core/HBOP.php";

// プロジェクト専用のコンフィグを読み込む
loadConfig(Config::ROOT_PATH.'/projectcore/project.config.xml');

// HBOPのMVCフレームワーク機能を使う
Core::webmain();

?>