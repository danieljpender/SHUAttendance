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
$role = $_SESSION['role'];

// Query the database for the events associated with the user
$query = "SELECT * FROM UserEvents ue
          JOIN Events e ON e.EventId=ue.EventId
          WHERE UserId= '$userid'";
$result = odbc_exec($connection, $query);
?>

<table>
  <tr>
    <th>TYPE</th>
    <th>Title</th>
    <th>Location</th>
    <th>Staff Member</th>
    <th>Time</th>
    <?php
    if ($role == 'admin') {
      echo "<th>Code</th>";
    }
    ?>
    <th>Action</th>
  </tr>
  <?php
  while ($row = odbc_fetch_array($result)) {
    echo "<tr>";
    echo "<td>" . $row['Type'] . "</td>";
    echo "<td>" . $row['Title'] . "</td>";
    echo "<td>" . $row['Location'] . "</td>";
    echo "<td>" . $row['StaffMember'] . "</td>";
    echo "<td>" . $row['Time'] . "</td>";
    if ($role == 'admin') {
      echo "<td>" . $row['Code'] . "</td>";
    }
    echo "<td>";
    if ($role == 'admin') {
      echo "<a href='set_code.php?eventid=" . $row['EventId'] . "'>Set Code</a> | ";
      echo "<a href='view_attendance.php?eventid=" . $row['EventId'] . "'>View Attendance</a>";
    } else if ($role == 'student') {
      echo "<a href='enter_code.php?eventid=" . $row['EventId'] . "'>Enter Code</a>";
    }
    echo "</td>";
    echo "</tr>";
  }
  ?>
</table>

<?php
odbc_close($connection);
?>
