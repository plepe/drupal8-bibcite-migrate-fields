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

//$node = \Drupal\node\Entity\Node::load(836);
$node = \Drupal::entityTypeManager()->getStorage('node')->load(836);
//print_r($node);

$value = $node->get('field_ieg_ike_project')->getValue();
print_r($value);


//print_r(array_keys(\Drupal::entityTypeManager()->getDefinitions()));
$entity = \Drupal::entityTypeManager()->getStorage('bibcite_reference')->load(473);
//
print_r($entity->get('field_funding_projects')->getValue());
$entity->get('field_funding_projects')->setValue($value);
print_r($entity->get('field_funding_projects')->getValue());
//print_r($entity);
$entity->save();
