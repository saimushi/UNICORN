<?php
/**
 * locale.inc.php
 *
 * ローカライズ用関数
 * gettextの設定を行う
 *
 * 条件
 * conf.phpをinculudeしていること
 *
 * @author t.matsuba
 * @version $Id$
 * @copyright cybird.co.jp
 */



/**
 * setLocalize
 * 翻訳したい場合は本関数をコールしてね
 *
 * param null
 * return boolean
 */
function setLocalize($argLanguage){
	//// ローカライズ設定
	// 言語設定
	$language = $argLanguage;

	// jaの場合はja_JPに変換
	if( 0 == strcmp($language, 'ja') ){
		$language = 'ja_JP';
	} elseif( 0 == strcmp($language, 'en') ){
		$language = 'en_US';
	} elseif( 0 == strcmp($language, 'zh') ){
		$language = 'zh_CN';
	}

	// gettext設定
	putenv("LANG={$language}");
	setlocale(LC_ALL, $language);
	bindtextdomain(LOCALE_DOMAIN,LOCALE_PATH);
	bind_textdomain_codeset(LOCALE_DOMAIN, 'utf-8');
	textdomain(LOCALE_DOMAIN);
}


/**
 * localeDate
 * 言語別の日付を取得する。
 *
 * param int, string
 * return string
 */
function localeDate($argTimestamp=NULL,$argLanguage='en') {

	$language = $argLanguage;
	$timestamp = $argTimestamp;

	// タイムスタンプ設定
	if (NULL===$timestamp) {
		$timestamp = time();
	}

	$DateInstance = new DateTime($timestamp);

	if ('en' == $language) {
		return $DateInstance->format('M. j, Y'); // Jan. 1, 2011 # アメリカ
	} elseif ('ja' == $language || 'zh' == $language) {
		return $DateInstance->format('Y/n/j'); // 2011/1/1 # 東アジア
	}
	return $DateInstance->format('j/n/Y'); // 2011/1/1 # その他
}
?>