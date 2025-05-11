<?php

    $php_scripts_root = str_replace("public_html", "phpscripts/",$_SERVER['DOCUMENT_ROOT'] );
    include $php_scripts_root.'utilities/mysql_connection.php';

    ob_start(); //this should be first line of your page
    session_start();
    
    $account_user = $_POST["username"];
    $reset_code_mail = $_POST["reset_code"];
    $new_password = $_POST["new_password"];
    
    if(empty($_POST)) {
        die();
    }
    
    if( ($account_user === "") || ($reset_code_mail === "") || ($new_password === "") ) {
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
        $reset_code_db = $row[3];
        $reset_code_expiration = $row[4]; 
        
        /*get time-date now*/
        $date_now = date("Y-m-d H:i:s");
        $thisMoment = new DateTime($date_now);
        echo "now: " . $date_now . "<br>";
        
        $expirationMoment = new DateTime($reset_code_expiration);
        echo "expiration: " . $reset_code_expiration . "<br>";
        
        if($thisMoment > $expirationMoment) {
            /*reset code expired!*/
            /*Close MySQL connection*/
            MySQLConnection::close();
            $_SESSION['userStatus'] = "reset code expired!";
            header("Location: https://www.nestinbase.com/monitor/app/new_password/new_password.php");
        } else {
            echo "reset code valid!<br>";
            /*reset code valid!*/
            
            /*comparison between reset code from mail and reset code from data base*/
            if(intval($reset_code_mail) == intval($reset_code_db)) {
                /*encrypt new password*/
                $new_password_hashed = password_hash($new_password, PASSWORD_BCRYPT);
                
                /*overwrite the hashed password with the new one.*/
                $sql_update_pass = "update users set password='".$new_password_hashed."' where username='".$account_user."'";
                $sql_update_pass_result = (MySQLConnection::getConnection())->query($sql_update_pass);
                
                /*Close MySQL connection*/
                MySQLConnection::close();
                
                if($sql_update_pass_result == true) {
                    /*new password successfully changed!*/
                    $_SESSION['userStatus'] = "new password successfully changed!";
                    header("Location: https://www.nestinbase.com/monitor/app/new_password/new_password.php");
                } else {
                    /*error during new password change!*/
                    $_SESSION['userStatus'] = "error during new password change!";
                    header("Location: https://www.nestinbase.com/monitor/app/new_password/new_password.php");
                }
            } else {
                /*invalid password reset code*/
                /*Close MySQL connection*/
                MySQLConnection::close();
                $_SESSION['userStatus'] = "invalid password reset code!";
                header("Location: https://www.nestinbase.com/monitor/app/new_password/new_password.php");
            }
        }
    } else if($sql_get_user_result->num_rows == 0) {
        /*Close MySQL connection*/
        MySQLConnection::close();
        $_SESSION['userStatus'] = "user not registred!";
        header("Location: https://www.nestinbase.com/monitor/app/new_password/new_password.php");
    }
?>