<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$serverName = "eam-group27.c0zwiexiop2w.eu-west-2.rds.amazonaws.com,1433";
$database = "SHUAttendance";
$dbuser = "eam";
$dbpass = "%PA55w0rd";

$connOptions = array(
    "Database" => $database,
    "UID" => $dbuser,
    "PWD" => $dbpass,
    "Encrypt" => true,
    "TrustServerCertificate" => true,
    "LoginTimeout" => 30
);

$connection = sqlsrv_connect($serverName, $connOptions);

if ($connection === false) {
    die(print_r(sqlsrv_errors(), true));
}

if (isset($_POST['timetableid'])) {
  $timetableid = $_POST['timetableid'];
  $code = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);

  $update_query = "UPDATE Timetable SET [code] = $code WHERE TimetableId = '$timetableid'";
  sqlsrv_query($connection, $update_query);

  echo $code;
}

sqlsrv_close($connection);
?>
