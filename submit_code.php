<?php
error_reporting(E_ALL);
session_start();

$server = "eam-group27.database.windows.net";
$database = "SHUAttendance";
$serverUsername = "eam";
$serverPassword = "%PA55w0rd";

$connection = odbc_connect("Driver={ODBC Driver 18 for SQL Server};Server=$server;Database=$database;", $serverUsername, $serverPassword);

if (!$connection) {
    die("Error connecting to database: " . odbc_errormsg());
}

// Get the data from the form
$code = $_POST['code'];
$eventid = $_POST['eventid'];

echo "Session data: " . var_dump($_SESSION) . "<br>";
echo "Code: " . $code . " TimetableId: " . $eventid;

// Insert the data into the database
$query = "UPDATE Timetable
          SET [Code] = '$code'
          WHERE TimetableId = '$eventid'";
odbc_exec($connection, $query);

// Redirect the user back to the main page
header("Location: myschedule.php");
exit();
?>
