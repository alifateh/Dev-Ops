<?php

// Include the configuration file
include 'DB-Config.php';


date_default_timezone_set("Asia/Tehran");
$d = date('m/d/Y h:i:s a', time());

try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "TRUNCATE TABLE `All_Member_Invoice`";
  // use exec() because no results are returned
  $conn->exec($sql);
} catch(PDOException $e) {
   echo "[Error] ==>". $e->getMessage();
}
$conn = null;

try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = 'DELETE FROM `Activity_Log` WHERE `Data and Time` < now() - interval 120 DAY';
  // use exec() because no results are returned
  $conn->exec($sql);
} catch(PDOException $e) {
   echo "[Error] ==>". $e->getMessage();
}
$conn = null;
echo "[Purge Activity_Log] on ". $d ."  "."\n";
try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = 'DELETE FROM `Member_Log` WHERE `DataTime` < now() - interval 45 DAY;';
  // use exec() because no results are returned
  $conn->exec($sql);
} catch(PDOException $e) {
   echo "[Error] ==>". $e->getMessage();

}

$conn = null;

echo "[Purge Member_Log] on ". $d ."  "."\n";
echo "*----------------------------------*"."\n";

?>