<?php

App::uses('HttpSocket', 'Network/Http');

class Client extends HttpSocket {
  private $defaultRequest = null;

  function __construct($apiUser, $config = array(), $autoConnect = false) {
  parent::__construct($config, $autoConnect);

  $uri = $this->_parseUri($apiUser->serverUrl);
  $uri['user'] = $apiUser->login;
  $uri['pass'] = $apiUser->password;
  $uri['path'] = '/registry/';
  
  $this->defaultRequest = array(
    'method' => 'POST',
    'uri' => $uri,
    'header' => array(
      'Content-Type' => 'application/json'
      ),
    );
  }

  function call($inputRequest, $expectedCode) {
    $request = $inputRequest;

    if(!empty($request['uri']['path'])) {
      $request['uri']['path'] = $this->defaultRequest['uri']['path'] . $request['uri']['path'];  
    }

    $request = $this->mergeRequests($this->defaultRequest, $request);

    $response = $this->request($request);

    $code = $response->code;

    if ($code != $expectedCode) {
      $msg = "Registry returned code $code, expected was $expectedCode";
      if(!empty($response)) {
        $msg = $msg . ": Response was" . $response;
        $msg = $msg . ": Request was" . print_r($request, true);
      }
      throw new Exception($msg);
    }

    $result = null;

    if(!empty($response->body)) {
      try {
        $result = json_decode($response->body);
      } catch (Exception $e) {
        $msg = 'Error decoding JSON from Registry: ' . $e->getMessage();
        throw new Exception($msg);
      }
    }

    return $result;
  }

  private function mergeRequests($req1, $req2) {
    foreach ($req2 as $key => $value){
      if(array_key_exists($key, $req1) and is_array($value))
        $req1[$key] = $this->mergeRequests($req1[$key], $req2[$key]);
      else
        $req1[$key] = $value;
    }

    return $req1;
  }
}

