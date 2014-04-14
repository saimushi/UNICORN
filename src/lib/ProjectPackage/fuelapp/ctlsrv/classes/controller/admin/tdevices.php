<?php
class Controller_Admin_Tdevices extends Controller_Admin 
{

	public function action_index()
	{
		$data['tdevices'] = Model_Tdevice::find('all');
		$this->template->title = "Tdevices";
		$this->template->content = View::forge('admin/tdevices/index', $data);

	}

	public function action_view($id = null)
	{
		$data['tdevice'] = Model_Tdevice::find($id);

		$this->template->title = "Tdevice";
		$this->template->content = View::forge('admin/tdevices/view', $data);

	}

	public function action_create()
	{
		if (Input::method() == 'POST')
		{
			$val = Model_Tdevice::validate('create');

			if ($val->run())
			{
				$tdevice = Model_Tdevice::forge(array(
					'user_id' => Input::post('user_id'),
					'uiid' => Input::post('uiid'),
					'os' => Input::post('os'),
					'version' => Input::post('version'),
					'created' => Input::post('created'),
					'modified' => Input::post('modified'),
					'available' => Input::post('available'),
				));

				if ($tdevice and $tdevice->save())
				{
					Session::set_flash('success', e('Added tdevice #'.$tdevice->id.'.'));

					Response::redirect('admin/tdevices');
				}

				else
				{
					Session::set_flash('error', e('Could not save tdevice.'));
				}
			}
			else
			{
				Session::set_flash('error', $val->error());
			}
		}

		$this->template->title = "Tdevices";
		$this->template->content = View::forge('admin/tdevices/create');

	}

	public function action_edit($id = null)
	{
		$tdevice = Model_Tdevice::find($id);
		$val = Model_Tdevice::validate('edit');

		if ($val->run())
		{
			$tdevice->user_id = Input::post('user_id');
			$tdevice->uiid = Input::post('uiid');
			$tdevice->os = Input::post('os');
			$tdevice->version = Input::post('version');
			$tdevice->created = Input::post('created');
			$tdevice->modified = Input::post('modified');
			$tdevice->available = Input::post('available');

			if ($tdevice->save())
			{
				Session::set_flash('success', e('Updated tdevice #' . $id));

				Response::redirect('admin/tdevices');
			}

			else
			{
				Session::set_flash('error', e('Could not update tdevice #' . $id));
			}
		}

		else
		{
			if (Input::method() == 'POST')
			{
				$tdevice->user_id = $val->validated('user_id');
				$tdevice->uiid = $val->validated('uiid');
				$tdevice->os = $val->validated('os');
				$tdevice->version = $val->validated('version');
				$tdevice->created = $val->validated('created');
				$tdevice->modified = $val->validated('modified');
				$tdevice->available = $val->validated('available');

				Session::set_flash('error', $val->error());
			}

			$this->template->set_global('tdevice', $tdevice, false);
		}

		$this->template->title = "Tdevices";
		$this->template->content = View::forge('admin/tdevices/edit');

	}

	public function action_delete($id = null)
	{
		if ($tdevice = Model_Tdevice::find($id))
		{
			$tdevice->delete();

			Session::set_flash('success', e('Deleted tdevice #'.$id));
		}

		else
		{
			Session::set_flash('error', e('Could not delete tdevice #'.$id));
		}

		Response::redirect('admin/tdevices');

	}


}