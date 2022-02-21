<?php

App::uses('Client', 'RestTest.Lib');

class UnixClusterTest {
  public $apiUser;
  public $name = 'UnixCluster';
  public $plural = 'UnixClusters';
  public $controller = 'unix_cluster/unix_clusters';

  public function __construct($apiUser) {
    $this->apiUser = $apiUser;
  }

  public function run() {
    $client = new Client($this->apiUser);

    // Need to create a Cluster first
    $this->name = 'Cluster';
    $this->plural = 'Clusters';
    $this->controller = 'clusters';

    $request = array();
    $request['uri']['path'] = "$this->controller.json";

    $body = array();
    $body['RequestType'] = "$this->plural";
    $body['Version'] = '1.0';
    $body['Clusters'][0]['Version'] = '1.0';
    $body['Clusters'][0]['CoId'] = 2;
    $body['Clusters'][0]['Description'] = 'Created by REST API';
    $body['Clusters'][0]['Plugin'] = 'UnixCluster';
    $body['Clusters'][0]['Status'] = "A";

    $encodedBody = json_encode($body);
    $request['body'] = $encodedBody;

    try {
      $result = $client->call($request, 201);
    } catch (Exception $e) {
      $msg = "$this->name Add exception:" . $e->getMessage();
      throw new Exception($msg);
    }

    if(!empty($result->Id)) {
      $newClusterId = $result->Id;
    } else {
      $msg = "$this->name Add could not get new ID";
      throw new Exception($msg);
    }

    // Now test the UnixCluster API
    $this->name = 'UnixCluster';
    $this->plural = 'UnixClusters';
    $this->controller = 'unix_cluster/unix_clusters';

    // There is no Add for UnixCluster since it should be created
    // by the creation of the Cluster object of type UnixCluster.
    $request = array();
    $request['uri']['path'] = "$this->controller.json";

    $body = array();
    $body['RequestType'] = "$this->plural";
    $body['Version'] = '1.0';
    $body["$this->plural"][0]['Version'] = '1.0';
    $body["$this->plural"][0]['ClusterId'] = $newClusterId;
    $body["$this->plural"][0]['UsernameType'] = 'eppn';
    $body["$this->plural"][0]['UidType'] = 'uid';
    $body["$this->plural"][0]['DefaultShell'] = '/bin/tcsh';
    $body["$this->plural"][0]['HomedirPrefix'] = '/home';
    $body["$this->plural"][0]['GidType'] = 'uid';
    $body["$this->plural"][0]['GroupnameType'] = 'eppn';
    $body["$this->plural"][0]['SyncMode'] = 'F';

    $encodedBody = json_encode($body);
    $request['body'] = $encodedBody;

    try {
      $result = $client->call($request, 401);
    } catch (Exception $e) {
      $msg = "$this->name Add exception:" . $e->getMessage();
      throw new Exception($msg);
    }

    // View (per Cluster)
    $request = array();
    $request['method'] = 'GET';
    $request['uri']['path'] = "$this->controller.json";
    $request['uri']['query']['clusterid'] = $newClusterId;

    try {
      $result = $client->call($request, 200);
    } catch (Exception $e) {
      $msg = "$this->name View (per Cluster) exception:" . $e->getMessage();
      throw new Exception($msg);
    }

    if($result->{$this->plural}[0]->ClusterId != $newClusterId) {
      $msg = "$this->name View (per Cluster) did not find created";
      throw new Exception($msg);
    }

    $newId = $result->{$this->plural}[0]->Id;

    // Edit
    $request = array();
    $request['method'] = 'PUT';
    $request['uri']['path'] = "$this->controller/$newId.json";

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

    // Delete the Cluster
    $this->name = 'Cluster';
    $this->plural = 'Clusters';
    $this->controller = 'clusters';

    $request = array();
    $request['method'] = 'DELETE';
    $request['uri']['path'] = "$this->controller/$newClusterId.json";
    $request['header'] = array();

    try {
      $response = $client->call($request, 200);
    } catch (Exception $e) {
      $msg = "$this->name Delete exception:" . $e->getMessage();
      throw new Exception($msg);
    }
  }
}
