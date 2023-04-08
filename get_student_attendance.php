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
    die(print_r(sqlsrv_errors(), true));
}

// $timetableid = $_POST['timetableid'];
// if (!isset($_POST['timetableid'])) {
//   die("Error: no timetable ID provided");
// }

$user = $_POST['user'];
if (!isset($_POST['user'])) {
  die("Error: no user ID provided");
}

// Get the students enrolled in the timetabled event
$query = "SELECT u.UserId, u.StudentId, u.FirstName, u.Surname, u.Email,
CASE WHEN a.UserId IS NULL THEN 'No' ELSE 'Yes' END AS Attended, t.Description, t.StartDate, t.StartTime
FROM UserTimetable ut
JOIN [Users] u ON u.UserId = ut.UserId
JOIN Timetable t ON t.ModuleId=ut.ModuleId
LEFT JOIN UserAttendanceHistory a ON a.TimetableId = t.TimetableId AND a.UserId = ut.UserId
WHERE u.UserId = '$user'
--AND m.ModuleId = '$moduleid";
//echo "SQL query: $query<br>"; // printing the SQL query for debugging purposes
$result = sqlsrv_query($connection, $query);
 ?>