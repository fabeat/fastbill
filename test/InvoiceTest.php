<?php
require_once dirname(__FILE__).'/../lib/autoload.php';
require_once dirname(__FILE__).'/bootstrap.php';

class InvoiceTest extends PHPUnit_Framework_TestCase
{

  public function setUp()
  {
    \Fastbill\Connection\Wrapper::init(\FASTBILL_EMAIL, \FASTBILL_KEY);
  }

}