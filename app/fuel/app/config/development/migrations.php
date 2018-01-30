<?php
return array(
  'version' => 
  array(
    'app' => 
    array(
      'default' => 
      array(
        0 => '001_create_tables',
        1 => '002_alter_table_deny_upload',
        2 => '003_create_table_denywords',
        3 => '004_create_tables_logs_image_hash',
        4 => '005_create_table_users',
      ),
    ),
    'module' => 
    array(
    ),
    'package' => 
    array(
      'auth' => 
      array(
        0 => '001_auth_create_usertables',
        1 => '002_auth_create_grouptables',
        2 => '003_auth_create_roletables',
        3 => '004_auth_create_permissiontables',
        4 => '005_auth_create_authdefaults',
        5 => '006_auth_add_authactions',
        6 => '007_auth_add_permissionsfilter',
      ),
    ),
  ),
  'folder' => 'migrations/',
  'table' => 'migration',
);
