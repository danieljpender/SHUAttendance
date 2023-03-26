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

// Retrieve the selected department from the query parameter
$department = $_GET['department'];

// Retrieve the modules for the selected department from the database
$query = "SELECT * FROM Module WHERE DepartmentId = '$department'";
$result = sqlsrv_query($connection, $query);

// Build the HTML for the module dropdown
$html = '<option value="">Select a Module</option>';
while ($row = sqlsrv_fetch_array($result)) {
  $html .= '<option value="' . $row['ModuleId'] . '">' . $row['ModuleName'] . '</option>';
}

// Return the HTML for the module dropdown
echo $html;
?>
