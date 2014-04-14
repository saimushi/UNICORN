<?php

namespace Fuel\Migrations;

class Add_params_to_tusers
{
	public function up()
	{
		\DBUtil::add_fields('tusers', array(
			'tutorial1' => array('constraint' => 1, 'type' => 'char', 'default' => '0', 'comment' => '1=今日の探検入り口終了'),
			'tutorial2' => array('constraint' => 1, 'type' => 'char', 'default' => '0', 'comment' => '1=ゲームクリア'),
			'tutorial3' => array('constraint' => 1, 'type' => 'char', 'default' => '0', 'comment' => '1=シングルゲームレベルセレクト'),

		));
	}

	public function down()
	{
		\DBUtil::drop_fields('tusers', array(
			'tutorial1'
,			'tutorial2'
,			'tutorial3'

		));
	}
}