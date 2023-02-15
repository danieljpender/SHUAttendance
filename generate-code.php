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

if (isset($_POST['timetableid'])) {
  $timetableid = $_POST['timetableid'];
  $code = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);

  $update_query = "UPDATE Timetable SET [code] = $code WHERE TimetableId = '$timetableid'";
  odbc_exec($connection, $update_query);

  echo $code;
}

odbc_close($connection);
?>
