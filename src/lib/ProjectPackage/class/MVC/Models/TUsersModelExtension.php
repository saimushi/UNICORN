<?php

/**
 * サンプルとして一部だけ公開
 * HBOPのautoORMapperの返すモデルクラスに拡張メソッドを追加した場合は
 * 以下の用にモデルクラスを定義する
 */
class TUserModelExtension extends ModelBase {
	public function extention($argment=null){
		return true;
	}
}

?>