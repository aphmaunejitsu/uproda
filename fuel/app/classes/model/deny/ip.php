<?php
/**
 * TORの出口ノード拒否用
 *
 */
class Model_Deny_Ip extends Model_Base
{
	protected static $_table_name = 'deny_ips';

	protected static $_properties = [
		'ip',
	];

	protected static $_rules = ['ip' => 'required|valid_ip'];
}

