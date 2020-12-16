<?php 
return array (
  'id' => 
  array (
    'name' => 'id',
    'type' => 'int(11)',
    'notnull' => false,
    'default' => NULL,
    'primary' => true,
    'autoinc' => true,
  ),
  'name' => 
  array (
    'name' => 'name',
    'type' => 'varchar(36)',
    'notnull' => false,
    'default' => NULL,
    'primary' => false,
    'autoinc' => false,
  ),
  'num' => 
  array (
    'name' => 'num',
    'type' => 'decimal(18,2)',
    'notnull' => false,
    'default' => NULL,
    'primary' => false,
    'autoinc' => false,
  ),
  'balance' => 
  array (
    'name' => 'balance',
    'type' => 'tinyint(1)',
    'notnull' => false,
    'default' => '0',
    'primary' => false,
    'autoinc' => false,
  ),
  'bili' => 
  array (
    'name' => 'bili',
    'type' => 'decimal(18,4)',
    'notnull' => false,
    'default' => NULL,
    'primary' => false,
    'autoinc' => false,
  ),
  'bili_max' => 
  array (
    'name' => 'bili_max',
    'type' => 'decimal(18,4)',
    'notnull' => false,
    'default' => '0.0000',
    'primary' => false,
    'autoinc' => false,
  ),
  'level' => 
  array (
    'name' => 'level',
    'type' => 'int(11)',
    'notnull' => false,
    'default' => NULL,
    'primary' => false,
    'autoinc' => false,
  ),
  'tixian_ci' => 
  array (
    'name' => 'tixian_ci',
    'type' => 'int(11)',
    'notnull' => false,
    'default' => NULL,
    'primary' => false,
    'autoinc' => false,
  ),
  'tixian_min' => 
  array (
    'name' => 'tixian_min',
    'type' => 'decimal(18,2)',
    'notnull' => false,
    'default' => NULL,
    'primary' => false,
    'autoinc' => false,
  ),
  'tixian_max' => 
  array (
    'name' => 'tixian_max',
    'type' => 'decimal(18,2)',
    'notnull' => false,
    'default' => NULL,
    'primary' => false,
    'autoinc' => false,
  ),
  'order_num' => 
  array (
    'name' => 'order_num',
    'type' => 'int(11)',
    'notnull' => false,
    'default' => NULL,
    'primary' => false,
    'autoinc' => false,
  ),
  'num_min' => 
  array (
    'name' => 'num_min',
    'type' => 'decimal(11,2)',
    'notnull' => false,
    'default' => NULL,
    'primary' => false,
    'autoinc' => false,
  ),
  'limit' => 
  array (
    'name' => 'limit',
    'type' => 'varchar(200)',
    'notnull' => false,
    'default' => '',
    'primary' => false,
    'autoinc' => false,
  ),
  'extended' => 
  array (
    'name' => 'extended',
    'type' => 'varchar(500)',
    'notnull' => false,
    'default' => '',
    'primary' => false,
    'autoinc' => false,
  ),
  'directions' => 
  array (
    'name' => 'directions',
    'type' => 'text',
    'notnull' => false,
    'default' => NULL,
    'primary' => false,
    'autoinc' => false,
  ),
  'addtime' => 
  array (
    'name' => 'addtime',
    'type' => 'datetime',
    'notnull' => false,
    'default' => NULL,
    'primary' => false,
    'autoinc' => false,
  ),
);