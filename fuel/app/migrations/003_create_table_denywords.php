<?php
namespace Fuel\Migrations;
class create_table_denywords
{
	public function up()
	{
		$this->denywords();
	}

	/**
	 * CREATE TABLE `deny_words` (
	 * `id` bigint(20) NOT NULL AUTO_INCREMENT,
	 * `word` varchar(40) DEFAULT NULL,
	 * `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
	 * PRIMARY KEY (`id`),
	 * KEY `idx_basename` (`ip`)
	 * ) ENGINE=InnoDB DEFAULT CHARSET=utf8
	**/
	private function denywords()
	{
		\DBUtil::create_table(
			'deny_words',
			 [
				'id'         => ['constraint' => 20,  'type' => 'bigint',  'auto_increment' => true],
				'word'       => ['constraint' => 200, 'type' => 'varchar', 'null' => false],
				'created_at' => ['type' => 'datetime', 'default' => \DB::expr('CURRENT_TIMESTAMP')],
			],
			['id'], true, 'InnoDB', 'utf8', [], 'uproda-maintenance'
		);

		\DBUtil::create_index('deny_words', 'word', 'idx_word', '', 'uproda-maintenance');
	}

	public function down()
	{
		\DBUtil::drop_table('deny_words', 'uproda-maintenance');
	}

}
