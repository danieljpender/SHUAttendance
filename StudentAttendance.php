<?php
session_start();

$serverName = "eam-group27.c0zwiexiop2w.eu-west-2.rds.amazonaws.com,1433";
$database = "SHUAttendance";
$dbuser = "eam";
$dbpass = "%PA55w0rd";

$connOptions = array(
    "Database" => $database,
    "UID" => $dbuser,
    "PWD" => $dbpass,
    "MultipleActiveResultSets" => false,
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
  var moduleDropdown = document.getElementById("module");
  if (department) {
    moduleDropdown.disabled = false;
    document.getElementById("module-container").style.display = "inline-block";
    document.getElementById("attendance-table").style.display = "none";

    // Make an AJAX request to retrieve the modules for the selected department
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        moduleDropdown.innerHTML = this.responseText;
      }
    };
    xhttp.open("GET", "getModules.php?department=" + department, true);
    xhttp.send();
  } else {
    moduleDropdown.disabled = true;
    moduleDropdown.selectedIndex = 0; // reset the module dropdown to the default option
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
  } else {
    document.getElementById("attendance-table").style.display = "none";
  }
}

function submitForm() {
  var department = document.getElementById("department").value;
  var module = document.getElementById("module").value;
  if (department && module) {
    document.forms[0].submit();
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
  <option value="" selected>Select a Department</option>
  <?php
    $query = "SELECT * FROM Department";
    $result = sqlsrv_query($connection, $query);
    while ($row = sqlsrv_fetch_array($result)) {
      echo '<option value="' . $row['DepartmentId'] . '">' . $row['DepartmentName'] . '</option>';
    }
  ?>
</select>
<div id="module-container">
  <label for="module">Module</label>
  <select class="dropdown-box" name="module" id="module" onchange="showAttendanceTable()" disabled>
    <option value="">Select a Module</option>
    <?php
      if (isset($_POST['department'])) {
        $department = $_POST['department'];
        $query = "SELECT * FROM Module WHERE DepartmentId = '$department'";
        $result = sqlsrv_query($connection, $query);
        while ($row = sqlsrv_fetch_array($result)) {
          echo '<option value="' . $row['ModuleId'] . '">' . $row['ModuleName'] . '</option>';
        }
      }
    ?>
  </select>
</div>
</div>
</form>
<table id="attendance-table" style="display:none">
  <thead>
    <tr>
      <th>Student ID</th>
      <th>Student Name</th>
      <th>Email Address</th>
    </tr>
  </thead>
  <tbody>
        <?php
          if (isset($_POST['submit'])) {
            $department = $_POST['department'];
            $module = $_POST['module'];
            $query = "SELECT *, u.FullName as student_name, u.StudentId as student_id, u.email FROM UserTimetable ut
                      JOIN Timetable t ON t.ModuleId = ut.ModuleId
                      JOIN Users u ON u.UserId = ut.UserId
                      WHERE ut.DepartmentId = '$department' AND ut.ModuleId = '$module'
                      AND u.RoleId = '17b1cdac-93f8-4a5f-a5cd-907272094140'";
            $result = sqlsrv_query($connection, $query);
            while ($row = sqlsrv_fetch_array($result)) {
              echo '<tr>';
              echo '<td>' . $row['student_id'] . '</td>';
              echo '<td>' . $row['student_name'] . '</td>';
              echo '<td>' . $row['email'] . '</td>';
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