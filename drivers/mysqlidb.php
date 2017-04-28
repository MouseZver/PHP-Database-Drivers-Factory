<?php

class mysqlidb implements dbInterface {

  // Database Object
  private $dbObject = null;
  // Query's result Object
  private $dbQuery = null;
  // Last Inserted ID
  private $lastInsertId = 0;
  // Bind params
  private $bindParams = array();

  // Construction Method
  public function __construct($dbHost, $dbUsername, $dbPassword, $dbName) {
    try {
      mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
      $this->dbObject = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);
    } catch (mysqli_sql_exception $e) {
      throw $e;
    }
  }

  public function getSingleData($query, $paramArray = array()) {
    $result = array();
    if ($query != "") {
      // run Query
      $this->dbQuery = $this->dbObject->prepare($query);
      // bind Params
      $this->bind($paramArray);
      // Execute Query
      $this->dbQuery->execute();
      // Get Results
      $queryResult = $this->dbQuery->get_result();
      // Check row exist
      if ($queryResult->num_rows > 0) {
        $result = $queryResult->fetch_assoc();
      }
      $this->dbQuery->close();
    }
    return $result;
  }

  public function getData($query, $paramArray = array()) {
    $result = array();
    if ($query != "") {
      // run Query
      $this->dbQuery = $this->dbObject->prepare($query);
      // bind Params
      $this->bind($paramArray);
      // Execute Query
      $this->dbQuery->execute();
      // Get Results
      $queryResult = $this->dbQuery->get_result();
      // Check row exist
      if ($queryResult->num_rows > 0) {
        while ($row = $queryResult->fetch_assoc()) {
          $result[] = $row;
        }
      }
      $this->dbQuery->close();
    }
    return $result;
  }

  public function executeQuery($query, $paramArray = array()) {
    $result = false;
    if ($query != "") {
      $this->dbQuery = $this->dbObject->prepare($query);
      $this->bind($paramArray);
      // Execute query
      $result = $this->dbQuery->execute();
      // Set Insert Id
      $this->lastInsertId = $this->dbQuery->insert_id;
      $this->dbQuery->close();
    }
    return $result;
  }

  public function getInsertId() {
    return $this->lastInsertId;
  }

  // Bind parameters
  private function bind($paramArray) {
    if (count($paramArray) > 0) {
      // Loop Params values
      for ($i = 0; $i < count($paramArray); $i++) {
        $value = $paramArray[$i];
        switch (true) {
          case is_int($value):
            $type = 'i';
            break;
          case is_float($value):
            $type = 'd';
            break;
          default:
            $type = 's';
        }
        $this->bindParams[][$type] = $value;
      }
      // Create bind types and values
      $params = array();
      $bind_type = '';
      $bind_value = array();
      for ($i = 0; $i < count($this->bindParams); $i++) {
        $bind_type .= array_keys($this->bindParams[$i])[0];
        $bind_value[] .= array_values($this->bindParams[$i])[0];
      }
      $params[] = &$bind_type;
      for ($i = 0; $i < count($bind_value); $i++) {
        $params[] = &$bind_value[$i];
      }
      $this->bindParams = array();
      //var_dump($params);exit;
      call_user_func_array(array($this->dbQuery, 'bind_param'), $params);
    }
  }

}