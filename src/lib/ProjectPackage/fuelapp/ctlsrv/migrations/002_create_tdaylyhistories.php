<?php

namespace Fuel\Migrations;

class Create_tdaylyhistories
{
	public function up()
	{
		\DBUtil::create_table('tdaylyhistories', array(
			'id' => array('constraint' => 10, 'type' => 'int', 'auto_increment' => true, 'comment' => 'PKey'),
			'user_id' => array('constraint' => 10, 'type' => 'int', 'comment' => 'ユーザーID'),
			'day' => array('constraint' => 8, 'type' => 'char', 'comment' => '今日の探検実行日'),
			'charaed' => array('constraint' => 1, 'type' => 'char', 'default' => '0', 'comment' => 'ゆるキャラ探検モードフラグ'),
			'created' => array('type' => 'timestamp', 'default' => '0000-00-00 00:00:00', 'comment' => 'レコード作成日'),
			'modified' => array('type' => 'timestamp', 'default' => '0000-00-00 00:00:00', 'comment' => 'レコード更新日'),
			'available' => array('constraint' => 1, 'type' => 'char', 'default' => '1', 'comment' => 'レコード有効状態'),

		), array('id'), true, false, null, array(), null, '今日の探検プレイ履歴テーブル');
	}

	public function down()
	{
		\DBUtil::drop_table('tdaylyhistories');
	}
}