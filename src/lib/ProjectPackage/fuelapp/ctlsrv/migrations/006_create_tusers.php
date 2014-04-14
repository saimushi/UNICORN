<?php

namespace Fuel\Migrations;

class Create_tusers
{
	public function up()
	{
		\DBUtil::create_table('tusers', array(
			'id' => array('constraint' => 10, 'type' => 'int', 'auto_increment' => true, 'comment' => 'PKey'),
			'identifier' => array('constraint' => 255, 'type' => 'varchar', 'comment' => '個人識別子'),
			'docomo_id' => array('constraint' => 128, 'type' => 'varchar', 'comment' => 'ドコモID'),
			'kids_id' => array('constraint' => 64, 'type' => 'varchar', 'default' => null, 'null' => true, 'comment' => 'Dキッズ子供ID'),
			'drop' => array('constraint' => 10, 'type' => 'int', 'default' => 0, 'comment' => '所持ドロップ数'),
			'drop_sum' => array('constraint' => 10, 'type' => 'int', 'default' => 0, 'comment' => '総取得ドロップ数'),
			'projection_level' => array('constraint' => 10, 'type' => 'int', 'default' => 1, 'comment' => '投影図レベル'),
			'fourplace_level' => array('constraint' => 10, 'type' => 'int', 'default' => 1, 'comment' => 'ナンプレレベル'),
			'maze_level' => array('constraint' => 10, 'type' => 'int', 'default' => 1, 'comment' => '迷路レベル'),
			'param1' => array('constraint' => 10, 'type' => 'int', 'default' => 0, 'comment' => '空間把握能力'),
			'param2' => array('constraint' => 10, 'type' => 'int', 'default' => 0, 'comment' => '仮設思考力'),
			'param3' => array('constraint' => 10, 'type' => 'int', 'default' => 0, 'comment' => 'イメージ化能力'),
			'param4' => array('constraint' => 10, 'type' => 'int', 'default' => 0, 'comment' => '着眼力'),
			'param5' => array('constraint' => 10, 'type' => 'int', 'default' => 0, 'comment' => '量感'),
			'param6' => array('constraint' => 10, 'type' => 'int', 'default' => 0, 'comment' => '計算力'),
			'param7' => array('constraint' => 10, 'type' => 'int', 'default' => 0, 'comment' => '注意力'),
			'param8' => array('constraint' => 10, 'type' => 'int', 'default' => 0, 'comment' => 'ねばり強さ'),
			'created' => array('type' => 'timestamp', 'default' => '0000-00-00 00:00:00', 'comment' => 'レコード作成日'),
			'modified' => array('type' => 'timestamp', 'default' => '0000-00-00 00:00:00', 'comment' => 'レコード更新日'),
			'available' => array('constraint' => 1, 'type' => 'char', 'default' => '1', 'comment' => 'レコード有効状態'),

		), array('id'), true, false, null, array(), null, 'ユーザーテーブル');
	}

	public function down()
	{
		\DBUtil::drop_table('tusers');
	}
}