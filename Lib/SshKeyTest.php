<?php

App::uses('Client', 'RestTest.Lib');

class SshKeyTest {
  public $apiUser;
  public $name = 'SshKey';
  public $plural = 'SshKeys';
  public $controller = 'ssh_key_authenticator/ssh_keys';

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
    $body["$this->plural"][0]['Person']['Type'] = 'CO';
    $body["$this->plural"][0]['Person']['Id'] = 2;
    $body["$this->plural"][0]['Comment'] = 'Created by REST API';
    $body["$this->plural"][0]['Type'] = 'ssh-rsa';
    $body["$this->plural"][0]['Skey'] = 'AAAAB3NzaC1yc2EAAAABIwAAAQEAr9amUAycDrDj2f2kLkK6b5X5DYr+4kmRcYfjcOQG9capsUDh44E8C1KKKRtVZNCM4QAXjHHuIQhb7/YysH6XpjUypzHciDNyJ6f3H0Phrcu9X5HSXlPNuyHlq1rtxtuXnd/UPmrZlSuy1Kt4bjjk5EYI6XMXQjlZMet9mT8y0kB1NALGqfLm1LesjShEmKCqP97fj//jRakm661TDdphjKR7e/O6wB80BHZAUznd+4XqYoQ8wPMWFKk21ZtwNWEpgooPhwC956BmsGRXHzySct/LISNAwbQdNaxTKeWdLGvsD6uivBsbBe2egrkCB0UKzcyXTuQv3W4cElAzDFROEQ==';
    $body["$this->plural"][0]['SshKeyAuthenticatorId'] = 1;

    $encodedBody = json_encode($body);
    $request['body'] = $encodedBody;

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

    $body["$this->plural"][0]['Comment'] = 'Edited by REST API';

    $encodedBody = json_encode($body);
    $request['body'] = $encodedBody;

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

    // View (per CO Person)
    $request = array();
    $request['method'] = 'GET';
    $request['uri']['path'] = "$this->controller.json";
    $request['uri']['query']['copersonid'] = 2;

    try {
      $result = $client->call($request, 200);
    } catch (Exception $e) {
      $msg = "$this->name View (per CO) exception:" . $e->getMessage();
      throw new Exception($msg);
    }

    $foundIt = false;
    foreach($result->{$this->plural} as $i) {
      if($i->Id == $newId) {
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

    if($result->{$this->plural}[0]->Id != $newId) {
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

    $encodedBody = json_encode($body);
    $request['body'] = $encodedBody;

    try {
      $response = $client->call($request, 401);
    } catch (Exception $e) {
      $msg = "$this->name GET with body succeeded";
      throw new Exception($msg);
    }
  }
}
