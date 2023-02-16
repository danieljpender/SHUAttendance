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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
    echo "<td><button id='generate_$timetableid'>Generate Code</button></td>"; 
    echo "<td><a>View Attendance</a></td>";
  } else if ($role == 'Student') {
    echo "<td><button class='enter-code-button' data-toggle='modal' data-target='#enter-code-modal' data-timetable-id='$timetableid'>Enter Code</button></td>";
  }  
  echo "</tr>";
}
?>
</table>
<!-- Modal -->
<div class="modal fade" id="enter-code-modal" tabindex="-1" role="dialog" aria-labelledby="enter-code-modal-label" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="enter-code-modal-label">Enter Code</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="enter-code-form">
          <div class="form-group">
            <label for="code-input">Code:</label>
            <input type="text" class="form-control" id="code-input" name="code">
          </div>
          <input type="hidden" id="timetable-id-input" name="timetable_id">
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="submit-code-button">Submit</button>
      </div>
    </div>
  </div>
</div>
</div>
</div>
<?php include 'footer.php'; ?>
</div>
<script>
$(document).ready(function() {
  $('button[id^="generate_"]').click(function() {
    var timetableid = $(this).attr('id').split('_')[1];

    $.ajax({
      url: 'generate-code.php',
      type: 'POST',
      data: { timetableid: timetableid },
      success: function(data) {
        $('#code_' + timetableid).text(data);
      }
    });
  });

  $('.enter-code-button').click(function() {
    var timetableid = $(this).data('timetable-id');
    $('#timetable-id-input').val(timetableid);
  });

  $('#submit-code-button').click(function() {
    var code = $('#code-input').val();
    var timetableid = $('#timetable-id-input').val();

    $.ajax({
      url: 'validate-code.php',
      type: 'POST',
      data: { code: code, timetableid: timetableid },
      success: function(data) {
        if (data == 'true') {
          alert('Code is valid');
        } else {
          alert("Code is invalid.");
          }
        }
      });
    });
    }
  )
  </script>
</body>
</html>
<?php
odbc_close($connection);
?>
