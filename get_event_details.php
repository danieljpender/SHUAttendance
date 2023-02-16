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

if (!isset($_POST['timetableid'])) {
    die("Error: no timetable ID provided");
}

$timetableid = $_POST['timetableid'];

$query = "SELECT *, m.ModuleName as ModuleName, t.TimetableId as timetable_id, 
                t.[code] as timetablecode, t.Location as location_name,
                ta.ActivityTypeName as type_name
          FROM Timetable t
          JOIN Module m ON m.ModuleId = t.ModuleId
          JOIN ActivityType ta ON ta.ActivityTypeId = t.TypeId
          WHERE t.TimetableId = '$timetableid'";
$result = odbc_exec($connection, $query);

if (!$result) {
    die("Error: " . odbc_errormsg());
}

$row = odbc_fetch_array($result);
$event = array(
    "timetable_id" => $row['timetable_id'],
    "module" => $row['ModuleName'],
    "activity_type" => $row['type_name'],
    "location_name" => $row['Location'],
    "staff_members" => $row['StaffMembers'],
    "start_time" => date("H:i", strtotime($row['StartTime'])),
    "end_time" => date("H:i", strtotime($row['EndTime'])),
    "description" => $row['Description'],
);

echo json_encode($event);
?>
