<?php
// Start the session
session_start();
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

  $server = "eam-group27.database.windows.net";
  $database = "SHUAttendance";
  $username = "eam";
  $password = "%PA55w0rd";

  $conn = odbc_connect("Driver={ODBC Driver 18 for SQL Server};Server=$server;Database=$database;", $username, $password);

  if (!$conn) {
    die("Connection failed: " . odbc_errormsg());
}

$query = "SELECT * FROM dbo.Events";
$result = odbc_exec($conn, $query);
if (!$result) {
    echo "Query failed: " . odbc_errormsg();
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
      
    while ($row = odbc_fetch_array($result)) {
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
</body>
</html>