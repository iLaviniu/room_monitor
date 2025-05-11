<!DOCTYPE html>
<?php
    ob_start(); /*turn ON output buffering*/
    if(session_status() != PHP_SESSION_ACTIVE) session_start();
    
    if((isset($_SESSION['userLoggedIn'])) && (true == $_SESSION['userLoggedIn'])) {
        header("Location: https://www.nestinbase.com/monitor/app/dashboard/dashboard.php");
    }
    
?>
<html lang="en">
    <head>
        <title>Login</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
        
        <link rel="stylesheet" href="login.css">
    </head>
  
    <body>
        <div class="container-md">
            <nav class="navbar navbar-expand-lg navbar-light bg-light" style="border-radius: 25px 8px 8px 25px; margin-top: 10px;">
                
                <a class="navbar-brand" href="https://www.nestinbase.com/monitor/app/dashboard/dashboard.php"><img src="../res/icon.png" width="30px" height="30px" class="d-inline-block align-top" alt="">Login</a>
                
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="navbarNav">
                    
                    <ul class="navbar-nav" style="margin-left: auto; margin-right: 0;">
           
                        <li class="nav-item">
                            <a class="nav-link" href="../password_reset/password_reset.php" style="text-align: right;">Reset Password</a>
                        </li>
                             
                        <li class="nav-item">
                            <a class="nav-link" href="../register_intent/register_intent.php" style="text-align: right;">Register</a>
                        </li> 
                        
                    </ul>
                    
                </div>
            </nav>
        </div>
        
        <?php
            $serverErrorMessage = "";
            $visibilityErrorMessageDiv = 'style="display: block;"';
		    
		    if(isset($_SESSION['userStatus'])) {
		        $serverErrorMessage = $_SESSION['userStatus'];
		        
		        
		        if(strlen($serverErrorMessage) == 0) {
		            $visibilityErrorMessageDiv = 'style="display: none;"';
		        }
		    } else if(!isset($_SESSION['userStatus'])) {
		        $visibilityErrorMessageDiv = 'style="display: none;"';
		    }
		    
		    $_SESSION['userStatus'] = ''; /*clear session status*/
        ?>
        
        <center>
            <div class="container-md">
                <div  class="bg-light" id="loginServerMessagesContainer" <?php print $visibilityErrorMessageDiv; ?>>
                    <?php
                        print $serverErrorMessage;
                    ?>
        	    </div>
    	    </div>
	    </center>
        
        <center>
            <div class="container-md">
                <div  class="bg-light" id="loginContainer">
                    <form action="login_redirection.php" onsubmit="return loginFormValidation()" method="post">
            			<label for="username">user</label>
            			<input class="loginInput bg-light" id="username" name="username" type="email">
            			<label for="password">password</label>
            			<input class="loginInput bg-light" id="password" name="password" type="password">
            			<button class="loginButton" style="margin-top: 15px;">c o n n e c t</button>
            		</form>
                </div>
            </div>
        </center>
        
    <script language="javascript">
        function loginFormValidation() {
            let username = document.getElementById("username").value;
            let password = document.getElementById("password").value;
            let isValidForm = true;
            
            if (username == "") {
                alert("username is required!");
                isValidForm = false;
            }
            
            if (password == "") {
                alert("password is required!");
                isValidForm = false;
            }
            
            return isValidForm;
        }
    </script>
    
    </body>
</html>