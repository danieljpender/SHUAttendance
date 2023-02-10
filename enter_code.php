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

if (isset($_POST['eventid']) && isset($_POST['code'])) {
  $eventid = $_POST['eventid'];
  $code = $_POST['code'];
  $userid = $_SESSION['userid'];

  // Check if the code is correct
  $query = "SELECT * FROM ScheduledEventCode WHERE EventId='$eventid' AND Code='$code'";
  $result = odbc_exec($connection, $query);
  if (odbc_num_rows($result) > 0) {
    // Insert the attendance record
    $query = "INSERT INTO UserEventAttendance VALUES (NEWID(), '$userid', '$eventid')";
    odbc_exec($connection, $query);
    header("Location: myschedule.php");
    exit();
  } else {
    echo "Incorrect code";
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
        Enter Code
      </h1>
      <div class="container">
        <form action="" method="post">
          <label for="code">Enter Code:</label>
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