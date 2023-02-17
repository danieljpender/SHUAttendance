<?php
// get_attendance.php

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

$query = $query = "SELECT u.UserId, u.FirstName, u.Surname, 
CASE WHEN a.UserId IS NULL THEN 'No' ELSE 'Yes' END AS Attended
FROM UserTimetable ut
JOIN [Users] u ON u.UserId = ut.UserId
JOIN Timetable t ON t.ModuleId=ut.ModuleId
LEFT JOIN UserAttendanceHistory a ON a.UserId = ut.UserId
WHERE t.TimetableId = '$timetableid'";
$result = odbc_exec($connection, $query);

$data = array();

while ($row = odbc_fetch_array($result)) {
  $data[] = array(
    'username' => $row['Username'],
    'firstname' => $row['FirstName'],
    'lastname' => $row['LastName'],
    'attendancestatus' => $row['Attended']
  );
}

echo json_encode($data);
?>
