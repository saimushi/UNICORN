<?php

namespace Fuel\Migrations;

class Add_params_to_tinfos
{
	public function up()
	{
		\DBUtil::add_fields('tinfos', array(
			'os' => array('constraint' => 64, 'type' => 'varchar', 'default' => 'ALL', 'comment' => 'お知らせ対象OS(tdevice>osと同等値)※デフォルト=ALL=全てのOSが対象'),

		));
	}

	public function down()
	{
		\DBUtil::drop_fields('tinfos', array(
			'os'

		));
	}
}