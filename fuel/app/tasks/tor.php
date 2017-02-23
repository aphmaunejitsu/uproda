<?php
namespace Fuel\Tasks;

class Tor
{
	private function log($message)
	{
		\Cli::write(\Date::forge()->format('%Y-%m-%d %H:%M').': '.$message);
	}

	public function run()
	{
		try {
			$this->log('Tor出口ノード取得開始');
			$curl = \Request::forge('https://check.torproject.org/exit-addresses', 'curl')->execute();

			$body_lines = explode("\n", $curl->response()->body());
			$count = 0;
			foreach ($body_lines as $body_line)
			{
				if (strpos($body_line, 'ExitAddress') !== false)
				{
					$c = explode(' ', $body_line);
					if (\Model_Deny_Ip::find_one_by_ip($c[1]) === null)
					{
						\Model_Deny_Ip::forge()->set(['ip' => $c[1]])->save(true);
						$count++;
					}
				}
			}

			$this->log($count.'件登録');
			$this->log('Tor出口ノード取得終了');
		} catch (\Exception $e) {
			\Log::error($e);
		}
	}
}
