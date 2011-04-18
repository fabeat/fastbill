<?php

namespace Fastbill\Customer;

class Customer extends \Fastbill\Base\Model
{

  protected $data = array(
    'CUSTOMER_ID'         => null,
    'CUSTOMER_NUMBER'     => null,
    'CUSTOMER_TYPE'       => null,
    'CUSTOMER_TOP'        => null,
    'ORGANIZATION'        => null,
    'POSITION'            => null,
    'SALUATION'           => null,
    'FIRST_NAME'          => null,
    'LAST_NAME'           => null,
    'ADDRESS'             => null,
    'ADDRESS_2'           => null,
    'ZIPCODE'             => null,
    'CITY'                => null,
    'COUNTRY_CODE'        => null,
    'PHONE'               => null,
    'PHONE_2'             => null,
    'FAX'                 => null,
    'MOBILE'              => null,
    'EMAIL'               => null,
    'ACCOUNT_RECEIVABLE'  => null,
    'CURRENCY_CODE'       => null,
    'VAT_ID'              => null,
    'DAYS_FOR_PAYMENT'    => null,
    'PAYMENT_TYPE'        => null,
    'SHOW_PAYMENT_NOTICE' => null,
    'BANK_NAME'           => null,
    'BANK_CODE'           => null,
    'BANK_ACCOUNT_NUMBER' => null,
    'BANK_ACCOUNT_OWNER'  => null,
    'COMMENT'             => null,
  );

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