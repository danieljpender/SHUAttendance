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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $timetable_id = $_POST['timetable_id'];
  $code = $_POST['code'];

  $query = "UPDATE Timetable SET [code] = $code WHERE TimetableId = $timetable_id";
  $result = odbc_exec($connection, $query);

  if ($result) {
    echo "Code updated successfully";
  } else {
    echo "Error updating code: " . odbc_errormsg();
  }
}

odbc_close($connection);
?>