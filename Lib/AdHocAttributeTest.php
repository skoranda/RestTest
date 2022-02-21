<?php

App::uses('Client', 'RestTest.Lib');

class AdHocAttributeTest {
  public $apiUser;
  public $name = 'AdHocAttribute';
  public $plural = 'AdHocAttributes';
  public $controller = 'ad_hoc_attributes';

  public function __construct($apiUser) {
    $this->apiUser = $apiUser;
  }

  public function run() {
    $client = new Client($this->apiUser);

    // Add
    $request = array();
    $request['uri']['path'] = "$this->controller.json";

    $body = array();
    $body['RequestType'] = "$this->plural";
    $body['Version'] = '1.0';
    $body["$this->plural"][0]['Version'] = '1.0';
    $body["$this->plural"][0]['Tag'] = 'ShoeSize';
    $body["$this->plural"][0]['Value'] = '11.5';
    $body["$this->plural"][0]['Person']['Type'] = 'CoRole';
    $body["$this->plural"][0]['Person']['Id'] = '2';

    $body = json_encode($body);

    $request['body'] = $body;

    try {
      $result = $client->call($request, 201);
    } catch (Exception $e) {
      $msg = "$this->name Add exception:" . $e->getMessage();
      throw new Exception($msg);
    }

    if(!empty($result->Id)) {
      $newId = $result->Id;
    } else {
      $msg = "$this->name Add could not get new ID";
      throw new Exception($msg);
    }

    // Edit
    $request = array();
    $request['method'] = 'PUT';
    $request['uri']['path'] = "$this->controller/$newId.json";

    $body = array();
    $body['RequestType'] = "$this->plural";
    $body['Version'] = '1.0';
    $body["$this->plural"][0]['Version'] = '1.0';
    $body["$this->plural"][0]['Tag'] = 'ShoeSize';
    $body["$this->plural"][0]['Value'] = '12';
    $body["$this->plural"][0]['Person']['Type'] = 'CoRole';
    $body["$this->plural"][0]['Person']['Id'] = '2';

    $body = json_encode($body);

    $request['body'] = $body;

    try {
      $response = $client->call($request, 200);
    } catch (Exception $e) {
      $msg = "$this->name Edit exception:" . $e->getMessage();
      throw new Exception($msg);
    }

    // View (all)
    $request = array();
    $request['method'] = 'GET';
    $request['uri']['path'] = "$this->controller.json";

    try {
      $response = $client->call($request, 401);
    } catch (Exception $e) {
      $msg = "$this->name View (all) exception:" . $e->getMessage();
      throw new Exception($msg);
    }

    // View (per person)
    $request = array();
    $request['method'] = 'GET';
    $request['uri']['path'] = "$this->controller.json";
    $request['uri']['query']['copersonroleid'] = 2;

    try {
      $result = $client->call($request, 200);
    } catch (Exception $e) {
      $msg = "$this->name View (per person) exception:" . $e->getMessage();
      throw new Exception($msg);
    }

    $foundIt = false;
    foreach($result->{$this->plural} as $i) {
      if($i->Value == 12) {
        $foundIt = true;
        $break;
      }
    }

    if(!$foundIt) {
      $msg = "$this->name View (per person) did not find created";
      throw new Exception($msg);
    }

    // View (one)
    $request = array();
    $request['method'] = 'GET';
    $request['uri']['path'] = "$this->controller/$newId.json";

    try {
      $result = $client->call($request, 200);
    } catch (Exception $e) {
      $msg = "$this->name View (one) exception:" . $e->getMessage();
      throw new Exception($msg);
    }

    if($result->{$this->plural}[0]->Value != 12) {
      $msg = "$this->name View (one) did not find created";
      throw new Exception($msg);
    }

    // Delete
    $request = array();
    $request['method'] = 'DELETE';
    $request['uri']['path'] = "$this->controller/$newId.json";
    $request['header'] = array();

    try {
      $response = $client->call($request, 200);
    } catch (Exception $e) {
      $msg = "$this->name Delete exception:" . $e->getMessage();
      throw new Exception($msg);
    }

    // Make sure PUT with no ID fails.
    $request = array();
    $request['method'] = 'PUT';
    $request['uri']['path'] = "$this->controller.json";

    $body = array();
    $body['RequestType'] = "$this->plural";
    $body['Version'] = '1.0';
    $body["$this->plural"][0]['Version'] = '1.0';
    $body["$this->plural"][0]['Tag'] = 'ShoeSize';
    $body["$this->plural"][0]['Value'] = '11.5';
    $body["$this->plural"][0]['Person']['Type'] = 'CoRole';
    $body["$this->plural"][0]['Person']['Id'] = '2';

    $body = json_encode($body);

    $request['body'] = $body;

    try {
      $response = $client->call($request, 404);
    } catch (Exception $e) {
      $msg = "$this->name PUT with no ID succeeded";
      throw new Exception($msg);
    }

    // Make sure DELETE with no ID fails.
    $request = array();
    $request['method'] = 'DELETE';
    $request['uri']['path'] = "$this->controller.json";
    $request['header'] = array();

    try {
      $response = $client->call($request, 404);
    } catch (Exception $e) {
      $msg = "$this->name DELETE with no ID succeeded";
      throw new Exception($msg);
    }

    // Make sure GET with a body fails.
    $request = array();
    $request['method'] = 'GET';
    $request['uri']['path'] = "$this->controller.json";
    $request['header'] = array();

    $body = array();
    $body['RequestType'] = "$this->plural";
    $body['Version'] = '1.0';
    $body["$this->plural"][0]['Version'] = '1.0';
    $body["$this->plural"][0]['Tag'] = 'ShoeSize';
    $body["$this->plural"][0]['Value'] = '11.5';
    $body["$this->plural"][0]['Person']['Type'] = 'CoRole';
    $body["$this->plural"][0]['Person']['Id'] = '2';

    $body = json_encode($body);

    $request['body'] = $body;

    try {
      $response = $client->call($request, 401);
    } catch (Exception $e) {
      $msg = "$this->name GET with body succeeded";
      throw new Exception($msg);
    }
  }
}
