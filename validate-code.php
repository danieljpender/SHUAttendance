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

// Check if the user is logged in
if (!isset($_SESSION['userid'])) {
  header("Location: login.php");
  exit();
}
if(isset($_POST['timetableid']) && isset($_POST['enteredcode'])) {
    $timetableid = $_POST['timetableid'];
    $code = $_POST['enteredcode'];

var_dump($_POST);

// Query the database for the timetable with the specified timetableid and code
$query = "SELECT * FROM Timetable WHERE TimetableId='$timetableid' AND [code]=$code";
$result = odbc_exec($connection, $query);
echo "Query: $query<br>";

if (odbc_num_rows($result) > 0) {
  // Code is valid, update the attendance for the user and timetable
  $userid = $_SESSION['userid'];

  $query = "INSERT INTO UserAttendanceHistory (UserAttendanceHistoryId, UserId, TimetableId, DateCreated) VALUES (NEWID(), '$userid', '$timetableid', GETDATE())";
  $result = odbc_exec($connection, $query);

  if ($result) {
    // Attendance has been recorded successfully
    echo "success";
  } else {
    // Failed to record attendance
    echo "error";
  }
} else {
  // Code is invalid
  echo "invalid";
}

}
odbc_close($connection);
?>
