<?php
session_start();

$serverName = "eam-group27.c0zwiexiop2w.eu-west-2.rds.amazonaws.com,1433";
$database = "SHUAttendance";
$dbuser = "eam";
$dbpass = "%PA55w0rd";

$connOptions = array(
    "Database" => $database,
    "UID" => $dbuser,
    "PWD" => $dbpass,
    "MultipleActiveResultSets" => false,
    "Encrypt" => true,
    "TrustServerCertificate" => true,
    "LoginTimeout" => 30
);

$connection = sqlsrv_connect($serverName, $connOptions);

if ($connection === false) {
    die(print_r(sqlsrv_errors(), true));
}
// Retrieve the department and module values from the URL parameters
$department = $_GET['department'];
$module = $_GET['module'];

// Make a database query to retrieve the attendance records
$query = "SELECT *, u.FullName as student_name FROM UserTimetable ut
                      --LEFT JOIN UserAttendanceHistory uah ON ut.UserTimetableId = uah.UserTimetableId
                      JOIN Timetable t ON t.ModuleId = ut.ModuleId
                      JOIN Users u ON u.UserId = ut.UserId
                      WHERE ut.DepartmentId = '$department' AND ut.ModuleId = '$module'
                      AND u.RoleId = '17b1cdac-93f8-4a5f-a5cd-907272094140'";
            $result = sqlsrv_query($connection, $query);
            while ($row = sqlsrv_fetch_array($result)) {
                echo '<tr>';
                echo '<td>' . $row['student_name'] . '</td>';
                echo '<td>' . $row['startdate'] . '</td>';
                echo '<td>' . $row['attendance'] . '</td>';
                echo '</tr>';
              }

// Generate the HTML for the attendance table
$tableHtml = '<thead>
  <tr>
    <th>Student Name</th>
    <th>Date</th>
    <th>Attendance Record</th>
  </tr>
</thead>
<tbody>';
// Loop through the query results and append each row to $tableHtml
// ...
$tableHtml .= '</tbody>';

// Return the HTML as the response to the AJAX request
echo $tableHtml;
?>
