<?php 
return array (
  'id' => 
  array (
    'name' => 'id',
    'type' => 'bigint(20) unsigned',
    'notnull' => false,
    'default' => NULL,
    'primary' => true,
    'autoinc' => true,
  ),
  'username' => 
  array (
    'name' => 'username',
    'type' => 'varchar(50)',
    'notnull' => false,
    'default' => '',
    'primary' => false,
    'autoinc' => false,
  ),
  'password' => 
  array (
    'name' => 'password',
    'type' => 'varchar(32)',
    'notnull' => false,
    'default' => '',
    'primary' => false,
    'autoinc' => false,
  ),
  'qq' => 
  array (
    'name' => 'qq',
    'type' => 'varchar(16)',
    'notnull' => false,
    'default' => '',
    'primary' => false,
    'autoinc' => false,
  ),
  'mail' => 
  array (
    'name' => 'mail',
    'type' => 'varchar(32)',
    'notnull' => false,
    'default' => '',
    'primary' => false,
    'autoinc' => false,
  ),
  'phone' => 
  array (
    'name' => 'phone',
    'type' => 'varchar(16)',
    'notnull' => false,
    'default' => '',
    'primary' => false,
    'autoinc' => false,
  ),
  'login_at' => 
  array (
    'name' => 'login_at',
    'type' => 'datetime',
    'notnull' => false,
    'default' => NULL,
    'primary' => false,
    'autoinc' => false,
  ),
  'login_ip' => 
  array (
    'name' => 'login_ip',
    'type' => 'varchar(255)',
    'notnull' => false,
    'default' => '',
    'primary' => false,
    'autoinc' => false,
  ),
  'login_num' => 
  array (
    'name' => 'login_num',
    'type' => 'bigint(20) unsigned',
    'notnull' => false,
    'default' => '0',
    'primary' => false,
    'autoinc' => false,
  ),
  'authorize' => 
  array (
    'name' => 'authorize',
    'type' => 'varchar(255)',
    'notnull' => false,
    'default' => '',
    'primary' => false,
    'autoinc' => false,
  ),
  'tags' => 
  array (
    'name' => 'tags',
    'type' => 'varchar(255)',
    'notnull' => false,
    'default' => '',
    'primary' => false,
    'autoinc' => false,
  ),
  'default_range' => 
  array (
    'name' => 'default_range',
    'type' => 'varchar(10)',
    'notnull' => false,
    'default' => 'zh-cn',
    'primary' => false,
    'autoinc' => false,
  ),
  'desc' => 
  array (
    'name' => 'desc',
    'type' => 'varchar(255)',
    'notnull' => false,
    'default' => '',
    'primary' => false,
    'autoinc' => false,
  ),
  'status' => 
  array (
    'name' => 'status',
    'type' => 'tinyint(3) unsigned',
    'notnull' => false,
    'default' => '1',
    'primary' => false,
    'autoinc' => false,
  ),
  'is_deleted' => 
  array (
    'name' => 'is_deleted',
    'type' => 'tinyint(3) unsigned',
    'notnull' => false,
    'default' => '0',
    'primary' => false,
    'autoinc' => false,
  ),
  'create_at' => 
  array (
    'name' => 'create_at',
    'type' => 'timestamp',
    'notnull' => false,
    'default' => 'CURRENT_TIMESTAMP',
    'primary' => false,
    'autoinc' => false,
  ),
);