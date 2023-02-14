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

$timetableid = $_GET['timetableid'];
$code = $_GET['code'];

$query = "UPDATE Timetable SET [code] = $code WHERE TimetableId = $timetableid";
$result = odbc_exec($connection, $query);

echo "<td>$code</td>";
?>
