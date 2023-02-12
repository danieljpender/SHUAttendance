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
  Student Attendance Records
</h1>
<form action="" method="post" class="mb-2">
      <label for="department">Department:</label>
      <select name="department" id="department" onchange="showModule()">
        <option value="">Select a Department</option>
        <?php
          $query = "SELECT * FROM Department";
          $result = odbc_exec($connection, $query);
          while ($row = odbc_fetch_array($result)) {
            echo '<option value="' . $row['DepartmentId'] . '">' . $row['DepartmentName'] . '</option>';
          }
        ?>
      </select>
      <div id="module-container" style="display:none">
      <div style="display: inline-block">
        <label for="module">Module:</label>
        <select name="module" id="module">
        </div>
          <option value="">Select a Module</option>
          <?php
          $query = "SELECT * FROM Module";
          $result = odbc_exec($connection, $query);
          while ($row = odbc_fetch_array($result)) {
            echo '<option value="' . $row['ModuleId'] . '">' . $row['ModuleName'] . '</option>';
          }
        ?>
        </select>
      </div>
      <script>
  function showModule() {
    var department = document.getElementById("department").value;
if (department) {
  document.getElementById("module-container").style.display = "inline-block";
} else {
  document.getElementById("module-container").style.display = "none";
  document.getElementById("attendance-table").style.display = "none";
}
document.getElementById("module").onchange = function() {
  var department = document.getElementById("department").value;
  var module = document.getElementById("module").value;
  if (department && module) {
    document.getElementById("attendance-table").style.display = "block";
  } else {
    document.getElementById("attendance-table").style.display = "none";
  }
}

  }
</script>
    </form>
  <div>
    <table id="attendance-table" style="display:none">
      <thead>
        <tr>
          <th>Student Name</th>
          <th>Date</th>
          <th>Attendance Record</th>
        </tr>
      </thead>
      <tbody>
        <?php
          if (isset($_POST['submit'])) {
            $department = $_POST['department'];
            $module = $_POST['module'];
            $query = "SELECT *, u.FullName as student_name, t.StartDate as startdate, uah.DateCreated as attendance FROM UserTimetable ut
                      LEFT JOIN UserAttendanceHistory uah ON ut.UserTimetableId = uah.UserTimetableId
                      JOIN Timetable t ON t.TimetableId = ut.TimetableId
                      JOIN Users u ON u.UserId = ut.UserId
                      WHERE ut.DepartmentId = '$department' 
                      AND ut.ModuleId = '$module'
                      AND u.RoleId = '17b1cdac-93f8-4a5f-a5cd-907272094140'";
            $result = odbc_exec($connection, $query);
            while ($row = odbc_fetch_array($result)) {
              echo '<tr>';
              echo '<td>' . $row['student_name'] . '</td>';
              echo '<td>' . $row['startdate'] . '</td>';
              echo '<td>' . $row['sttendance'] . '</td>';
              echo '</tr>';
            }
          }
        ?>
      </tbody>
    </table>
  </div>
    </div>

<?php include 'footer.php'; ?>
</body>
</html>