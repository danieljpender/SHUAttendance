<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$serverName = "eam-group27.c0zwiexiop2w.eu-west-2.rds.amazonaws.com,1433";
$database = "SHUAttendance";
$dbuser = "eam";
$dbpass = "%PA55w0rd";

$connOptions = array(
    "Database" => $database,
    "UID" => $dbuser,
    "PWD" => $dbpass,
    "Encrypt" => true,
    "TrustServerCertificate" => true,
    "LoginTimeout" => 30
);

$connection = sqlsrv_connect($serverName, $connOptions);

if ($connection === false) {
  echo "Unable to connect to database. Error message: ";
  die(print_r(sqlsrv_errors(), true));
}

if (isset($_POST['timetableid']) && isset($_POST['code'])) {
  $timetableid = $_POST['timetableid'];
  $code = $_POST['code'];
  echo "Timetable ID: " . $timetableid . "<br>";
  echo "Code: " . $code . "<br>";

  // Query the database to check if the code is valid
  $query = "SELECT TimetableId, [code] FROM Timetable WHERE TimetableId = '$timetableid' AND [code] = $code";
  echo "SQL Query: " . $query . "<br>";
  $result = sqlsrv_query($connection, $query);
  
  if ($result === false) {
    // Print the last SQL error message, if any
    echo "Unable to execute query. Error message: ";
    die(print_r(sqlsrv_errors(), true));
  }
  if (sqlsrv_has_rows($result)) { // Changed to sqlsrv_has_rows to be more reliable
    // Code is valid, update the attendance record for the user and the event
    $userid = $_SESSION['userid'];
    $query = "INSERT INTO UserAttendanceHistory (UserAttendanceHistoryId, UserId, TimetableId, DateCreated) VALUES (NEWID(), '$userid', '$timetableid', GETUTCDATE())";
    echo "SQL Query: " . $query . "<br>";
    $result = sqlsrv_query($connection, $query);
    
    if ($result === false) {
      // Print the last SQL error message, if any
      echo "Unable to execute query. Error message: ";
      die(print_r(sqlsrv_errors(), true));
    }
    
    echo 'success';
  } else {
    // Code is invalid
    echo 'failure';
  }
}

// Close the database connection
sqlsrv_close($connection);
?> 
<!-- 
  if (sqlsrv_has_rows($result)) {
    // Code is valid, update the attendance record for the user and the event
    $userid = $_SESSION['userid'];
    $query = "INSERT INTO UserAttendanceHistory (UserAttendanceHistoryId, UserId, TimetableId, DateCreated) VALUES (NEWID(), '$userid', '$timetableid', GETUTCDATE())";
    sqlsrv_query($connection, $query);
    echo 'success';
  } else {
    // Code is invalid
    echo 'failure';
  }
} -->
