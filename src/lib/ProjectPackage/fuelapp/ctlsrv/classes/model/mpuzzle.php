<?php
class Model_Mpuzzle extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'level',
		'type',
		'licensed',
		'drop',
		'mission_time',
		'mission_msg',
		'data',
		'param1',
		'param2',
		'param3',
		'param4',
		'param5',
		'param6',
		'param7',
		'param8',
		'created',
		'modified',
		'available',
		'scale',
	);


	public static function validate($factory)
	{
		$val = Validation::forge($factory);
		$val->add_field('level', 'Level', 'required|valid_string[numeric]');
		$val->add_field('type', 'Type', 'required|max_length[1]');
		$val->add_field('licensed', 'Licensed', 'required|max_length[1]');
		$val->add_field('drop', 'Drop', 'required|valid_string[numeric]');
		$val->add_field('mission_time', 'Mission Time', 'required|valid_string[numeric]');
		$val->add_field('mission_msg', 'Mission Msg', 'required');
		$val->add_field('data', 'Data', 'required|max_length[5000]');
		$val->add_field('param1', 'Param1', 'required|valid_string[numeric]');
		$val->add_field('param2', 'Param2', 'required|valid_string[numeric]');
		$val->add_field('param3', 'Param3', 'required|valid_string[numeric]');
		$val->add_field('param4', 'Param4', 'required|valid_string[numeric]');
		$val->add_field('param5', 'Param5', 'required|valid_string[numeric]');
		$val->add_field('param6', 'Param6', 'required|valid_string[numeric]');
		$val->add_field('param7', 'Param7', 'required|valid_string[numeric]');
		$val->add_field('param8', 'Param8', 'required|valid_string[numeric]');
		$val->add_field('created', 'Created', 'required');
		$val->add_field('modified', 'Modified', 'required');
		$val->add_field('available', 'Available', 'required|max_length[1]');
		$val->add_field('scale', 'Scale', 'required');

		return $val;
	}

}
