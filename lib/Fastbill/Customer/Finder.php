<?php

namespace Fastbill\Customer;

class Finder extends \Fastbill\Base\Finder
{
  public function findOneById($id, $con = null)
  {
    return self::findOneHelper(
      array(
        'SERVICE' => 'customer.get',
        'DATA'    => array( 'CUSTOMER_ID' => $id )
      ),
      'CUSTOMERS',
      '\Fastbill\Customer\Customer',
      $con
    );
  }
}