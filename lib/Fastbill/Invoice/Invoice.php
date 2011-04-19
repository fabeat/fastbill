<?php

namespace Fastbill\Invoice;

class Invoice extends \Fastbill\Base\Model
{

  protected $customer = null;

  protected $data = array(
    'ITEMS' => array()
  );

  protected $readonly_data = array(
    'INVOICE_ID'
  );

  /**
   * Set a customer for this invoice
   */
  public function setCustomer(\Fastbill\Customer\Customer $customer)
  {
    $this->customer = $customer;
  }

  /**
   * Get the customer associated with this invoice
   */
  public function getCustomer()
  {
    if (null === $customer && isset($this['CUSTOMER_ID']))
    {
      $this->customer = \Fastbill\Customer\Finder::findOneById($this['CUSTOMER_ID']);
    }
    return $this->customer;
  }

  /**
   * Get the items associated with this invoice
   */
  public function getItems()
  {
    return $this['ITEMS'];
  }

  /**
   * Get the items associated with this invoice as an array
   */
  public function getItemsAsArray()
  {
    $map = function ($item) {
      return $item->toArray();
    };
    return array_map($map, $this['ITEMS']);
  }

  /**
   * Set the items associated with this invoice (overwrite the existing items)
   *
   * @param array An array of Items to add.
   * @see Invoice::addItem
   */
  public function setItems($items)
  {
    $this['ITEMS'] = array();
    $this->addItems($items);
  }

  /**
   * Add a item to this invoice
   *
   * @param array|\Fastbill\Item\Item Item to add. Arrays will be automatically converted to Fastbill\Item\Item objects
   */
  public function addItem($item)
  {
    if ($item instanceof \Fastbill\Item\Item)
    {
      $this['ITEMS'][] = $item;
    }
    else
    {
      $itemObj = new \Fastbill\Item\Item();
      $itemObj->fillFromArray($item);
      $this['ITEMS'];
    }

  }

  /**
   * Add items to this invoice
   *
   * @param array An array of Items to add.
   * @see Invoice::addItem
   */
  public function addItems($items)
  {
    foreach ($items as $item)
    {
      $this->addItem($item);
    }
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
    if (null !== $this->customer)
    {
      $this->customer->save($con);
      $this['CUSTOMER_ID'] = $this->customer['CUSTOMER_ID'];
    }
    return $this->doSaveHelper('invoice.create', 'invoice.update', 'INVOICE_ID', $con);
  }

  protected function doDelete($con)
  {
    return $this->doDeleteHelper('invoice.delete', 'INVOICE_ID', $con);
  }

  protected function getDataForRequest()
  {
    $data = parent::getDataForRequest();
    if (isset($data['ITEMS']))
    {
      $data['ITEMS'] = $this->getItemsAsArray();
    }
    return $data;
  }

}