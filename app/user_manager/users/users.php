<!DOCTYPE html>
<?php
    ob_start(); //this should be first line of your page
    session_start();
    
    $php_scripts_root = str_replace("public_html", "phpscripts/",$_SERVER['DOCUMENT_ROOT'] );
    
    include $php_scripts_root.'utilities/constants.php';
    include $php_scripts_root.'utilities/types.php';
    
    if(isset($_SESSION['userLoggedIn'])) {
        if(false == $_SESSION['userLoggedIn']) {
            header("Location: https://www.nestinbase.com/monitor/app/login/login.php");
        } else if(!isset($_SESSION['userRole'])) {
            header("Location: https://www.nestinbase.com/monitor/app/login/login.php");
        } else if(isset($_SESSION['userRole']) && ("viewer" == $_SESSION['userRole'])) {
            header("Location: https://www.nestinbase.com/monitor/app/login/login.php");
        }
    } else if(!isset($_SESSION['userLoggedIn'])) {
        header("Location: https://www.nestinbase.com/monitor/app/login/login.php");
    }
    
?>

<html lang="en">
    <head>
        <base href="https://www.nestinbase.com/monitor/app/user_manager/users/users.php">
        
        <link rel="stylesheet" href="/../../generic_style/table_style.css">
        <link rel="stylesheet" href="users_style.css">
        <title>Users</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>

    </head>
  
    <body>
        <div class="container-md">
            <nav class="navbar navbar-expand-lg navbar-light bg-light" style="border-radius: 25px 8px 8px 25px; margin-top: 10px;">
                
                
                
                <a class="navbar-brand" href="https://www.nestinbase.com/monitor/app/dashboard/dashboard.php"><img src="../../res/icon.png" width="30px" height="30px" class="d-inline-block align-top" alt="">Dashboard</a>
                
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="navbarNav">
                
                    
                    <ul class="navbar-nav" style="margin-left: auto; margin-right: 0;">
                        
                        <li class="nav-item">
                            <a class="nav-link active" href="https://www.nestinbase.com/monitor/app/user_manager/users/users_redirection.php" style="text-align: right;">Users</a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link" href="https://www.nestinbase.com/monitor/app/user_manager/new_comers/new_comers_redirection.php" style="text-align: right;">New Comers</a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link" href="https://www.nestinbase.com/monitor/app/user_manager/remove_users/remove_users_redirection.php" style="text-align: right;">Remove Users</a>
                        </li>
                        
                    </ul>
                    
                </div>
            </nav>
        </div>
        
        
        
        <div class="container-md">
            <div  class="bg-light" id="usersTableContainer">
                    
                <center>
        	        <?php  
                	    $usersList = unserialize($_SESSION['usersList']);
                	    
                	    if(count($usersList) > 0) {

                            /*list implementation for small screens*/
                            echo "<ul class='conversion-rate-list'>";
                                $counter = 0;
                                foreach($usersList as &$user) {
                                    
                                    if($counter > 0) {
                                        echo "<li class='item'>";
                                    } else {
                                        echo "<li class='first_item'>";
                                    }

                                        echo "<dl class='ad'>";
                                            
                                            echo "<dt class='name'>User Index</dt>";
                                            echo "<dd class='value'>".$user->getUserIndex()."</dd>";

                                            echo "<dt class='name'>Username</dt>";
                                            echo "<dd class='value'>".$user->getUsername()."</dd>";

                                            echo "<dt class='name'>Role</dt>";
                                            echo "<dd class='value'>".$user->getUserRole()."</dd>";

                                        echo "</dl>";

                                    echo "</li>";

                                    $counter++;
                                }
                            echo "</ul>";

                            /*table implementation for larger screens*/
                    	    echo "<table class='conversion-rate-table' id='users_table'>";

                                echo "<thead class='header-section'>";
                                    echo "<tr class='headers'>";
                                        echo "<th class='header' scope='col'>User Index</th>";
                                        echo "<th class='header' scope='col'>Username</th>";
                                        echo "<th class='header' scope='col'>Role</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                
                                foreach($usersList as &$user) {
                                    echo "<tbody class='data-section'>";
                                        echo "<tr class='ad'>";
                                            echo "<td class='cell'>".$user->getUserIndex()."</td>";
                                            echo "<td class='cell'>".$user->getUsername()."</td>";
                                            echo "<td class='cell'>".$user->getUserRole()."</td>";
                                        echo "</tr>";
                                    echo "</tbody>";
                    	        }
                    	    echo "</table>";
                	    } else {
                	        echo "no content to show!";
                	    }
            	    ?>
                </center>
        
            </div>
        </div>
        
    </body>
</html>