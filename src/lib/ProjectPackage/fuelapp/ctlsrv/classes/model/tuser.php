<?php
class Model_Tuser extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'identifier',
		'docomo_id',
		'kids_id',
		'drop',
		'drop_sum',
		'projection_level',
		'fourplace_level',
		'maze_level',
		'param1',
		'param2',
		'param3',
		'param4',
		'param5',
		'param6',
		'param7',
		'param8',
		'tutorial1',
		'tutorial2',
		'tutorial3',
		'created',
		'modified',
		'available',
	);


	public static function validate($factory)
	{
		$val = Validation::forge($factory);
		$val->add_field('identifier', 'Identifier', 'required|max_length[255]');
		$val->add_field('docomo_id', 'Docomo Id', 'required|max_length[128]');
		$val->add_field('kids_id', 'Kids Id', 'required|max_length[64]');
		$val->add_field('drop', 'Drop', 'required|valid_string[numeric]');
		$val->add_field('drop_sum', 'Drop Sum', 'required|valid_string[numeric]');
		$val->add_field('projection_level', 'Projection Level', 'required|valid_string[numeric]');
		$val->add_field('fourplace_level', 'Fourplace Level', 'required|valid_string[numeric]');
		$val->add_field('maze_level', 'Maze Level', 'required|valid_string[numeric]');
		$val->add_field('param1', 'Param1', 'required|valid_string[numeric]');
		$val->add_field('param2', 'Param2', 'required|valid_string[numeric]');
		$val->add_field('param3', 'Param3', 'required|valid_string[numeric]');
		$val->add_field('param4', 'Param4', 'required|valid_string[numeric]');
		$val->add_field('param5', 'Param5', 'required|valid_string[numeric]');
		$val->add_field('param6', 'Param6', 'required|valid_string[numeric]');
		$val->add_field('param7', 'Param7', 'required|valid_string[numeric]');
		$val->add_field('param8', 'Param8', 'required|valid_string[numeric]');
		$val->add_field('tutorial1', 'Tutorial1', 'required|max_length[1]');
		$val->add_field('tutorial2', 'Tutorial2', 'required|max_length[1]');
		$val->add_field('tutorial3', 'Tutorial3', 'required|max_length[1]');
		$val->add_field('created', 'Created', 'required');
		$val->add_field('modified', 'Modified', 'required');
		$val->add_field('available', 'Available', 'required|max_length[1]');

		return $val;
	}

}
