<?php
session_start();

$username = $_POST["username"];
$password = $_POST["password"];

// $server = "eam-group27.c0zwiexiop2w.eu-west-2.rds.amazonaws.com,1433";
// $database = "SHUAttendance";
// $serverUsername = "eam";
// $serverPassword = "%PA55w0rd";

// $connection = odbc_connect("Driver={ODBC Driver 18 for SQL Server};Server=$server;Database=$database;TrustServerCertificate=yes;", $serverUsername, $serverPassword);

// if (!$connection) {
//     die("Error connecting to database: " . odbc_errormsg());
// }

$serverName = "eam-group27.c0zwiexiop2w.eu-west-2.rds.amazonaws.com,1433";
$database = "SHUAttendance";
$username = "eam";
$password = "%PA55w0rd";

$connOptions = array(
    "Database" => $database,
    "UID" => $username,
    "PWD" => $password,
    "MultipleActiveResultSets" => false,
    "Encrypt" => true,
    "TrustServerCertificate" => true,
    "LoginTimeout" => 30
);

$conn = sqlsrv_connect($serverName, $connOptions);

if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}

$sql = "SELECT [userid], [firstname], [surname], u.RoleId, r.rolename as rolename FROM [users] u
        JOIN [Role] r ON r.RoleId = u.RoleId
        WHERE username='$username' AND password='$password'";
        echo "SQL query: $sql<br>"; // printing the SQL query for debugging purposes
$result = sqlsrv_query($conn, $sql);

if (!$result) {
    die("Error executing the query: " . print_r(sqlsrv_errors(), true));
}

if (sqlsrv_has_rows($result)) {
    $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
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

sqlsrv_free_stmt($result);
sqlsrv_close($conn);

?>
