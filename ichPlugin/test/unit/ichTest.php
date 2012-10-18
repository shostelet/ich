<?php
/**
 * Unit tests for lib/vac.class.php
 * @author  Simon Hostelet
 * @version test/unit/vacTest.php 2009-07-09
 */

include(dirname(__FILE__) . '/../../../../test/bootstrap/unit.php');

$t = new lime_test(8, new lime_output_color());

$c=1;


$t->diag('');
$t->diag('Test I Can Haz helpers');

$t->diag('');
$t->diag('extract template path');

$t->is(ich::extractTemplatePath('my_app/my_module/my_template'),array('my_app', 'my_module', 'my_template'), 'extract path');
$t->is(ich::extractTemplatePath('ichPlugin/test/test'),array('ichPlugin', 'test', 'test'), 'extract path');
try {
  ich::extractTemplatePath('');
  $t->fail('should raise exception');
}
catch(sfParseException $e)
{
  $t->pass('exception raised');
}


$t->diag('');
$t->diag('findTemplate');

$t->is(ich::findTemplate('ichPlugin','test', 'test'), sfConfig::get('sf_plugins_dir') . '/ichPlugin/modules/test/templates/ich_test.html', 'find template');
try {
  ich::findTemplate('ichPlugin','test', 'notexists');
  $t->fail('exception should raise');
}
catch(sfFileException $e)
{
  $t->pass('Exception raised');
}


$t->diag('');
$t->diag('getTemplateContent');

$expected = '<span>This is a test {{my_var}}</span>';

$filename = sfConfig::get('sf_plugins_dir') . '/ichPlugin/modules/test/templates/ich_test.html';
$t->is(ich::getTemplateContent($filename), $expected, 'get template content');

try {
  $filename = sfConfig::get('sf_plugins_dir') . '/ichPlugin/modules/test/templates/no_exists.html';
  ich::getTemplateContent($filename);
  $t->fail('exception should raise');
}
catch(sfFileException $e)
{
  $t->pass('Exception raised');
}


$t->diag('');
$t->diag('renderTemplate');

$expected = '<script type="text/html" id="ich_test"><span>This is a test {{my_var}}</span></script>';
$t->is(ich::renderTemplate('ichPlugin/test/test'), $expected, 'render Template');

