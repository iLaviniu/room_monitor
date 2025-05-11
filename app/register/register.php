<!DOCTYPE html>

<?php

    ob_start(); /*turn ON output buffering*/
    session_start();
    
?>


<html lang="en">
    <head>
        <title>Register</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
        
        <link rel="stylesheet" href="register.css">
    </head>
    
    <body>
        
        <div class="container-md">
            <nav class="navbar navbar-expand-lg navbar-light bg-light" style="border-radius: 25px 8px 8px 25px; margin-top: 10px;">
                
                <a class="navbar-brand" href="https://www.nestinbase.com/monitor/app/login/login.php"><img src="../res/icon.png" width="30px" height="30px" class="d-inline-block align-top" alt="">Login</a>
                
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
    	    
    	    $_SESSION['userStatus'] = ''; /*clear user status*/
	    ?>
    	
    	<center>
            <div class="container-md">
                <div  class="bg-light" id="registerServerMessagesContainer" <?php print $visibilityErrorMessageDiv; ?>>
                    <?php
                        print $serverErrorMessage;
                    ?>
            	</div>
        	</div>
	    </center>
	    
	    <center>
            <div class="container-md">
                <div  class="bg-light" id="registerContainer">
    		        
    		        <form action="register_redirection.php" method="post">
    		            
            			<label for="username">user</label>
            			<input class="registerInput bg-light" id="username" name="username" type="email">
            			
            			<label for="password">password</label>
            			<input class="registerInput bg-light" id="password" name="password" type="password">
            			
            			<label for="registercode">register code</label>
            			<input class="registerInput bg-light" id="registercode" name="registercode" type="number">
            			
            			<button class="registerButton" style="margin-top: 15px;">s e n d</button>
            			
            		</form>
            		
                </div>
            </div>
        </center>
    	
        <script language="javascript">
        
        </script>
    
    </body>
</html>