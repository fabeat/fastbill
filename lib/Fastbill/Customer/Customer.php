<?php

namespace Fastbill\Customer;

class Customer extends \Fastbill\Base\Model
{
  protected $readonly_data = array(
    'CUSTOMER_ID'
  );

  public function offsetSet($offset, $value)
  {
    if ($offset == 'COUNTRY_CODE' && is_string($value))
    {
      switch ($value)
      {
        case 'DE':
          $value = 1;
          break;
        case 'AT':
          $value = 195;
          break;
        case 'CH':
          $value = 150;
          break;
      }
    }
    parent::offsetSet($offset, $value);
  }

  protected function doSave($con)
  {
    $con = \Fastbill\Connection\Wrapper::getInstance()->chooseConnection($con);
    $req = array(
      'SERVICE' => $this->isNew()?'customer.create':'customer.update',
      'DATA'    => $this->getDataForRequest(),
    );
    $json = \Fastbill\Base\Helper::jsonDecodedRequest($req, $con);
    if (!('success' == $json['RESPONSE']['STATUS'] && isset($json['RESPONSE']['CUSTOMER_ID'])))
    {
      \Fastbill\Base\Helper::checkNotParsableResponse($json);
    }
    $this->data['CUSTOMER_ID'] = $json['RESPONSE']['CUSTOMER_ID'];
    return true;
  }

  protected function doDelete($con)
  {
    $con = \Fastbill\Connection\Wrapper::getInstance()->chooseConnection($con);
    $req = array(
      'SERVICE' => 'customer.delete',
      'DATA'    => array(
        'CUSTOMER_ID' => $this['CUSTOMER_ID']
      ),
    );
    $json = \Fastbill\Base\Helper::jsonDecodedRequest($req, $con);
    if ('success' != $json['RESPONSE']['STATUS'])
    {
      \Fastbill\Base\Helper::checkNotParsableResponse($json);
    }
    $this->data['CUSTOMER_ID'] = null;
    return true;
  }
}