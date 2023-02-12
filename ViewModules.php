<?php
session_start();

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

$userid = $_SESSION['userid'];
$role = $_SESSION['rolename'];

// Query the database for the events associated with the user
$query = "SELECT *, d.DepartmentName AS department_name FROM Module m
          JOIN Department d ON d.DepartmentId = m.DepartmentId
          ORDER BY d.DepartmentName, [Year], ModuleName ASC";
$result = odbc_exec($connection, $query);

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
  Manage Departments
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
 while ($row = odbc_fetch_array($result)) {
    echo "<tr>";
              echo "<td>" . $row['department_name'] . "</td>";
              echo "<td>" . $row['ModuleCode'] . "</td>";
              echo "<td>" . $row['ModuleName'] . "</td>";
              echo "<td class='link'><i class='fa-regular fa-pen-to-square symbol'></i>Edit<i class='fa-regular fa-trash-can symbol'></i>Delete</td>";
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
odbc_close($connection);
?>