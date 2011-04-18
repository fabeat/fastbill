<?php

namespace Fastbill\Base;

class Helper
{
  public static function checkNotParsableResponse($json)
  {
    if (isset($json['RESPONSE']['ERRORS']))
    {
      throw new \Fastbill\Exception\ResponseErrorException(implode(', ', $json['RESPONSE']['ERRORS']));
    }
    else
    {
      throw new \Fastbill\Exception\ResponseParseErrorException('Error parsing the response: '. json_encode($json));
    }
  }

  public static  function jsonDecodedRequest($data, $con)
  {
    return json_decode((string) $con->apiRequest($data), true);
  }

}