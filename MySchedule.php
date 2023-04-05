<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$serverName = "eam-group27.c0zwiexiop2w.eu-west-2.rds.amazonaws.com,1433";
$database = "SHUAttendance";
$dbuser = "eam";
$dbpass = "%PA55w0rd";

$connOptions = array(
    "Database" => $database,
    "UID" => $dbuser,
    "PWD" => $dbpass,
    "Encrypt" => true,
    "TrustServerCertificate" => true,
    "LoginTimeout" => 30
);

$connection = sqlsrv_connect($serverName, $connOptions);

if ($connection === false) {
    die(print_r(sqlsrv_errors(), true));
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
          --AND t.StartDate >= CONVERT(DATE, GETDATE()) AND t.EndDate <= CONVERT(DATE, GETDATE())
          ";
$result = sqlsrv_query($connection, $query);

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
    <th style="display:none;">TimetableId</th>
    <th>Type</th>
    <th>Module</th>
    <th>Location</th>
    <th>Staff Member</th>
    <th>Time</th>
    <?php
    if ($role == 'Admin' or $role == 'Staff') {
      echo "<th>Code</th>";
      echo "<th>Generate Code</th>";
      echo "<th>View Attendance</th>";
    }
    else if ($role == 'Student') {
      echo "<th>Record Attendance</th>";
      echo "<th>Record Absence</th>";
    }
    ?>
  </tr>
<?php
while ($row = sqlsrv_fetch_array($result)) {
  $timetableid = $row['timetable_id'];
  $startTime = date("H:i", strtotime($row['StartTime']->format('Y-m-d H:i:s')));
  $lectureEndDate = $row['EndDate'];
  $lectureEndTime = $row['EndTime'];

  $lectureEndDate->modify($lectureEndTime->format('H:i:s.u'));
$timestamp = $lectureEndDate->getTimestamp();

  // $end_date_time_str = $endDate . ' ' . $endTime;
  // $end_date_time = strtotime($end_date_time_str);
  $endTime = date("H:i", strtotime($row['EndTime']->format('Y-m-d H:i:s')));
//$end_datetime = date("H:i", strtotime($row['EndDate']->format('Y-m-d H:i:s')));
  $lectureEndDateStr = $lectureEndDate->format('Y-m-d H:i:s');
  $currentDatetimeStr = (new DateTime())->format('Y-m-d H:i:s');
 // $event_has_ended = $current_datetime < $end_datetime;
  $code_disabled = $row['timetablecode'] !== NULL;
  $no_register = $row['timetablecode'] == NULL && $currentDatetimeStr > $lectureEndDateStr;
  $enter_code_disabled = $role == 'Student' && $currentDatetimeStr > $lectureEndDateStr;

  echo "<tr id='row_$timetableid' data-timetableid='$timetableid'>";
  echo "<td  style='display:none;''>" . $row['timetable_id'] . "</td>";
  echo "<td>" . $row['ActivityTypeName'] . "</td>";
  echo "<td>" . $row['ModuleName'] . "</td>";
  echo "<td>" . $row['Location'] . "</td>";
  echo "<td>" . $row['StaffMembers'] . "</td>";
  echo "<td>" . $startTime . " - " . $endTime . "</td>";
  //echo "<td>" . $lectureEndDateStr . " - " . $currentDatetimeStr . "</td>";
  
  // Check if attendance has been recorded for this event
  $query2 = "SELECT COUNT(*) as count FROM UserAttendanceHistory WHERE UserId='$userid' AND TimetableId='$timetableid'";
  $result2 = sqlsrv_query($connection, $query2);
  if ($result2 === false) {
    die("Error executing the query: " . print_r(sqlsrv_errors(), true));
}
  $row2 = sqlsrv_fetch_array($result2);
  $attendance_recorded = $row2['count'] > 0;
  
 if ($role == 'Admin') {
    echo "<td id='code_$timetableid'>" . $row['timetablecode'] . "</td>";
    if ($no_register) {
      echo "<td><button disabled='disabled'>Register Not Taken</button></td>";
    }
    else {
    echo "<td><button class='generate-code-btn' id='generate_$timetableid' " . ($code_disabled ? 'disabled' : '') . ">Generate Code</button></td>"; 
    }
    echo "<td><button class='view-attendance-btn' data-timetableid='$timetableid' id='attendance_$timetableid'>View Attendance</button></td>";
  } else if ($role == 'Student') {
      if ($attendance_recorded) {
        echo "<td><button disabled='disabled'>Attendance Recorded</button></td>";
      } else if ($no_register) {
        echo "<td><button disabled='disabled'>Register Not Taken</button></td>";
      } else if ($enter_code_disabled) {
        echo "<td><button disabled='disabled'>Attendance Not Recorded</button></td>";
      } else {
        echo "<td><button class='enter-code-btn' data-timetableid='$timetableid'>Enter Code</button></td>";
      }
      echo "<td><button>Record Absence</button></td>";
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
      <h1 for="code-input">Enter Code</h1>
      <div id="error-message"></div>
      <input type="text" id="code-input" name="code" placeholder="Please Enter Code">
      <input type="hidden" id="timetable-id" name="timetableid">
      <input type="submit" value="Submit">
    </form>
  </div>
</div>

<!-- Event Information Modal -->
<div id="event-modal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h1 id="event-description"></h1>
    <h2 id="event-location"></h2>
    <h3 id="event-date"></h3>
    <h3>From <span id="event-starttime"></span> to <span id="event-endtime"></span></h3>
    <ul>
      <li><strong>Type:</strong> <span id="event-type"></span></li>
      <li><strong>Activity name:</strong> <span id="event-name"></span></li>
      <li><strong>Module code:</strong> <span id="event-modulecode"></span></li>
      <li><strong>Module description:</strong> <span id="event-module"></span></li>
      <li><strong>Location(s):</strong> <span id="event-locations"></span></li>
      <li><strong>Staff Member(s):</strong> <span id="event-staff"></span></li>
    </ul>
  </div>
</div>

<!-- Attendance Modal -->
<div id="attendance-modal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h1>Attendance</h1>
    <table id="attendance-modal-body"></table>
  </div>
</div>


<script>
$(document).ready(function() {
  // Add an event listener to each row to open the modal
  $("tr").click(function() {
    // Get the timetable id from the row id
    //var timetableid = this.id.replace("row_", "");
    var timetableid = $(this).closest('tr').attr('id').split('_')[1];
    $('#timetable-id').val(timetableid);
    // Retrieve the event details from the database using an AJAX request
    $.ajax({
      type: "POST",
      url: "get_event_details.php",
      data: {timetableid: timetableid},
      dataType: "json",
      success: function(data) {
        // Update the modal with the event details
        $("#event-description").text(data.description);
        $("#event-module").text(data.module);
        $("#event-modulecode").text(data.module_code);
        $("#event-type").text(data.activity_type);
        $("#event-location").text(data.location_name);
        $("#event-locations").text(data.location_name);
        $("#event-staff").text(data.staff_members);
        $("#event-date").text(data.event_date);
        $("#event-starttime").text(data.start_time);
        $("#event-endtime").text(data.end_time);
        $("#event-name").text(data.activity_name);
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
        if (data.trim() === 'success') {
          alert('Code validated successfully!');
          $('#code-modal').css('display', 'none');
          $('#row_' + timetableid + ' button').text('Attendance Recorded').prop('disabled', true);
        $('.close').click();
        } else {
          $('#error-message').html('<span class="alert alert-danger">Invalid code! Please try again.</span>');
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
    $(this).prop('disabled', true);
});
  });
  </script>
  <script>
  $(document).ready(function() {
    // Prevent event-modal from opening when clicking enter code button
    $('.enter-code-btn').click(function(event) {
      event.stopPropagation();
    });
    $('.generate-code-btn').click(function(event) {
      event.stopPropagation();
    });
    $('.view-attendance-btn').click(function(event) {
      event.stopPropagation();
    });
  });
</script>

<script>
  $(document).ready(function() {
    $('.view-attendance-btn').click(function() {
      var timetableid = this.id.replace("attendance_", "");
    $('#timetable-id').val(timetableid);
      $.ajax({
        url: 'get_attendance.php',
        type: 'POST',
        data: { timetableid: timetableid },
        success: function(data) {
          $('#attendance-modal-body').html(data);
          $('#attendance-modal').show();
        }
      });
    });
     // Add an event listener to the modal close button
  $('.close').click(function() {
    $('#attendance-modal').css('display', 'none');
  });
  });
</script>

</body>
</html>
<?php
sqlsrv_close($connection);
?>
