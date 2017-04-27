<?php
include("dbInterface.php");

class dbFactory {

  // Database Driver
  private static $dbDriver = null;

  // Available Drivers
  private static $availableDrivers = array('pdodb', 'mysqlidb');

  // Get Instance Of Selected Driver
  public static function getInstance($dbHost, $dbUsername, $dbPassword, $dbName, $dbDriverType = 'pdodb') {
    self::$dbDriver = strtolower(trim($dbDriverType));
    // Check Selected driver exist
    if (in_array(self::$dbDriver, self::$availableDrivers)) {
      // Check DB class exist
      if (class_exists(self::$dbDriver)) {
        // Return Created Object
        return new self::$dbDriver($dbHost, $dbUsername, $dbPassword, $dbName);
      }
      throw new Exception(self::$dbDriver . " Class Not Found");
    }
    throw new Exception("Please Select one of the available drivers");
  }

  // Get Available Database Drivers
  public static function getAvailableDrivers() {
    return self::$availableDrivers;
  }

  // Get Selected Database driver
  public static function getActiveDriver() {
    return self::$dbDriver;
  }

}