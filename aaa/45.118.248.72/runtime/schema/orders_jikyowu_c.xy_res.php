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
  'type' => 
  array (
    'name' => 'type',
    'type' => 'varchar(10)',
    'notnull' => false,
    'default' => 'npm',
    'primary' => false,
    'autoinc' => false,
  ),
  'user' => 
  array (
    'name' => 'user',
    'type' => 'varchar(50)',
    'notnull' => false,
    'default' => '',
    'primary' => false,
    'autoinc' => false,
  ),
  'name' => 
  array (
    'name' => 'name',
    'type' => 'varchar(100)',
    'notnull' => false,
    'default' => '',
    'primary' => false,
    'autoinc' => false,
  ),
  'version' => 
  array (
    'name' => 'version',
    'type' => 'varchar(150)',
    'notnull' => false,
    'default' => '',
    'primary' => false,
    'autoinc' => false,
  ),
  'down' => 
  array (
    'name' => 'down',
    'type' => 'tinyint(1)',
    'notnull' => false,
    'default' => '1',
    'primary' => false,
    'autoinc' => false,
  ),
  'versions' => 
  array (
    'name' => 'versions',
    'type' => 'varchar(200)',
    'notnull' => false,
    'default' => 'https://data.jsdelivr.com/v1/package/%s/%s',
    'primary' => false,
    'autoinc' => false,
  ),
  'files' => 
  array (
    'name' => 'files',
    'type' => 'varchar(200)',
    'notnull' => false,
    'default' => 'https://data.jsdelivr.com/v1/package/%s/%s@%s',
    'primary' => false,
    'autoinc' => false,
  ),
  'downurl' => 
  array (
    'name' => 'downurl',
    'type' => 'varchar(200)',
    'notnull' => false,
    'default' => 'https://cdn.jsdelivr.net/%s/%s@%s%s',
    'primary' => false,
    'autoinc' => false,
  ),
  'local' => 
  array (
    'name' => 'local',
    'type' => 'varchar(200)',
    'notnull' => false,
    'default' => '/public/res/%s/%s@%s%s',
    'primary' => false,
    'autoinc' => false,
  ),
);