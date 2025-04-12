<?php
// Include the configuration file
include 'DB-Config.php';
date_default_timezone_set("Asia/Tehran");
$d = date('m/d/Y h:i:s a', time());


$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false
];
$dsn = "mysql:host=$servername;dbname=$dbname;charset=$charset";
try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

function Get_MechanicsAll($pdo)
{
    $qur = "SELECT * FROM `Member` WHERE `Visible` = 1 ORDER BY `ID` ASC";
    $stmt = $pdo->prepare($qur);
    $stmt->execute();
    $data = $stmt->fetchAll();
    return $data;
}

function Get_ContractsAll($pdo)
{
    $qur = 'SELECT * FROM `Contarct_All`';
    $stmt = $pdo->prepare($qur);
    $stmt->execute();
    $data = $stmt->fetchAll();
    return $data;
}

function Get_Contract_ByMechanicContractTariff($Contract, $mechanic, $Tar, $pdo)
{
    $qur = "SELECT * FROM `Contarct_All` WHERE `ContractGUID` ='$Contract' and `MechanicGUID` ='$mechanic' and `TariffVersionID` ='$Tar'";
    $stmt = $pdo->prepare($qur);
    $stmt->execute();
    $data = $stmt->fetchAll();
    return $data;
}

function C_Contract_ByMechanicContract($Contract, $mechanic, $Tar, $pdo)
{
    //pre info
    $date = date('Y-m-d H:i:s');
    $status = 0;
    //Database
    $qur = "INSERT INTO `Contarct_All`( `ContractGUID`, `MechanicGUID`, `TariffVersionID`, `Status` ,`DateTime`) VALUES ( ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($qur);
    $stmt->bindParam(1, $Contract);
    $stmt->bindParam(2, $mechanic);
    $stmt->bindParam(3, $Tar);
    $stmt->bindParam(4, $status);
    $stmt->bindParam(5, $date);
    $stmt->execute();
}

$Mechanics = Get_MechanicsAll($pdo);
$Contracts = Get_ContractsAll($pdo);

$conn = null;


if (!empty($Contracts)) {
    if (!empty($Mechanics)) {
        foreach ($Contracts as $cont) {
            foreach ($Mechanics as $Mech) {
                $condition = Get_Contract_ByMechanicContractTariff($cont['ContractGUID'], $Mech['GUID'], $cont['TariffVersionID'], $pdo);
                if (empty($condition)) {
                    C_Contract_ByMechanicContract($cont['ContractGUID'], $Mech['GUID'], $cont['TariffVersionID'], $pdo);
                    echo "new contarct created for user".  $Mech['GUID']." and contractID" . $cont['ContractGUID']." at " . $d . "  " . "\n";
                    echo "*----------------------------------*" . "\n";
                }
            }
            echo "foreach contarct each mechanic checked " . $d . "  " . "\n";
            echo "*----------------------------------*" . "\n";
        }
    } else {
        echo "there is no Contract";
        echo "*----------------------------------*" . "\n";
    }
} else {
    echo "there is no mechanic";
    echo "*----------------------------------*" . "\n";
}


echo "[OK] on " . $d . "  " . "\n";
echo "*----------------------------------*" . "\n";
