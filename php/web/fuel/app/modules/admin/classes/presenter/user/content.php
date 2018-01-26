<?php
namespace Admin;
class Presenter_User_Content extends Presenter_User
{
	public function view()
	{
		parent::view();

		$hash = $this->param['hash'];

		$user = \Libs_User::get_by_hash($hash);
		$this->set('user', $user);

		$this->set_safe('get_email', function($user) {
			return ($user)?$user->email:null;
		});

		$this->set_safe('get_name', function($user) {
			return ($user)?$user->username:null;
		});


	}
}


