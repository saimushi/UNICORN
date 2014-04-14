<?php
class Controller_Admin_Tsessions extends Controller_Admin 
{

	public function action_index()
	{
		$data['tsessions'] = Model_Tsession::find('all');
		$this->template->title = "Tsessions";
		$this->template->content = View::forge('admin/tsessions/index', $data);

	}

	public function action_view($id = null)
	{
		$data['tsession'] = Model_Tsession::find($id);

		$this->template->title = "Tsession";
		$this->template->content = View::forge('admin/tsessions/view', $data);

	}

	public function action_create()
	{
		if (Input::method() == 'POST')
		{
			$val = Model_Tsession::validate('create');

			if ($val->run())
			{
				$tsession = Model_Tsession::forge(array(
					'token' => Input::post('token'),
					'data' => Input::post('data'),
					'created' => Input::post('created'),
				));

				if ($tsession and $tsession->save())
				{
					Session::set_flash('success', e('Added tsession #'.$tsession->id.'.'));

					Response::redirect('admin/tsessions');
				}

				else
				{
					Session::set_flash('error', e('Could not save tsession.'));
				}
			}
			else
			{
				Session::set_flash('error', $val->error());
			}
		}

		$this->template->title = "Tsessions";
		$this->template->content = View::forge('admin/tsessions/create');

	}

	public function action_edit($id = null)
	{
		$tsession = Model_Tsession::find($id);
		$val = Model_Tsession::validate('edit');

		if ($val->run())
		{
			$tsession->token = Input::post('token');
			$tsession->data = Input::post('data');
			$tsession->created = Input::post('created');

			if ($tsession->save())
			{
				Session::set_flash('success', e('Updated tsession #' . $id));

				Response::redirect('admin/tsessions');
			}

			else
			{
				Session::set_flash('error', e('Could not update tsession #' . $id));
			}
		}

		else
		{
			if (Input::method() == 'POST')
			{
				$tsession->token = $val->validated('token');
				$tsession->data = $val->validated('data');
				$tsession->created = $val->validated('created');

				Session::set_flash('error', $val->error());
			}

			$this->template->set_global('tsession', $tsession, false);
		}

		$this->template->title = "Tsessions";
		$this->template->content = View::forge('admin/tsessions/edit');

	}

	public function action_delete($id = null)
	{
		if ($tsession = Model_Tsession::find($id))
		{
			$tsession->delete();

			Session::set_flash('success', e('Deleted tsession #'.$id));
		}

		else
		{
			Session::set_flash('error', e('Could not delete tsession #'.$id));
		}

		Response::redirect('admin/tsessions');

	}


}