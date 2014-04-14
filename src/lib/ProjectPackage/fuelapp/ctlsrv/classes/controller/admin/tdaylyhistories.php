<?php
class Controller_Admin_Tdaylyhistories extends Controller_Admin 
{

	public function action_index()
	{
		$data['tdaylyhistories'] = Model_Tdaylyhistory::find('all');
		$this->template->title = "Tdaylyhistories";
		$this->template->content = View::forge('admin/tdaylyhistories/index', $data);

	}

	public function action_view($id = null)
	{
		$data['tdaylyhistory'] = Model_Tdaylyhistory::find($id);

		$this->template->title = "Tdaylyhistory";
		$this->template->content = View::forge('admin/tdaylyhistories/view', $data);

	}

	public function action_create()
	{
		if (Input::method() == 'POST')
		{
			$val = Model_Tdaylyhistory::validate('create');

			if ($val->run())
			{
				$tdaylyhistory = Model_Tdaylyhistory::forge(array(
					'user_id' => Input::post('user_id'),
					'day' => Input::post('day'),
					'charaed' => Input::post('charaed'),
					'created' => Input::post('created'),
					'modified' => Input::post('modified'),
					'available' => Input::post('available'),
				));

				if ($tdaylyhistory and $tdaylyhistory->save())
				{
					Session::set_flash('success', e('Added tdaylyhistory #'.$tdaylyhistory->id.'.'));

					Response::redirect('admin/tdaylyhistories');
				}

				else
				{
					Session::set_flash('error', e('Could not save tdaylyhistory.'));
				}
			}
			else
			{
				Session::set_flash('error', $val->error());
			}
		}

		$this->template->title = "Tdaylyhistories";
		$this->template->content = View::forge('admin/tdaylyhistories/create');

	}

	public function action_edit($id = null)
	{
		$tdaylyhistory = Model_Tdaylyhistory::find($id);
		$val = Model_Tdaylyhistory::validate('edit');

		if ($val->run())
		{
			$tdaylyhistory->user_id = Input::post('user_id');
			$tdaylyhistory->day = Input::post('day');
			$tdaylyhistory->charaed = Input::post('charaed');
			$tdaylyhistory->created = Input::post('created');
			$tdaylyhistory->modified = Input::post('modified');
			$tdaylyhistory->available = Input::post('available');

			if ($tdaylyhistory->save())
			{
				Session::set_flash('success', e('Updated tdaylyhistory #' . $id));

				Response::redirect('admin/tdaylyhistories');
			}

			else
			{
				Session::set_flash('error', e('Could not update tdaylyhistory #' . $id));
			}
		}

		else
		{
			if (Input::method() == 'POST')
			{
				$tdaylyhistory->user_id = $val->validated('user_id');
				$tdaylyhistory->day = $val->validated('day');
				$tdaylyhistory->charaed = $val->validated('charaed');
				$tdaylyhistory->created = $val->validated('created');
				$tdaylyhistory->modified = $val->validated('modified');
				$tdaylyhistory->available = $val->validated('available');

				Session::set_flash('error', $val->error());
			}

			$this->template->set_global('tdaylyhistory', $tdaylyhistory, false);
		}

		$this->template->title = "Tdaylyhistories";
		$this->template->content = View::forge('admin/tdaylyhistories/edit');

	}

	public function action_delete($id = null)
	{
		if ($tdaylyhistory = Model_Tdaylyhistory::find($id))
		{
			$tdaylyhistory->delete();

			Session::set_flash('success', e('Deleted tdaylyhistory #'.$id));
		}

		else
		{
			Session::set_flash('error', e('Could not delete tdaylyhistory #'.$id));
		}

		Response::redirect('admin/tdaylyhistories');

	}


}