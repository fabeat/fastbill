<?php
require_once dirname(__FILE__).'/../lib/autoload.php';
require_once dirname(__FILE__).'/bootstrap.php';

class CustomerTest extends PHPUnit_Framework_TestCase
{

  public function setUp()
  {
    \Fastbill\Connection\Wrapper::init(\FASTBILL_EMAIL, \FASTBILL_KEY);
  }

  protected function getFixture()
  {
    $data = array(
      'CUSTOMER_NUMBER'  => 1,
      'CUSTOMER_TYPE'    => 'business',
      'ORGANIZATION'     => 'Test Gbr',
      'SALUATION'        => 'mr',
      'FIRST_NAME'       => 'Max',
      'LAST_NAME'        => 'Mustemann',
      'ADDRESS'          => 'Musterstraße 1',
      'ZIPCODE'          => '80808',
      'CITY'             => 'München',
      'PAYMENT_TYPE'     => 1,
      'COUNTRY_CODE'     => 'DE',
    );
    $customer = new \Fastbill\Customer\Customer();
    $customer->fillFromArray($data);
    return $customer;
  }

  public function testCreate()
  {
    $customer = new \Fastbill\Customer\Customer();

    # check status
    $this->assertTrue($customer->isNew(), 'isNew() returns true.');
    $this->assertFalse($customer->isDeleted(), 'isDeleted() returns false.');
    $this->assertFalse($customer->isChanged(), 'isChanged() returns false.');

    # setting data
    $customer['CUSTOMER_NUMBER']  = 1;
    $customer['CUSTOMER_TYPE']    = 'business';
    $customer['ORGANIZATION']     = 'Test Gbr';
    $customer['SALUATION']        = 'mr';
    $customer['FIRST_NAME']       = 'Max';
    $customer['LAST_NAME']        = 'Mustemann';
    $customer['ADDRESS']          = 'Musterstraße 1';
    $customer['ZIPCODE']          = '80808';
    $customer['CITY']             = 'München';
    $customer['PAYMENT_TYPE']     = 1;
    $customer['COUNTRY_CODE']     = 'DE';

    # check status
    $this->assertTrue($customer->isNew(), 'isNew() returns true.');
    $this->assertFalse($customer->isDeleted(), 'isDeleted() returns false.');
    $this->assertTrue($customer->isChanged(), 'isChanged() returns true.');

    # save object
    $this->assertTrue($customer->save(), 'save() returns true.');

    # check status
    $this->assertFalse($customer->isNew(), 'isNew() returns false.');
    $this->assertFalse($customer->isDeleted(), 'isDeleted() returns false.');
    $this->assertFalse($customer->isChanged(), 'isChanged() returns false.');

    $this->assertNotNull($customer['CUSTOMER_ID'], 'CUSTOMER_ID set.');

    # delete object
    $this->assertTrue($customer->delete(), 'delete() returns true.');

    # check status
    $this->assertTrue($customer->isNew(), 'isNew() returns true.');
    $this->assertTrue($customer->isDeleted(), 'isDeleted() returns true.');
    $this->assertTrue($customer->isChanged(), 'isChanged() returns true.');
  }

  public function testGet()
  {
    $customer = $this->getFixture();
    $customer->save();

    $this->assertNotNull($customer['CUSTOMER_ID'], 'CUSTOMER_ID set.');

    $customer2 = \Fastbill\Customer\Finder::findOneById($customer['CUSTOMER_ID']);
    $this->assertInstanceOf('\Fastbill\Customer\Customer', $customer2, 'Testing response instance');

    # check status
    $this->assertFalse($customer2->isNew(), 'isNew() returns false.');
    $this->assertFalse($customer2->isDeleted(), 'isDeleted() returns false.');
    $this->assertFalse($customer2->isChanged(), 'isChanged() returns false.');

    # check data
    $this->assertEquals($customer['CUSTOMER_ID'], $customer2['CUSTOMER_ID'], 'Verifying data from Fastbill');
    $this->assertEquals($customer['CUSTOMER_NUMBER'], $customer2['CUSTOMER_NUMBER'], 'Verifying data from Fastbill');
    $this->assertEquals($customer['CUSTOMER_TYPE'], $customer2['CUSTOMER_TYPE'], 'Verifying data from Fastbill');
    $this->assertEquals($customer['ORGANIZATION'], $customer2['ORGANIZATION'], 'Verifying data from Fastbill');
    $this->assertEquals($customer['SALUATION'], $customer2['SALUATION'], 'Verifying data from Fastbill');
    $this->assertEquals($customer['FIRST_NAME'], $customer2['FIRST_NAME'], 'Verifying data from Fastbill');
    $this->assertEquals($customer['LAST_NAME'], $customer2['LAST_NAME'], 'Verifying data from Fastbill');
    $this->assertEquals($customer['ADDRESS'], $customer2['ADDRESS'], 'Verifying data from Fastbill');
    $this->assertEquals($customer['ZIPCODE'], $customer2['ZIPCODE'], 'Verifying data from Fastbill');
    $this->assertEquals($customer['CITY'], $customer2['CITY'], 'Verifying data from Fastbill');
    $this->assertEquals($customer['PAYMENT_TYPE'], $customer2['PAYMENT_TYPE'], 'Verifying data from Fastbill');
    $this->assertEquals($customer['COUNTRY_CODE'], $customer2['COUNTRY_CODE'], 'Verifying data from Fastbill');

    $customer2->delete();
  }

  public function testUpdate()
  {
    $customer = $this->getFixture();
    $customer->save();
    $customer['CUSTOMER_NUMBER'] = 2;
    $this->assertEquals($customer['CUSTOMER_NUMBER'], 2);
    $this->assertTrue($customer->save());
    $customer2 = \Fastbill\Customer\Finder::findOneById($customer['CUSTOMER_ID']);
    $this->assertEquals($customer2['CUSTOMER_NUMBER'], $customer['CUSTOMER_NUMBER']);
    $customer2->delete();

  }

  public function testDuplicate()
  {
    $customer1 = $this->getFixture();
    $customer2 = $this->getFixture();

    $customer1->save();
    $customer2->save();

    $customer1_neu = \Fastbill\Customer\Finder::findOneById($customer1['CUSTOMER_ID']);
    $customer2_neu = \Fastbill\Customer\Finder::findOneById($customer2['CUSTOMER_ID']);

    $this->assertEquals($customer1['CUSTOMER_ID'], $customer1_neu['CUSTOMER_ID'], 'Verifying data from Fastbill');
    $this->assertEquals($customer2['COUNTRY_CODE'], $customer2_neu['COUNTRY_CODE'], 'Verifying data from Fastbill');

    $customer1->delete();
    $customer2->delete();
  }

  public function testFindNull()
  {
    $not_found = \Fastbill\Customer\Finder::findOneById(1);
    $this->assertNull($not_found, 'Search for invalid id returns null.');
  }

  public function testNonExistant()
  {
    $customer = $this->getFixture();
    $customer->save();
    $not_found = \Fastbill\Customer\Finder::findOneById(1);
    $this->assertNull($not_found, 'Search for invalid id returns null.');
    $customer->delete();
    echo 'HALLO';
  }
}