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

$fields = array('field_ieg_ike_project', 'field_projects', 'field_videolink', 'field_suppl', 'field_teaserimage', 'upload' => 'field_upload', 'uid', 'created', 'changed');
$fields_strip_html = array('bibcite_abst_e', 'bibcite_abst_f', 'bibcite_notes', 'bibcite_custom1', 'bibcite_custom2', 'bibcite_custom3', 'bibcite_custom4', 'bibcite_custom5', 'bibcite_custom6', 'bibcite_custom7');

$db = new PDO('mysql:dbname=drupal8', '', '');
$res = $db->query('select old.nid node_id, new.id bibcite_id from biblio old join bibcite_reference new on old.biblio_citekey=new.bibcite_citekey');

$nids = \Drupal::entityQuery('node')->condition('type', 'biblio')->execute();

while ($elem = $res->fetch()) {
  $nid = $elem['node_id'];
  $bibid = $elem['bibcite_id'];

  print "Copy {$nid} to {$bibid}\n";
  $src = \Drupal::entityTypeManager()->getStorage('node')->load($nid);
  $dest = \Drupal::entityTypeManager()->getStorage('bibcite_reference')->load($bibid);

  if ($src->get('title')->getValue() !== $dest->get('title')->getValue()) {
    print "Titles do not match!\n";
    print "SRC  "; print_r($src->get('title')->getValue());
    print "DEST "; print_r($dest->get('title')->getValue());
    exit(1);
  }

  foreach ($fields as $field_old => $field_new) {
    if (is_numeric($field_old)) {
      $field_old = $field_new;
    }

    print "  load {$field_old} ... ";
    $value = $src->get($field_old)->getValue();
    print "save {$field_new}\n";
    $dest->get($field_new)->setValue($value);
  }

  foreach ($fields_strip_html as $field) {
    print "  load {$field} ... ";
    $value = $dest->get($field)->getValue();
    $value = html_entity_decode(strip_tags($value[0]['value']));
    print "save\n";
    $value = $dest->get($field)->setValue(array(0 => array('value' => $value)));
  }

  $dest->save();
}
