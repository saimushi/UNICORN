<?php

namespace Fuel\Migrations;

class Create_tblacklists
{
	public function up()
	{
		\DBUtil::create_table('tblacklists', array(
			'id' => array('constraint' => 10, 'type' => 'int', 'auto_increment' => true, 'comment' => 'PKey'),
			'device' => array('constraint' => 255, 'type' => 'varchar', 'comment' => '端末名'),
			'type' => array('constraint' => 1, 'type' => 'char', 'default' => '1', 'comment' => '1=非推奨,9=禁止'),
			'created' => array('type' => 'timestamp', 'default' => '0000-00-00 00:00:00', 'comment' => 'レコード作成日'),
			'modified' => array('type' => 'timestamp', 'default' => '0000-00-00 00:00:00', 'comment' => 'レコード更新日'),
			'available' => array('constraint' => 1, 'type' => 'char', 'default' => '1', 'comment' => 'レコード有効状態'),

		), array('id'), true, 'MyISAM', null, array(), null, '非推奨端末テーブル');
	}

	public function down()
	{
		\DBUtil::drop_table('tblacklists');
	}
}