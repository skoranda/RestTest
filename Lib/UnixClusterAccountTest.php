<?php

App::uses('Client', 'RestTest.Lib');

class UnixClusterAccountTest {
  public $apiUser;
  public $name = 'UnixClusterAccount';
  public $plural = 'UnixClusterAccounts';
  public $controller = 'unix_cluster/unix_cluster_accounts';

  public function __construct($apiUser) {
    $this->apiUser = $apiUser;
  }

  public function run() {
    $client = new Client($this->apiUser);

    // Need to create some CoExtendedType first
    $this->name = 'CoExtendedType';
    $this->plural = 'CoExtendedTypes';
    $this->controller = 'co_extended_types';

    $request = array();
    $request['uri']['path'] = "$this->controller.json";

    $body = array();
    $body['RequestType'] = "$this->plural";
    $body['Version'] = '1.0';
    $body["$this->plural"][0]['Version'] = '1.0';
    $body["$this->plural"][0]['CoId'] = '2';
    $body["$this->plural"][0]['Attribute'] = 'Identifier.type';
    $body["$this->plural"][0]['Name'] = 'uidNumberTest';
    $body["$this->plural"][0]['DisplayName'] = 'uidNumberTest';
    $body["$this->plural"][0]['Status'] = 'Active';

    $encodedBody = json_encode($body);
    $request['body'] = $encodedBody;

    try {
      $result = $client->call($request, 201);
    } catch (Exception $e) {
      $msg = "$this->name Add exception:" . $e->getMessage();
      throw new Exception($msg);
    }

    if(!empty($result->Id)) {
      $newExtendedTypeUidNumberTestId = $result->Id;
    } else {
      $msg = "$this->name Add could not get new ID";
      throw new Exception($msg);
    }

    $request['uri']['path'] = "$this->controller.json";
    $body["$this->plural"][0]['Name'] = 'gidNumberTest';
    $body["$this->plural"][0]['DisplayName'] = 'gidNumberTest';

    $encodedBody = json_encode($body);
    $request['body'] = $encodedBody;

    try {
      $result = $client->call($request, 201);
    } catch (Exception $e) {
      $msg = "$this->name Add exception:" . $e->getMessage();
      throw new Exception($msg);
    }

    if(!empty($result->Id)) {
      $newExtendedTypeGidNumberTestId = $result->Id;
    } else {
      $msg = "$this->name Add could not get new ID";
      throw new Exception($msg);
    }

    $body["$this->plural"][0]['Name'] = 'uidTest';
    $body["$this->plural"][0]['DisplayName'] = 'uidTest';

    $encodedBody = json_encode($body);
    $request['body'] = $encodedBody;

    try {
      $result = $client->call($request, 201);
    } catch (Exception $e) {
      $msg = "$this->name Add exception:" . $e->getMessage();
      throw new Exception($msg);
    }

    if(!empty($result->Id)) {
      $newExtendedTypeUidTestId = $result->Id;
    } else {
      $msg = "$this->name Add could not get new ID";
      throw new Exception($msg);
    }

    // Create a CoGroup
    $this->name = 'CoGroup';
    $this->plural = 'CoGroups';
    $this->controller = 'co_groups';

    $request = array();
    $request['uri']['path'] = "$this->controller.json";

    $body = array();
    $body['RequestType'] = "$this->plural";
    $body['Version'] = '1.0';
    $body["$this->plural"][0]['Version'] = '1.0';
    $body["$this->plural"][0]['CoId'] = 2;
    $body["$this->plural"][0]['Name'] = 'UnixCluster Test Group';
    $body["$this->plural"][0]['Description'] = 'Created by REST client';
    $body["$this->plural"][0]['Open'] = false;
    $body["$this->plural"][0]['Status'] = 'Active';

    $encodedBody = json_encode($body);
    $request['body'] = $encodedBody;

    try {
      $result = $client->call($request, 201);
    } catch (Exception $e) {
      $msg = "$this->name Add exception:" . $e->getMessage();
      throw new Exception($msg);
    }

    if(!empty($result->Id)) {
      $newCoGroupId = $result->Id;
    } else {
      $msg = "$this->name Add could not get new ID";
      throw new Exception($msg);
    }

    // Add identifiers for CO Group
    $this->name = 'Identifier';
    $this->plural = 'Identifiers';
    $this->controller = 'identifiers';

    $request = array();
    $request['uri']['path'] = "$this->controller.json";

    $body = array();
    $body['RequestType'] = "$this->plural";
    $body['Version'] = '1.0';
    $body["$this->plural"][0]['Version'] = '1.0';
    $body["$this->plural"][0]['Type'] = 'uidTest';
    $body["$this->plural"][0]['Identifier'] = 'some.user';
    $body["$this->plural"][0]['Login'] = false;
    $body["$this->plural"][0]['Person']['Type'] = 'Group';
    $body["$this->plural"][0]['Person']['Id'] = $newCoGroupId;
    $body["$this->plural"][0]['Status'] = 'Active';

    $encodedBody = json_encode($body);
    $request['body'] = $encodedBody;

    try {
      $result = $client->call($request, 201);
    } catch (Exception $e) {
      $msg = "$this->name Add exception:" . $e->getMessage();
      throw new Exception($msg);
    }

    if(!empty($result->Id)) {
      $newCoGroupIdentifierUidTestId = $result->Id;
    } else {
      $msg = "$this->name Add could not get new ID";
      throw new Exception($msg);
    }

    $body["$this->plural"][0]['Type'] = 'gidNumberTest';
    $body["$this->plural"][0]['Identifier'] = '3010';

    $encodedBody = json_encode($body);
    $request['body'] = $encodedBody;

    try {
      $result = $client->call($request, 201);
    } catch (Exception $e) {
      $msg = "$this->name Add exception:" . $e->getMessage();
      throw new Exception($msg);
    }

    if(!empty($result->Id)) {
      $newCoGroupIdentifierGidNumberTestId = $result->Id;
    } else {
      $msg = "$this->name Add could not get new ID";
      throw new Exception($msg);
    }

    // Add identifiers for CO Person
    $this->name = 'Identifier';
    $this->plural = 'Identifiers';
    $this->controller = 'identifiers';

    $request = array();
    $request['uri']['path'] = "$this->controller.json";

    $body = array();
    $body['RequestType'] = "$this->plural";
    $body['Version'] = '1.0';
    $body["$this->plural"][0]['Version'] = '1.0';
    $body["$this->plural"][0]['Type'] = 'uidTest';
    $body["$this->plural"][0]['Identifier'] = 'some.user';
    $body["$this->plural"][0]['Login'] = false;
    $body["$this->plural"][0]['Person']['Type'] = 'CO';
    $body["$this->plural"][0]['Person']['Id'] = '2';
    $body["$this->plural"][0]['Status'] = 'Active';

    $encodedBody = json_encode($body);
    $request['body'] = $encodedBody;

    try {
      $result = $client->call($request, 201);
    } catch (Exception $e) {
      $msg = "$this->name Add exception:" . $e->getMessage();
      throw new Exception($msg);
    }

    if(!empty($result->Id)) {
      $newIdentifierUidTestId = $result->Id;
    } else {
      $msg = "$this->name Add could not get new ID";
      throw new Exception($msg);
    }

    $body["$this->plural"][0]['Type'] = 'uidNumberTest';
    $body["$this->plural"][0]['Identifier'] = '3010';

    $encodedBody = json_encode($body);
    $request['body'] = $encodedBody;

    try {
      $result = $client->call($request, 201);
    } catch (Exception $e) {
      $msg = "$this->name Add exception:" . $e->getMessage();
      throw new Exception($msg);
    }

    if(!empty($result->Id)) {
      $newIdentifierUidNumberTestId = $result->Id;
    } else {
      $msg = "$this->name Add could not get new ID";
      throw new Exception($msg);
    }

    $body["$this->plural"][0]['Type'] = 'gidNumberTest';
    $body["$this->plural"][0]['Identifier'] = '3010';

    $encodedBody = json_encode($body);
    $request['body'] = $encodedBody;

    try {
      $result = $client->call($request, 201);
    } catch (Exception $e) {
      $msg = "$this->name Add exception:" . $e->getMessage();
      throw new Exception($msg);
    }

    if(!empty($result->Id)) {
      $newIdentifierGidNumberTestId = $result->Id;
    } else {
      $msg = "$this->name Add could not get new ID";
      throw new Exception($msg);
    }


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

    // Next get the UnixCluster just created when the Cluster
    // was created.
    $this->name = 'UnixCluster';
    $this->plural = 'UnixClusters';
    $this->controller = 'unix_cluster/unix_clusters';

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

    $newUnixClusterId = $result->{$this->plural}[0]->Id;

    // Next edit the UnixCluster just created so that it has the
    // minimum required attributes.
    $request = array();
    $request['method'] = 'PUT';
    $request['uri']['path'] = "$this->controller/$newUnixClusterId.json";

    $body = array();
    $body['RequestType'] = "$this->plural";
    $body['Version'] = '1.0';
    $body["$this->plural"][0]['Version'] = '1.0';
    $body["$this->plural"][0]['ClusterId'] = $newClusterId;
    $body["$this->plural"][0]['UsernameType'] = 'uidTest';
    $body["$this->plural"][0]['UidType'] = 'uidNumberTest';
    $body["$this->plural"][0]['DefaultShell'] = '/bin/bash';
    $body["$this->plural"][0]['HomedirPrefix'] = '/home';
    $body["$this->plural"][0]['HomedirSubdivisions'] = '0';
    $body["$this->plural"][0]['GidType'] = 'gidNumber';
    $body["$this->plural"][0]['GroupnameType'] = 'uidTest';
    $body["$this->plural"][0]['SyncMode'] = 'F';

    $encodedBody = json_encode($body);
    $request['body'] = $encodedBody;

    try {
      $response = $client->call($request, 200);
    } catch (Exception $e) {
      $msg = "$this->name Edit exception:" . $e->getMessage();
      throw new Exception($msg);
    }

    // Create a UnixClusterGroup
    $this->name = 'UnixClusterGroup';
    $this->plural = 'UnixClusterGroups';
    $this->controller = 'unix_cluster/unix_cluster_groups';

    $request = array();
    $request['uri']['path'] = "$this->controller.json";

    $body = array();
    $body['RequestType'] = "$this->plural";
    $body['Version'] = '1.0';
    $body["$this->plural"][0]['Version'] = '1.0';
    $body["$this->plural"][0]['UnixClusterId'] = $newUnixClusterId;
    $body["$this->plural"][0]['CoGroupId'] = $newCoGroupId;

    $encodedBody = json_encode($body);
    $request['body'] = $encodedBody;

    try {
      $result = $client->call($request, 201);
    } catch (Exception $e) {
      $msg = "$this->name Add exception:" . $e->getMessage();
      throw new Exception($msg);
    }

    if(!empty($result->Id)) {
      $newUnixClusterGroupId = $result->Id;
    } else {
      $msg = "$this->name Add could not get new ID";
      throw new Exception($msg);
    }


    // Now test the UnixClusterAccount API
    $this->name = 'UnixClusterAccount';
    $this->plural = 'UnixClusterAccounts';
    $this->controller = 'unix_cluster/unix_cluster_accounts';

    // Add
    $request = array();
    $request['uri']['path'] = "$this->controller.json";

    $body = array();
    $body['RequestType'] = "$this->plural";
    $body['Version'] = '1.0';
    $body["$this->plural"][0]['Version'] = '1.0';
    $body["$this->plural"][0]['UnixClusterId'] = $newUnixClusterId;
    $body["$this->plural"][0]['Person']['Type'] = 'CO';
    $body["$this->plural"][0]['Person']['Id'] = 2;
    $body["$this->plural"][0]['Username'] = 'some.user';
    $body["$this->plural"][0]['Uid'] = '4000';
    $body["$this->plural"][0]['LoginShell'] = '/bin/bash';
    $body["$this->plural"][0]['HomeDirectory'] = '/home/some.user';
    $body["$this->plural"][0]['PrimaryCoGroupId'] = "$newCoGroupId";
    $body["$this->plural"][0]['Status'] = 'A';
    $body["$this->plural"][0]['SyncMode'] = 'F';


    $encodedBody = json_encode($body);
    $request['body'] = $encodedBody;

    try {
      $result = $client->call($request, 201);
    } catch (Exception $e) {
      $msg = "$this->name Add exception:" . $e->getMessage();
    }

    if(!empty($result->Id)) {
      $newId = $result->Id;
    } else {
      $msg = "$this->name Add could not get new ID";
    }

    // Edit
    $request = array();
    $request['method'] = 'PUT';
    $request['uri']['path'] = "$this->controller/$newId.json";

    $body["$this->plural"][0]['LoginShell'] = '/bin/tcsh';

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
      $msg = "$this->name View (per CO Person) exception:" . $e->getMessage();
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
      $msg = "$this->name View (per CO Person) did not find created";
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

    // Delete the UnixClusterGroup
    $this->name = 'UnixClusterGroup';
    $this->plural = 'UnixClusterGroups';
    $this->controller = 'unix_cluster/unix_cluster_groups';

    $request = array();
    $request['method'] = 'DELETE';
    $request['uri']['path'] = "$this->controller/$newUnixClusterGroupId.json";
    $request['header'] = array();

    try {
      $response = $client->call($request, 200);
    } catch (Exception $e) {
      $msg = "$this->name Delete exception:" . $e->getMessage();
      throw new Exception($msg);
    }

    // Delete the UnixCluster
    $this->name = 'UnixCluster';
    $this->plural = 'UnixClusters';
    $this->controller = 'unix_cluster/unix_clusters';

    $request = array();
    $request['method'] = 'DELETE';
    $request['uri']['path'] = "$this->controller/$newUnixClusterId.json";
    $request['header'] = array();

    try {
      $response = $client->call($request, 200);
    } catch (Exception $e) {
      $msg = "$this->name Delete exception:" . $e->getMessage();
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

    // Delete identifiers for CO Person
    $this->name = 'Identifier';
    $this->plural = 'Identifiers';
    $this->controller = 'identifiers';

    $request = array();
    $request['method'] = 'DELETE';
    $request['uri']['path'] = "$this->controller/$newIdentifierUidTestId.json";
    $request['header'] = array();

    try {
      $response = $client->call($request, 200);
    } catch (Exception $e) {
      $msg = "$this->name Delete exception:" . $e->getMessage();
      throw new Exception($msg);
    }

    $request['uri']['path'] = "$this->controller/$newIdentifierUidNumberTestId.json";

    try {
      $response = $client->call($request, 200);
    } catch (Exception $e) {
      $msg = "$this->name Delete exception:" . $e->getMessage();
      throw new Exception($msg);
    }

    $request['uri']['path'] = "$this->controller/$newIdentifierGidNumberTestId.json";

    try {
      $response = $client->call($request, 200);
    } catch (Exception $e) {
      $msg = "$this->name Delete exception:" . $e->getMessage();
      throw new Exception($msg);
    }

    // Delete identifiers for CO Group
    $this->name = 'Identifier';
    $this->plural = 'Identifiers';
    $this->controller = 'identifiers';

    $request = array();
    $request['method'] = 'DELETE';
    $request['uri']['path'] = "$this->controller/$newCoGroupIdentifierUidTestId.json";
    $request['header'] = array();

    try {
      $response = $client->call($request, 200);
    } catch (Exception $e) {
      $msg = "$this->name Delete exception:" . $e->getMessage();
      throw new Exception($msg);
    }

    $request['uri']['path'] = "$this->controller/$newCoGroupIdentifierGidNumberTestId.json";

    try {
      $response = $client->call($request, 200);
    } catch (Exception $e) {
      $msg = "$this->name Delete exception:" . $e->getMessage();
      throw new Exception($msg);
    }

    // Delete CoGroup
    $this->name = 'CoGroup';
    $this->plural = 'CoGroups';
    $this->controller = 'co_groups';

    $request = array();
    $request['method'] = 'DELETE';
    $request['uri']['path'] = "$this->controller/$newCoGroupId.json";
    $request['header'] = array();

    try {
      $response = $client->call($request, 200);
    } catch (Exception $e) {
      $msg = "$this->name Delete exception:" . $e->getMessage();
      throw new Exception($msg);
    }

    // Delete CoExtendedType
    $this->name = 'CoExtendedType';
    $this->plural = 'CoExtendedTypes';
    $this->controller = 'co_extended_types';

    $request = array();
    $request['method'] = 'DELETE';
    $request['uri']['path'] = "$this->controller/$newExtendedTypeUidNumberTestId.json";
    $request['header'] = array();

    try {
      $response = $client->call($request, 200);
    } catch (Exception $e) {
      $msg = "$this->name Delete exception:" . $e->getMessage();
      throw new Exception($msg);
    }

    $request['uri']['path'] = "$this->controller/$newExtendedTypeGidNumberTestId.json";

    try {
      $response = $client->call($request, 200);
    } catch (Exception $e) {
      $msg = "$this->name Delete exception:" . $e->getMessage();
      throw new Exception($msg);
    }

    $request['uri']['path'] = "$this->controller/$newExtendedTypeUidTestId.json";

    try {
      $response = $client->call($request, 200);
    } catch (Exception $e) {
      $msg = "$this->name Delete exception:" . $e->getMessage();
      throw new Exception($msg);
    }
  }
}
