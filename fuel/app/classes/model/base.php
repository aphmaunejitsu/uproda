<?php
// モデルクラスの親
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
