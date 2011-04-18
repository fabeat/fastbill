<?php

namespace Fastbill\Customer;

class Finder extends \Fastbill\Base\Finder
{
  public function findOneById($id, $con = null)
  {
    $con = \Fastbill\Connection\Wrapper::getInstance()->chooseConnection($con);
    $req = array(
      'SERVICE' => 'customer.get',
      'DATA'    => array(
        'CUSTOMER_ID' => $id
      ),
    );
    $json = \Fastbill\Base\Helper::jsonDecodedRequest($req, $con);
    if (empty($json['RESPONSE']['CUSTOMERS'][0]))
    {
      \Fastbill\Base\Helper::checkNotParsableResponse($json);
    }
    return new \Fastbill\Customer\Customer($json['RESPONSE']['CUSTOMERS'][0]);
  }
}