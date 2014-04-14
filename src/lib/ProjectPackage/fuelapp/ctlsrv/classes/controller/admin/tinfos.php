<?php
class Controller_Admin_Tinfos extends Controller_Admin 
{

	public function action_index()
	{
		$data['tinfos'] = Model_Tinfo::find('all');
		$this->template->title = "Tinfos";
		$this->template->content = View::forge('admin/tinfos/index', $data);

	}

	public function action_view($id = null)
	{
		$data['tinfo'] = Model_Tinfo::find($id);

		$this->template->title = "Tinfo";
		$this->template->content = View::forge('admin/tinfos/view', $data);

	}

	public function action_create()
	{
		if (Input::method() == 'POST')
		{
			$val = Model_Tinfo::validate('create');

			if ($val->run())
			{
				$tinfo = Model_Tinfo::forge(array(
					'startdate' => Input::post('startdate'),
					'enddate' => Input::post('enddate'),
					'msg' => Input::post('msg'),
					'chara_cnt' => Input::post('chara_cnt'),
					'assets' => Input::post('assets'),
					'created' => Input::post('created'),
					'modified' => Input::post('modified'),
					'available' => Input::post('available'),
					'os' => Input::post('os'),
				));

				if ($tinfo and $tinfo->save())
				{
					Session::set_flash('success', e('Added tinfo #'.$tinfo->id.'.'));

					Response::redirect('admin/tinfos');
				}

				else
				{
					Session::set_flash('error', e('Could not save tinfo.'));
				}
			}
			else
			{
				Session::set_flash('error', $val->error());
			}
		}

		$this->template->title = "Tinfos";
		$this->template->content = View::forge('admin/tinfos/create');

	}

	public function action_edit($id = null)
	{
		$tinfo = Model_Tinfo::find($id);
		$val = Model_Tinfo::validate('edit');

		if ($val->run())
		{
			$tinfo->startdate = Input::post('startdate');
			$tinfo->enddate = Input::post('enddate');
			$tinfo->msg = Input::post('msg');
			$tinfo->chara_cnt = Input::post('chara_cnt');
			$tinfo->assets = Input::post('assets');
			$tinfo->created = Input::post('created');
			$tinfo->modified = Input::post('modified');
			$tinfo->available = Input::post('available');
			$tinfo->os = Input::post('os');

			if ($tinfo->save())
			{
				Session::set_flash('success', e('Updated tinfo #' . $id));

				Response::redirect('admin/tinfos');
			}

			else
			{
				Session::set_flash('error', e('Could not update tinfo #' . $id));
			}
		}

		else
		{
			if (Input::method() == 'POST')
			{
				$tinfo->startdate = $val->validated('startdate');
				$tinfo->enddate = $val->validated('enddate');
				$tinfo->msg = $val->validated('msg');
				$tinfo->chara_cnt = $val->validated('chara_cnt');
				$tinfo->assets = $val->validated('assets');
				$tinfo->created = $val->validated('created');
				$tinfo->modified = $val->validated('modified');
				$tinfo->available = $val->validated('available');
				$tinfo->os = $val->validated('os');

				Session::set_flash('error', $val->error());
			}

			$this->template->set_global('tinfo', $tinfo, false);
		}

		$this->template->title = "Tinfos";
		$this->template->content = View::forge('admin/tinfos/edit');

	}

	public function action_delete($id = null)
	{
		if ($tinfo = Model_Tinfo::find($id))
		{
			$tinfo->delete();

			Session::set_flash('success', e('Deleted tinfo #'.$id));
		}

		else
		{
			Session::set_flash('error', e('Could not delete tinfo #'.$id));
		}

		Response::redirect('admin/tinfos');

	}


}