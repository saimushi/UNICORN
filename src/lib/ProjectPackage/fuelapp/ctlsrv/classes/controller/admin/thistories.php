<?php
class Controller_Admin_Thistories extends Controller_Admin 
{

	public function action_index()
	{
		$data['thistories'] = Model_Thistory::find('all');
		$this->template->title = "Thistories";
		$this->template->content = View::forge('admin/thistories/index', $data);

	}

	public function action_view($id = null)
	{
		$data['thistory'] = Model_Thistory::find($id);

		$this->template->title = "Thistory";
		$this->template->content = View::forge('admin/thistories/view', $data);

	}

	public function action_create()
	{
		if (Input::method() == 'POST')
		{
			$val = Model_Thistory::validate('create');

			if ($val->run())
			{
				$thistory = Model_Thistory::forge(array(
					'user_id' => Input::post('user_id'),
					'puzzle_id' => Input::post('puzzle_id'),
					'game_mode' => Input::post('game_mode'),
					'charaed' => Input::post('charaed'),
					'time' => Input::post('time'),
					'cleared' => Input::post('cleared'),
					'location' => Input::post('location'),
					'created' => Input::post('created'),
					'modified' => Input::post('modified'),
					'available' => Input::post('available'),
				));

				if ($thistory and $thistory->save())
				{
					Session::set_flash('success', e('Added thistory #'.$thistory->id.'.'));

					Response::redirect('admin/thistories');
				}

				else
				{
					Session::set_flash('error', e('Could not save thistory.'));
				}
			}
			else
			{
				Session::set_flash('error', $val->error());
			}
		}

		$this->template->title = "Thistories";
		$this->template->content = View::forge('admin/thistories/create');

	}

	public function action_edit($id = null)
	{
		$thistory = Model_Thistory::find($id);
		$val = Model_Thistory::validate('edit');

		if ($val->run())
		{
			$thistory->user_id = Input::post('user_id');
			$thistory->puzzle_id = Input::post('puzzle_id');
			$thistory->game_mode = Input::post('game_mode');
			$thistory->charaed = Input::post('charaed');
			$thistory->time = Input::post('time');
			$thistory->cleared = Input::post('cleared');
			$thistory->location = Input::post('location');
			$thistory->created = Input::post('created');
			$thistory->modified = Input::post('modified');
			$thistory->available = Input::post('available');

			if ($thistory->save())
			{
				Session::set_flash('success', e('Updated thistory #' . $id));

				Response::redirect('admin/thistories');
			}

			else
			{
				Session::set_flash('error', e('Could not update thistory #' . $id));
			}
		}

		else
		{
			if (Input::method() == 'POST')
			{
				$thistory->user_id = $val->validated('user_id');
				$thistory->puzzle_id = $val->validated('puzzle_id');
				$thistory->game_mode = $val->validated('game_mode');
				$thistory->charaed = $val->validated('charaed');
				$thistory->time = $val->validated('time');
				$thistory->cleared = $val->validated('cleared');
				$thistory->location = $val->validated('location');
				$thistory->created = $val->validated('created');
				$thistory->modified = $val->validated('modified');
				$thistory->available = $val->validated('available');

				Session::set_flash('error', $val->error());
			}

			$this->template->set_global('thistory', $thistory, false);
		}

		$this->template->title = "Thistories";
		$this->template->content = View::forge('admin/thistories/edit');

	}

	public function action_delete($id = null)
	{
		if ($thistory = Model_Thistory::find($id))
		{
			$thistory->delete();

			Session::set_flash('success', e('Deleted thistory #'.$id));
		}

		else
		{
			Session::set_flash('error', e('Could not delete thistory #'.$id));
		}

		Response::redirect('admin/thistories');

	}


}