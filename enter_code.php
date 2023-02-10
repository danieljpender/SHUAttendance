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

if (isset($_POST['eventid']) && isset($_POST['code'])) {
  $eventid = $_POST['eventid'];
  $code = $_POST['code'];
  $userid = $_SESSION['userid'];

  // Check if the code is correct
  $query = "SELECT * FROM ScheduledEventCode WHERE EventId='$eventid' AND Code='$code'";
  $result = odbc_exec($connection, $query);
  if (odbc_num_rows($result) > 0) {
    // Insert the attendance record
    $query = "INSERT INTO UserEventAttendance VALUES (NEWID(), '$userid', '$eventid')";
    odbc_exec($connection, $query);
    header("Location: schedule.php");
    exit();
  } else {
    echo "Incorrect code";
  }
}
?>
