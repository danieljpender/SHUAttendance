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

// Get the students enrolled in the timetabled event
$query = "SELECT u.UserId, u.StudentId, u.FirstName, u.Surname, u.Email,
CASE WHEN a.UserId IS NULL THEN 'No' ELSE 'Yes' END AS Attended
FROM UserTimetable ut
JOIN [Users] u ON u.UserId = ut.UserId
JOIN Timetable t ON t.ModuleId=ut.ModuleId
LEFT JOIN UserAttendanceHistory a ON a.TimetableId = t.TimetableId
WHERE t.TimetableId = '$timetableid'
AND u.RoleId = '17B1CDAC-93F8-4A5F-A5CD-907272094140'";
$result = sqlsrv_query($connection, $query);


// Generate the attendance table
echo '<table>';
echo '<tr><th>Student ID</th><th>Name</th><th>Email</th><th>Attended</th></tr>';
while ($row = sqlsrv_fetch_array($result)) {
  echo '<tr>';
  echo '<td>' . $row['StudentId'] . '</td>';
  echo '<td>' . $row['FirstName'] . ' ' . $row['Surname'] . '</td>';
  echo '<td>' . $row['Email'] . '</td>';
  echo '<td>' . $row['Attended'] . '</td>';
  echo '</tr>';
}
echo '</table>';


// // Get the attendance of each student for the timetabled event
// $query2 = "SELECT * FROM UserAttendanceHistory
//           WHERE TimetableId='$timetableid'";
// $result2 = odbc_exec($connection, $query2);

// echo $query2;
// echo $result2;

// // Create the table header
// echo "<tr>";
// echo "<th>Name</th>";
// echo "<th>Attendance</th>";
// echo "</tr>";

// // Loop through the students enrolled in the timetabled event and display their attendance
// while ($row = odbc_fetch_array($result)) {
//   echo "<tr>";
//   echo "<td>" . $row['FirstName'] . " " . $row['Surname'] . "</td>";
//   $attendance_recorded = false;
//   odbc_data_seek($result2, 0);
//   while ($row2 = odbc_fetch_array($result2)) {
//     if ($row['UserId'] == $row2['UserId']) {
//       echo "<td>Attended</td>";
//       $attendance_recorded = true;
//       break;
//     }
//   }
//   if (!$attendance_recorded) {
//     echo "<td>Absent</td>";
//   }
//   echo "</tr>";
// }

// <?php
// session_start();
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

// $server = "eam-group27.database.windows.net";
// $database = "SHUAttendance";
// $serverUsername = "eam";
// $serverPassword = "%PA55w0rd";

// $connection = odbc_connect("Driver={ODBC Driver 18 for SQL Server};Server=$server;Database=$database;", $serverUsername, $serverPassword);

// if (!$connection) {
//     die("Error connecting to database: " . odbc_errormsg());
// }

// // Check if the user is logged in
// if (!isset($_SESSION['userid'])) {
//   header("Location: login.php");
//   exit();
// }

// $userid = $_SESSION['userid'];
// $role = $_SESSION['rolename'];

// // Check if timetableid is provided
// if (isset($_POST['timetableid'])) {
//     $timetableid = $_POST['timetableid'];

// // Query the database for the enrolled students in the event
// $query = "SELECT u.UserId, u.FirstName, u.Surname, 
//                  CASE WHEN a.UserId IS NULL THEN 'No' ELSE 'Yes' END AS Attended
//           FROM UserTimetable ut
//           JOIN [Users] u ON u.UserId = ut.UserId
//           JOIN Timetable t ON t.ModuleId=ut.ModuleId
//           LEFT JOIN UserAttendanceHistory a ON a.UserId = ut.UserId
//           WHERE t.TimetableId = '$timetableid'";
// $result = odbc_exec($connection, $query);
// echo $query;
// echo $result;
// // Generate the attendance table
// echo '<table>';
// echo '<tr><th>User ID</th><th>Name</th><th>Email</th><th>Attended</th></tr>';
// while ($row = odbc_fetch_array($result)) {
//   echo '<tr>';
//   echo '<td>' . $row['UserId'] . '</td>';
//   echo '<td>' . $row['FirstName'] . ' ' . $row['LastName'] . '</td>';
//   echo '<td>' . $row['Attended'] . '</td>';
//   echo '</tr>';
// }
// echo '</table>';

// }
 ?>