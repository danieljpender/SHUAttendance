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
  <div class="container">
    <form action="" method="post">
      <label for="department">Select a Department:</label>
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
        <label for="module">Select a Module:</label>
        <select name="module" id="module">
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
      document.getElementById("module-container").style.display = "block";
      document.getElementById("attendance-table").style.display = "block";
    } else {
      document.getElementById("module-container").style.display = "none";
      document.getElementById("attendance-table").style.display = "none";
    }
  }
</script>
    </form>
    <table id="attendance-table" style="display:none">
      <thead>
        <tr>
          <th>User ID</th>
          <th>Module ID</th>
          <th>Attendance Date</th>
        </tr>
      </thead>
      <tbody>
        <?php
          if (isset($_POST['submit'])) {
            $department = $_POST['department'];
            $module = $_POST['module'];
            $query = "SELECT * FROM UserAttendance WHERE DepartmentID = '$department' AND ModuleID = '$module'";
            $result = odbc_exec($connection, $query);
            while ($row = odbc_fetch_array($result)) {
              echo '<tr>';
              echo '<td>' . $row['UserID'] . '</td>';
              echo '<td>' . $row['ModuleID'] . '</td>';
              echo '<td>' . $row['AttendanceDate'] . '</td>';
              echo '</tr>';
            }
          }
        ?>
      </tbody>
    </table>
  </div>
    </div>
    <script>
  function showModule() {
    var department = document.getElementById("department").value;
    if (department) {
      document.getElementById("module-container").style.display = "block";
    } else {
      document.getElementById("module-container").style.display = "none";
    }
  }
</script>
</body>
</html>