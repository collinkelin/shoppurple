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
  'uid' => 
  array (
    'name' => 'uid',
    'type' => 'int(11)',
    'notnull' => false,
    'default' => NULL,
    'primary' => false,
    'autoinc' => false,
  ),
  'sid' => 
  array (
    'name' => 'sid',
    'type' => 'int(10) unsigned',
    'notnull' => false,
    'default' => '0',
    'primary' => false,
    'autoinc' => false,
  ),
  'oid' => 
  array (
    'name' => 'oid',
    'type' => 'char(20)',
    'notnull' => false,
    'default' => NULL,
    'primary' => false,
    'autoinc' => false,
  ),
  'num' => 
  array (
    'name' => 'num',
    'type' => 'decimal(10,2)',
    'notnull' => false,
    'default' => NULL,
    'primary' => false,
    'autoinc' => false,
  ),
  'type' => 
  array (
    'name' => 'type',
    'type' => 'int(11)',
    'notnull' => false,
    'default' => NULL,
    'primary' => false,
    'autoinc' => false,
  ),
  'status' => 
  array (
    'name' => 'status',
    'type' => 'int(11)',
    'notnull' => false,
    'default' => '1',
    'primary' => false,
    'autoinc' => false,
  ),
  'addtime' => 
  array (
    'name' => 'addtime',
    'type' => 'int(11)',
    'notnull' => false,
    'default' => NULL,
    'primary' => false,
    'autoinc' => false,
  ),
);