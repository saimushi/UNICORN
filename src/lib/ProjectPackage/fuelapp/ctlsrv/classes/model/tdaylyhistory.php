<?php
class Model_Tdaylyhistory extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'user_id',
		'day',
		'charaed',
		'created',
		'modified',
		'available',
	);


	public static function validate($factory)
	{
		$val = Validation::forge($factory);
		$val->add_field('user_id', 'User Id', 'required|valid_string[numeric]');
		$val->add_field('day', 'Day', 'required|max_length[8]');
		$val->add_field('charaed', 'Charaed', 'required|max_length[1]');
		$val->add_field('created', 'Created', 'required');
		$val->add_field('modified', 'Modified', 'required');
		$val->add_field('available', 'Available', 'required|max_length[1]');

		return $val;
	}

}
