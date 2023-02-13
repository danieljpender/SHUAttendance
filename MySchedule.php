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
$query = "SELECT *, m.ModuleName as ModuleName, t.TimetableId as eventid, t.[code] as timetablecode  FROM UserTimetable ut
          JOIN Timetable t ON t.ModuleId = ut.ModuleId
          JOIN Module m ON m.ModuleId = t.ModuleId
          JOIN ActivityType ta ON ta.ActivityTypeId = t.TypeId
          WHERE ut.UserId= '$userid'
          AND t.StartDate >= CONVERT(DATE, GETDATE()) AND t.EndDate <= CONVERT(DATE, GETDATE())";
echo "Query: " . $query . "<br>";
$result = odbc_exec($connection, $query);
var_dump($result);

echo "Session data: " . var_dump($_SESSION) . "<br>";
echo "Username: " . $_SESSION["username"] . "<br>";
echo "First name: " . $_SESSION["firstname"] . "<br>";
echo "Surname: " . $_SESSION["surname"] . "<br>";
echo "User ID: " . $_SESSION["userid"] . "<br>";
echo "Role: " . $_SESSION["rolename"] . "<br>";

?>
<html>
<head>
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
    <script src="https://kit.fontawesome.com/4e04e438c0.js" crossorigin="anonymous"></script>
  </head>
  <style>
  .modal {
  display: none;
  position: fixed;
  z-index: 1;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background-color: rgb(0, 0, 0);
  background-color: rgba(0, 0, 0, 0.4);
}

.modal-content {
  background-color: #fefefe;
  margin: 15% auto;
  padding: 20px;
  border: 1px solid #888;
  width: 80%;
}

.close-btn {
  color: #aaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
  cursor: pointer;
}

.close-btn:hover,
.close-btn:focus {
  color: black;
  text-decoration: none;
  cursor: pointer;
}
</style>
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
    <th>Type</th>
    <th>Module</th>
    <th>Location</th>
    <th>Staff Member</th>
    <th>Time</th>
    <?php
    if ($role == 'Admin') {
      echo "<th>Code</th>";
    }
    ?>
    <th>Action</th>
  </tr>
  <?php
 while ($row = odbc_fetch_array($result)) {
  echo "<tr>";
    echo "<td>" . $row['ActivityTypeName'] . "</td>";
    echo "<td>" . $row['ModuleName'] . "</td>";
    echo "<td>" . $row['Location'] . "</td>";
    echo "<td>" . $row['StaffMembers'] . "</td>";
    echo "<td>" . date("H:i", strtotime($row['StartTime'])) . " - " . date("H:i", strtotime($row['EndTime'])) . "</td>";
    if ($role == 'Admin') {
      echo "<td>" . $row['timetablecode'] . "</td>";
    }
    if ($role == 'Admin') {
      echo "<td><button onclick='openModal()'>Set Code</button></td>";    
      echo "<td><a>View Attendance</a></td>";
    } else if ($role == 'Student') {
      echo "<td><button>Enter Code</button></td>";
    }
    echo "</td>";
    echo "</tr>";
  }

  ?>

</table>

</div>
</div>
<?php include 'footer.php'; ?>
</div>


<?php
if (isset($_POST['submitCode'])) {
  $code = $_POST['timetablecode'];
  $timetableid = $row['eventid'];
  $updateQuery = "UPDATE Timetable SET [Code] = '$code' WHERE TimetableId = '$timetableid'";
  odbc_exec($connection, $updateQuery);
  var_dump($code);
  var_dump($timetableid);
  var_dump($updateQuery);
  closeModal();
}
?>
<!-- Modal -->
<div id="myModal" class="modal">
  <!-- Modal Content -->
  <div class="modal-content">
    <span class="close-btn" onclick="closeModal()">&times;</span>
    <form action="" method="post">
      <input type="text" name="timetablecode" placeholder="Enter code">
      <input type="submit" name="submitCode" value="Submit">
    </form>
  </div>
</div>
<script>
  function openModal() {
    document.getElementById("myModal").style.display = "block";
  }

  function closeModal() {
    document.getElementById("myModal").style.display = "none";
  }
</script>
</body>
</html>
<?php
odbc_close($connection);
?>
