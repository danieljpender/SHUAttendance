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
    <script>
  function showModule() {
    var department = document.getElementById("department").value;
    if (department) {
      document.getElementById("module-container").style.display = "inline-block";
      // Submit the form to retrieve the modules for the selected department
      document.forms[0].submit();
    } else {
      document.getElementById("module-container").style.display = "none";
      document.getElementById("attendance-table").style.display = "none";
    }
  }

  function showAttendanceTable() {
    var department = document.getElementById("department").value;
    var module = document.getElementById("module").value;
    if (department && module) {
      document.getElementById("attendance-table").style.display = "block";
      // Submit the form to retrieve the attendance records for the selected department and module
      document.forms[0].submit();
    } else {
      document.getElementById("attendance-table").style.display = "none";
    }
  }
</script>
  </head>
  <body>
    <header>
    <?php include 'navbar.php'; ?>
    </header>
    <div class="main-content">
  <h1>
  Student Attendance Records
</h1>
<form action="StudentAttendance.php" method="post" class="mb-2">
  <label for="department">Department</label>
  <select class="dropdown-box" name="department" id="department" onchange="showModule()">
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
    <label for="module">Module</label>
    <select class="dropdown-box" name="module" id="module" onchange="showAttendanceTable()">
      <option value="">Select a Module</option>
      <?php
        if (isset($_POST['department'])) {
          $department = $_POST['department'];
          $query = "SELECT * FROM Module WHERE DepartmentId = '$department'";
          $result = odbc_exec($connection, $query);
          while ($row = odbc_fetch_array($result)) {
            echo '<option value="' . $row['ModuleId'] . '">' . $row['ModuleName'] . '</option>';
          }
        }
      ?>
    </select>
  </div>
</form>
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
                      JOIN Timetable t ON t.ModuleId = ut.ModuleId
                      JOIN Users u ON u.UserId = ut.UserId
                      WHERE ut.DepartmentId = '$department' AND ut.ModuleId = '$module'
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