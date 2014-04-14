<?php
class Model_Tinfo extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'startdate',
		'enddate',
		'msg',
		'chara_cnt',
		'assets',
		'created',
		'modified',
		'available',
		'os',
	);


	public static function validate($factory)
	{
		$val = Validation::forge($factory);
		$val->add_field('startdate', 'Startdate', 'required');
		$val->add_field('enddate', 'Enddate', 'required');
		$val->add_field('msg', 'Msg', 'required');
		$val->add_field('chara_cnt', 'Chara Cnt', 'required|valid_string[numeric]');
		$val->add_field('assets', 'Assets', 'required');
		$val->add_field('created', 'Created', 'required');
		$val->add_field('modified', 'Modified', 'required');
		$val->add_field('available', 'Available', 'required|max_length[1]');
		$val->add_field('os', 'Os', 'required|max_length[64]');

		return $val;
	}

}
