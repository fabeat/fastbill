<?php

namespace Fastbill\Base;

abstract class DataObject implements \ArrayAccess, \Countable, \Iterator
{
  protected $data         = array();
  protected $readonlyData = array();

  public function __construct($data = null)
  {
    if (null !== $data)
    {
      $this->data   = $data;
    }
  }

  // ArrayAccess methods
  public function offsetSet($offset, $value)
  {
    $this->checkReadonly($offset);
    $this->data[$offset] = $value;
  }

  public function offsetExists($offset)
  {
    return isset($this->data[$offset]);
  }

  public function offsetUnset($offset)
  {
    $this->checkReadonly($offset);
    if (isset($this->data[$offset]))
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

  protected function checkReadonly($offset)
  {
    if (in_array($offset, $this->readonlyData))
    {
      throw new \Fastbill\Exception\InvalidArgumentException('The field "'.$offset.'" is read-only.');
    }
  }

}