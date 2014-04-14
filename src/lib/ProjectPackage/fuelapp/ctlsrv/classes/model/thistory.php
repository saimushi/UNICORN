<?php
class Model_Thistory extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'user_id',
		'puzzle_id',
		'game_mode',
		'charaed',
		'time',
		'cleared',
		'location',
		'created',
		'modified',
		'available',
	);


	public static function validate($factory)
	{
		$val = Validation::forge($factory);
		$val->add_field('user_id', 'User Id', 'required|valid_string[numeric]');
		$val->add_field('puzzle_id', 'Puzzle Id', 'required|valid_string[numeric]');
		$val->add_field('game_mode', 'Game Mode', 'required|max_length[1]');
		$val->add_field('charaed', 'Charaed', 'required|max_length[1]');
		$val->add_field('time', 'Time', 'required|valid_string[numeric]');
		$val->add_field('cleared', 'Cleared', 'required|max_length[1]');
		$val->add_field('location', 'Location', 'required|valid_string[numeric]');
		$val->add_field('created', 'Created', 'required');
		$val->add_field('modified', 'Modified', 'required');
		$val->add_field('available', 'Available', 'required|max_length[1]');

		return $val;
	}

}
