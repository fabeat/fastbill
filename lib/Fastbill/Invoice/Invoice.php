<?php

namespace Fastbill\Invoice;

class Invoice extends \Fastbill\Base\Model
{

  protected $customer = null;

  protected $readonly_data = array(
    'INVOICE_ID'
  );

  public function setCustomer(\Fastbill\Customer\Customer $customer)
  {
    $this->customer = $customer;
  }

  public function getCustomer()
  {
    if (null === $customer && isset($this['CUSTOMER_ID']))
    {
      $this->customer = \Fastbill\Customer\Finder::findOneById($this['CUSTOMER_ID']);
    }
    return $this->customer;
  }

  public function offsetSet($offset, $value)
  {
    if ($offset == 'CUSTOMER_ID')
    {
      $this->customer = null;
    }
    parent::offsetSet($offset, $value);
  }

  public function offsetUnset($offset)
  {
    if ($offset == 'CUSTOMER_ID')
    {
      $this->customer = null;
    }
    parent::offsetUnset($offset);
  }

  protected function doSave($con)
  {
    $con = \Fastbill\Connection\Wrapper::getInstance()->chooseConnection($con);
    if (null !== $this->customer)
    {
      $this->customer->save($con);
      $this['CUSTOMER_ID'] = $this->customer['CUSTOMER_ID'];
    }
    $create = $this->isNew();
    $req = array(
      'SERVICE' => $create?'invoice.create':'invoice.update',
      'DATA'    => $this->getDataForRequest(),
    );
    $json = \Fastbill\Base\Helper::jsonDecodedRequest($req, $con);
    if (!('success' == $json['RESPONSE']['STATUS']) OR ($create AND !isset($json['RESPONSE']['INVOICE_ID'])))
    {
      \Fastbill\Base\Helper::checkNotParsableResponse($json);
    }
    if ($create)
    {
      $this->data['INVOICE_ID'] = $json['RESPONSE']['INVOICE_ID'];
    }
    return true;
  }

  protected function doDelete($con)
  {
    $con = \Fastbill\Connection\Wrapper::getInstance()->chooseConnection($con);
    $req = array(
      'SERVICE' => 'invoice.delete',
      'DATA'    => array(
        'INVOICE_ID' => $this['INVOICE_ID']
      ),
    );
    $json = \Fastbill\Base\Helper::jsonDecodedRequest($req, $con);
    if ('success' != $json['RESPONSE']['STATUS'])
    {
      \Fastbill\Base\Helper::checkNotParsableResponse($json);
    }
    $this->data['INVOICE_ID'] = null;
    return true;
  }

}