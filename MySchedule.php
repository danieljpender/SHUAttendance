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
$query = "SELECT *, sec.Code, e.EventId FROM UserEvents ue
          JOIN Events e ON e.EventId=ue.EventId
          LEFT JOIN ScheduledEventCode sec ON e.EventId = sec.EventId
          WHERE UserId= '$userid'";
$result = odbc_exec($connection, $query);
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
      Admin Schedule 
    </h1>
  <div class="container">
<table>
  <tr>
    <th>EventId</th>
    <th>Type</th>
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
  $dayOfWeek = date('l', strtotime($row['Time']));
  if ($dayOfWeek == date('l')) {
    echo "<tr>";
    echo "<td>" . $row['e.EventId'] . "</td>";
    echo "<td>" . $row['TYPE'] . "</td>";
    echo "<td>" . $row['Title'] . "</td>";
    echo "<td>" . $row['Location'] . "</td>";
    echo "<td>" . $row['StaffMember'] . "</td>";
    echo "<td>" . $row['Time'] . " - " . $row['EndTime'] . "</td>";
    if ($role == 'admin') {
      echo "<td>" . $row['sec.Code'] . "</td>";
    }
    echo "<td>";
    if ($role == 'admin') {
      echo "<button class='set-code-btn'>Set Code</button> | ";
      echo "<a href='view_attendance.php?eventid=" . $row['e.EventId'] . "'>View Attendance</a>";
    } else if ($role == 'student') {
      echo "<a href='enter_code.php?eventid=" . $row['e.EventId'] . "'>Enter Code</a>";
    }
    echo "</td>";
    echo "</tr>";
  }
}

  ?>

  <!-- The modal -->
<div id="modal" class="modal">
  <!-- Modal content -->
  <div class="modal-content">
    <span class="close-btn">&times;</span>
    <form action="submit_code.php" method="post">
      <input type="text" name="code" placeholder="Enter code">
      <input type="text" name="eventid" value="<?php echo $row['e.EventId']; ?>">
      <input type="submit" value="Submit">
    </form>
  </div>
</div>

<script>
// Get the modal
var modal = document.getElementById("modal");

// Get the button that opens the modal
var btns = document.getElementsByClassName("set-code-btn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close-btn")[0];

// Loop through all buttons and bind the click event
for (var i = 0; i < btns.length; i++) {
  btns[i].addEventListener("click", function() {
    modal.style.display = "block";
  });
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
};

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
};
</script>

</table>
</div>
</div>
</div>
</body>

<?php
odbc_close($connection);
?>
