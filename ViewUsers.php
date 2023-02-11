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
  Manage Departments
</h1>
  <div class="container">
        <table>
            <tr>
                <th>Department Code</th>
                <th>Department Name</th>
                <th>Location</th>
                <th>Options</th>
            </tr>
            <tr>
                <td>3333</td>
                <td>3Squared Admin</td>
                <td>Administrator</td>
                <td class="link"><i class="fa-regular fa-pen-to-square symbol"></i>Edit<i class="fa-regular fa-trash-can symbol"></i>Delete</td>

            </tr>
            <tr>
                <td>3334</td>
                <td>3Squared Admin 2</td>
                <td>Administrator</td>
                <td class="link"><i class="fa-regular fa-pen-to-square symbol"></i>Edit<i class="fa-regular fa-trash-can symbol"></i>Delete</td>
            </tr>
        </table>
        <br />
        <br />
    </div>
    </div>
    </div>
  </body>
</html>

<?php
odbc_close($connection);
?>