<?php
define('DRUPAL_DIR', '.');

use Drupal\Core\DrupalKernel;
use Symfony\Component\HttpFoundation\Request;
use Drupal\file\Plugin\Field\FieldType;

$autoloader = require_once DRUPAL_DIR . '/autoload.php';
$request = Request::createFromGlobals();
$kernel = DrupalKernel::createFromRequest($request, $autoloader, 'prod');
$kernel->boot();

require_once DRUPAL_DIR . '/core/includes/database.inc';
require_once DRUPAL_DIR . '/core/includes/schema.inc';

  $src = \Drupal::entityTypeManager()->getStorage('node')->load(836);
  $dest = \Drupal::entityTypeManager()->getStorage('bibcite_reference')->load(473);

  print_r($src);

  $fields = array('field_teaserimage', 'field_ieg_ike_project', 'field_projects', 'field_videolink', 'field_suppl');

  foreach ($fields as $field) {
    $value = $src->get($field)->getValue();
    print_r($value);


  //  print_r($dest->get($field)->getValue());
    $dest->get($field)->setValue($value);
  //  print_r($dest->get($field)->getValue());
    //print_r($entity);
  }

  $dest->save();
