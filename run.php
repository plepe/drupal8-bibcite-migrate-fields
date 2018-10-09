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

$nids = \Drupal::entityQuery('node')->condition('type', 'biblio')->execute();
$bibid = 0;
foreach ($nids as $nid) {
  $bibid++;

  print "Copy {$nid} to {$bibid}\n";
  $src = \Drupal::entityTypeManager()->getStorage('node')->load($nid);
  $dest = \Drupal::entityTypeManager()->getStorage('bibcite_reference')->load($bibid);

  foreach ($fields as $field) {
    print "{$field}: load ... ";
    $value = $src->get($field)->getValue();
    print "save\n";
    $dest->get($field)->setValue($value);
  }

  $dest->save();
}
