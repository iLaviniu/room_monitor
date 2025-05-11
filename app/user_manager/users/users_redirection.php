<?php
    ob_start(); //this should be first line of your page
    session_start();

    if(isset($_SESSION['userLoggedIn'])) {
        if(false == $_SESSION['userLoggedIn']) {
            header("Location: https://www.nestinbase.com/monitor/app/login/login.php");
        } else if(!isset($_SESSION['userRole'])) {
            header("Location: https://www.nestinbase.com/monitor/app/login/login.php");
        } else if(isset($_SESSION['userRole']) && ("admin" == $_SESSION['userRole'])) {
            require '../../../../../phpscripts/scripts/user_manager/users/get_users.php';
        } else if(isset($_SESSION['userRole']) && ("viewer" == $_SESSION['userRole'])) {
            header("Location: https://www.nestinbase.com/monitor/app/dashboard/dashboard.php");
        }
    } else if(!isset($_SESSION['userLoggedIn'])) {
        header("Location: https://www.nestinbase.com/monitor/app/login/login.php");
    }
?>