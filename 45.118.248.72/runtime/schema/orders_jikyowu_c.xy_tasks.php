<?php 
return array (
  'id' => 
  array (
    'name' => 'id',
    'type' => 'int(10)',
    'notnull' => false,
    'default' => NULL,
    'primary' => true,
    'autoinc' => true,
  ),
  'title' => 
  array (
    'name' => 'title',
    'type' => 'varchar(200)',
    'notnull' => false,
    'default' => '',
    'primary' => false,
    'autoinc' => false,
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
  'reward' => 
  array (
    'name' => 'reward',
    'type' => 'bigint(20)',
    'notnull' => false,
    'default' => '0',
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
  'time_start' => 
  array (
    'name' => 'time_start',
    'type' => 'varchar(8)',
    'notnull' => false,
    'default' => '',
    'primary' => false,
    'autoinc' => false,
  ),
  'time_end' => 
  array (
    'name' => 'time_end',
    'type' => 'varchar(8)',
    'notnull' => false,
    'default' => '',
    'primary' => false,
    'autoinc' => false,
  ),
  'day_start' => 
  array (
    'name' => 'day_start',
    'type' => 'int(10)',
    'notnull' => false,
    'default' => '0',
    'primary' => false,
    'autoinc' => false,
  ),
  'day_end' => 
  array (
    'name' => 'day_end',
    'type' => 'int(10)',
    'notnull' => false,
    'default' => '0',
    'primary' => false,
    'autoinc' => false,
  ),
  'description' => 
  array (
    'name' => 'description',
    'type' => 'varchar(200)',
    'notnull' => false,
    'default' => '',
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
);