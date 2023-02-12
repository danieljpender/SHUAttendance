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
$query = "SELECT * FROM ActivityType
          ORDER BY ActivityTypeName ASC";
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
  Manage Activity Types
</h1>
  <div class="container">
        <table>
            <tr>
                <th>Activity Type</th>
                <th>Options</th>
            </tr>
            <?php
 while ($row = odbc_fetch_array($result)) {
    echo "<tr>";
              echo "<td>" . $row['ActivityTypeName'] . "</td>";
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
odbc_close($connection);
?>