<?php
/*
  * Google class to authroize the user
  * and get their info.
 */
namespace App\Helpers\Auth;

use Google_Client;
use Google_Service_Oauth2;

class Google {
  /*
    * @var - $client - Google Client variable.
   */
  private $client;
  /*
    * Parameterized constructor to initialize the
    * object and set required options.
   */
  public function __construct($role, $access_token=null) {
    $this->client = new Google_Client();
    $this->client->setAuthConfig($_ENV['GOOGLE_APPLICATION_CREDENTIALS']);
    $this->client->setRedirectUri('http://gethired.com/auth/google/'.$role);
    $this->client->addScope(Google_Service_Oauth2::USERINFO_EMAIL);
    $this->client->addScope(Google_Service_Oauth2::USERINFO_PROFILE);
    if($access_token) {
      $this->client->authenticate($access_token);
    }
  }
  /*
    * Returns the prepared auth url where
    * user will be redirected.
    * @return string
   */
  public function getSigninUrl() {
    return $this->client->createAuthUrl();
  }
  /*
    * Returns the user object returned by
    * google.
    * @return object
   */
  public function getUserInfo() {
    try {
      $oauth2 = new Google_Service_Oauth2($this->client);
      return $oauth2->userinfo->get();
    } catch (\Exception $e) {
      die(json_decode($e->getMessage())->error->errors[0]->reason);
    }
  }
}
