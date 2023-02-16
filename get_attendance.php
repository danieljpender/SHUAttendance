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

$userid = $_SESSION['userid'];
$role = $_SESSION['rolename'];

// Check if timetableid is provided
if (!isset($_GET['timetableid'])) {
  die("Error: Timetable ID not provided.");
}

$timetableid = $_GET['timetableid'];

// Query the database for the enrolled students in the event
$query = "SELECT u.UserId, u.FirstName, u.LastName, 
                 CASE WHEN a.UserId IS NULL THEN 'No' ELSE 'Yes' END AS Attended
          FROM UserTimetable ut
          JOIN [User] u ON u.UserId = ut.UserId
          LEFT JOIN UserAttendanceHistory a ON a.UserId = u.UserId AND a.TimetableId = ut.TimetableId
          WHERE ut.TimetableId = '$timetableid'";
$result = odbc_exec($connection, $query);

// Generate the attendance table
echo '<table>';
echo '<tr><th>User ID</th><th>Name</th><th>Email</th><th>Attended</th></tr>';
while ($row = odbc_fetch_array($result)) {
  echo '<tr>';
  echo '<td>' . $row['UserId'] . '</td>';
  echo '<td>' . $row['FirstName'] . ' ' . $row['LastName'] . '</td>';
  echo '<td>' . $row['Attended'] . '</td>';
  echo '</tr>';
}
echo '</table>';
?>
