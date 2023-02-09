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

<html>
  <head>
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://kit.fontawesome.com/4e04e438c0.js" crossorigin="anonymous"></script>
    <script>
      function showModal(eventId) {
        $("#eventId").val(eventId);
        $("#codeModal").show();
      }

      function hideModal() {
        $("#codeModal").hide();
      }

      function insertCode() {
        const eventId = $("#eventId").val();
        const code = $("#code").val();
        
        $.ajax({
          url: "insert_code.php",
          type: "post",
          data: {eventId: eventId, code: code},
          success: function(response) {
            console.log(response);
            hideModal();
            location.reload();
          },
          error: function(error) {
            console.error(error);
          }
        });
      }
    </script>
  </head>
  <body>
    <header>
    <?php include 'navbar.php'; ?>
  </header>
  <div class="main-content">
    <h1>
      My Schedule 
    </h1>
    <div class="container">
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
    echo "<td>" . $row['TYPE'] . "</td>";
    echo "<td>" . $row['Title'] . "</td>";
    echo "<td>" . $row['Location'] . "</td>";
    echo "<td>" . $row['StaffMember'] . "</td>";
    echo "<td>" . $row['Time'] . "</td>";
    if ($role == 'admin') {
      echo "<td>" . $row['Code'] . "</td>";
    }
    echo "<td>";
    if ($role == 'admin') {
      echo "<a href='javascript:showModal(" . $row['EventId'] . ")'>Set Code</a> | ";
      echo "<a href='view_attendance.php?eventid=" . $row['EventId'] . "'>View Attendance</a>";
    } else if ($role == 'student') {
      echo "<a href='enter_code.php?eventid=" . $row['EventId'] . "'>Enter Code</a>";
    }
    echo "</td>";
    echo "</tr>";
  }
  ?>
</table>
    </div>
  </div>
</body>
</html>
<?php
odbc_close($connection);
?>
