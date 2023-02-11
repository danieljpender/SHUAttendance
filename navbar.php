<header class="forge-nav"><nav class="navbar forge-main-nav p-0 navbar-dark bg-brand navbar-expand-lg"><a href="MySchedule.php" target="_self" class="navbar-brand pl-4"><img src="../images/SHU.png" alt="SHU Logo" height="32px"></a> <button type="button" aria-label="Toggle navigation" class="navbar-toggler collapsed" aria-expanded="false" aria-controls="nav-collapse" style="overflow-anchor: none;"><span class="navbar-toggler-icon"></span></button> <div id="nav-collapse" class="h-100 navbar-collapse collapse" style="display: none;"><ul class="navbar-nav ml-auto flex-row align-items-end align-items-center h-100"><!----> 
<li class="nav-item b-nav-dropdown dropdown forge-nav-user-dropdown border-left border-white h-100 px-1" id="__BVID__8"><a role="button" aria-haspopup="true" aria-expanded="false" href="#" target="_self" class="nav-link dropdown-toggle" id="__BVID__8__BV_toggle_"><svg viewBox="0 0 16 16" width="1em" height="1em" focusable="false" role="img" aria-label="person circle" xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi-person-circle mr-2 b-icon bi" style="width: 2.5rem; height: 2.5rem;"><g><path d="M13.468 12.37C12.758 11.226 11.195 10 8 10s-4.757 1.225-5.468 2.37A6.987 6.987 0 0 0 8 15a6.987 6.987 0 0 0 5.468-2.63z"></path><path fill-rule="evenodd" d="M8 9a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"></path><path fill-rule="evenodd" d="M8 1a7 7 0 1 0 0 14A7 7 0 0 0 8 1zM0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8z"></path></g></svg> 
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
    <em>
   
   <?php
                                if (isset($_SESSION['username'])) {
                                 echo $_SESSION['username'];
                                } else {
                                 echo "Unknown";
                                }
                              ?>
            </em></a></ul></li> </ul></nav> <!----></div></header>
     
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
          <nav class="shadow">
              <ul>
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
