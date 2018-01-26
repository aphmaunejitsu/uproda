<?php
namespace Fuel\Tasks;

class hashes
{
	private function log($message)
	{
		\Cli::write(\Date::forge()->format('%Y-%m-%d %H:%M').': '.$message);
	}

	public function run()
	{
		try {
			$this->log('Hashes登録開始');

			\Libs_Config::load('hash');
			$hashes = \Libs_Config::get('hashes');
			$count = 0;

			foreach ($hashes as $hash)
			{
					if (\Model_Image_Hash::find_one_by_hash($hash) === null)
					{
						\Model_Image_Hash::forge()->set(['hash' => $hash, 'ng' => 1, 'comment' => 'NG Image'])->save();
						$count++;
					}
			}

			$this->log($count.'件登録');
			$this->log('Hash登録終了');
		} catch (\Exception $e) {
			\Log::error($e);
		}
	}
}
