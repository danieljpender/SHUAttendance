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
                      </div>
              </nav>
      <div>
          <nav class="navbar shadow">
              <ul class="navbar-nav forge-nav-dividers flex-lg-row w-100">
                  <li><a class="nav-link <?php if ($currentPage === 'MySchedule') { echo 'nav-link-active'; } ?>" href="MySchedule.php">My Schedule</a></li>
                  <?php if ($role === "student") { ?><li class="nav-item"><a class="nav-link" href="MyAttendance.php">My Attendance Record</a></li><?php } ?>
                  <?php if ($role === "admin") { ?><li class="nav-item"><a class="nav-link" href="StudentAttendance.php">Student Attendance Records</a></li><?php } ?>
                  <?php if ($role === "admin") { ?><li class="nav-item"><a class="nav-link" href="Events.php">Events</a></li><?php } ?>
                  <?php if ($role === "admin") { ?><li class="nav-item"><a class="nav-link" href="Reporting.php">Reporting</a></li><?php } ?>
                  <?php if ($role === "admin") { ?><li class="nav-item dropdown-nav">
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
                      
                  </li><?php } ?>
              </ul>
          </nav>
      </div>
