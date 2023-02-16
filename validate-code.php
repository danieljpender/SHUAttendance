<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$server = "eam-group27.database.windows.net";
$database = "SHUAttendance";
$serverUsername = "eam";
$serverPassword = "%PA55w0rd";

$connection = odbc_connect("Driver={ODBC Driver 18 for SQL Server};Server=$server;Database=$database;", $serverUsername, $serverPassword);

if (!$connection) {
  die("Error connecting to database: " . odbc_errormsg());
}

if (isset($_POST['timetableid']) && isset($_POST['code'])) {
  $timetableid = $_POST['timetableid'];
  $code = $_POST['code'];

  // Query the database to check if the code is valid
  $query = "SELECT TimetableId, [code] FROM Timetable WHERE TimetableId = '$timetableid' AND [code] = '$code'";
  $result = odbc_exec($connection, $query);

  if (odbc_num_rows($result) == 1) {
    // Code is valid, update the attendance record for the user and the event
    $userid = $_SESSION['userid'];
    $query = "INSERT INTO AttendanceRecord (UserId, TimetableId) VALUES ('$userid', '$timetableid')";
    odbc_exec($connection, $query);
    echo 'success';
  } else {
    // Code is invalid
    echo 'failure';
  }
}
