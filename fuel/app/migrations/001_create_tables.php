<?php
namespace Fuel\Migrations;
class create_tables
{
	public function up()
	{
		$this->images();
		$this->deny_upload();
	}

	/**
	 * CREATE TABLE `deny_upload` (
	 * `id` bigint(20) NOT NULL AUTO_INCREMENT,
	 * `ip` varchar(40) DEFAULT NULL,
	 * `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
	 * PRIMARY KEY (`id`),
	 * KEY `idx_basename` (`ip`)
	 * ) ENGINE=InnoDB DEFAULT CHARSET=utf8
	**/
	private function deny_upload()
	{
		\DBUtil::create_table(
			'deny_upload',
			 [
				'id'         => ['constraint' => 20,  'type' => 'bigint',  'auto_increment' => true],
				'ip'         => ['constraint' => 40,  'type' => 'varchar', 'default' => \DB::expr('null'), 'null' => true],
				'created_at' => ['type' => 'datetime', 'default' => \DB::expr('CURRENT_TIMESTAMP')],
			],
			['id'], true, 'InnoDB', 'utf8', [], 'uproda-maintenance'
		);

		\DBUtil::create_index('deny_upload', 'ip', 'idx_ip', '', 'uproda-maintenance');
	}

	/**
	 * CREATE TABLE `images` (
	 * `id` bigint(20) NOT NULL AUTO_INCREMENT,
	 * `basename` varchar(100) DEFAULT NULL,
	 * `ext` varchar(10) DEFAULT NULL,
	 * `original` varchar(100) DEFAULT NULL,
	 * `delkey` varchar(20) DEFAULT NULL,
	 * `mimetype` varchar(20) DEFAULT NULL,
	 * `size` int(10) DEFAULT NULL,
	 * `comment` text,
	 * `ip` varchar(40) DEFAULT NULL,
	 * `ng` int(10) DEFAULT NULL,
	 * `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
	 * PRIMARY KEY (`id`),
	 * KEY `idx_basename` (`basename`)
	 * ) ENGINE=InnoDB DEFAULT CHARSET=utf8
	**/
	private function images()
	{
		\DBUtil::create_table(
			'images',
			 [
				'id'         => ['constraint' => 20,  'type' => 'bigint',  'auto_increment' => true],
				'basename'   => ['constraint' => 100, 'type' => 'varchar', 'null' => false],
				'ext'        => ['constraint' => 10,  'type' => 'varchar', 'default' => \DB::expr('null'), 'null' => true],
				'original'   => ['constraint' => 100, 'type' => 'varchar', 'default' => \DB::expr('null'), 'null' => true],
				'delkey'     => ['constraint' => 20,  'type' => 'varchar', 'default' => \DB::expr('null'), 'null' => true],
				'mimetype'   => ['constraint' => 20,  'type' => 'varchar', 'default' => \DB::expr('null'), 'null' => true],
				'size'       => ['constraint' => 10,  'type' => 'int', 'default' => \DB::expr('null'),     'null' => true],
				'comment'    => ['type' => 'text', 'default' => \DB::expr('null'), 'null' => true],
				'ip'         => ['constraint' => 40,  'type' => 'varchar', 'default' => \DB::expr('null'), 'null' => true],
				'ng'         => ['constraint' => 20,  'type' => 'varchar', 'default' => \DB::expr('null'), 'null' => true],
				'created_at' => ['type' => 'datetime', 'default' => \DB::expr('CURRENT_TIMESTAMP')],
			],
			['id'], true, 'InnoDB', 'utf8', [], 'uproda-maintenance'
		);

		\DBUtil::create_index('images', 'basename', 'idx_basename', '', 'uproda-maintenance');
	}


	public function down()
	{
		\DBUtil::drop_table('images', 'uproda-maintenance');
		\DBUtil::drop_table('deny_upload', 'uproda-maintenance');
	}

}
