<?php

require_once(dirname(__FILE__).'/vendor/curl/autoload.php');

function fastbill_autoload($className)
{
  # only autoload fastbill classes
  if (preg_match('/^Fastbill\\\\/', $className))
  {
    $className = ltrim($className, '\\');
    $fileName  = '';
    $namespace = '';
    if ($lastNsPos = strripos($className, '\\'))
    {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }
    $fileName = dirname(__FILE__).DIRECTORY_SEPARATOR.$fileName.str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
    if (is_file($fileName))
    {
      require_once($fileName);
    }
  }
}

spl_autoload_register('fastbill_autoload');
