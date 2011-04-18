<?php

namespace Fastbill\Base;

abstract class Model implements \ArrayAccess, \Countable, \Iterator
{
  protected $isNew        = true;
  protected $isDeleted    = false;
  protected $isChanged    = false;
  protected $data         = array();
  protected $readonlyData = array();

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

  protected function checkReadonly($offset)
  {
    if (in_array($offset, $this->readonlyData))
    {
      throw new \Fastbill\Exception\InvalidArgumentException('The field "'.$offset.'" is read-only.');
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

  // ArrayAccess methods
  public function offsetSet($offset, $value)
  {
    $this->checkReadonly($offset);

    if ($this->offsetGet($offset) !== $value)
    {
      $this->isChanged = true;
    }

    $this->data[$offset] = $value;
  }

  public function offsetExists($offset)
  {
    return isset($this->data[$offset]);
  }

  public function offsetUnset($offset)
  {
    $this->checkReadonly($offset);
    if (null !== $this->data[$offset])
    {
      $this->isChanged = true;
      $this->data[$offset] = null;
    }
  }

  public function offsetGet($offset)
  {
    return isset($this->data[$offset]) ? $this->data[$offset] : null;
  }

  // Countable methods
  public function count()
  {
    return count($this->data);
  }

  // Iterator methods
  public function rewind()
  {
    reset($this->data);
  }

  public function current()
  {
    return current($this->data);
  }

  public function key()
  {
    return key($this->data);
  }

  public function next()
  {
    return next($this->data);
  }

  public function valid()
  {
    return !is_null(key($this->data));
  }

}