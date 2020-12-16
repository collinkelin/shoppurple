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
  'title' => 
  array (
    'name' => 'title',
    'type' => 'varchar(50)',
    'notnull' => false,
    'default' => '',
    'primary' => false,
    'autoinc' => false,
  ),
  'data' => 
  array (
    'name' => 'data',
    'type' => 'longtext',
    'notnull' => false,
    'default' => NULL,
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
  'preload' => 
  array (
    'name' => 'preload',
    'type' => 'varchar(500)',
    'notnull' => false,
    'default' => '',
    'primary' => false,
    'autoinc' => false,
  ),
  'time' => 
  array (
    'name' => 'time',
    'type' => 'bigint(20) unsigned',
    'notnull' => false,
    'default' => '0',
    'primary' => false,
    'autoinc' => false,
  ),
  'double' => 
  array (
    'name' => 'double',
    'type' => 'tinyint(1)',
    'notnull' => false,
    'default' => '1',
    'primary' => false,
    'autoinc' => false,
  ),
  'desc' => 
  array (
    'name' => 'desc',
    'type' => 'varchar(500)',
    'notnull' => false,
    'default' => '',
    'primary' => false,
    'autoinc' => false,
  ),
  'start_at' => 
  array (
    'name' => 'start_at',
    'type' => 'varchar(20)',
    'notnull' => false,
    'default' => '',
    'primary' => false,
    'autoinc' => false,
  ),
  'end_at' => 
  array (
    'name' => 'end_at',
    'type' => 'varchar(20)',
    'notnull' => false,
    'default' => '',
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