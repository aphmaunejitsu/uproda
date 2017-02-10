<?php
// モデルクラスの親
// create database uproda default character set=utf8
// create user updater@'localhost' indentified by 'password'
// create user reader@'localhost' IDENTIFIED by 'password'
// create user maintenance@'localhost' IDENTIFIED by 'password'
// grant select on uproda.* to reader@'localhost'
// grant select,insert,update,delete on uproda.* to updater@'localhost'
// grant alter,create,drop,index,select,insert,update,delete on uproda.* to maintenance@'localhost'
abstract class Model_Base extends Model_Crud
{
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
