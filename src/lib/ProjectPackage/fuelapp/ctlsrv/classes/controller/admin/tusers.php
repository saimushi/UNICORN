<?php
class Controller_Admin_Tusers extends Controller_Admin 
{

	public function action_index()
	{
		$data['tusers'] = Model_Tuser::find('all');
		$this->template->title = "Tusers";
		$this->template->content = View::forge('admin/tusers/index', $data);

	}

	public function action_view($id = null)
	{
		$data['tuser'] = Model_Tuser::find($id);

		$this->template->title = "Tuser";
		$this->template->content = View::forge('admin/tusers/view', $data);

	}

	public function action_create()
	{
		if (Input::method() == 'POST')
		{
			$val = Model_Tuser::validate('create');

			if ($val->run())
			{
				$tuser = Model_Tuser::forge(array(
					'identifier' => Input::post('identifier'),
					'docomo_id' => Input::post('docomo_id'),
					'kids_id' => Input::post('kids_id'),
					'drop' => Input::post('drop'),
					'drop_sum' => Input::post('drop_sum'),
					'projection_level' => Input::post('projection_level'),
					'fourplace_level' => Input::post('fourplace_level'),
					'maze_level' => Input::post('maze_level'),
					'param1' => Input::post('param1'),
					'param2' => Input::post('param2'),
					'param3' => Input::post('param3'),
					'param4' => Input::post('param4'),
					'param5' => Input::post('param5'),
					'param6' => Input::post('param6'),
					'param7' => Input::post('param7'),
					'param8' => Input::post('param8'),
					'tutorial1' => Input::post('tutorial1'),
					'tutorial2' => Input::post('tutorial2'),
					'tutorial3' => Input::post('tutorial3'),
					'created' => Input::post('created'),
					'modified' => Input::post('modified'),
					'available' => Input::post('available'),
				));

				if ($tuser and $tuser->save())
				{
					Session::set_flash('success', e('Added tuser #'.$tuser->id.'.'));

					Response::redirect('admin/tusers');
				}

				else
				{
					Session::set_flash('error', e('Could not save tuser.'));
				}
			}
			else
			{
				Session::set_flash('error', $val->error());
			}
		}

		$this->template->title = "Tusers";
		$this->template->content = View::forge('admin/tusers/create');

	}

	public function action_edit($id = null)
	{
		$tuser = Model_Tuser::find($id);
		$val = Model_Tuser::validate('edit');

		if ($val->run())
		{
			$tuser->identifier = Input::post('identifier');
			$tuser->docomo_id = Input::post('docomo_id');
			$tuser->kids_id = Input::post('kids_id');
			$tuser->drop = Input::post('drop');
			$tuser->drop_sum = Input::post('drop_sum');
			$tuser->projection_level = Input::post('projection_level');
			$tuser->fourplace_level = Input::post('fourplace_level');
			$tuser->maze_level = Input::post('maze_level');
			$tuser->param1 = Input::post('param1');
			$tuser->param2 = Input::post('param2');
			$tuser->param3 = Input::post('param3');
			$tuser->param4 = Input::post('param4');
			$tuser->param5 = Input::post('param5');
			$tuser->param6 = Input::post('param6');
			$tuser->param7 = Input::post('param7');
			$tuser->param8 = Input::post('param8');
			$tuser->created = Input::post('created');
			$tuser->modified = Input::post('modified');
			$tuser->available = Input::post('available');
			$tuser->tutorial1 = Input::post('tutorial1');
			$tuser->tutorial2 = Input::post('tutorial2');
			$tuser->tutorial3 = Input::post('tutorial3');

			if ($tuser->save())
			{
				Session::set_flash('success', e('Updated tuser #' . $id));

				Response::redirect('admin/tusers');
			}

			else
			{
				Session::set_flash('error', e('Could not update tuser #' . $id));
			}
		}

		else
		{
			if (Input::method() == 'POST')
			{
				$tuser->identifier = $val->validated('identifier');
				$tuser->docomo_id = $val->validated('docomo_id');
				$tuser->kids_id = $val->validated('kids_id');
				$tuser->drop = $val->validated('drop');
				$tuser->drop_sum = $val->validated('drop_sum');
				$tuser->projection_level = $val->validated('projection_level');
				$tuser->fourplace_level = $val->validated('fourplace_level');
				$tuser->maze_level = $val->validated('maze_level');
				$tuser->param1 = $val->validated('param1');
				$tuser->param2 = $val->validated('param2');
				$tuser->param3 = $val->validated('param3');
				$tuser->param4 = $val->validated('param4');
				$tuser->param5 = $val->validated('param5');
				$tuser->param6 = $val->validated('param6');
				$tuser->param7 = $val->validated('param7');
				$tuser->param8 = $val->validated('param8');
				$tuser->created = $val->validated('created');
				$tuser->modified = $val->validated('modified');
				$tuser->available = $val->validated('available');
				$tuser->tutorial1 = $val->validated('tutorial1');
				$tuser->tutorial2 = $val->validated('tutorial2');
				$tuser->tutorial3 = $val->validated('tutorial3');

				Session::set_flash('error', $val->error());
			}

			$this->template->set_global('tuser', $tuser, false);
		}

		$this->template->title = "Tusers";
		$this->template->content = View::forge('admin/tusers/edit');

	}

	public function action_delete($id = null)
	{
		if ($tuser = Model_Tuser::find($id))
		{
			$tuser->delete();

			Session::set_flash('success', e('Deleted tuser #'.$id));
		}

		else
		{
			Session::set_flash('error', e('Could not delete tuser #'.$id));
		}

		Response::redirect('admin/tusers');

	}


}