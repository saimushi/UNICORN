<?php

/**
 * 独自Session
 * PHP標準のPHPSESSIDは使わず、tokenをCookieに書き込む方式によるSessionの実現の為のクラス
 * @author saimushi
 */
Interface SessionIO {

	/**
	 * システム毎に書き換え推奨
	 */
	public static function start($argDomain=NULL, $argExpiredtime=NULL, $argDSN=NULL);

	public static function count();

	public static function keys();

	public static function get($argKey = NULL);

	public static function set($argKey, $argment);

	public static function clean();
}

?>