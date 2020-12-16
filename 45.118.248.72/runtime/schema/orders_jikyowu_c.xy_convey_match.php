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
  'cate' => 
  array (
    'name' => 'cate',
    'type' => 'int(10)',
    'notnull' => false,
    'default' => '0',
    'primary' => false,
    'autoinc' => false,
  ),
  'frequency' => 
  array (
    'name' => 'frequency',
    'type' => 'int(10)',
    'notnull' => false,
    'default' => NULL,
    'primary' => false,
    'autoinc' => false,
  ),
  'initial' => 
  array (
    'name' => 'initial',
    'type' => 'decimal(15,2)',
    'notnull' => false,
    'default' => '0.00',
    'primary' => false,
    'autoinc' => false,
  ),
  'min' => 
  array (
    'name' => 'min',
    'type' => 'decimal(15,2)',
    'notnull' => false,
    'default' => NULL,
    'primary' => false,
    'autoinc' => false,
  ),
  'max' => 
  array (
    'name' => 'max',
    'type' => 'decimal(15,2)',
    'notnull' => false,
    'default' => NULL,
    'primary' => false,
    'autoinc' => false,
  ),
  'proportion' => 
  array (
    'name' => 'proportion',
    'type' => 'decimal(15,4)',
    'notnull' => false,
    'default' => '0.0000',
    'primary' => false,
    'autoinc' => false,
  ),
  'cancel' => 
  array (
    'name' => 'cancel',
    'type' => 'tinyint(1)',
    'notnull' => false,
    'default' => '0',
    'primary' => false,
    'autoinc' => false,
  ),
);