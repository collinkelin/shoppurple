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
  'auth' => 
  array (
    'name' => 'auth',
    'type' => 'bigint(20) unsigned',
    'notnull' => false,
    'default' => NULL,
    'primary' => false,
    'autoinc' => false,
  ),
  'node' => 
  array (
    'name' => 'node',
    'type' => 'varchar(200)',
    'notnull' => false,
    'default' => NULL,
    'primary' => false,
    'autoinc' => false,
  ),
);