<?php

// Include the configuration file
include 'DB-Config.php';

try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "TRUNCATE TABLE `All_Member_Invoice`";
  // use exec() because no results are returned
  $conn->exec($sql);
  $conn = null;
} catch(PDOException $e) {
  echo "[Error] ==>". $e->getMessage();
}

try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = 'INSERT INTO All_Member_Invoice(Member_GUID,Inv_GUID) 
    SELECT 
     `Member_Bill`.`Member_GUID` as "Member_GUID",
     `Member_Bill`.`Inv_GUID` as "Inv_GUID" 
     FROM (
     SELECT 
     `Invoice`.`GUID` as "Inv_GUID",
     `Invoice`.`Start_Date` as "Inv_SDate",
     `Invoice`.`Title` as "Inv_Title",
     `Invoice`.`Amount` as "Inv_Amount",
     `Invoice`.`Comment` as "Inv_Comment",
     `Member`.`GUID` as "Member_GUID"
     FROM `Invoice` CROSS JOIN `Member` 
     where `Member`.`Visible`=1 and `Invoice`.`Visible`=1) 
     Member_Bill LEFT JOIN (
     SELECT `Member_GUID` as "Member_GUID",
     `Invoice_GUID` as "payed_invoice" FROM `Payed_Bills` where `Visible`=1)
     payed ON `Member_Bill`.`Member_GUID` = payed.Member_GUID and `Member_Bill`.`Inv_GUID` = payed.payed_invoice WHERE payed.payed_invoice IS NULL';
      // use exec() because no results are returned
      $conn->exec($sql);
      $conn = null;
} catch(PDOException $e) {
  echo "[Error] ==>". $e->getMessage();

}


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

date_default_timezone_set("Asia/Tehran");
$d = date('m/d/Y h:i:s a', time());

echo "[OK] on ". $d ."  "."\n";
echo "*----------------------------------*"."\n";




?>