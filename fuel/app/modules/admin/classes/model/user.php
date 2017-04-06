<?php
namespace Admin;
class Model_User extends \Model_Base
{
	protected static $_table_name = 'users';

	protected static $_properties = [
  	'username',
  	'password',
  	'email',
  	'last_login',
	];

	protected static $_rules = [
		'username' => 'required',
		'email'    => 'required',
		'password' => 'required',
	];
}
