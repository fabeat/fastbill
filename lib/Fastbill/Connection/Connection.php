<?php

namespace Fastbill\Connection;

class Connection
{
  protected $apiUrl = 'https://portal.fastbill.com/api/0.1/api.php';
  protected $curl = null;
  protected $email = null;
  protected $key = null;

  public function __construct($email, $key, \Curl $curl = null)
  {
    if (null === $curl)
    {
      $curl = new \Curl();
    }
    $this->email = $email;
    $this->key = $key;
    $this->curl = $curl;
  }

  public function getCurl()
  {
    return $this->curl;
  }

  public function setCurl($curl)
  {
    $this->curl = $curl;
  }

  public function getEmail()
  {
    return $this->email;
  }

  public function setEmail($email)
  {
    $this->email = $email;
  }

  public function getKey()
  {
    return $this->key;
  }

  public function setKey($apiUrl)
  {
    $this->apiUrl = $apiUrl;
  }

  public function getApiUrl()
  {
    return $this->apiUrl;
  }

  public function setApiUrl($apiUrl)
  {
    $this->apiUrl = $apiUrl;
  }

  public function apiRequest($data)
  {
    $this->curl->setHeader('Content-Type', 'application/json');
    $this->curl->setOption('USERPWD', $this->email.':'.$this->key);
    $json_data = json_encode($data);
    return $this->curl->post($this->apiUrl, $json_data);
  }
}