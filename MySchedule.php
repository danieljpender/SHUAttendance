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

$userid = $_SESSION['userid'];
$role = $_SESSION['rolename'];

// Query the database for the events associated with the user
$query = "SELECT *, m.ModuleName as ModuleName, t.TimetableId as timetable_id, t.[code] as timetablecode  FROM UserTimetable ut
          JOIN Timetable t ON t.ModuleId = ut.ModuleId
          JOIN Module m ON m.ModuleId = t.ModuleId
          JOIN ActivityType ta ON ta.ActivityTypeId = t.TypeId
          WHERE ut.UserId= '$userid'
          /*AND t.StartDate >= CONVERT(DATE, GETDATE()) AND t.EndDate <= CONVERT(DATE, GETDATE())*/";
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
  My Schedule - <?php echo date('jS F Y'); ?>
</h1>
  <div class="container">
<table>
  <tr>
    <th>TimetableId</th>
    <th>Type</th>
    <th>Module</th>
    <th>Location</th>
    <th>Staff Member</th>
    <th>Time</th>
    <?php
    if ($role == 'Admin' or $role == 'Staff') {
      echo "<th>Code</th>";
      echo "<th>Set Code</th>";
      echo "<th>View Attendance</th>";
    }
    else if ($role == 'Student') {
      echo "<th>Enter Code</th>";
    }
    ?>
  </tr>
<?php
while ($row = odbc_fetch_array($result)) {
  $timetableid = $row['timetable_id'];
  echo "<tr id='row_$timetableid'>";
  echo "<td>" . $row['timetable_id'] . "</td>";
  echo "<td>" . $row['ActivityTypeName'] . "</td>";
  echo "<td>" . $row['ModuleName'] . "</td>";
  echo "<td>" . $row['Location'] . "</td>";
  echo "<td>" . $row['StaffMembers'] . "</td>";
  echo "<td>" . date("H:i", strtotime($row['StartTime'])) . " - " . date("H:i", strtotime($row['EndTime'])) . "</td>";
  if ($role == 'Admin') {
    echo "<td id='code_$timetableid'>" . $row['timetablecode'] . "</td>";
  }
  if ($role == 'Admin') {
    echo "<td><button onclick='generateCode('$timetableid')'>Set Code</button></td>"; 
    echo "<td><a>View Attendance</a></td>";
  } else if ($role == 'Student') {
    echo "<td><button>Enter Code</button></td>";
  }
  echo "</tr>";

  // Move the form inside the while loop
  echo "<form id='codeForm_$timetableid' method='POST' action='set-code.php'>";
  echo "<input type='hidden' name='timetable_id' value='$timetableid'>";
  echo "</form>";
}
?>

</table>
</div>
</div>
<?php include 'footer.php'; ?>
</div>
<script>
function generateCode(timetable_id) {
  console.log("Generating code for timetable id: ", timetable_id);
  var code = Math.floor(Math.random() * 9000) + 1000;
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("code_" + timetable_id).innerHTML = this.responseText;
    }
  };
  xhttp.open("POST", "set-code.php", true);
  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhttp.send("timetable_id=" + timetable_id + "&code=" + code);
}
</script>
</body>
</html>
<?php
odbc_close($connection);
?>
