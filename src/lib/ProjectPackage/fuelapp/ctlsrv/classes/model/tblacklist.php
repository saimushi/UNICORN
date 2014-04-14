<?php
class Model_Tblacklist extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'device',
		'type',
		'created',
		'modified',
		'available',
	);


	public static function validate($factory)
	{
		$val = Validation::forge($factory);
		$val->add_field('device', 'Device', 'required|max_length[255]');
		$val->add_field('type', 'Type', 'required|max_length[1]');
		$val->add_field('created', 'Created', 'required');
		$val->add_field('modified', 'Modified', 'required');
		$val->add_field('available', 'Available', 'required|max_length[1]');

		return $val;
	}

}
