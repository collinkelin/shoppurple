<?php 
return array (
  'id' => 
  array (
    'name' => 'id',
    'type' => 'bigint(16) unsigned',
    'notnull' => false,
    'default' => NULL,
    'primary' => true,
    'autoinc' => true,
  ),
  'type' => 
  array (
    'name' => 'type',
    'type' => 'int(10)',
    'notnull' => false,
    'default' => NULL,
    'primary' => false,
    'autoinc' => false,
  ),
  'orderid' => 
  array (
    'name' => 'orderid',
    'type' => 'varchar(40)',
    'notnull' => false,
    'default' => '',
    'primary' => false,
    'autoinc' => false,
  ),
  'money' => 
  array (
    'name' => 'money',
    'type' => 'decimal(14,2)',
    'notnull' => false,
    'default' => '0.00',
    'primary' => false,
    'autoinc' => false,
  ),
  'commission' => 
  array (
    'name' => 'commission',
    'type' => 'decimal(14,2)',
    'notnull' => false,
    'default' => '0.00',
    'primary' => false,
    'autoinc' => false,
  ),
  'arrival' => 
  array (
    'name' => 'arrival',
    'type' => 'decimal(30,10)',
    'notnull' => false,
    'default' => '0.0000000000',
    'primary' => false,
    'autoinc' => false,
  ),
  'handling_fee' => 
  array (
    'name' => 'handling_fee',
    'type' => 'decimal(14,2)',
    'notnull' => false,
    'default' => '0.00',
    'primary' => false,
    'autoinc' => false,
  ),
  'balance' => 
  array (
    'name' => 'balance',
    'type' => 'decimal(14,2)',
    'notnull' => false,
    'default' => '0.00',
    'primary' => false,
    'autoinc' => false,
  ),
  'freeze' => 
  array (
    'name' => 'freeze',
    'type' => 'decimal(14,2)',
    'notnull' => false,
    'default' => '0.00',
    'primary' => false,
    'autoinc' => false,
  ),
  'content' => 
  array (
    'name' => 'content',
    'type' => 'longtext',
    'notnull' => false,
    'default' => NULL,
    'primary' => false,
    'autoinc' => false,
  ),
  'status' => 
  array (
    'name' => 'status',
    'type' => 'int(2)',
    'notnull' => false,
    'default' => '1',
    'primary' => false,
    'autoinc' => false,
  ),
  'create_uid' => 
  array (
    'name' => 'create_uid',
    'type' => 'bigint(16)',
    'notnull' => false,
    'default' => '0',
    'primary' => false,
    'autoinc' => false,
  ),
  'create_time' => 
  array (
    'name' => 'create_time',
    'type' => 'int(10)',
    'notnull' => false,
    'default' => '0',
    'primary' => false,
    'autoinc' => false,
  ),
  'create_ip' => 
  array (
    'name' => 'create_ip',
    'type' => 'bigint(15)',
    'notnull' => false,
    'default' => '0',
    'primary' => false,
    'autoinc' => false,
  ),
);