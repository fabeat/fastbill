<?php

namespace Fastbill\Base;

abstract class Finder
{
  protected static function finderHelper($jsonRequest, $responseArrayName, $objectName, $con)
  {
    $con = \Fastbill\Connection\Wrapper::getInstance()->chooseConnection($con);
    $json = \Fastbill\Base\Helper::jsonDecodedRequest($jsonRequest, $con);
    if (!isset($json['RESPONSE'][$responseArrayName][0]) AND !is_array($json['RESPONSE'][$responseArrayName]))
    {
      \Fastbill\Base\Helper::checkNotParsableResponse($json);
    }
    $ret = array();
    foreach ($json['RESPONSE'][$responseArrayName] as $obj)
    {
      $ret[] = new $objectName($obj);
    }
    return $ret;
  }

  protected static function findOneHelper($jsonRequest, $responseArrayName, $objectName, $con)
  {
    $ret = self::finderHelper($jsonRequest, $responseArrayName, $objectName, $con);
    return count($ret)?$ret[0]:null;
  }
}