<?php
$conFile = dirname(__FILE__).'/connection.php';
if (!is_file($conFile))
{
  $text = 'Define a connection.php in '.dirname(__FILE__).' with your Fastbill API credentials for the tests to run properly (the file is added to .gitignore).
  Example:
  <?php
  define(\'FASTBILL_EMAIL\', \'YOUR@EMAIL\');
  define(\'FASTBILL_KEY\', \'YOUR_API_KEY_HERE\');
  ';
  throw new Exception($text);
}

require_once($conFile);
