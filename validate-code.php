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

$timetableid = $_POST['timetableid'];
$enteredcode = $_POST['enteredcode'];

$query = "SELECT COUNT(*) AS code_count FROM AttendanceCode WHERE TimetableId = '$timetableid' AND Code = '$enteredcode'";
echo "Query: $query<br>";

$result = odbc_exec($connection, $query);
if (!$result) {
    die("Error executing query: " . odbc_errormsg());
}

$row = odbc_fetch_array($result);
$codeCount = $row['code_count'];

echo "Code count: $codeCount<br>";

if ($codeCount == 1) {
    echo "valid";
} else {
    echo "invalid";
}
