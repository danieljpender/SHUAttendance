<?php
session_start();

$role = $_SESSION["role"];
$userid = $_SESSION["userid"];

$server = "eam-group27.database.windows.net";
$database = "SHUAttendance";
$username = "eam";
$password = "%PA55w0rd";

$conn = odbc_connect("Driver={ODBC Driver 18 for SQL Server};Server=$server;Database=$database;", $username, $password);

if (!$conn) {
  die("Connection failed: " . odbc_errormsg());
}
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
      My Schedule 
    </h1>
    <div class="container">
      
    <table>
        <thead>
          <tr>
            <th>Type</th>
            <th>Title</th>
            <th>Time</th>
            <th>Location(s)</th>
            <th>Staff Member(s)</th>
            <?php if ($user_role === "admin") { ?><th>Code</th><?php } ?>
            <th>Action</th>
          </tr>
        </thead>
        <tbody id="table-body">
          <?php
          // Get data from the database to populate the table
          $query = "SELECT * FROM UserEvents ue
          JOIN Events e ON e.EventId=ue.EventId
          WHERE UserId= '$userid'";
          $result = odbc_exec($conn, $query);
          while ($row = odbc_fetch_array($result)) {
            $id = $row['id'];
            $type = $row['type'];
            $title = $row['title'];
            $time = $row['time'];
            $location = $row['location'];
            $staff = $row['staff'];
            $code = $row['code'];
            ?>
            <tr>
              <td><?php echo $type; ?></td>
              <td><?php echo $title; ?></td>
              <td><?php echo $time; ?></td>
              <td><?php echo $location; ?></td>
              <td><?php echo $staff; ?></td>
              <?php if ($role === "admin") { ?><td><?php echo $code; ?></td><?php } ?>
              <td>
                <?php if ($role === "admin") { ?>
                  <td>
  <?php if ($role === "admin") { ?>
    <button type="button" class="button-admin" onclick="setCode()">Set Code</button>
    <button type="button" class="button-admin" onclick="viewAttendance()">View Attendance</button>
  <?php } else if ($role === "student") { ?>
    <button type="button" class="button-student" onclick="enterCode()">Enter Code</button>
  <?php } ?>
</td>
          </tr>
        </tbody>
      </table>
      <!-- Modal -->
      <div id="event-modal" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
          <span class="close">&times;</span>
          <form action="#" method="post">
            <label for="event-code">Event Code:</label>
            <input type="text" id="event-code" name="code">
            <input type="hidden" id="selected-event" name="event">
            <input type="submit" name="submit" value="Validate Code">
          </form>
        </div>
      </div>
    </div>
    <script>
      // Get the events table
      const eventsTable = document.querySelector("table tbody");
  
        // Get the modal element
  const modal = document.querySelector("#event-modal");

// Get the close button element
const closeButton = document.querySelector(".close");

// Static list of events
const eventData = [
  {
    type: "Lecture",
    title: "Event 1",
    time: "10:00 AM",
    location: "Room 1",
    teacher: "John Doe",
  },
  {
    type: "Lecture",
    title: "Event 2",
    time: "11:00 AM",
    location: "Room 2",
    teacher: "Jane Doe",
  },
  {
    type: "Lecture",
    title: "Event 3",
    time: "12:00 PM",
    location: "Room 3",
    teacher: "Jim Doe",
  },
];

// Hide the modal
modal.style.display = "none";

// Add a click event listener to the close button
closeButton.addEventListener("click", () => {
  modal.style.display = "none";
});

// Function to populate the events table
function populateEventsTable() {
  // Loop through the list of events
  eventData.forEach((event) => {
    // Create a new table row for each event
    const tableRow = document.createElement("tr");

    // Add a click event listener to the table row
    tableRow.addEventListener("click", () => {
      // Show the modal
      modal.style.display = "block";

      // Fill the form with the details of the selected event
      document.querySelector("#selected-event").value = JSON.stringify(event);
    });

    // Create a table cell for each event property
    const typeCell = document.createElement("td");
    typeCell.textContent = event.type;
    const titleCell = document.createElement("td");
    titleCell.textContent = event.title;
    const timeCell = document.createElement("td");
    timeCell.textContent = event.time;
    const locationCell = document.createElement("td");
    locationCell.textContent = event.location;
    const teacherCell = document.createElement("td");
    teacherCell.textContent = event.teacher;

    // Append the table cells to the table row
    tableRow.appendChild(typeCell);
    tableRow.appendChild(titleCell);
    tableRow.appendChild(timeCell);
    tableRow.appendChild(locationCell);
    tableRow.appendChild(teacherCell);

    // Append the table row to the events table
    eventsTable.appendChild(tableRow);
  });
}

// Call the function to populate the events table
populateEventsTable();

// Hide the modal
modal.style.display = "none";

// Add a click event listener to the close button
closeButton.addEventListener("click", () => {
// Hide the modal
modal.style.display = "none";
});
</script>
</div>
</body>
</html>


