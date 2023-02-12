<!DOCTYPE html>
<html lang="en-gb" class="login-page">
<head>
    <meta charset="UTF-8">
    <title>SHU | Attendance</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico" />
    <link type="text/css" rel="stylesheet" href="style.css" />
    
</head>
<body>
    <header>
        <nav class="navbar forge-main-nav p-0 navbar-dark bg-brand">
            <div class="navigation-container">
                <div class="topnav">
                        <img src="images/SHU.png" alt="EDSLogo" class="logo">
                </div>
                </div>
                </nav>
    </header>
        <div class="login-box p-3">
            <div class="bg-brand  pt-3 login-header pb-3 d-flex">
              <h2 class="text-white mb-0 ml-4">Sign In</h2>  
            </div>
            <div class="bg-white">
                <div>
                    <span>
                        <div>
                          <form action="authenticate.php" method="post">
                            <div class="form-group">
            <label for="username">Username</label>
            <input class="login_form" type="text" id="username" name="username" size="50" placeholder="Enter Username"/>
                </div>
                <div class="form-group">
            <label for="password">Password</label>
            <input class="login_form" type="password" id="password" name="password" size="50" placeholder="Enter Password"/>
            </div>
            <a class="link mb-2" href="">Forgot your password?</a>
            <input class="btn btn-primary btn-login" type="submit" value="Sign In"/>
                </form>
                </div>
                </span>
                </div>
            </div>
        </div>
        <?php include 'footer.php'; ?>
  </body>
</html>
