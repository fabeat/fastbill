<?php

namespace Fastbill\Base;

abstract class Model extends DataObject
{
  protected $isNew        = true;
  protected $isDeleted    = false;
  protected $isChanged    = false;

  abstract protected function doSave($con);
  abstract protected function doDelete($con);

  public function __construct($data = null)
  {
    if (null !== $data)
    {
      $this->data   = $data;
      $this->isNew  = false;
    }
  }

  public function isNew()
  {
    return $this->isNew;
  }

  public function isChanged()
  {
    return $this->isChanged;
  }

  public function isDeleted()
  {
    return $this->isDeleted;
  }

  public function save($con = null)
  {
    if (!($this->isNew() || $this->isDeleted()) && !$this->isChanged())
    {
      // nothing to save
      return false;
    }

    $result = $this->doSave($con);

    $this->isChanged = $this->isNew = $this->isDeleted = false;

    return $result;
  }

  public function delete($con = null)
  {
    if ($this->isDeleted())
    {
      // nothing to delete
      return false;
    }

    $result = $this->doDelete($con);

    $this->isDeleted = $this->isNew = $this->isChanged = true;

    return $result;
  }

  public function fillFromArray($arr)
  {
    foreach ($arr as $key => $val)
    {
      $this[$key] = $val;
    }
  }

  protected function getDataForRequest()
  {
    $cb = function($var)
    {
      return $var !== null;
    };
    return array_filter($this->data, $cb);
  }

  public function offsetSet($offset, $value)
  {
    if ($this->offsetGet($offset) !== $value)
    {
      $this->isChanged = true;
    }
    parent::offsetSet($offset, $value);
  }

  public function offsetUnset($offset)
  {
    if (isset($this->data[$offset]))
    {
      parent::offsetUnset($offset);
      $this->isChanged = true;
    }
  }

}