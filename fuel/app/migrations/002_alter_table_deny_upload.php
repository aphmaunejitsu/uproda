<?php
namespace Fuel\Migrations;
class alter_table_deny_upload
{
	public function up()
	{
		$this->deny_upload();
	}

	private function deny_upload()
	{
		\DBUtil::rename_table(
			'deny_upload',
			'deny_ips',
			'uproda-maintenance'
		);
	}

	public function down()
	{
		\DBUtil::rename_table('deny_ips', 'deny_upload', 'uproda-maintenance');
	}

}
