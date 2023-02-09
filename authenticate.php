<?php
$server = "eam-group27.database.windows.net";
$database = "SHUAttendance";
$username = "eam";
$password = "%PA55w0rd";

$conn = odbc_connect("Driver={ODBC Driver 17 for SQL Server};Server=$server;Database=$database;", $username, $password);

// Check the connection
if (!$conn) {
    die("ODBC Connection Failed: " . odbc_errormsg());
}

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT * FROM [user] WHERE username = ? AND password = ?";
$stmt = odbc_prepare($conn, $sql);
$exec = odbc_execute($stmt, array($username, $password));

if (!$exec) {
  exit("Execution Failed: " . odbc_errormsg($conn));
}

if (odbc_num_rows($stmt) > 0) {
  session_start();
  $_SESSION['loggedin'] = true;
  $_SESSION['username'] = $username;
  header("Location: home.php");
} else {
  header("Location: login.php?error=Invalid Credentials");
}

odbc_close($conn);
