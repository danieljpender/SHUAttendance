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
$query = "SELECT *, d.DepartmentName AS department_name, [Year] AS module_year FROM Module m
          JOIN Department d ON d.DepartmentId = m.DepartmentId
          ORDER BY d.DepartmentName, [Year], ModuleName ASC";
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
  Manage Modules
</h1>
  <div class="container">
        <table>
            <tr>
                <th>Department Name</th>
                <th>Module Code</th>
                <th>Module Name</th>
                <th>Year</th>
                <th>Options</th>
            </tr>
            <?php
 while ($row = sqlsrv_fetch_array($result)) {
    echo "<tr>";
              echo "<td>" . $row['department_name'] . "</td>";
              echo "<td>" . $row['ModuleCode'] . "</td>";
              echo "<td>" . $row['ModuleName'] . "</td>";
              echo "<td>" . $row['module_year'] . "</td>";
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