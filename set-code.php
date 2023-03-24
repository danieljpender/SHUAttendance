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
    "Encrypt" => true,
    "TrustServerCertificate" => true,
    "LoginTimeout" => 30
);

$connection = sqlsrv_connect($serverName, $connOptions);

if ($connection === false) {
    die(print_r(sqlsrv_errors(), true));
}

$timetable_id = $_POST['timetable_id'];
$code = $_POST['code'];

// Generate SQL query to update the timetable with the new code
$query = "UPDATE Timetable SET [code] = $code WHERE TimetableId = $timetable_id";

$result = sqlsrv_query($connection, $query);

if (!$result) {
    die("Error updating code: " . sqlsrv_errors());
}

// Return the generated code in the response
echo $code;

sqlsrv_close($connection);
?>


?>