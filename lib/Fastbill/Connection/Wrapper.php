<?php

namespace Fastbill\Connection;

class Wrapper extends \Fastbill\Base\Singleton
{
  protected $connection = null;

  /**
   * @see Fastbill\Connection\Connection::__construct()
   */
  public static function init()
  {
    $obj = self::getInstance();
    $args = func_get_args();
    $reflectionObj = new \ReflectionClass('Fastbill\Connection\Connection');
    $con = $reflectionObj->newInstanceArgs($args);
    $obj->setConnection($con);
    return $obj;
  }

  public function setConnection(Connection $con)
  {
    $this->connection = $con;
  }

  public function getConnection()
  {
    return $this->connection;
  }

  public function chooseConnection(Connection $con = null)
  {
    if (null === $con)
    {
      $con = $this->getConnection();
      if (null == $con)
      {
        throw new \Fastbill\Exception\InvalidArgumentException('No Connection default connection set & no connection given.');
      }
    }
    return $con;
  }
}