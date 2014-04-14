<?php
class Model_Tdevice extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'user_id',
		'uiid',
		'os',
		'version',
		'created',
		'modified',
		'available',
	);


	public static function validate($factory)
	{
		$val = Validation::forge($factory);
		$val->add_field('user_id', 'User Id', 'required|valid_string[numeric]');
		$val->add_field('uiid', 'Uiid', 'required|max_length[36]');
		$val->add_field('os', 'Os', 'required|max_length[64]');
		$val->add_field('version', 'Version', 'required|max_length[10]');
		$val->add_field('created', 'Created', 'required');
		$val->add_field('modified', 'Modified', 'required');
		$val->add_field('available', 'Available', 'required|max_length[1]');

		return $val;
	}

}
