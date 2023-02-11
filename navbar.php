<nav class="navbar forge-main-nav p-0 navbar-dark bg-brand">
          <div class="navigation-container">
                  <div class="topnav">
                      <a href="MySchedule.php">
                          <img src="../images/SHU.png" alt="SHULogo" class="logo">
                      </a>
                  </div>
                  </div> 
                  <div class="nav-notification" style="float: right;">
                      <a class="topnav-user-profile" href="javascript:void(0)"></a>
                          <div class="dropdown-nav dropbtn-user ml-left" style="float: right;">
                              <span><i class="fa-regular fa-circle-user topnav-user-profile-pic"></i></span>
                              <span class="topnav-user-profile-name">
                              <?php
                                if (isset($_SESSION['firstname'])) {
                                 echo $_SESSION['firstname'];
                                } else {
                                 echo "Unknown";
                                }
                              ?>
                              </span><i class="fa-solid fa-caret-down symbol white"></i>
                              <div class="dropdown-content">
                                  <a href="logout.php">Sign Out</a>
                              </div>
                          </div>
                      </div>
              </nav>
      <div>
          <nav class="navbar shadow">
              <ul class="navbar-nav forge-nav-dividers flex-lg-row w-100">
              <li><a class="nav-link <?php if (basename($_SERVER['PHP_SELF']) === 'MySchedule.php') { echo 'nav-link-active'; } ?>" href="MySchedule.php">My Schedule</a></li>
                  <?php if ($role === "student") { ?><li class="nav-item"><a class="nav-link <?php if (basename($_SERVER['PHP_SELF']) === 'MyAttendance.php') { echo 'nav-link-active'; } ?>" href="MyAttendance.php">My Attendance Record</a></li><?php } ?>
                  <?php if ($role === "admin") { ?><li class="nav-item"><a class="nav-link <?php if (basename($_SERVER['PHP_SELF']) === 'StudentAttendance.php') { echo 'nav-link-active'; } ?>" href="StudentAttendance.php">Student Attendance Records</a></li><?php } ?>
                  <?php if ($role === "admin") { ?><li class="nav-item"><a class="nav-link <?php if (basename($_SERVER['PHP_SELF']) === 'Events.php') { echo 'nav-link-active'; } ?>" href="Events.php">Events</a></li><?php } ?>
                  <?php if ($role === "admin") { ?><li class="nav-item"><a class="nav-link <?php if (basename($_SERVER['PHP_SELF']) === 'Reporting.php') { echo 'nav-link-active'; } ?>" href="Reporting.php">Reporting</a></li><?php } ?>
                  <?php if ($role === "admin") { ?><li class="nav-item dropdown-nav">
                      <a href="javascript:void(0)" class="dropbtn">Admin Tools</a>
                      <i class="fa-solid fa-caret-down symbol-margin-right grey"></i>
                      <div class="dropdown-content">
                            <a href="ViewDepartments.php">Departments</a>
                          <a href="ViewModules.php">Modules</a>
                          <a href="ViewActivityTypes.php">Activity Types</a>
                          <a href="ViewRoles.php">Roles</a>
                          <a href="ViewUsers.php">Users</a>
                      </div>
                      
                  </li><?php } ?>
              </ul>
          </nav>
      </div>
