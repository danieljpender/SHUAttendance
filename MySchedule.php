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
          AND t.StartDate >= CONVERT(DATE, GETDATE()) AND t.EndDate <= CONVERT(DATE, GETDATE())";
echo "Query: " . $query . "<br>";
$result = odbc_exec($connection, $query);

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

<!-- Modal HTML -->
<div id="myModal" class="modal">
  <div class="modal-content">
    <span class="close-btn">&times;</span>
    <form action="MySchedule.php" method="post">
      <label for="code">Enter Code:</label>
      <input type="text" id="code" name="code">
      <input type="text" name="timetable_id" value="<?php echo $timetableid; ?>" readonly>
      <input type="submit" value="Submit">
    </form>
  </div>
</div>

<?php
if (isset($_POST['code']) && isset($_POST['timetable_id'])) {
  $code = $_POST['code'];
  $timetableid = $_POST['timetable_id'];

  // Update the code in the database using the timetableid
  $query = "UPDATE Timetable SET [code] = '$code' WHERE TimetableId = '$timetableid'";
  odbc_exec($connection, $query);
 var_dump($code);
var_dump($timetableid);
var_dump($query);
}
?>

<script>
// get all the table rows


const rows = document.querySelectorAll('tr[id^="row_"]');
rows.forEach(row => {
  row.addEventListener('click', e => {
    const timetableid = row.id.split('_')[1];
    document.querySelector('input[name="timetable_id"]').value = timetableid;
  });
});

// const tableRows = document.querySelectorAll('.table-row');
// // add an event listener to each row
// tableRows.forEach(row => {
//   row.addEventListener('click', event => {
//     // get the timetable_id of the clicked row
//     const timetableId = event.target.parentNode.id.split('_')[1];
//     // update the value of the input field with the timetable_id
//     document.querySelector('#timetable_id').value = timetableId;
//   });
// });
</script>
<script>
  function openModal() {
    document.getElementById("myModal").style.display = "block";
  }
// Get the modal
var modal = document.getElementById("myModal");

// Get the buttons that trigger the modal
var btns = document.querySelectorAll("set-code-btn");

// Loop through the buttons and add event listeners to them
btns.forEach(function(btn) {
  btn.addEventListener("click", function() {
    modal.style.display = "block";

    // Get the timetableid for the row
    var timetableid = this.dataset.timetableid;

    // Set the value of the hidden input in the modal form
    document.getElementById("timetableid").value = timetableid;
  });
});

// Get the close button in the modal
var closeBtn = document.querySelector(".close-btn");

// Add an event listener to the close button
closeBtn.addEventListener("click", function() {
  modal.style.display = "none";
});

// Add an event listener to the window to close the modal if the user clicks outside of it
window.addEventListener("click", function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
});
</script>
</body>
</html>
<?php
odbc_close($connection);
?>
