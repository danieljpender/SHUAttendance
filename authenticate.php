<?php
session_start();

$username = $_POST["username"];
$password = $_POST["password"];

$server = "eam-group27.database.windows.net";
$database = "SHUAttendance";
$serverUsername = "eam";
$serverPassword = "%PA55w0rd";

$connection = odbc_connect("Driver={ODBC Driver 18 for SQL Server};Server=$server;Database=$database;", $serverUsername, $serverPassword);

if (!$connection) {
    die("Error connecting to database: " . odbc_errormsg());
}

$sql = "SELECT [role], [userid] FROM [user] WHERE username='$username' AND password='$password'";
$result = odbc_exec($connection, $sql);

if (!$result) {
    die("Error executing the query: " . odbc_errormsg());
}

if (odbc_num_rows($result) > 0) {
    $row = odbc_fetch_array($result);
    $_SESSION["username"] = $username;
    $_SESSION["role"] = $row["role"];
    $userid = $row["userid"];
    $_SESSION["userid"] = $userid;

    if ($_SESSION["role"] == "admin") {
        header("Location: MySchedule.php");
    } elseif ($_SESSION["role"] == "student") {
        header("Location: MySchedule.php");
    } elseif ($_SESSION["role"] == "teacher") {
        header("Location: teacher.php");
    }
} else {
    echo "Invalid username or password";
}

odbc_close($connection);

?>
