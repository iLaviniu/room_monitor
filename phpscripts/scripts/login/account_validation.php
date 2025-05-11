<?php
    $php_scripts_root = str_replace("public_html", "phpscripts/",$_SERVER['DOCUMENT_ROOT'] );
    include $php_scripts_root.'utilities/constants.php';
    include $php_scripts_root.'utilities/mysql_connection.php';

    ob_start(); //this should be first line of your page
    session_start();
    
    $account_user = $_POST["username"];
    $account_password = $_POST["password"];
    

    if( ($account_user === "") || (is_null($account_user)) || (!isset($account_user)) || (empty($account_user)) || !$account_user) {
        
        die();
    }
    
    if( ($account_password === "") || (is_null($account_password)) || (!isset($account_password)) || (empty($account_password)) || !$account_password) {
        
        die();
    }
    
    $arr_split = explode("@",$account_user);
    $account_user_perfix = $arr_split[0];
    
    MySQLConnection::getInstance();
    
    /*check if user exists*/
    $sql_user_exists = "select * from users where username = '".$account_user."'";
    $result_user_exists = (MySQLConnection::getConnection())->query($sql_user_exists);
    
    /*Close MySQL connection, because it's not needed from now on.*/
    MySQLConnection::close();
    
    if(!$result_user_exists){
        header( 'Location: https://www.nestinbase.com/monitor/app/errors/404_error_page.php' ); die;
    }
    
    if($result_user_exists->num_rows == 0) {
        $_SESSION['userStatus'] = "user not registered!";
        $_SESSION['userLoggedIn'] = false;
        header("Location: https://www.nestinbase.com/monitor/app/login/login.php");
    } else {
        
        $row = $result_user_exists->fetch_row();

        $account_name = $row[0];
        $account_h_pass = $row[1]; //hassed pass
        $account_role = $row[2];
        
        if(password_verify($account_password, $account_h_pass)) { 
            $_SESSION['userStatus'] = "";
            $_SESSION['userLoggedIn'] = true;
            $_SESSION['userRole'] = $account_role;
            $_SESSION['userNamePrefix'] = $account_user_perfix;
            header("Location: https://www.nestinbase.com/monitor/app/dashboard/dashboard.php");
        } else {
            $_SESSION['userStatus'] = "wrong password!";
            $_SESSION['userLoggedIn'] = false;
            header("Location: https://www.nestinbase.com/monitor/app/login/login.php");
        }
    }
?>