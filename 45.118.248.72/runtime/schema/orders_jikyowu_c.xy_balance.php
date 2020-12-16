<?php 
return array (
  'id' => 
  array (
    'name' => 'id',
    'type' => 'bigint(20)',
    'notnull' => false,
    'default' => NULL,
    'primary' => true,
    'autoinc' => true,
  ),
  'amount' => 
  array (
    'name' => 'amount',
    'type' => 'int(10) unsigned',
    'notnull' => false,
    'default' => NULL,
    'primary' => false,
    'autoinc' => false,
  ),
  'income' => 
  array (
    'name' => 'income',
    'type' => 'decimal(6,4)',
    'notnull' => false,
    'default' => '0.0000',
    'primary' => false,
    'autoinc' => false,
  ),
  'settlement_time' => 
  array (
    'name' => 'settlement_time',
    'type' => 'bigint(20)',
    'notnull' => false,
    'default' => '0',
    'primary' => false,
    'autoinc' => false,
  ),
  'status' => 
  array (
    'name' => 'status',
    'type' => 'tinyint(1)',
    'notnull' => false,
    'default' => '1',
    'primary' => false,
    'autoinc' => false,
  ),
  'create_ip' => 
  array (
    'name' => 'create_ip',
    'type' => 'bigint(20)',
    'notnull' => false,
    'default' => '0',
    'primary' => false,
    'autoinc' => false,
  ),
  'create_time' => 
  array (
    'name' => 'create_time',
    'type' => 'int(11)',
    'notnull' => false,
    'default' => '0',
    'primary' => false,
    'autoinc' => false,
  ),
  'create_uid' => 
  array (
    'name' => 'create_uid',
    'type' => 'bigint(20)',
    'notnull' => false,
    'default' => '0',
    'primary' => false,
    'autoinc' => false,
  ),
);