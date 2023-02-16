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

// Query the database for the event details associated with the given timetable id
$query = "SELECT m.ModuleName as module, t.StartTime as start_time, t.EndTime as end_time, t.Location as location, ta.ActivityTypeName as type, CONCAT(u.FirstName, ' ', u.LastName) as staff_member 
FROM Timetable t 
JOIN Module m ON t.ModuleId = m.ModuleId 
JOIN ActivityType ta ON t.TypeId = ta.ActivityTypeId 
JOIN UserTimetable ut ON t.ModuleId = ut.ModuleId 
JOIN [User] u ON m.StaffId = u.UserId 
WHERE t.TimetableId = '$timetableid' 
AND ut.UserId = '" . $_SESSION['userid'] . "'";
$result = odbc_exec($connection, $query);

if (!$result) {
    die("Error retrieving event details: " . odbc_errormsg());
}

$row = odbc_fetch_array($result);

// Prepare the response as a JSON object
$response = array(
    'module' => $row['module'],
    'start_time' => $row['start_time'],
    'end_time' => $row['end_time'],
    'location' => $row['location'],
    'type' => $row['type'],
    'staff_member' => $row['staff_member']
);

echo json_encode($response);
?>
