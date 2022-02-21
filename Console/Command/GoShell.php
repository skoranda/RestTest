<?php

App::uses('ApiUser', 'RestTest.Lib');

class GoShell extends AppShell {
  // Not implemented
  //
  // No testing of XML payloads.
  //
  // ApplicationPreference, uses web browser session for authentication
  // AttributeEnumeration, removed in 4.0.0
  // CoEnrollmentAttribute, uses web browser session for authentication
  // CoInvite waiting for CO-2350
  // CoPerson Find, uses web browser session for authentication
  // CoPetiton, not currently supported
  // CoProvisioningTarget, not implemented or requires web browser session for authentication
  public $apiObjects = array(
    'Address',
    'AdHocAttribute',
    'Cluster',
    'Co',
    'Cou',
    'CoDepartment',
    'CoEmailList',
    'CoExtendedAttribute',
    'CoExtendedType',
    'CoGroup',
    'CoGroupMember',
    'CoNavigationLink',
    'CoNsfDemographics',
    'CoOrgIdentityLink',
    'CoPerson',
    'CoPersonRole',
    'CoService',
    'CoTAndCAgreement',
    'CoTermsAndConditions',
    'EmailAddress',
    'HistoryRecord',
    'Identifier',
    'IdentityDocument',
    'Name',
    'NavigationLink',
    'Organization',
    'OrgIdentity',
    'Password',
    'SshKey',
    'TelephoneNumber',
    'UnixCluster',
    'UnixClusterAccount',
    'UnixClusterGroup',
    'Url',
    );

  public function getOptionParser() {
    $parser = parent::getOptionParser();

    $parser->addOption('privileged_api_login', array(
      'help' => 'CO privileged API user login'
    ))->addOption('privileged_api_password', array(
      'help' => 'CO privileged API user password'
    ))->addOption('platform_api_login', array(
      'help' => 'Platform API user login'
    ))->addOption('platform_api_password', array(
      'help' => 'Platform API user password'
    ))->addOption('registry_url', array(
      'help' => 'Registry URL',
      'default' => 'http://127.0.0.1'
    ));

    return $parser;
  }

  public function main() {
    $privilegedApiUsername = $this->params['privileged_api_login'];
    $privilegedApiPassword = $this->params['privileged_api_password'];

    $platformApiUsername = $this->params['platform_api_login'];
    $platformApiPassword = $this->params['platform_api_password'];

    $registryUrl = $this->params['registry_url'];

    $privilegedApiUser = new ApiUser($registryUrl, $privilegedApiUsername, $privilegedApiPassword);
    $platformApiUser = new ApiUser($registryUrl, $platformApiUsername, $platformApiPassword);

    foreach($this->apiObjects as $api) {
      $class = $api . 'Test';
      App::uses($class, 'RestTest.Lib');
      
      switch($api) {
        case 'Co':
        case 'NavigationLink':
          $user = $platformApiUser;
          break;
        default:
          $user = $privilegedApiUser;
      }

      $tests = new $class($user);
      $tests->run();
    }

    $this->out("All tests complete.");
  }
}
