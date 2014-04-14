<?php
class Model_Tsession extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'token',
		'data',
		'created',
	);


	public static function validate($factory)
	{
		$val = Validation::forge($factory);
		$val->add_field('token', 'Token', 'required|max_length[255]');
		$val->add_field('data', 'Data', 'required');
		$val->add_field('created', 'Created', 'required');

		return $val;
	}

}
