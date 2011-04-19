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
    return $this->doSaveHelper('customer.create', 'customer.update', 'CUSTOMER_ID', $con);
  }

  protected function doDelete($con)
  {
    return $this->doDeleteHelper('customer.delete', 'CUSTOMER_ID', $con);
  }
}