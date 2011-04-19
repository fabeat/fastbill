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

  protected function doSaveHelper($create_service, $update_service, $idName, $con)
  {
    $con = \Fastbill\Connection\Wrapper::getInstance()->chooseConnection($con);
    $create = $this->isNew();
    $req = array(
      'SERVICE' => $create?$create_service:$update_service,
      'DATA'    => $this->getDataForRequest(),
    );
    $json = \Fastbill\Base\Helper::jsonDecodedRequest($req, $con);
    if (!('success' == $json['RESPONSE']['STATUS']) OR ($create AND !isset($json['RESPONSE'][$idName])))
    {
      \Fastbill\Base\Helper::checkNotParsableResponse($json);
    }
    if ($create)
    {
      $this->data[$idName] = $json['RESPONSE'][$idName];
    }
    return true;
  }

  protected function doDeleteHelper($delete_service, $idName, $con)
  {
    $con = \Fastbill\Connection\Wrapper::getInstance()->chooseConnection($con);
    $req = array(
      'SERVICE' => $delete_service,
      'DATA'    => array(
        $idName => $this[$idName]
      ),
    );
    $json = \Fastbill\Base\Helper::jsonDecodedRequest($req, $con);
    if ('success' != $json['RESPONSE']['STATUS'])
    {
      \Fastbill\Base\Helper::checkNotParsableResponse($json);
    }
    $this->data[$idName] = null;
    return true;
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