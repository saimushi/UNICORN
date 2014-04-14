<?php

namespace Fuel\Migrations;

class Create_tinfos
{
	public function up()
	{
		\DBUtil::create_table('tinfos', array(
			'id' => array('constraint' => 10, 'type' => 'int', 'auto_increment' => true, 'comment' => 'PKey'),
			'startdate' => array('type' => 'timestamp', 'default' => '0000-00-00 00:00:00', 'comment' => 'お知らせ開始日'),
			'enddate' => array('type' => 'timestamp', 'default' => '0000-00-00 00:00:00', 'comment' => 'お知らせ終了日'),
			'msg' => array('type' => 'text', 'default' => null, 'null' => true, 'comment' => 'お知らせ文言'),
			'chara_cnt' => array('constraint' => 1, 'type' => 'int', 'default' => 0, 'comment' => 'ゆるキャラ探検後の表示ゆるキャラ数'),
			'assets' => array('type' => 'text', 'default' => null, 'null' => true, 'comment' => 'お知らせに紐づくダウンロードアセットURL一覧(json)'),
			'created' => array('type' => 'timestamp', 'default' => '0000-00-00 00:00:00', 'comment' => 'レコード作成日'),
			'modified' => array('type' => 'timestamp', 'default' => '0000-00-00 00:00:00', 'comment' => 'レコード更新日'),
			'available' => array('constraint' => 1, 'type' => 'char', 'default' => '1', 'comment' => 'レコード有効状態'),

		), array('id'), true, 'MyISAM', null, array(), null, 'お知らせテーブル');
	}

	public function down()
	{
		\DBUtil::drop_table('tinfos');
	}
}