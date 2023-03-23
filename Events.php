<?php
// Start the session
session_start();

$user_role = "";
if (isset($_SESSION['role'])) {
  $user_role = $_SESSION['role'];
}

$userid = $_SESSION['userid'];
$role = $_SESSION['role'];

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
      Events 
    </h1>
  <div class="container">
  <?php
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

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

$query = "SELECT * FROM dbo.Events";
$result = sqlsrv_query($conn, $query);
if (!$result) {
    echo "Query failed: " . sqlsrv_errormsg();
} else {
    echo "Query executed successfully";
}
  ?>

  <table>
    <tr>
      <th>Type</th>
      <th>Title</th>
      <th>Location</th>
      <th>Staff Member</th>
      <th>Frequency</th>
      <th>Day of Week</th>
      <th>Time</th>
      <th>Start Date</th>
      <th>End Date</th>
    </tr>
    <?php
      
    while ($row = sqlsrv_fetch_array($result)) {
      echo "<tr>";
      echo "<td>" . $row["TYPE"] . "</td>";
      echo "<td>" . $row["Title"] . "</td>";
      echo "<td>" . $row["Location"] . "</td>";
      echo "<td>" . $row["StaffMember"] . "</td>";
      echo "<td>" . $row["Frequency"] . "</td>";
      echo "<td>" . $row["DayOfWeek"] . "</td>";
      echo "<td>" . $row["Time"] . "</td>";
      echo "<td>" . $row["StartDate"] . "</td>";
      echo "<td>" . $row["EndDate"] . "</td>";
      echo "</tr>";
    }
    ?>
  </table>
  </div>
  <?php include 'footer.php'; ?>
</body>
</html>