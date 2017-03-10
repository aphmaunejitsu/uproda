<?php
namespace Fuel\Migrations;
class create_table_users
{
	public function up()
	{
		$this->users();
	}

	/**
		* CREATE TABLE `users` (
		*    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
		*    `username` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
		*    `password` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
		*    `group` INT NOT NULL DEFAULT 1 ,
		*    `email` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
		*    `last_login` VARCHAR( 25 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 0,
		*    `login_hash` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
		*    `profile_fields` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
		*    `created_at` INT( 11 ) UNSIGNED NOT NULL ,
		*    UNIQUE (
		*        `username` ,
		*        `email`
		*    )
		* )
		**/
	private function users()
	{
		\DBUtil::create_table(
			'users',
			 [
					'id'             => ['type' => 'bigint', 'constraint' => 20, 'auto_increment' => true],
					'username'       => ['type' => 'varchar', 'constraint' => 50],
					'password'       => ['type' => 'varchar', 'constraint' => 255],
					'group'          => ['type' => 'int', 'constraint' => 11, 'default' => 1],
					'email'          => ['type' => 'varchar', 'constraint' => 255],
					'last_login'     => ['type' => 'varchar', 'constraint' => 25],
					'login_hash'     => ['type' => 'varchar', 'constraint' => 255],
					'profile_fields' => ['type' => 'text'],
					'created_at'     => ['type' => 'int', 'constraint' => 11, 'default' => 0],
					'updated_at'     => ['type' => 'int', 'constraint' => 11, 'default' => 0],
				], ['id'], true, 'InnoDB', 'utf8', [], 'uproda-maintenance'
		);

		// add a unique index on username and email
		\DBUtil::create_index('users', ['username', 'email'], 'username', 'UNIQUE', 'uproda-maintenance');
	}


	public function down()
	{
		\DBUtil::drop_table('users', 'uproda-maintenance');
	}
}
