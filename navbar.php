<nav class="navbar forge-main-nav p-0 navbar-dark bg-brand">
          <div class="navigation-container">
                  <div class="topnav">
                      <a href="MySchedule.php">
                          <img src="../images/SHU.png" alt="SHULogo" class="logo">
                      </a>
                  </div>
                  </div> 
                  <div style="float: right;">
                      <a class="topnav-user-profile" href="javascript:void(0)"></a>
                          <div class="dropdown-nav dropbtn-user ml-left" style="float: right;">
                              <span><i class="fa-regular fa-circle-user topnav-user-profile-pic"></i></span>
                              <span class="topnav-user-profile-name">
                              <?php
                                if (isset($_SESSION['username'])) {
                                 echo $_SESSION['username'];
                                } else {
                                 echo "Unknown";
                                }
                              ?>
                              </span><i class="fa-solid fa-caret-down symbol white"></i>
                              <div class="dropdown-content">
                                  <a href="logout.php">Sign Out</a>
                              </div>
                          </div>
                          <div class="nav-notification">
                              <a class="link-white" href="../Notifications.html"><i class="fa-regular fa-bell"></i></a>
                          </div>
                      </div>
              </nav>
      <div>
          <nav class="navbar shadow">
              <ul class="navbar-nav forge-nav-dividers flex-lg-row w-100">
                  <li><a href="MySchedule.php">My Schedule</a></li>
                  <li><?php if ($role === "student") { ?><a href="MyAttendance.php">My Attendance Record</a><?php } ?></li>
                  <li><?php if ($role === "admin") { ?><a href="StudentAttendance.php">Student Attendance Records</a><?php } ?></li>
                  <li><?php if ($role === "admin") { ?><a href="Events.php">Events</a><?php } ?></li>
                  <li><?php if ($role === "admin") { ?><a href="Reporting.php">Reporting</a><?php } ?></li>
                  <li class="dropdown-nav"><?php if ($role === "admin") { ?>
                      <a href="javascript:void(0)" class="dropbtn">Admin Tools</a>
                      <i class="fa-solid fa-caret-down symbol-margin-right grey"></i>
                      <div class="dropdown-content">
                          <a href="../AdminManagement/ViewDepartments.html">Departments</a>
                          <a href="../AdminManagement/ViewLocations.html">Locations</a>
                          <a href="../AdminManagement/ViewRoles.html">Roles</a>
                          <a href="../AdminManagement/ManageRoutes.html">Routes</a>
                          <a href="../AdminManagement/ViewTasks.html">Tasks</a>
                          <a href="../AdminManagement/ViewTeams.html">Teams</a>
                          <a href="../AdminManagement/ManageTractions.html">Traction</a>
                          <a href="../AdminManagement/ManageVerificationStatements.html">Verification Statements</a>
                          <a href="../AdminManagement/ViewCompanyInformation.html">Company Information</a>
                          <a href="../AdminManagement/ManageSkills.html">Skills</a>
                          <a href="../AdminManagement/ViewSafetyBriefs.html">Safety Briefs</a>
                      </div>
                      <?php } ?>
                  </li>
              </ul>
          </nav>
      </div>
