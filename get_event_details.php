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
$result = sqlsrv_query($connection, $query);

if (!$result) {
    die("Error: " . sqlsrv_errors());
}

$row = sqlsrv_fetch_array($result);
$event = array(
    "timetable_id" => $row['timetable_id'],
    "module" => $row['ModuleName'],
    "activity_type" => $row['type_name'],
    "location_name" => $row['Location'],
    "staff_members" => $row['StaffMembers'],
    "start_time" => date("H:i", strtotime($row['StartTime']->format('Y-m-d H:i:s'))),
    "end_time" => date("H:i", strtotime($row['EndTime']->format('Y-m-d H:i:s'))),
    "description" => $row['Description'],
    "module_code" => $row['ModuleCode'],
    "activity_name" => $row['ActivityName'],
    "event_date" => date("l, j M Y", strtotime($row['StartDate']->format('Y-m-d H:i:s'))),
);

echo json_encode($event);
?>
