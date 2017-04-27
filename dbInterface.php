<?php

interface dbInterface {

  // Get Multiple Records
  public function getData($Query, $paramArray = array());

  // Get Single Record
  public function getSingleData($Query, $paramArray = array());

  // Execute Query's
  public function executeQuery($Query, $paramArray = array());

  // Get Insert Id
  public function getInsertId();

}