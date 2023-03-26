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

if (isset($_GET['department']) && isset($_GET['module'])) {
  $department = $_GET['department'];
  $module = $_GET['module'];
  
  $query = "SELECT *, u.FullName as student_name, t.StartDate as startdate, u.UserId as userid
            FROM UserTimetable ut
            JOIN [User] u ON ut.StudentId = u.UserId
            JOIN [Module] m ON ut.ModuleId = m.ModuleId
            WHERE m.DepartmentId = '$department' AND m.ModuleId = '$module'";
  $result = sqlsrv_query($connection, $query);
  if ($result === false) {
    die(print_r(sqlsrv_errors(), true));
  }
  
  while ($row = sqlsrv_fetch_array($result)) {
    echo '<tr>';
    echo '<td>' . $row['student_name'] . '</td>';
    echo '<td>' . $row['startdate']->format('Y-m-d') . '</td>';
    echo '<td>';
    if ($row['Attendance'] === null) {
      echo 'No record';
    } else {
      echo $row['Attendance'] == 1 ? 'Present' : 'Absent';
    }
    echo '</td>';
    echo '</tr>';
  }
}

sqlsrv_free_stmt($result);
sqlsrv_close($connection);
?>
