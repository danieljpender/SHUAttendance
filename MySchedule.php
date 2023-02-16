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
  echo "<tr id='row_$timetableid' data-timetableid='$timetableid'>";
  echo "<td>" . $row['timetable_id'] . "</td>";
  echo "<td>" . $row['ActivityTypeName'] . "</td>";
  echo "<td>" . $row['ModuleName'] . "</td>";
  echo "<td>" . $row['Location'] . "</td>";
  echo "<td>" . $row['StaffMembers'] . "</td>";
  echo "<td>" . date("H:i", strtotime($row['StartTime'])) . " - " . date("H:i", strtotime($row['EndTime'])) . "</td>";
  
  // Check if attendance has been recorded for this event
  $query2 = "SELECT COUNT(*) as count FROM UserAttendanceHistory WHERE UserId='$userid' AND TimetableId='$timetableid'";
  $result2 = odbc_exec($connection, $query2);
  $row2 = odbc_fetch_array($result2);
  $attendance_recorded = $row2['count'] > 0;
  
  if ($role == 'Admin') {
    echo "<td id='code_$timetableid'>" . $row['timetablecode'] . "</td>";
    echo "<td><button id='generate_$timetableid'>Generate Code</button></td>"; 
    echo "<td><a>View Attendance</a></td>";
  } else if ($role == 'Student') {
    if ($attendance_recorded) {
      echo "<td><button disabled='disabled'>Attendance Recorded</button></td>";
    } else {
      echo "<td><button>Enter Code</button></td>";
    }
  }  
  echo "</tr>";
}
?>
</table>
</div>
</div>
<?php include 'footer.php'; ?>
</div>
<!-- Enter Code Modal -->
<div id="code-modal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <form id="code-form" method="POST">
      <label for="code-input">Enter Code:</label>
      <input type="text" id="code-input" name="code">
      <input type="text" id="timetable-id" name="timetableid">
      <input type="submit" value="Submit">
    </form>
  </div>
</div>
<!-- Event Information Modal -->
<div id="event-modal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h2 id="event-title"></h2>
    <p id="event-description"></p>
    <ul>
      <li><strong>Module:</strong> <span id="event-module"></span></li>
      <li><strong>Type:</strong> <span id="event-type"></span></li>
      <li><strong>Location:</strong> <span id="event-location"></span></li>
      <li><strong>Staff Member:</strong> <span id="event-staff"></span></li>
      <li><strong>Date:</strong> <span id="event-date"></span></li>
      <li><strong>Time:</strong> <span id="event-time"></span></li>
    </ul>
  </div>
</div>
<script>
$(document).ready(function() {
  // Add an event listener to each row to open the modal
  $("tr").click(function() {
    // Get the timetable id from the row id
    var timetableid = this.id.replace("row_", "");
    // Retrieve the event details from the database using an AJAX request
    $.ajax({
      type: "POST",
      url: "get_event_details.php",
      data: {timetableid: timetableid},
      dataType: "json",
      success: function(data) {
        // Update the modal with the event details
        $("#event-title").text(data.title);
        $("#event-module").text(data.module);
        $("#event-type").text(data.type);
        $("#event-location").text(data.location);
        $("#event-staff").text(data.staff_member);
        $("#event-date").text(data.start_date);
        $("#event-time").text(data.start_time);
        // Show the modal
        $("#event-modal").show();
      }
    });
  }); 
  // Add an event listener to the modal close button
 $('.close').click(function() {
    $('#event-modal').css('display', 'none');
});

  });
</script>
<script>
$(document).ready(function() {
  // Add an event listener to the 'Enter Code' button
  $('button:contains("Enter Code")').click(function() {
    var timetableid = $(this).closest('tr').attr('id').split('_')[1];
    $('#timetable-id').val(timetableid);
    $('#code-modal').css('display', 'block');
  });

  // Add an event listener to the modal close button
  $('.close').click(function() {
    $('#code-modal').css('display', 'none');
  });

  // Add an event listener to the code form submit button
  $('#code-form').submit(function(event) {
    event.preventDefault();
    var timetableid = $('#timetable-id').val();
    var code = $('#code-input').val();

    // Send an AJAX request to the server to validate the code
    $.ajax({
      url: 'validate-code.php',
      type: 'POST',
      data: { timetableid: timetableid, code: code },
      success: function(data) {
        if (data == 'success') {
          alert('Code validated successfully!');
          $('#code-modal').css('display', 'none');
          $('#row_' + timetableid + ' button').text('Attendance Recorded').prop('disabled', true);
        $('.close').click();
        } else {
          alert('Invalid code! Please try again.');
        }
      }
    });
  });
});
</script>
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
  });
  </script>
</body>
</html>
<?php
odbc_close($connection);
?>
