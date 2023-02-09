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

// Check if the eventid is set in the URL
if (!isset($_GET['eventid'])) {
  header("Location: myschedule.php");
  exit();
}

$userid = $_SESSION['userid'];
$role = $_SESSION['role'];
$eventid = $_GET['eventid'];

// Check if the user is authorized to set the code for the event
$query = "SELECT * FROM UserEvents WHERE UserId = '$userid' AND EventId = '$eventid'";
$result = odbc_exec($connection, $query);

if (!odbc_fetch_array($result)) {
  header("Location: myschedule.php");
  exit();
}

// Insert the code for the event into the database
if (isset($_POST['code'])) {
  $code = $_POST['code'];

  $query = "INSERT INTO ScheduledEventCode (ScheduledEventCodeId, ScheduledEventId, Code)
            VALUES (NEWID(), '$eventid', '$code')";
  $result = odbc_exec($connection, $query);

  if ($result) {
    header("Location: myschedule.php");
    exit();
  }
}
?>

<html>
  <head>
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
  </head>
  <body>
    <header>
    <?php include 'navbar.php'; ?>
    </header>
    <div class="main-content">
      <h1>
        Set Code
      </h1>
      <div class="container">
        <form action="" method="post">
          <label for="code">Code:</label>
          <input type="text" id="code" name="code">
          <input type="submit" value="Submit">
        </form>
      </div>
    </div>
  </body>
</html>

<?php
odbc_close($connection);
?>
