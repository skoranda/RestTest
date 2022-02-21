<?php

class ApiUser {
  public $serverUrl;
  public $login;
  public $password;

  public function __construct($serverUrl, $login, $password) {
    $this->serverUrl = $serverUrl;
    $this->login = $login;
    $this->password = $password;
  }

}
