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

$userid = $_SESSION['userid'];
$role = $_SESSION['rolename'];

// Query the database for the events associated with the user
$query = "SELECT *, r.RoleName as role_name, u.Email as email FROM Users u
          JOIN [Role] r ON r.RoleId = u.RoleId
          WHERE r.RoleId = 'B964A9EF-6635-432B-B364-2460B00D8ED1'
          ORDER BY RoleName, FirstName, Surname ASC";
$result = sqlsrv_query($connection, $query);

?>
<html>
  <head>
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
    <script src="https://kit.fontawesome.com/4e04e438c0.js" crossorigin="anonymous"></script>
  </head>
  <body>
    <header>
    <?php include 'navbar.php'; ?>
    </header>
    <div class="main-content">
    <h1>
  Manage Students
</h1>
  <div class="container">
        <table>
            <tr>
                <th>Student ID</th>
                <th>Firstname</th>
                <th>Surname</th>
                <th>Email</th>
                <th>Role</th>
                <th>Options</th>
            </tr>
            <?php
 while ($row = sqlsrv_fetch_array($result)) {
    echo "<tr>";
              echo "<td>" . $row['StudentId'] . "</td>";
              echo "<td>" . $row['FirstName'] . "</td>";
              echo "<td>" . $row['Surname'] . "</td>";
              echo "<td>" . $row['email'] . "</td>";
              echo "<td>" . $row['role_name'] . "</td>";
              echo "<td class='link'>Edit</td>";
    echo "</tr>";
    
  }
  ?>
        </table>
        <br />
        <br />
    </div>
    </div>
    </div>
    <?php include 'footer.php'; ?>
  </body>
</html>

<?php
sqlsrv_close($connection);
?>