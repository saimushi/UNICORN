<?php
class Controller_Tinfos extends Controller_Template 
{

	public function action_index()
	{
		$data['tinfos'] = Model_Tinfo::find('all');
		$this->template->title = "Tinfos";
		$this->template->content = View::forge('tinfos/index', $data);

	}

	public function action_view($id = null)
	{
		is_null($id) and Response::redirect('Tinfos');

		if ( ! $data['tinfo'] = Model_Tinfo::find($id))
		{
			Session::set_flash('error', 'Could not find tinfo #'.$id);
			Response::redirect('Tinfos');
		}

		$this->template->title = "Tinfo";
		$this->template->content = View::forge('tinfos/view', $data);

	}

	public function action_create()
	{
		if (Input::method() == 'POST')
		{
			$val = Model_Tinfo::validate('create');
			
			if ($val->run())
			{
				$tinfo = Model_Tinfo::forge(array(
				));

				if ($tinfo and $tinfo->save())
				{
					Session::set_flash('success', 'Added tinfo #'.$tinfo->id.'.');

					Response::redirect('tinfos');
				}

				else
				{
					Session::set_flash('error', 'Could not save tinfo.');
				}
			}
			else
			{
				Session::set_flash('error', $val->error());
			}
		}

		$this->template->title = "Tinfos";
		$this->template->content = View::forge('tinfos/create');

	}

	public function action_edit($id = null)
	{
		is_null($id) and Response::redirect('Tinfos');

		if ( ! $tinfo = Model_Tinfo::find($id))
		{
			Session::set_flash('error', 'Could not find tinfo #'.$id);
			Response::redirect('Tinfos');
		}

		$val = Model_Tinfo::validate('edit');

		if ($val->run())
		{

			if ($tinfo->save())
			{
				Session::set_flash('success', 'Updated tinfo #' . $id);

				Response::redirect('tinfos');
			}

			else
			{
				Session::set_flash('error', 'Could not update tinfo #' . $id);
			}
		}

		else
		{
			if (Input::method() == 'POST')
			{

				Session::set_flash('error', $val->error());
			}

			$this->template->set_global('tinfo', $tinfo, false);
		}

		$this->template->title = "Tinfos";
		$this->template->content = View::forge('tinfos/edit');

	}

	public function action_delete($id = null)
	{
		is_null($id) and Response::redirect('Tinfos');

		if ($tinfo = Model_Tinfo::find($id))
		{
			$tinfo->delete();

			Session::set_flash('success', 'Deleted tinfo #'.$id);
		}

		else
		{
			Session::set_flash('error', 'Could not delete tinfo #'.$id);
		}

		Response::redirect('tinfos');

	}


}