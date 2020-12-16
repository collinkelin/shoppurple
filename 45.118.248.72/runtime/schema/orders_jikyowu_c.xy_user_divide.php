<?php 
return array (
  'id' => 
  array (
    'name' => 'id',
    'type' => 'smallint(5) unsigned',
    'notnull' => false,
    'default' => NULL,
    'primary' => true,
    'autoinc' => true,
  ),
  'level' => 
  array (
    'name' => 'level',
    'type' => 'smallint(6)',
    'notnull' => false,
    'default' => NULL,
    'primary' => false,
    'autoinc' => false,
  ),
  'divide' => 
  array (
    'name' => 'divide',
    'type' => 'decimal(5,3)',
    'notnull' => false,
    'default' => NULL,
    'primary' => false,
    'autoinc' => false,
  ),
);