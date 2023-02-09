<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// rest of your code

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
          LEFT JOIN ScheduledEventCode sec ON e.EventId = sec.ScheduledEventId
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
      echo "<a href='view_attendance.php?eventid=" . $row['EventId'] . "'>View Attendance</a>";
    } else if ($role == 'student') {
      echo "<a href='enter_code.php?eventid=" . $row['EventId'] . "'>Enter Code</a>";
    }
    echo "</td>";
    echo "</tr>";
  }
}

  ?>
</table>
<div id="set-code-modal" class="modal">
  <div class="modal-content">
    <span class="close-btn">&times;</span>
    <form action="set_code.php" method="post">
      <input type="hidden" name="eventid" id="eventid-input">
      <label for="code-input">Enter Code:</label>
      <input type="text" id="code-input" name="code">
      <input type="submit" value="Submit">
    </form>
  </div>
</div>
</div>
</div>
</body>
<script>
  const setCodeBtns = document.querySelectorAll('.set-code-btn');
  const modal = document.getElementById('set-code-modal');
  const closeBtn = document.querySelector('.close-btn');
  const eventidInput = document.getElementById('eventid-input');

  setCodeBtns.forEach(btn => {
    btn.addEventListener('click', function() {
      eventidInput.value = this.parentElement.parentElement.querySelectorAll('td')[0].innerText;
      modal.style.display = "block";
    });
  });

  closeBtn.addEventListener('click', function() {
    modal.style.display = "none";
  });

  window.addEventListener('click', function(event) {
    if (event.target === modal) {
      modal.style.display = "none";
    }
  });
</script>

<?php
odbc_close($connection);
?>
