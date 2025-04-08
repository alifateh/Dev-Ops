<?php

// Include the configuration file
include 'DB-Config.php';

try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = 'SELECT * FROM `Invoice` ORDER BY `Invoice`.`ID` DESC LIMIT 1';
  $last_Invoice = $conn->query($sql);//select last INVOICE
  if ($last_Invoice->rowCount() > 0) {
    while ($row = $last_Invoice->fetch(PDO::FETCH_ASSOC)) {
      try {
      $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
      // set the PDO error mode to exception
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $step2_q = "SELECT * FROM All_Member_Invoice WHERE `Inv_GUID` LIKE '".$row['GUID']."'";
      $step2 = $conn->query($step2_q); //how many mechanic did not pay
        if ($step2->rowCount() > 0) {
          while ($row2 = $step2->fetch(PDO::FETCH_ASSOC)) {
              
              try {
                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                // set the PDO error mode to exception
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $step3_q = "UPDATE `Member` SET `Status`='0' WHERE `Visible` = 1 AND `GUID` = '".$row2['Member_GUID']."'";
                $step3 = $conn->query($step3_q);
                $conn = null;
              
              } catch(PDOException $e) {
                echo "[Error] ==>". $e->getMessage();
              }

          }
        }
        $conn = null;
      }catch(PDOException $e) {
        echo "[Error] ==>". $e->getMessage();
      
      }
    }

  }

  $conn = null;
} catch(PDOException $e) {
  echo "[Error] ==>". $e->getMessage();

}


?>