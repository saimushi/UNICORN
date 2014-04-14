<?php

namespace Fuel\Migrations;

class Create_thistories
{
	public function up()
	{
		\DBUtil::create_table('thistories', array(
			'id' => array('constraint' => 10, 'type' => 'int', 'auto_increment' => true, 'comment' => 'PKey'),
			'user_id' => array('constraint' => 10, 'type' => 'int', 'comment' => 'ユーザーID'),
			'puzzle_id' => array('constraint' => 10, 'type' => 'int', 'comment' => 'パズルID'),
			'game_mode' => array('constraint' => 1, 'type' => 'char', 'default' => '1', 'comment' => '今日の探検=2,シングルゲーム=1,検定=0'),
			'charaed' => array('constraint' => 1, 'type' => 'char', 'default' => '0', 'comment' => 'ゆるキャラ探検モードフラグ'),
			'time' => array('constraint' => 10, 'type' => 'int', 'default' => 0, 'comment' => 'プレイタイム'),
			'cleared' => array('constraint' => 1, 'type' => 'char', 'default' => '1', 'comment' => 'ゲームクリアフラグ'),
			'location' => array('constraint' => 5, 'type' => 'int', 'default' => 0, 'comment' => '地域番号'),
			'created' => array('type' => 'timestamp', 'default' => '0000-00-00 00:00:00', 'comment' => 'レコード作成日'),
			'modified' => array('type' => 'timestamp', 'default' => '0000-00-00 00:00:00', 'comment' => 'レコード更新日'),
			'available' => array('constraint' => 1, 'type' => 'char', 'default' => '1', 'comment' => 'レコード有効状態'),

		), array('id'), true, false, null, array(), null, 'ゲームプレイ履歴テーブル');
	}

	public function down()
	{
		\DBUtil::drop_table('thistories');
	}
}