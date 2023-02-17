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

echo $timetableid;
// Get the students enrolled in the timetabled event
$query = "SELECT * FROM UserTimetable ut 
          JOIN Timetable t ON t.ModuleId=ut.ModuleId
          WHERE t.TimetableId='$timetableid'";
$result = odbc_exec($connection, $query);

echo $query;
echo $result;

// Get the attendance of each student for the timetabled event
$query2 = "SELECT * FROM UserAttendanceHistory
          WHERE TimetableId='$timetableid'";
$result2 = odbc_exec($connection, $query2);

echo $query2;
echo $result2;

// Create the table header
echo "<tr>";
echo "<th>Name</th>";
echo "<th>Attendance</th>";
echo "</tr>";

// Loop through the students enrolled in the timetabled event and display their attendance
while ($row = odbc_fetch_array($result)) {
  echo "<tr>";
  echo "<td>" . $row['FirstName'] . " " . $row['Surname'] . "</td>";
  $attendance_recorded = false;
  odbc_data_seek($result2, 0);
  while ($row2 = odbc_fetch_array($result2)) {
    if ($row['UserId'] == $row2['UserId']) {
      echo "<td>Attended</td>";
      $attendance_recorded = true;
      break;
    }
  }
  if (!$attendance_recorded) {
    echo "<td>Absent</td>";
  }
  echo "</tr>";
}
?>
