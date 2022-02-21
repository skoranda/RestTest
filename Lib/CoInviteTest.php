<?php

App::uses('Client', 'RestTest.Lib');

App::uses('CoInvite', 'Model');

class CoInviteTest {
  public $apiUser;
  public $name = 'CoInvite';
  public $controller = 'co_invites';

  public function __construct($apiUser) {
    $this->apiUser = $apiUser;
  }

  public function run() {
    $client = new Client($this->apiUser);

    // Send
    $request = array();
    $request['uri']['path'] = "$this->controller.json";

    $body = array();
    $body['RequestType'] = 'CoInvites';
    $body['Version'] = '1.0';
    $body['CoInvites'][0]['Version'] = '1.0';
    $body['CoInvites'][0]['CoId'] = 2;
    $body['CoInvites'][0]['CoPersonId'] = 2;

    $encodedBody = json_encode($body);
    $request['body'] = $encodedBody;

    try {
      //$result = $client->call($request, 201);
      $result = $client->call($request, 302);
      CakeLog::write('error', print_r($result, true));
    } catch (Exception $e) {
      $msg = "$this->name Send exception:" . $e->getMessage();
      throw new Exception($msg);
    }

    if(!empty($result->Id)) {
      $newId = $result->Id;
    } else {
      $msg = "$this->name Send could not get new ID";
      throw new Exception($msg);
    }

    // Query to find the nonce
//    $coInviteModel = new CoInvite();
//
//    $args = array();
//    $args['conditions']['CoInvite.id'] = $newId;
//    $args['contain'] = false;
//
//    $coInvite = $coInviteModel->find('first', $args);
//
//    $nonce = $coInvite['CoInvite']['invitation'];
//
//    // Confirm
//    $request = array();
//    $request['method'] = 'DELETE';
//    $request['uri']['path'] = "$this->controller.json";
//    $request['uri']['query']['inviteid'] = $nonce;
//    $request['uri']['query']['reply'] = 'confirm';
//
//    try {
//      $result = $client->call($request, 200);
//    } catch (Exception $e) {
//      $msg = "$this->name Confirm exception:" . $e->getMessage();
//      throw new Exception($msg);
//    }

  }
}
