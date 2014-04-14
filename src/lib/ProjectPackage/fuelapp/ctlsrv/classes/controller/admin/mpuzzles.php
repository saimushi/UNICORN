<?php
class Controller_Admin_Mpuzzles extends Controller_Admin 
{

	public function action_index()
	{
		$data['mpuzzles'] = Model_Mpuzzle::find('all');
		$this->template->title = "Mpuzzles";
		$this->template->content = View::forge('admin/mpuzzles/index', $data);

	}

	public function action_view($id = null)
	{
		$data['mpuzzle'] = Model_Mpuzzle::find($id);

		$this->template->title = "Mpuzzle";
		$this->template->content = View::forge('admin/mpuzzles/view', $data);

	}

	public function action_create()
	{
		if (Input::method() == 'POST')
		{
			$val = Model_Mpuzzle::validate('create');

			if ($val->run())
			{
				$mpuzzle = Model_Mpuzzle::forge(array(
					'level' => Input::post('level'),
					'type' => Input::post('type'),
					'licensed' => Input::post('licensed'),
					'drop' => Input::post('drop'),
					'mission_time' => Input::post('mission_time'),
					'mission_msg' => Input::post('mission_msg'),
					'data' => Input::post('data'),
					'param1' => Input::post('param1'),
					'param2' => Input::post('param2'),
					'param3' => Input::post('param3'),
					'param4' => Input::post('param4'),
					'param5' => Input::post('param5'),
					'param6' => Input::post('param6'),
					'param7' => Input::post('param7'),
					'param8' => Input::post('param8'),
					'created' => Input::post('created'),
					'modified' => Input::post('modified'),
					'available' => Input::post('available'),
					'scale' => Input::post('scale'),
				));

				if ($mpuzzle and $mpuzzle->save())
				{
					Session::set_flash('success', e('Added mpuzzle #'.$mpuzzle->id.'.'));

					Response::redirect('admin/mpuzzles');
				}

				else
				{
					Session::set_flash('error', e('Could not save mpuzzle.'));
				}
			}
			else
			{
				Session::set_flash('error', $val->error());
			}
		}

		$this->template->title = "Mpuzzles";
		$this->template->content = View::forge('admin/mpuzzles/create');

	}

	public function action_edit($id = null)
	{
		$mpuzzle = Model_Mpuzzle::find($id);
		$val = Model_Mpuzzle::validate('edit');

		if ($val->run())
		{
			$mpuzzle->level = Input::post('level');
			$mpuzzle->type = Input::post('type');
			$mpuzzle->licensed = Input::post('licensed');
			$mpuzzle->drop = Input::post('drop');
			$mpuzzle->mission_time = Input::post('mission_time');
			$mpuzzle->mission_msg = Input::post('mission_msg');
			$mpuzzle->data = Input::post('data');
			$mpuzzle->param1 = Input::post('param1');
			$mpuzzle->param2 = Input::post('param2');
			$mpuzzle->param3 = Input::post('param3');
			$mpuzzle->param4 = Input::post('param4');
			$mpuzzle->param5 = Input::post('param5');
			$mpuzzle->param6 = Input::post('param6');
			$mpuzzle->param7 = Input::post('param7');
			$mpuzzle->param8 = Input::post('param8');
			$mpuzzle->created = Input::post('created');
			$mpuzzle->modified = Input::post('modified');
			$mpuzzle->available = Input::post('available');
			$mpuzzle->scale = Input::post('scale');

			if ($mpuzzle->save())
			{
				Session::set_flash('success', e('Updated mpuzzle #' . $id));

				Response::redirect('admin/mpuzzles');
			}

			else
			{
				Session::set_flash('error', e('Could not update mpuzzle #' . $id));
			}
		}

		else
		{
			if (Input::method() == 'POST')
			{
				$mpuzzle->level = $val->validated('level');
				$mpuzzle->type = $val->validated('type');
				$mpuzzle->licensed = $val->validated('licensed');
				$mpuzzle->drop = $val->validated('drop');
				$mpuzzle->mission_time = $val->validated('mission_time');
				$mpuzzle->mission_msg = $val->validated('mission_msg');
				$mpuzzle->data = $val->validated('data');
				$mpuzzle->param1 = $val->validated('param1');
				$mpuzzle->param2 = $val->validated('param2');
				$mpuzzle->param3 = $val->validated('param3');
				$mpuzzle->param4 = $val->validated('param4');
				$mpuzzle->param5 = $val->validated('param5');
				$mpuzzle->param6 = $val->validated('param6');
				$mpuzzle->param7 = $val->validated('param7');
				$mpuzzle->param8 = $val->validated('param8');
				$mpuzzle->created = $val->validated('created');
				$mpuzzle->modified = $val->validated('modified');
				$mpuzzle->available = $val->validated('available');
				$mpuzzle->scale = $val->validated('scale');

				Session::set_flash('error', $val->error());
			}

			$this->template->set_global('mpuzzle', $mpuzzle, false);
		}

		$this->template->title = "Mpuzzles";
		$this->template->content = View::forge('admin/mpuzzles/edit');

	}

	public function action_delete($id = null)
	{
		if ($mpuzzle = Model_Mpuzzle::find($id))
		{
			$mpuzzle->delete();

			Session::set_flash('success', e('Deleted mpuzzle #'.$id));
		}

		else
		{
			Session::set_flash('error', e('Could not delete mpuzzle #'.$id));
		}

		Response::redirect('admin/mpuzzles');

	}


}