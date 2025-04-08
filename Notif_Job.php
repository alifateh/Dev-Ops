<?php
// Include the configuration file
include 'DB-Config.php';

date_default_timezone_set("Asia/Tehran");
$d = date('m/d/Y h:i:s a', time());

function C_Notif($NotifGUID, $MechanicGUID)
{

    $servername = "localhost";
    $username = "fateh";
    $password = 'BEh9E!$PorbrQK';
    $dbname = "Mechanic_Portal";
    $visible = 1;

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $qur = "INSERT INTO `Member_Notification`( `Visible`, `Member_GUID`, `Notif_GUID`) VALUES ( ?, ?, ?)";
        $stmt = $conn->prepare($qur);
        $stmt->bindParam(1, $visible);
        $stmt->bindParam(2, $MechanicGUID);
        $stmt->bindParam(3, $NotifGUID);
        $stmt->execute();
    } catch (PDOException $e) {
        echo "[Error] ==>" . $e->getMessage();
    }
    $conn = null;
}



try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * FROM `Autoapp_NotificationInApp` WHERE Autoapp_NotificationInApp.End < now() AND `Visible` =1 AND `Validation`=1";
    // use exec() because no results are returned
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "[Error] ==>" . $e->getMessage();
}
$conn = null;


if (!empty($data)) {

    foreach ($data as $row) {

        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "UPDATE `Autoapp_NotificationInApp` SET `Visible`=0, `Validation`=0 WHERE `GUID` = '" . $row['GUID'] . "'";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
        } catch (PDOException $e) {
            echo "[Error] ==>" . $e->getMessage();
        }
        $conn = null;
        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "UPDATE `Member_Notification` SET `Visible`=0 WHERE `Notif_GUID` = '" . $row['GUID'] . "'";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
        } catch (PDOException $e) {
            echo "[Error] ==>" . $e->getMessage();
        }
        $conn = null;
    }
    $conn = null;
} else {
    echo "[ NO OLD Notification to Unvalidate ] on " . $d . " " . "\n";
    echo "*----------------------------------*" . "\n";
}

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * FROM `Autoapp_NotificationInApp` WHERE `Visible` = 0 OR `Validation`= 0";
    // use exec() because no results are returned
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "[Error] ==>" . $e->getMessage();
}
$conn = null;

if (!empty($data)) {

    foreach ($data as $row) {
        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "UPDATE `Member_Notification` SET `Visible`=0 WHERE `Notif_GUID` = '" . $row['GUID'] . "'";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
        } catch (PDOException $e) {
            echo "[Error] ==>" . $e->getMessage();
        }
        $conn = null;
    }

    echo "[ All OLD Notif Deleted ] on " . $d . " " . "\n";
    echo "*----------------------------------*" . "\n";
}


// baray onhaie ke ID= Null bayad insert beshe to Member_Notification va visible = 1


try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT DISTINCT Autoapp_NotificationInApp.GUID AS Notif_GUID, Member_Notification.Member_GUID, Member_Notification.Visible AS IS_SHOW FROM Autoapp_NotificationInApp LEFT JOIN Member_Notification ON Autoapp_NotificationInApp.GUID = Member_Notification.Notif_GUID WHERE Autoapp_NotificationInApp.Visible =1 AND Autoapp_NotificationInApp.Validation =1";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $NewNotif = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "[Error] ==>" . $e->getMessage();
}
$conn = null;


try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * FROM `Member` WHERE `Visible` =1 AND `Status` =1";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $AllMechanics = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "[Error] ==>" . $e->getMessage();
}
$conn = null;

if (!empty($NewNotif)) {
    foreach ($NewNotif as $key) {
        if (is_null($key['IS_SHOW'])) {
            foreach ($AllMechanics as $mech) {
                C_Notif($key['Notif_GUID'], $mech['GUID']);
            }
        }
    }
    echo "[New Notification Added to Members] on " . $d . "  " . "\n";
    echo "*----------------------------------*" . "\n";
} else {
    echo "[No New Notification] on " . $d . "  " . "\n";
    echo "*----------------------------------*" . "\n";
}
