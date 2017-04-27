<?php
/**
 * Created by PhpStorm.
 * User: Amir
 * Date: 4/27/17
 * Time: 21:59
 */

// Load DB factory class
include("dbFactory.php");
include("drivers/pdodb.php");

try {
  $db = dbFactory::getInstance('localhost', 'laravel', '123456', 'laravel');
  $info = $db->getSingleData("SELECT * FROM student WHERE firstname = ? ", array("asd"));

  var_dump($info);
} catch (Exception $ex) {
  echo($ex->getMessage());
}