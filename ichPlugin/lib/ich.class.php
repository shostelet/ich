<?php

/**
 * I Can Haz JS template helpers.
 *
 * http://icanhazjs.com/
 *
 * Easier to add mustache templates in the layout.
 *
 * Create a html file in one of yout module, for example :
 * apps/frontend/my_module/templates/ich_hello.html
 *
 * Content of your ICH template should be without script tags, for exemple:
 *    Hello {{my_var}}
 *
 * Call the template from a PHP template:
 * <?php ich::addTemplate('frontend/my_module/hello') ?>
 *
 * Make sure layout.php prints ich::renderTemplates()
 *
 * Each rendered template has an #id, the name of the template, eg: #ich_hello
 */
class ich
{
  protected static
    $_template_list = array(),
    $_directory_list = array('apps', 'plugins')   // directories wherein template will be looked for
  ;

  const
    OPENING_TAG = '<script type="text/html" id="{{mustache_id}}">',
    CLOSING_TAG = '</script>'
  ;

  /**
   * @param $name
   * Add a ICH template. To be called from a template or partial as
   * ich::addTemplate('my_app/my_module/my_template')
   *
   * make sure a file exists in my_app/my_module/templates/ich_my_template.html
   * or .php
   */
  public static function addTemplate($name)
  {
    self::$_template_list[] = $name;
  }

  /**
   * @return string
   * Call this method where you want your templates to appear. Within <head> is a good idea
   */
  public static function renderTemplates()
  {
    $output = '';
    foreach(self::$_template_list as $name)
    {
      $output .= self::renderTemplate($name) ."\n";
    }
    return $output;
  }

  public static function renderTemplate($name)
  {
    list($app, $module, $template) = self::extractTemplatePath($name);
    $filename = self::findTemplate($app, $module, $template);
    $html = self::getTemplateContent($filename);

    $header = str_replace('{{mustache_id}}', sprintf('ich_%s', $template), self::OPENING_TAG);
    $footer = self::CLOSING_TAG;
    return $header . $html . $footer;
  }

  public static function getTemplateContent($filename)
  {
    if(file_exists($filename))
    {
      ob_start();
      include $filename;
      $html = ob_get_contents();
      ob_end_clean();
      return $html;
    }
    else
    {
      throw new sfFileException(sprintf('Impossible to get content for ICH template %s', $filename));
    }
  }

  public static function findTemplate($app, $module, $template)
  {
    foreach(self::$_directory_list as $directory)
    {
      if(file_exists(sprintf('%s/%s/modules/%s/templates/ich_%s.html', sfConfig::get(sprintf('sf_%s_dir', $directory)), $app, $module, $template)))
      {
        $path = sprintf('%s/%s/modules/%s/templates/ich_%s.html', sfConfig::get(sprintf('sf_%s_dir', $directory)), $app, $module, $template);
        break;
      }
      elseif(file_exists(sprintf('%s/%s/modules/%s/templates/ich_%s.php', sfConfig::get(sprintf('sf_%s_dir', $directory)), $app, $module, $template)))
      {
        $path = sprintf('%s/%s/modules/%s/templates/ich_%s.php', sfConfig::get(sprintf('sf_%s_dir', $directory)), $app, $module, $template);
        break;
      }
    }
    if(!isset($path))
    {
      throw new sfFileException(sprintf("Cannot find app (%s), module (%s) or template (%s)", $app, $module, $template));
    }
    return $path;
  }

  public static function extractTemplatePath($name)
  {
    $pattern = '/^(?P<app>[^\/]+)\/(?P<module>[^\/]+)\/(?P<template>[^\/]+)$/i';
    if(preg_match($pattern, $name, $matches))
    {
      return array($matches['app'], $matches['module'], $matches['template']);
    }
    else
    {
      throw new sfParseException("Impossible to parse ICH template path");
    }
  }
}