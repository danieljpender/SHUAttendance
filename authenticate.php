<?php
session_start();

$username = $_POST["username"];
$password = $_POST["password"];

// $server = "eam-group27.c0zwiexiop2w.eu-west-2.rds.amazonaws.com,1433";
// $database = "SHUAttendance";
// $serverUsername = "eam";
// $serverPassword = "%PA55w0rd";

// $connection = odbc_connect("Driver={ODBC Driver 18 for SQL Server};Server=$server;Database=$database;", $serverUsername, $serverPassword);

// if (!$connection) {
//     die("Error connecting to database: " . odbc_errormsg());
// }
$serverName = "tcp:eam-group27.c0zwiexiop2w.eu-west-2.rds.amazonaws.com,1433";
$connectionOptions = array("Database"=>"SHUAttendance",
                           "Uid"=>"eam", "PWD"=>"%PA55w0rd");
$conn = sqlsrv_connect($serverName, $connectionOptions);
if($conn === false)
{
    die(print_r(sqlsrv_errors(), true));
}

$sql = "SELECT [userid], [firstname], [surname], u.RoleId, r.rolename as rolename FROM [users] u
        JOIN [Role] r ON r.RoleId = u.RoleId
        WHERE username='$username' AND password='$password'";
$result = odbc_exec($connection, $sql);

if (!$result) {
    die("Error executing the query: " . odbc_errormsg());
}

if (odbc_num_rows($result) > 0) {
    $row = odbc_fetch_array($result);
    $_SESSION["rolename"] = $row["rolename"];
    $_SESSION["username"] = $username;
    $_SESSION["firstname"] = $row["firstname"];
    $_SESSION["surname"] = $row["surname"];
    $userid = $row["userid"];
    $_SESSION["userid"] = $userid;
    
    header("Location: MySchedule.php");
} else {
    echo "Invalid username or password";
}

odbc_close($connection);

?>
