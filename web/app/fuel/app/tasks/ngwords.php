<?php
namespace Fuel\Tasks;

class Ngwords
{
	private function log($message)
	{
		\Cli::write(\Date::forge()->format('%Y-%m-%d %H:%M').': '.$message);
	}

	public function run()
	{
		try {
			$this->log('NGWords登録開始');

			\Libs_Config::load('spamwords');
			$words = \Libs_Config::get('words');
			$count = 0;

			foreach ($words as $word)
			{
					if (\Model_Deny_word::find_one_by_word($word) === null)
					{
						\Model_Deny_word::forge()->set(['word' => $word])->save();
						$count++;
					}
			}

			$this->log($count.'件登録');
			$this->log('NGWords登録終了');
		} catch (\Exception $e) {
			\Log::error($e);
		}
	}
}
