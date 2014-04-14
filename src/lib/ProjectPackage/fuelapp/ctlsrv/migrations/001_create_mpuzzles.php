<?php

namespace Fuel\Migrations;

class Create_mpuzzles
{
	public function up()
	{
		\DBUtil::create_table('mpuzzles', array(
			'id' => array('constraint' => 10, 'type' => 'int', 'auto_increment' => true, 'comment' => 'PKey'),
			'level' => array('constraint' => 10, 'type' => 'int', 'default' => 1, 'comment' => '難易度'),
			'type' => array('constraint' => 1, 'type' => 'char', 'comment' => '1:迷路 2:ナンプレ 3:投影図'),
			'licensed' => array('constraint' => 1, 'type' => 'char', 'default' => '0', 'comment' => '検定問題フラグ'),
			'drop' => array('constraint' => 10, 'type' => 'int', 'default' => 1, 'comment' => '取得ドロップ数(※検定で消費するドロップ数は0として下さい)'),
			'mission_time' => array('constraint' => 10, 'type' => 'int', 'comment' => '目標タイム(※検定以外の問題でも目標タイムを定義する事)'),
			'mission_msg' => array('type' => 'text', 'comment' => '目標文(30文字以内程度)'),
			'data' => array('constraint' => 5000, 'type' => 'varchar', 'comment' => '問題データ(json)'),
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

		), array('id'), true, 'MyISAM', null, array(), null, '問題マスターテーブル');
	}

	public function down()
	{
		\DBUtil::drop_table('mpuzzles');
	}
}