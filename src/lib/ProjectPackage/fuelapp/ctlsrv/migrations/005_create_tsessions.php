<?php

namespace Fuel\Migrations;

class Create_tsessions
{
	public function up()
	{
		\DBUtil::create_table('tsessions', array(
			'id' => array('constraint' => 10, 'type' => 'int', 'auto_increment' => true, 'comment' => 'Pkey(Fuelマイグレート用)'),
			'token' => array('constraint' => 255, 'type' => 'varchar', 'comment' => 'トークン(実際のPKey)'),
			'data' => array('type' => 'longtext', 'default' => null, 'null' => true, 'comment' => 'セッションデータ(json)'),
			'created' => array('type' => 'timestamp', 'comment' => '作成日'),
		), array('id'), true, 'MyISAM', null, array(), null, 'セッションテーブル');
		\DBUtil::create_index('tsessions', 'token', 'tsessionidx1', 'UNIQUE');
		\DBUtil::create_index('tsessions', array('token','created'), 'tsessionidx2');
	}

	public function down()
	{
		\DBUtil::drop_table('tsessions');
	}
}