<?php

class pdodb implements dbInterface {

  // Database Object
  private $dbObject = null;
  // Query's Object
  private $dbQuery = null;
  // Last Inserted ID
  private $lastInsertId = 0;

  // Construction Method
  public function __construct($dbHost, $dbUsername, $dbPassword, $dbName) {
    $dsn = 'mysql:host=' . $dbHost . ';dbname=' . $dbName . ";charset=utf8";
    try {
      $this->dbObject = new PDO($dsn, $dbUsername, $dbPassword, array(
          PDO::ATTR_PERSISTENT => true,
          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
          PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
        )
      );
    } catch (PDOException $e) {
      throw $e;
    }
  }

  // Get Multiple Rows
  public function getData($query, $paramArray = array()) {
    $result = array();
    if ($query != "") {
      // Set Query
      $this->dbQuery = $this->dbObject->prepare($query);
      // Bind Params
      if (count($paramArray) > 0) {
        for ($i = 0; $i < count($paramArray); $i++) {
          $this->bind($i + 1, $paramArray[$i]);
        }
      }
      // Execute Query
      $this->dbQuery->execute();
      // Check Rows
      if ($this->dbQuery->rowCount() > 0) {
        $result = $this->dbQuery->fetchAll(PDO::FETCH_ASSOC);
      }
    }
    return $result;
  }

  // Get Single Row
  public function getSingleData($query, $paramArray = array()) {
    $result = array();
    if ($query != "") {
      // Set Query
      $this->dbQuery = $this->dbObject->prepare($query);
      // Bind Params
      if (count($paramArray) > 0) {
        for ($i = 0; $i < count($paramArray); $i++) {
          $this->bind($i + 1, $paramArray[$i]);
        }
      }
      // Execute Query
      $this->dbQuery->execute();
      // Check Rows
      if ($this->dbQuery->rowCount() > 0) {
        $result = $this->dbQuery->fetch(PDO::FETCH_ASSOC);
      }
    }
    return $result;
  }

  // Execute Insert , Delete , Update Query's
  public function executeQuery($query, $paramArray = array()) {
    $result = false;
    if ($query != '') {
      $this->dbQuery = $this->dbObject->prepare($query);
      // Bind Params
      if (count($paramArray) > 0) {
        for ($i = 0; $i < count($paramArray); $i++) {
          $this->bind($i + 1, $paramArray[$i]);
        }
      }
      $this->dbQuery->execute();
      $this->lastInsertId = $this->dbObject->lastInsertId();
      $result = ($this->dbQuery->rowCount() > 0) ? true : false;
    }
    return $result;
  }

  // Get Last Insert Id
  public function getInsertId() {
    return $this->lastInsertId;
  }

  // Bind parameters
  private function bind($param, $value, $type = null) {
    if (is_null($type)) {
      switch (true) {
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
    }
    $this->dbQuery->bindValue($param, $value, $type);
  }

}
