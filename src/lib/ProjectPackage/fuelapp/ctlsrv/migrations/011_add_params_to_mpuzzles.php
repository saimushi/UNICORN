<?php

namespace Fuel\Migrations;

class Add_params_to_mpuzzles
{
	public function up()
	{
		\DBUtil::add_fields('mpuzzles', array(
			'scale' => array('constraint' => '2,2', 'type' => 'decimal', 'default' => '1.0', 'comment' => '係数'),

		));
	}

	public function down()
	{
		\DBUtil::drop_fields('mpuzzles', array(
			'scale'

		));
	}
}