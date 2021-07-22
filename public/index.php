<?php
/*
  * Landing page, where app is initialized.
 */
require_once "../vendor/autoload.php";
session_start();

use App\Helpers\Session;
use App\Libraries\App;

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__)); // Creating DotENV object.
$dotenv->load(); // Loading the environment variables.


$app = new App; // Initializing the application.

