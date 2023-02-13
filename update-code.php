<?php
session_start();

$server = "eam-group27.database.windows.net";
$database = "SHUAttendance";
$serverUsername = "eam";
$serverPassword = "%PA55w0rd";

$connection = odbc_connect("Driver={ODBC Driver 18 for SQL Server};Server=$server;Database=$database;", $serverUsername, $serverPassword);

if (!$connection) {
    die("Error connecting to database: " . odbc_errormsg());
}

if (!isset($_SESSION['userid'])) {
  header("Location: login.php");
  exit();
}

$timetableId = $_POST['timetableId'];
$code = $_POST['code'];

$query = "UPDATE Timetable SET [Code] = '$code' WHERE TimetableId = '$timetableId'";
$result = odbc_exec($connection, $query);

odbc_close($connection);
?>