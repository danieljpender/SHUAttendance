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

// Check if the timetable id has been provided
if (!isset($_GET['timetableid'])) {
  die("Timetable id not provided");
}

$timetableid = $_GET['timetableid'];

// Query the database for the event details
$query = "SELECT t.Title as title, t.[Description] as description, t.Location as location, t.StaffMembers as staff_members, m.ModuleName as module_name, ta.ActivityTypeName as activity_type_name, t.StartTime as start_time, t.EndTime as end_time FROM Timetable t
          JOIN Module m ON m.ModuleId = t.ModuleId
          JOIN ActivityType ta ON ta.ActivityTypeId = t.TypeId
          WHERE t.TimetableId = '$timetableid'";
$result = odbc_exec($connection, $query);

if (!$result) {
  die("Error retrieving event details: " . odbc_errormsg());
}

$row = odbc_fetch_array($result);

// Create an associative array of the event details
$event_details = array(
  "title" => $row['title'],
  "description" => $row['description'],
  "location" => $row['location'],
  "staff_members" => $row['staff_members'],
  "module_name" => $row['module_name'],
  "activity_type_name" => $row['activity_type_name'],
  "start_time" => $row['start_time'],
  "end_time" => $row['end_time']
);

// Encode the event details as a JSON object and return it
header("Content-Type: application/json");
echo json_encode($event_details);
?>
