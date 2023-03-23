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
  My Attendance Record
</h1>
  <div class="container">
  </div>
    </div>
    <?php include 'footer.php'; ?>
  </body>
</html>

<?php
sqlsrv_close($connection);
?>