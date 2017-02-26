<?php
namespace Fuel\Migrations;
class create_tables_logs_image_hash
{
	public function up()
	{
		$this->action_logs();
		$this->image_hash();
		$this->alter_image();
	}

	/**
	 * CREATE TABLE `deny_words` (
	 * `id` bigint(20) NOT NULL AUTO_INCREMENT,
	 * `ip` varchar(40) DEFAULT NULL,
	 * `action` varchar(40) DEFAULT NULL,
	 * `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
	 * PRIMARY KEY (`id`),
	 * KEY `idx_basename` (`ip`)
	 * ) ENGINE=InnoDB DEFAULT CHARSET=utf8
	**/
	private function action_logs()
	{
		\DBUtil::create_table(
			'action_logs',
			 [
				'id'         => ['constraint' => 20,  'type' => 'bigint',  'auto_increment' => true],
				'ip'         => ['constraint' => 40,  'type' => 'varchar', 'default' => \DB::expr('null'), 'null' => true],
				'action'     => ['constraint' => 40,  'type' => 'varchar', 'null' => false],
				'image_id'   => ['constraint' => 20,  'type' => 'bigint', 'comment' => 'image.id'],
				'created_at' => ['type' => 'datetime', 'default' => \DB::expr('CURRENT_TIMESTAMP')],
			],
			['id'], true, 'InnoDB', 'utf8', [], 'uproda-maintenance'
		);

		\DBUtil::create_index('action_logs', ['ip', 'created_at'] , 'idx_ip_date', '', 'uproda-maintenance');
	}

	/**
	 * CREATE TABLE `image_hash` (
	 * `id` bigint(20) NOT NULL AUTO_INCREMENT,
	 * `hash` varchar(256) DEFAULT NULL,
	 * `ng'   tinyint(1) DEFAULT 0,
	 * `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
	 * PRIMARY KEY (`id`),
	 * KEY `idx_basename` (`ip`)
	 * ) ENGINE=InnoDB DEFAULT CHARSET=utf8
	**/
	private function image_hash()
	{
		\DBUtil::create_table(
			'image_hash',
			 [
				'id'         => ['constraint' => 20,  'type' => 'bigint',  'auto_increment' => true],
				'hash'       => ['constraint' => 256, 'type' => 'varchar', 'null' => false],
				'comment'    => ['type' => 'text', 'default' => \DB::expr('null'), 'null' => true],
				'ng'         => ['constraint' => 1,   'type' => 'tinyint', 'default' => 0, 'null' => true],
				'created_at' => ['type' => 'datetime', 'default' => \DB::expr('CURRENT_TIMESTAMP')],
			],
			['id'], true, 'InnoDB', 'utf8', [], 'uproda-maintenance'
		);

		\DBUtil::create_index('image_hash', ['hash'], 'idx_hash', '', 'uproda-maintenance');
	}

	/**
	 *
	 **/
	private function alter_image()
	{
		\DBUtil::add_fields('images',[
			'image_hash_id' => ['constraint' => 20, 'type' => 'bigint', 'comment' => 'image_hash.id', 'after' => 'ng'],
		], 'uproda-maintenance');
		\DBUtil::drop_fields('images', 'ng', 'uproda-maintenance');
	}

	public function down()
	{
		\DBUtil::drop_fields('images', 'image_hash_id', 'uproda-maintenance');
		\DBUtil::add_fields('images', [
			'ng' => ['constraint' => 20,  'type' => 'varchar', 'default' => \DB::expr('null'), 'null' => true],
		], 'uproda-maintenance');
		\DBUtil::drop_table('action_logs', 'uproda-maintenance');
		\DBUtil::drop_table('image_hash', 'uproda-maintenance');
	}
}
