<?php
class Controller_Admin_Tblacklists extends Controller_Admin 
{

	public function action_index()
	{
		$data['tblacklists'] = Model_Tblacklist::find('all');
		$this->template->title = "Tblacklists";
		$this->template->content = View::forge('admin/tblacklists/index', $data);

	}

	public function action_view($id = null)
	{
		$data['tblacklist'] = Model_Tblacklist::find($id);

		$this->template->title = "Tblacklist";
		$this->template->content = View::forge('admin/tblacklists/view', $data);

	}

	public function action_create()
	{
		if (Input::method() == 'POST')
		{
			$val = Model_Tblacklist::validate('create');

			if ($val->run())
			{
				$tblacklist = Model_Tblacklist::forge(array(
					'device' => Input::post('device'),
					'type' => Input::post('type'),
					'created' => Input::post('created'),
					'modified' => Input::post('modified'),
					'available' => Input::post('available'),
				));

				if ($tblacklist and $tblacklist->save())
				{
					Session::set_flash('success', e('Added tblacklist #'.$tblacklist->id.'.'));

					Response::redirect('admin/tblacklists');
				}

				else
				{
					Session::set_flash('error', e('Could not save tblacklist.'));
				}
			}
			else
			{
				Session::set_flash('error', $val->error());
			}
		}

		$this->template->title = "Tblacklists";
		$this->template->content = View::forge('admin/tblacklists/create');

	}

	public function action_edit($id = null)
	{
		$tblacklist = Model_Tblacklist::find($id);
		$val = Model_Tblacklist::validate('edit');

		if ($val->run())
		{
			$tblacklist->device = Input::post('device');
			$tblacklist->type = Input::post('type');
			$tblacklist->created = Input::post('created');
			$tblacklist->modified = Input::post('modified');
			$tblacklist->available = Input::post('available');

			if ($tblacklist->save())
			{
				Session::set_flash('success', e('Updated tblacklist #' . $id));

				Response::redirect('admin/tblacklists');
			}

			else
			{
				Session::set_flash('error', e('Could not update tblacklist #' . $id));
			}
		}

		else
		{
			if (Input::method() == 'POST')
			{
				$tblacklist->device = $val->validated('device');
				$tblacklist->type = $val->validated('type');
				$tblacklist->created = $val->validated('created');
				$tblacklist->modified = $val->validated('modified');
				$tblacklist->available = $val->validated('available');

				Session::set_flash('error', $val->error());
			}

			$this->template->set_global('tblacklist', $tblacklist, false);
		}

		$this->template->title = "Tblacklists";
		$this->template->content = View::forge('admin/tblacklists/edit');

	}

	public function action_delete($id = null)
	{
		if ($tblacklist = Model_Tblacklist::find($id))
		{
			$tblacklist->delete();

			Session::set_flash('success', e('Deleted tblacklist #'.$id));
		}

		else
		{
			Session::set_flash('error', e('Could not delete tblacklist #'.$id));
		}

		Response::redirect('admin/tblacklists');

	}


}