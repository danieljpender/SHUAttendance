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
$eventid = $_POST['event_id'];

echo "Code: " . $code . " EventId: " . $eventid;

// Insert the data into the database
$query = "INSERT INTO ScheduledEventCode (ScheduledEventCodeId, Code, EventId, DateCreated)
          VALUES (NEWID(), '$code', '$eventid', GETDATE())";
odbc_exec($connection, $query);

// Redirect the user back to the main page
header("Location: myschedule.php");
exit();
?>
