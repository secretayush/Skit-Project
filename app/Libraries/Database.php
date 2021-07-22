<?php
/*
  * Database class that holds
  * all database related functions.
  * Initializes the PDO object and
  * performs SQL operations.
 */
namespace App\Libraries;

use PDO;
use PDOException;

class Database {
  /*
    * @var string $dbHost - Hostname for database.
   */
  private $dbHost;
  /*
    * @var string $dbUser - Username for database.
   */
  private $dbUser;
  /*
    * @var string $dbPass - Password for database.
   */
  private $dbPass;
  /*
    * @var string $dbName - Name of the database.
   */
  private $dbName;
  /*
    * @var object $statement - Prepared statement object.
   */
  private $statement;
  /*
    * @var object $dbHandler - PDO object to handle the operations.
   */
  private $dbHandler;
  /*
    * @var string $error - Error messages.
   */
  private $error;

  public function __construct() {
      $this->dbHost = $_ENV['DB_HOST'];
      $this->dbUser = $_ENV['DB_USER'];
      $this->dbPass = $_ENV['DB_PASS'];
      $this->dbName = $_ENV['DB_NAME'];

      $conn = 'mysql:host=' . $this->dbHost . ';dbname=' . $this->dbName;
      $options = array(
          PDO::ATTR_PERSISTENT => true,
          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
      );
      try {
          $this->dbHandler = new PDO($conn, $this->dbUser, $this->dbPass, $options);
      } catch (PDOException $e) {
          $this->error = $e->getMessage();
          echo $this->error;
      }
  }
  /*
    * Method to prepare the statement.
    * @param string $sql - Query statement.
   */
  public function query($sql) {
      $this->statement = $this->dbHandler->prepare($sql);
  }
  /*
    * Method to bind values to the preapred statement.
    * @param string $paremeter - paremeter placeholder
    * @param string $value - value to bind
    * @param string $type - type of the value.
   */
  public function bind($parameter, $value, $type = null) {
      switch (is_null($type)) {
          case is_int($value):
              $type = PDO::PARAM_INT;
              break;
          case is_bool($value):
              $type = PDO::PARAM_BOOL;
              break;
          case is_null($value):
              $type = PDO::PARAM_NULL;
              break;
          default:
              $type = PDO::PARAM_STR;
      }
      $this->statement->bindValue($parameter, $value, $type);
  }
  /*
    * Method to execute the statement.
   */
  public function execute() {
    try {
      return $this->statement->execute();
    } catch (PDOException $e) {
        $this->error = $e->getMessage();
        die($this->error);
    }
  }
  /*
    * Method to fetch result object.
   */
  public function getResult() {
      $this->execute();
      return $this->statement->fetchAll(PDO::FETCH_OBJ);
  }
  /*
    * Method to fetch single result object.
   */
  public function getResultSingle() {
      $this->execute();
      return $this->statement->fetch(PDO::FETCH_OBJ);
  }
  /*
    * Method to get row count.
   */
  public function getRowCount() {
      return $this->statement->rowCount();
  }

  public function getLastInsertId() {
    return $this->dbHandler->lastInsertId();
  }
}
