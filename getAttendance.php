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

// Check if the user is logged in
if (!isset($_SESSION['userid'])) {
  header("Location: login.php");
  exit();
}

// Retrieve the department and module values from the GET request
if (isset($_GET['department']) && isset($_GET['module'])) {
    $department = $_GET['department'];
    $module = $_GET['module'];

    // Perform a query to retrieve attendance records for the specified department and module
    $query = "SELECT *, u.FullName as student_name, u.StudentId as student_id FROM User u
              INNER JOIN Attendance a ON u.UserId = a.UserId
              WHERE u.DepartmentId = ? AND a.ModuleId = ?";
    $params = array($department, $module);
    $result = sqlsrv_query($connection, $query, $params);

    // Output the attendance records as a HTML table
    echo "<tr>";
    echo "<th>Student ID</th>";
    echo "<th>Student Name</th>";
    echo "<th>Email Address</th>";
    echo "<th>Attendance Records</th>";
    echo "</tr>";

    while ($row = sqlsrv_fetch_array($result)) {
      echo '<tr>';
      echo '<td>' . $row['student_id'] . '</td>';
      echo '<td>' . $row['student_name'] . '</td>';
      echo '<td>' . $row['email'] . '</td>';
      echo "<td><button>View Attendance Records</button></td>";
      echo '</tr>';
    }
  }
?>
