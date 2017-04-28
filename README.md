# PHP Database Drivers Factory (PDO,MySQLi)

A Class that helps you work with Database drivers such as PDO and MySQLi. You just need to select easily what kind of driver you would like to work.

<h1>Initilize</h1>

```PHP
// Inlcude Required Files
include("dbFactory.php");
// Include Drivers
include("drivers/pdodb.php");
include("drivers/mysqlidb.php");

try {
  // Create Object
  $db = dbFactory::getInstance("localhost", "username", "password", "testDB",'DBDriverName');
  // Available drivers you can choose to use
  $availableDrivers = dbFactory::getAvailableDrivers();
} catch (Exception $ex) {
  echo $ex->getMessage();
}
```

<h1>Usage</h1>

<h3>Get Multiple row</h3>

```PHP
 $multipleRows = $db->getData("SELECT * FROM student"));
 ```
 Result : 
 
 ```PHP
 
 array(2) {
   [0]=>
   array(4) {
     ["student_id"] => string(1) "1"
     ["firstname"]  => string(4) "amir"
     ["lastname"]   => string(6) "etemad"
     ["age"]        => string(2) "26"
   }
   [1]=>
   array(4) {
     ["student_id"] => string(1) "2"
     ["firstname"]  => string(4) "kian"
     ["lastname"]   => string(6) "barker"
     ["age"]        => string(2) "24"
   }
 }
 
```

<h3>Get Single Row</h3>

```PHP
 $singleRow = $db->getSingleData("SELECT firstname,lastname FROM student WHERE student_id = 3 ");
```

Result :
```php
array(2) {
  ["firstname"] => string(5) "david"
  ["lastname"]  => string(6) "castor"
}
```

<h3> Insert , Delete and Update </h3>

```php
 // Insert New Record
 $insert = $db->executeQuery("INSERT INTO student (firstname,lastname,age) VALUES ('amir','etemad','26') "); 

 // Delete Record
 $delete = $db->executeQuery("DELETE FROM student WHERE student_id >= 6");
 
 // Update Record
 $update = $db->executeQuery("UPDATE student SET firstname = 'amir' WHERE student_id = 2");

```

<h3>Get Insert ID</h3>

<p> Get insert id after insert record</p>

```php
 $insertId = $db->getInsertId();
```

<h3>Prepared Statements</h3>

Use Parameter Binding to Prevent SQL Injection Attacks
```php
$db->getSingleData("SELECT * FROM student WHERE firstname = ?", array("amir"))
```