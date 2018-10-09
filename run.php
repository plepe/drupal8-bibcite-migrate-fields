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

$fields = array('field_ieg_ike_project', 'field_projects', 'field_videolink', 'field_suppl');

  $src = \Drupal::entityTypeManager()->getStorage('node')->load(824);
  $dest = \Drupal::entityTypeManager()->getStorage('bibcite_reference')->load(466);

  foreach ($fields as $field) {
    $value = $src->get($field)->getValue();
    $dest->get($field)->setValue($value);
  }

  $dest->save();
