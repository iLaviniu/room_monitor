<?php

    $php_scripts_root = str_replace("public_html", "phpscripts/",$_SERVER['DOCUMENT_ROOT'] );
    include $php_scripts_root.'utilities/mysql_connection.php';
    include $php_scripts_root.'utilities/email_sender.php';

    ob_start(); //this should be first line of your page
    session_start();
    
    $account_user = $_POST["username"];
    
    if(empty($_POST)) {
        die();
    }
    
    if($account_user === "") {
        die();
    }
    
    /*Create connection*/
    MySQLConnection::getInstance();
    
    $sql_get_user = "select * from users where username='".$account_user."'";
    $sql_get_user_result = (MySQLConnection::getConnection())->query($sql_get_user);
    
    /*check if user already exists*/
    if($sql_get_user_result->num_rows == 1) {
        
        $row = $sql_get_user_result->fetch_row();

        $account_name = $row[0];
        $account_h_pass = $row[1]; /*hassed pass*/
        
        /*generate random number*/
        $random_number = rand(100000,999999);
        echo $random_number;
        echo '<br>';
        
        /*get time-date now plus 5 minutes*/
        $date_now = date("Y-m-d H:i:s");
        $thisMoment = new DateTime($date_now);
        echo "now: " . $date_now . "<br>";
        $thisMoment->add(new DateInterval('PT5M'));
        $nowPlus5Minutes = $thisMoment->format('Y-m-d H:i:s');
        echo "now + 5 minutes: " . $nowPlus5Minutes . "<br>";
        
        /*update column: code_reset_password and crp_date_time inside users table*/
        $sql_update = "update users set code_reset_password='".$random_number."', crp_date_time='".$nowPlus5Minutes."' where username='".$account_user."'";
        $sql_update_result = (MySQLConnection::getConnection())->query($sql_update);
        
        /*Close MySQL connection*/
        MySQLConnection::close();
        
        if ($sql_update_result == true) {

            /*send email with password reset code.*/
            $emailSender = new EmailSender($account_user, "Pasword Reset Code", "Password reset code: ".$random_number."<br>"." 5 minutes validation.");

            $_SESSION['userStatus'] = "Reset password code successfully sent by email!";
            header("Location: https://www.nestinbase.com/monitor/app/new_password/new_password.php");
            
        } else {
            $_SESSION['userStatus'] = "error resetting password!";
            header("Location: https://www.nestinbase.com/monitor/app/password_reset/password_reset.php");
        }
        
    } else if($sql_get_user_result->num_rows == 0) {
        /*Close MySQL connection*/
        MySQLConnection::close();
        $_SESSION['userStatus'] = "user not registred!";
        header("Location: https://www.nestinbase.com/monitor/app/password_reset/password_reset.php");
    }
?>