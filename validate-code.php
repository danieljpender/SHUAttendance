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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $code = $_POST['code'];
    $timetableid = $_POST['timetableid'];
    $userid = $_SESSION['userid'];

    $query = "SELECT * FROM Timetable WHERE TimetableId = '$timetableid' AND code = '$code'";
    $result = odbc_exec($connection, $query);

    if (odbc_num_rows($result) == 1) {
        $query = "INSERT INTO UserAttendanceHistory (UserId, TimetableId, AttendanceDate) VALUES ('$userid', '$timetableid', GETDATE())";
        $result = odbc_exec($connection, $query);

        if ($result) {
            echo "success";
        } else {
            echo "error";
        }
    } else {
        echo "invalid";
    }
} else {
    echo "invalid method";
}

odbc_close($connection);
?>
