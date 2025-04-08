<?php

// Include the configuration file
include 'DB-Config.php';


date_default_timezone_set("Asia/Tehran");
$DateANDTime = date('m/d/Y h:i:s a', time());

function U_LicenseValidate($GUID , $stat, $servername, $username, $password, $dbname)
{

    try {
        $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $qur = "UPDATE `Member_License` SET `LicenseStatus`= ".$stat." WHERE `GUID` ='" . $GUID . "'";
        $stmt = $pdo->prepare($qur);
        $stmt->execute();
    } catch (Exception $e) {
        throw $e;
    }
    unset($pdo);
}



try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT `Member_License`.`GUID` as licenseGUID, `Member_License`.`LicenseExpDate`, `Member_License`.`LicenseStatus` FROM `Member_License` INNER JOIN `Member` ON `Member_License`.`GUID` = `Member`.`GUID` WHERE `Member`.`Visible`=1";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll();
} catch (Exception $e) {
    throw $e;
}

unset($conn);

if (!empty($data)) {
    foreach ($data as $row) {
        if (strtotime($row['LicenseExpDate']) < strtotime($DateANDTime)) {
            U_LicenseValidate($row['licenseGUID'], 0, $servername, $username, $password, $dbname);
        }else{
            U_LicenseValidate($row['licenseGUID'], 1, $servername, $username, $password, $dbname);
        }
    }
    echo "[ok] on " . $DateANDTime . "  " . "\n";
    echo "*----------------------------------*" . "\n";
} else {
    echo "[Failed No Member] on " . $DateANDTime . "  " . "\n";
    echo "*----------------------------------*" . "\n";
}

?>