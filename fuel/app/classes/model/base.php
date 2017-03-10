<?php
// モデルクラスの親
// create database uproda default character set=utf8
// create user reader@'localhost' IDENTIFIED by 'password'
// create user updater@'localhost' IDENTIFIED by 'password'
// create user maintenance@'localhost' IDENTIFIED by 'password'
// grant select on uproda.* to reader@'localhost'
// grant select,insert,update,delete on uproda.* to updater@'localhost'
// grant alter,create,drop,index,select,insert,update,delete on uproda.* to maintenance@'localhost'
// create user admin_updater@'localhost' identified by 'password'
// create user admin_reader@'localhost' IDENTIFIED by 'password'
// create user admin_maintenance@'localhost' IDENTIFIED by 'password'
// grant select on admin_uproda.* to admin_reader@'localhost'
// grant select,insert,update,delete on admin_uproda.* to admin_updater@'localhost'
// grant alter,create,drop,index,select,insert,update,delete on admin_uproda.* to madmin_aintenance@'localhost'
abstract class Model_Base extends Model_Crud
{
	protected static $_connection = 'uproda-slave';
	protected static $_write_connection = 'uproda-master';

	protected static function pre_find(&$query)
	{
		Log::debug($query);
	}

	protected function pre_update(&$query)
	{
		Log::debug($query);
	}

	protected function pre_delete(&$query)
	{
		Log::debug($query);
	}

	protected function pre_save(&$query)
	{
		Log::debug($query);
	}
}
