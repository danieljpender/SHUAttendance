<?php
// Start the session
session_start();
$user_role = "";
if (isset($_SESSION['role'])) {
  $user_role = $_SESSION['role'];
}
?>
<html>
<style>
  /* Add styles for the modal */
  .modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
  }

    /* Modal Content/Box */
.modal-content {
background-color: #fefefe;
margin: 15% auto; /* 15% from the top and centered */
padding: 20px;
border: 1px solid #888;
width: 80%; /* Could be more or less, depending on screen size */
}

/* The Close Button */
.close {
color: #aaa;
float: right;
font-size: 28px;
font-weight: bold;
}

.close:hover,
.close:focus {
color: black;
text-decoration: none;
cursor: pointer;
}
</style>
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
      Admin Schedule 
    </h1>
  <div class="container">
    <table class="event-table">
      <tr>
        <th>Event</th>
        <th>Code</th>
        <th>Action</th>
      </tr>
      <tr>
        <td>Event 1</td>
        <td id="code1">Not Set</td>
        <td>
          <button onclick="openModal('event1', 'code1', 'inputCode1')">Set Code</button>
          <button onclick="openAttendanceModal('event1')">View Attendance</button>
        </td>
      </tr>
      <tr>
        <td>Event 2</td>
        <td id="code2">Not Set</td>
        <td>
          <button onclick="openModal('event2', 'code2', 'inputCode2')">Set Code</button>
          <button onclick="openAttendanceModal('event2')">View Attendance</button>
        </td>
      </tr>
    </table>
  </div>
  <!-- The Modal -->
<div id="myModal" class="modal">
  <!-- Modal content -->
  <div class="modal-content">
    <form action="#" method="post">
      <span class="close">&times;</span>
      <input type="hidden" id="selectedEvent" name="event">
      <label for="inputCode">Code:</label>
      <input type="text" id="inputCode" name="code">
      <input type="submit" value="Submit">
    </form>
  </div>
</div>

<script>
  // Get the modal
  var modal = document.getElementById("myModal");
  var attendanceModal = document.getElementById("attendanceModal");
  
  // Get the <span> element that closes the modal
  var span = document.getElementsByClassName("close")[0];
  
  function openModal(eventId, codeId, inputCodeId) {
  document.getElementById("selectedEvent").value = eventId;
  document.getElementById("inputCode").value = document.getElementById(codeId).innerHTML;
  modal.style.display = "block";
}
  
  // When the user clicks on <span> (x), close the modal
  span.onclick = function() {
    modal.style.display = "none";
  }
  
  // When the user clicks anywhere outside of the modal, close it
  window.onclick = function(event) {
    if (event.target == modal) {
      modal.style.display = "none";
    }
  }
  
  // Handle form submission
  document.querySelector("form").addEventListener("submit", function(event) {
    event.preventDefault();
    var selectedEvent = document.getElementById("selectedEvent").value;
    var code = document.getElementById("inputCode").value;
    document.getElementById(selectedEvent + "Code").innerHTML = code;
    modal.style.display = "none";
  });
  </script>
  
  <div id="attendanceModal" class="modal">
    <!-- Modal content -->
    <div class="modal-content">
      <span class="close">&times;</span>
      <table id="attendanceTable">
        <tr>
          <th>Name</th>
          <th>Attendance</th>
        </tr>
      </table>
    </div>
  </div>
  
  <script>
    // Get the modal
    var attendanceModal = document.getElementById("attendanceModal");
    
    // Get the <span> element that closes the modal
    var attendanceSpan = document.getElementsByClassName("close")[1];
    
    function openAttendanceModal(eventId) {
      // Code to retrieve and display the attendance record for the event
      var attendanceTable = document.getElementById("attendanceTable");
      attendanceTable.innerHTML = ""; // Reset the table
      // Code to retrieve the attendance data for the event
      // Example: let data = getAttendanceData(eventId);
      let data = [{ name: "John Doe", attendance: "Present" }, { name: "Jane Doe", attendance: "Absent" }];
      for (var i = 0; i < data.length; i++) {
        var row = document.createElement("tr");
        var nameCell = document.createElement("td");
        nameCell.innerHTML = data[i].name;
        row.appendChild(nameCell);
        var attendanceCell = document.createElement("td");
        attendanceCell.innerHTML = data[i].attendance;
        row.appendChild(attendanceCell);
        attendanceTable.appendChild(row);
      }
      attendanceModal.style.display = "block";
    }
    
    // When the user clicks on <span> (x), close the modal
    attendanceSpan.onclick = function() {
      attendanceModal.style.display = "none";
    }
    
    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
      if (event.target == attendanceModal) {
        attendanceModal.style.display = "none";
      }
    }
  </script>

  

</body>
</html>
