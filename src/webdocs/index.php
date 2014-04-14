<?php

// 出力エンコードの明示指定
mb_http_output("UTF-8");

// 内部文字エンコードの明示指定
mb_internal_encoding("UTF-8");

// フレームワーク利用を開始する
require_once dirname(dirname(__FILE__))."/lib/FrameworkPackage/core/HBOP.php";

// プロジェクト専用のコンフィグを読み込む
loadConfig(Configure::LIB_PATH."Projectpackage/core/project.config.xml");

// フレームワークのMVCフレームワーク機能を使う
Core::webmain();

?>