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

$timetableid = $_POST['timetableid'];
if (!isset($_POST['timetableid'])) {
  die("Error: no timetable ID provided");
}


// Get the students enrolled in the timetabled event
$query = "SELECT u.UserId, u.StudentId, u.FirstName, u.Surname, u.Email,
CASE WHEN a.UserId IS NULL THEN 'No' ELSE 'Yes' END AS Attended, t.Description, t.StartDate, t.StartTime
FROM UserTimetable ut
JOIN [Users] u ON u.UserId = ut.UserId
JOIN Timetable t ON t.ModuleId=ut.ModuleId
LEFT JOIN UserAttendanceHistory a ON a.TimetableId = t.TimetableId AND a.UserId = ut.UserId
WHERE t.TimetableId = '$timetableid'
AND u.RoleId = 'B964A9EF-6635-432B-B364-2460B00D8ED1'";
//echo "SQL query: $query<br>"; // printing the SQL query for debugging purposes
$result = sqlsrv_query($connection, $query);


// Generate the attendance table
echo '<table>';
echo '<tr><th>Student ID</th><th>Name</th><th>Email</th><th>Attended</th><th>Email Student</th></tr>';
while ($row = sqlsrv_fetch_array($result)) {
  $subject = $row['Description'];
  $body = '';
if ($row['Attended'] == 'No') {
  $subject = $row['Description'].' - Attendance';
  $body = 'Dear '.$row['FirstName']."%0A%0A".'I just wanted to check with you regarding your attendance record for the session today. Did you face any issues in accessing the event or submitting your attendance record?'."%0A%0A".'Thank you for your cooperation.'."%0A%0A".'Regards,'."%0A".''.$_SESSION["firstname"].'';
}
  echo '<tr>';
  echo '<td>' . $row['StudentId'] . '</td>';
  echo '<td>' . $row['FirstName'] . ' ' . $row['Surname'] . '</td>';
  echo '<td>' . $row['Email'] . '</td>';
  echo '<td>' . $row['Attended'] . '</td>';
  echo '<td><a href="mailto:'.$row['Email'].'?subject='.$subject.'&body='.$body.'">Email Student</a></td>';
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