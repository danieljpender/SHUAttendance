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

if (isset($_POST['timetableid']) && isset($_POST['code'])) {
  $timetableid = $_POST['timetableid'];
  $code = $_POST['code'];

  // Query the database to check if the code is valid
  $query = "SELECT TimetableId, [code] FROM Timetable WHERE TimetableId = '$timetableid' AND [code] = $code";
  echo $query;
  $result = sqlsrv_query($connection, $query);
  if (sqlsrv_has_rows($result) == 1) {
    // Code is valid, update the attendance record for the user and the event
    $userid = $_SESSION['userid'];
    $query = "INSERT INTO UserAttendanceHistory (UserAttendanceHistoryId, UserId, TimetableId, DateCreated) VALUES (NEWID(), '$userid', '$timetableid', GETUTCDATE())";
    sqlsrv_query($connection, $query);
    echo 'success';
  } else {
    // Code is invalid
    echo 'failure';
  }
}
