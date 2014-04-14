<?php

namespace Fuel\Migrations;

class Create_tdevices
{
	public function up()
	{
		\DBUtil::create_table('tdevices', array(
			'id' => array('constraint' => 10, 'type' => 'int', 'auto_increment' => true, 'comment' => 'PKey'),
			'user_id' => array('constraint' => 10, 'type' => 'int', 'comment' => 'ユーザーID'),
			'uiid' => array('constraint' => 36, 'type' => 'char', 'comment' => 'インストールID'),
			'os' => array('constraint' => 64, 'type' => 'varchar', 'comment' => 'OS種別(iOS or Android or PC)'),
			'version' => array('constraint' => 10, 'type' => 'varchar', 'comment' => 'アプリバージョン'),
			'created' => array('type' => 'timestamp', 'default' => '0000-00-00 00:00:00', 'comment' => 'レコード作成日'),
			'modified' => array('type' => 'timestamp', 'default' => '0000-00-00 00:00:00', 'comment' => 'レコード更新日'),
			'available' => array('constraint' => 1, 'type' => 'char', 'default' => '1', 'comment' => 'レコード有効状態'),

		), array('id'), true, false, null, array(), null, 'デバイステーブル');
	}

	public function down()
	{
		\DBUtil::drop_table('tdevices');
	}
}